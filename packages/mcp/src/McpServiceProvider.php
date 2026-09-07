<?php

namespace Icebearsoft\Kitukizuri\Mcp;

use Icebearsoft\Kitukizuri\Mcp\Http\EnsureMcpContext;
use Icebearsoft\Kitukizuri\Mcp\Http\ExecutionContext;
use Icebearsoft\Kitukizuri\Mcp\Servers\KitukizuriServer;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Laravel\Mcp\Facades\Mcp;
use Icebearsoft\Kitukizuri\Mcp\Http\AuditMcpCall;
use Icebearsoft\Kitukizuri\Mcp\Console\InspectCommand;
use Icebearsoft\Kitukizuri\Mcp\Console\CacheCommand;

class McpServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/kitukizuri-mcp.php', 'kitukizuri-mcp');
        if (!config('logging.channels.kitukizuri-mcp')) {
            config(['logging.channels.kitukizuri-mcp' => [
                'driver' => 'daily', 'path' => storage_path('logs/kitukizuri-mcp.log'), 'days' => 14,
            ]]);
        }
    }

    public function boot(): void
    {
        $this->publishes([__DIR__.'/../config/kitukizuri-mcp.php' => config_path('kitukizuri-mcp.php')], 'krud-mcp-config');
        $this->commands([InspectCommand::class, CacheCommand::class]);
        if (!config('kitukizuri-mcp.enabled')) {
            return;
        }
        RateLimiter::for('kitukizuri-mcp', fn ($request) => Limit::perMinute(config('kitukizuri-mcp.per_minute'))
            ->by(app(ExecutionContext::class)->rateKey($request->user())));
        Mcp::web(config('kitukizuri-mcp.path'), KitukizuriServer::class)
            ->middleware(array_merge([AuditMcpCall::class], config('kitukizuri-mcp.middleware'), [EnsureMcpContext::class, 'throttle:kitukizuri-mcp']));
    }
}
