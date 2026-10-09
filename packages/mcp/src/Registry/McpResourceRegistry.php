<?php

namespace Icebearsoft\Kitukizuri\Mcp\Registry;

use Icebearsoft\Kitukizuri\Krud;
use Icebearsoft\Kitukizuri\Mcp\Concerns\ExposesMcp;
use Icebearsoft\Kitukizuri\Mcp\Metadata\ResourceDefinition;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;
use LogicException;

class McpResourceRegistry
{
    public function routeIndex(): array
    {
        $index = [];
        foreach (Route::getRoutes() as $route) {
            $name = $route->getName();
            $class = $route->getAction('controller');
            if (!$name || !is_string($class)) {
                continue;
            }
            $class = explode('@', $class)[0];
            if (!is_subclass_of($class, Krud::class)
                || !in_array(ExposesMcp::class, class_uses_recursive($class), true)) {
                continue;
            }
            $module = explode('.', $name)[0];
            if (isset($index[$module]) && $index[$module] !== $class) {
                throw new LogicException('Ambiguous MCP module route.');
            }
            $index[$module] = $class;
        }
        ksort($index);
        return $index;
    }

    public function resources(): array
    {
        $index = Cache::get(config('kitukizuri-mcp.cache_key')) ?? $this->routeIndex();
        $resources = [];
        foreach ($index as $module => $class) {
            $config = $class::mcpConfiguration();
            if (($config['enabled'] ?? false) !== true) {
                continue;
            }
            $operations = $config['operations'] ?? [];
            if (!preg_match('/^[a-z][a-z0-9_-]{0,47}$/D', $module)
                || array_diff($operations, ['list', 'get', 'create', 'update', 'delete'])) {
                throw new LogicException('Invalid MCP module name or operation.');
            }
            $resources[$module] = new ResourceDefinition($module, $class, $config);
        }
        return $resources;
    }
}
