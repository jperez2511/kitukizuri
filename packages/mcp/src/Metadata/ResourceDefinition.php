<?php

namespace Icebearsoft\Kitukizuri\Mcp\Metadata;

use Icebearsoft\Kitukizuri\Krud;
use Icebearsoft\Kitukizuri\Mcp\Http\ExecutionContext;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use LogicException;
use ReflectionMethod;

class ResourceDefinition
{
    private ?array $snapshot = null;

    public function __construct(public string $module, public string $controller, public array $config) {}

    public function state(): array
    {
        if ($this->snapshot !== null) {
            return $this->snapshot;
        }
        $controller = app()->make($this->controller);
        $state = $controller->resourceState();
        if (!$state['model'] instanceof Model || !$state['query'] instanceof Builder || $state['errors']) {
            throw new LogicException('Invalid Krud resource metadata.');
        }
        app(ExecutionContext::class)->assertConnection($state['model']);
        app(ExecutionContext::class)->assertConnection($state['query']->getModel());
        if (!array_key_exists('company_column', $this->config)
            || ($this->config['company_column'] !== false && !preg_match('/^[a-zA-Z_][a-zA-Z0-9_]*$/D', $this->config['company_column']))) {
            throw new LogicException('Declare a company_column, or false for an explicitly shared resource.');
        }
        if (app(FieldPolicy::class)->sensitive($state['model']->getKeyName())
            || in_array($state['model']->getKeyName(), $state['model']->getHidden(), true)) {
            throw new LogicException('Sensitive primary keys cannot be exposed.');
        }
        $state['fields'] = app(FieldPolicy::class)->fields($state, $controller->mcpFieldConfiguration());
        if ($this->config['company_column'] !== false) {
            unset($state['fields'][$this->config['company_column']]);
        }
        $query = $state['query']->getQuery();
        $state['complex'] = $state['complex'] || $query->groups || $query->havings || $query->unions
            || $query->limit !== null || $query->offset !== null
            || $query->from !== $state['model']->getTable();
        foreach (['store', 'destroy', 'show', 'getData', 'index', 'edit', 'create'] as $method) {
            if ((new ReflectionMethod($this->controller, $method))->getDeclaringClass()->getName() !== Krud::class) {
                $state['complex'] = true;
            }
        }
        return $this->snapshot = $state;
    }

    public function fields(): array
    {
        return $this->state()['fields'];
    }

    public function description(string $operation): string
    {
        $purpose = [
            'list' => 'Lists a bounded page of accessible records. Use when the user asks to browse records.',
            'get' => 'Retrieves one accessible record by its ID. Use when the user requests details of an existing record.',
            'create' => 'Creates a record. Use only when the user explicitly requests a new record; collect required fields first.',
            'update' => 'Updates an existing accessible record by ID. Submit all required fields; omitted optional fields are unchanged.',
            'delete' => 'Deletes an existing accessible record by ID. This operation may permanently remove data.',
        ];
        return $this->config['descriptions'][$operation]
            ?? trim(($this->config['description'] ?? $this->module).'. '.$purpose[$operation]
                .' Access is restricted to the authenticated server context.');
    }
}
