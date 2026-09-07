<?php

namespace Icebearsoft\Kitukizuri\Mcp\Console;

use Icebearsoft\Kitukizuri\Mcp\Authorization\McpPermissionResolver;
use Icebearsoft\Kitukizuri\Mcp\Registry\McpResourceRegistry;
use Illuminate\Console\Command;

class InspectCommand extends Command
{
    protected $signature = 'krud:mcp:inspect {module?}';
    protected $description = 'Inspect configured MCP tools (metadata only, no user impersonation)';

    public function handle(McpResourceRegistry $registry, McpPermissionResolver $permissions): int
    {
        $rows = [];
        foreach ($registry->resources() as $resource) {
            if ($this->argument('module') && $this->argument('module') !== $resource->module) {
                continue;
            }
            foreach ($resource->config['operations'] as $operation) {
                $rows[] = [$resource->module.'.'.$operation, $resource->module.'.'.$permissions->permission($operation),
                    in_array($operation, ['list', 'get'], true) ? 'yes' : 'no',
                    in_array($operation, ['update', 'delete'], true) ? 'yes' : 'no'];
            }
        }
        $this->info('Configured metadata; runtime discovery is filtered by the authenticated user.');
        $this->table(['Tool', 'Kitukizuri permission', 'Read-only', 'Destructive'], $rows);
        return self::SUCCESS;
    }
}
