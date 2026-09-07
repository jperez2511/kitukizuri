<?php

namespace Icebearsoft\Kitukizuri\Mcp\Servers;

use Icebearsoft\Kitukizuri\Mcp\Registry\McpResourceRegistry;
use Icebearsoft\Kitukizuri\Mcp\Tools\KrudTool;
use Laravel\Mcp\Server;

class KitukizuriServer extends Server
{
    protected string $name = 'Kitukizuri';
    protected string $version = '0.1.0';
    protected string $instructions = 'Use only the available tools for the authenticated user. IDs are strings. Tenant and company are selected by the server. Writes require explicit user intent.';

    protected function boot(): void
    {
        foreach (app(McpResourceRegistry::class)->resources() as $resource) {
            foreach (array_unique($resource->config['operations']) as $operation) {
                $this->tools[] = new KrudTool($resource, $operation);
            }
        }
    }
}
