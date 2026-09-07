<?php

namespace Icebearsoft\Kitukizuri\Mcp\Console;

use Icebearsoft\Kitukizuri\Mcp\Registry\McpResourceRegistry;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class CacheCommand extends Command
{
    protected $signature = 'krud:mcp:cache {--clear : Clear the route index instead of rebuilding it}';
    protected $description = 'Cache the MCP route index; never caches user permissions or field values';

    public function handle(McpResourceRegistry $registry): int
    {
        $key = config('kitukizuri-mcp.cache_key');
        $this->option('clear') ? Cache::forget($key) : Cache::forever($key, $registry->routeIndex());
        $this->info($this->option('clear') ? 'MCP route index cleared.' : 'MCP route index cached.');
        return self::SUCCESS;
    }
}
