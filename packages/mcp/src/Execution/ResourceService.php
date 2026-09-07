<?php

namespace Icebearsoft\Kitukizuri\Mcp\Execution;

use Icebearsoft\Kitukizuri\Mcp\Metadata\ResourceDefinition;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use LogicException;
use Icebearsoft\Kitukizuri\Mcp\Schema\McpSchemaBuilder;
use Icebearsoft\Kitukizuri\Resources\ModelPersistence;
use Illuminate\Validation\Rule;

/** Application service: no web controller actions, redirects or synthetic requests. */
class ResourceService
{
    public function query(ResourceDefinition $resource, Authenticatable $user): Builder
    {
        $state = $resource->state();
        if ($state['complex']) {
            throw new LogicException('This resource requires a domain ResourceService adapter.');
        }
        $model = $state['model'];
        // Wrap the configured query as a membership subquery: top-level OR filters
        // can never escape the company constraint on the outer query.
        $membership = (clone $state['query'])->reorder()->select($model->qualifyColumn($model->getKeyName()));
        $query = $model->newQuery()->whereIn($model->qualifyColumn($model->getKeyName()), $membership);
        if ($column = $resource->config['company_column']) {
            if (empty($user->empresaid)) {
                throw new AuthorizationException('A company context is required.');
            }
            $query->where($model->qualifyColumn($column), $user->empresaid);
        }
        return $query;
    }

    public function execute(ResourceDefinition $resource, string $operation, array $arguments, Authenticatable $user): array
    {
        if (in_array($operation, ['create', 'update', 'delete'], true)) {
            return $this->write($resource, $operation, $arguments, $user);
        }
        $allowed = $operation === 'list' ? ['limit', 'offset'] : ['id'];
        if (array_diff(array_keys($arguments), $allowed)) {
            throw \Illuminate\Validation\ValidationException::withMessages(['arguments' => 'Unsupported arguments.']);
        }
        $rules = $operation === 'list'
            ? ['limit' => ['integer', 'min:1', 'max:100'], 'offset' => ['integer', 'min:0', 'max:100000']]
            : ['id' => ['required', 'string', 'max:255']];
        Validator::make($arguments, $rules)->validate();
        $query = $this->query($resource, $user);
        if ($operation === 'list') {
            $limit = $arguments['limit'] ?? 25;
            $records = $query->orderBy($query->getModel()->getQualifiedKeyName())
                ->offset($arguments['offset'] ?? 0)->limit($limit + 1)->get();
            return ['items' => $records->take($limit)->map(fn ($record) => $this->serialize($resource, $record))->all(),
                'has_more' => $records->count() > $limit];
        }
        if ($operation === 'get') {
            return $this->serialize($resource, $query->whereKey($arguments['id'])->firstOrFail());
        }
        throw new LogicException('Unsupported operation.');
    }

    protected function write(ResourceDefinition $resource, string $operation, array $arguments, Authenticatable $user): array
    {
        $state = $resource->state();
        $query = $this->query($resource, $user);
        $fields = array_filter($resource->fields(), fn ($f) => $f['mcp']['write']);
        $allowed = $operation === 'delete' ? ['id'] : array_keys($fields);
        if ($operation === 'update') {
            $allowed[] = 'id';
        }
        if (array_diff(array_keys($arguments), $allowed)) {
            throw \Illuminate\Validation\ValidationException::withMessages(['arguments' => 'Unsupported arguments.']);
        }

        return $query->getConnection()->transaction(function () use ($resource, $operation, $arguments, $user, $state, $query, $fields) {
            if ($operation !== 'create') {
                Validator::make($arguments, ['id' => ['required', 'string', 'max:255']])->validate();
            }
            $record = $operation === 'create' ? $state['model']->newInstance()
                : $query->whereKey($arguments['id'])->lockForUpdate()->firstOrFail();
            $persistence = app(ModelPersistence::class);
            if ($operation === 'delete') {
                $id = (string) $record->getKey();
                if (!$persistence->delete($record)) {
                    throw new LogicException('Deletion was cancelled.');
                }
                return ['id' => $id, 'deleted' => true];
            }
            // Preserve the complete authoritative rule set, including custom rules.
            // Required protected fields fail closed instead of being silently ignored.
            $rules = $state['validation'];
            $builder = app(McpSchemaBuilder::class);
            foreach ($fields as $name => $field) {
                $fieldRules = $builder->rules($field['validation']);
                $type = $builder->type($field);
                $fieldRules[] = ['number' => 'numeric', 'boolean' => 'boolean'][$type] ?? $type;
                if ($field['tipo'] === 'date') {
                    $fieldRules[] = 'date_format:Y-m-d';
                }
                if ($field['tipo'] === 'datetime') {
                    $fieldRules[] = 'date_format:Y-m-d\TH:i:sP';
                }
                $choices = $builder->choices($field);
                if ($choices !== null) {
                    if ($type === 'array') {
                        $rules[$name.'.*'] = [Rule::in($choices)];
                    } else {
                        $fieldRules[] = Rule::in($choices);
                    }
                }
                if ($type === 'array') {
                    $fieldRules[] = 'max:100';
                    $rules[$name.'.*'][] = function ($attribute, $value, $fail) {
                        if (!is_scalar($value) && $value !== null) {
                            $fail('Nested objects are not accepted.');
                        }
                    };
                }
                if ($field['unique'] ?? false) {
                    $fieldRules[] = Rule::unique(get_class($record), $name)->ignore($record->exists ? $record->getKey() : null, $record->getKeyName());
                }
                unset($rules[$field['inputName']]);
                $rules[$name] = $fieldRules;
            }
            Validator::make($arguments, $rules)->validate();
            foreach ($fields as $name => $field) {
                if (array_key_exists($name, $arguments)) {
                    $record->setAttribute($name, $arguments[$name]);
                }
            }
            if ($column = $resource->config['company_column']) {
                $record->setAttribute($column, $user->empresaid);
            }
            if (!$persistence->save($record)) {
                throw new LogicException('Save was cancelled.');
            }
            // Reject and roll back writes that move records outside the resource scope.
            if (!$this->query($resource, $user)->whereKey($record->getKey())->exists()) {
                throw new AuthorizationException('Access denied.');
            }
            return $this->serialize($resource, $record->refresh());
        });
    }

    public function serialize(ResourceDefinition $resource, Model $model): array
    {
        $data = ['id' => (string) $model->getKey()];
        foreach ($resource->fields() as $name => $field) {
            if ($field['mcp']['read'] && !in_array($name, $model->getHidden(), true)) {
                $value = $model->getAttribute($name);
                // Arbitrary nested objects may contain secrets; no automatic traversal.
                if (is_scalar($value) || $value === null) {
                    $data[$name] = $value;
                } elseif ($value instanceof \DateTimeInterface) {
                    $data[$name] = $value->format($field['tipo'] === 'date' ? 'Y-m-d' : DATE_ATOM);
                } elseif (is_array($value) && array_is_list($value)
                    && count(array_filter($value, fn ($v) => !is_scalar($v) && $v !== null)) === 0) {
                    $data[$name] = $value;
                }
            }
        }
        return $data;
    }
}
