<?php

namespace Icebearsoft\Kitukizuri\Mcp\Schema;

use Icebearsoft\Kitukizuri\Mcp\Metadata\ResourceDefinition;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Illuminate\JsonSchema\Types\Type;

/** Conservative translation. Laravel validation remains authoritative. Bind a subclass to extend. */
class McpSchemaBuilder
{
    public function rules(mixed $rules): array
    {
        return is_string($rules) ? explode('|', $rules) : ($rules ?? []);
    }

    public function type(array $field): string
    {
        $rules = $this->rules($field['validation']);
        foreach (['array', 'integer', 'numeric', 'boolean', 'string'] as $rule) {
            if (in_array($rule, $rules, true)) {
                return ['numeric' => 'number', 'boolean' => 'boolean'][$rule] ?? $rule;
            }
        }
        if (($field['multiple'] ?? false) || ($field['htmlAttr']?->get('multiple') ?? false)) {
            return 'array';
        }
        return ['numeric' => 'number', 'bool' => 'boolean'][$field['tipo']] ?? 'string';
    }

    public function choices(array $field): ?array
    {
        foreach ($this->rules($field['validation']) as $rule) {
            if (is_string($rule) && str_starts_with($rule, 'in:')) {
                return str_getcsv(substr($rule, 3), ',', '"', '\\');
            }
        }
        if (isset($field['enumArray']) && array_is_list($field['enumArray'])) {
            return $field['enumArray'];
        }
        if (in_array($field['tipo'], ['combobox', 'select2'], true) && $field['collect'] !== null) {
            return $field['collect']->pluck($field['column'][0] ?? 'id')->all();
        }
        return null;
    }

    public function schema(ResourceDefinition $resource, string $operation, JsonSchema $schema): array
    {
        if ($operation === 'list') {
            return ['limit' => $schema->integer()->min(1)->max(100), 'offset' => $schema->integer()->min(0)->max(100000)];
        }
        $result = $operation === 'create' ? [] : ['id' => $schema->string()->max(255)->required()];
        if (in_array($operation, ['create', 'update'], true)) {
            foreach ($resource->fields() as $name => $field) {
                if ($field['mcp']['write']) {
                    if ($name === 'id') {
                        throw new \LogicException('The id argument is reserved.');
                    }
                    $result[$name] = $this->field($field, $schema);
                }
            }
        }
        return $result;
    }

    protected function field(array $field, JsonSchema $schema): Type
    {
        $type = $this->type($field);
        $property = $schema->{$type}();
        $rules = $this->rules($field['validation']);
        $choices = $this->choices($field);
        if ($type === 'array') {
            $item = $schema->string();
            if ($choices !== null && $choices !== []) {
                $item = is_int($choices[0]) ? $schema->integer() : $schema->string();
                $item->enum($choices);
            }
            $property->items($item);
        } elseif ($choices !== null && $choices !== []) {
            // Do not coerce enum strings into numbers unless every value is safe.
            if ($type === 'integer' && count(array_filter($choices, fn ($v) => filter_var($v, FILTER_VALIDATE_INT) !== false)) === count($choices)) {
                $property->enum(array_map('intval', $choices));
            } elseif ($type === 'string') {
                $property->enum(array_map('strval', $choices));
            }
        }
        foreach ($rules as $rule) {
            if (!is_string($rule)) {
                continue;
            }
            if ($rule === 'required' && !in_array('sometimes', $rules, true)) {
                $property->required();
            } elseif ($rule === 'nullable') {
                $property->nullable();
            } elseif (preg_match('/^(min|max):(-?\d+(?:\.\d+)?)$/D', $rule, $matches)) {
                $explicit = ['number' => 'numeric', 'integer' => 'integer', 'string' => 'string', 'array' => 'array'][$type] ?? null;
                if ($explicit !== null && in_array($explicit, $rules, true)) {
                    $property->{$matches[1]}(in_array($type, ['string', 'array'], true) ? (int) $matches[2] : (float) $matches[2]);
                }
            }
        }
        if ($type === 'string' && in_array($field['tipo'], ['date', 'datetime'], true)) {
            $property->format($field['tipo'] === 'date' ? 'date' : 'date-time');
        }
        return $property->description($field['mcp']['description'] ?? $field['nombre']);
    }
}
