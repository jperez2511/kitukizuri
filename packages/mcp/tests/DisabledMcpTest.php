<?php

namespace Icebearsoft\Kitukizuri\Mcp\Tests;

use Illuminate\Support\Facades\Route;
use Orchestra\Testbench\TestCase;

class DisabledMcpTest extends TestCase
{
    protected function getPackageProviders($app): array
    {
        $package = json_decode(file_get_contents(__DIR__.'/../../../composer.json'), true, flags: JSON_THROW_ON_ERROR);

        return array_merge([\Laravel\Mcp\Server\McpServiceProvider::class], array_values(array_filter(
            $package['extra']['laravel']['providers'],
            fn (string $provider): bool => str_contains($provider, '\\Mcp\\'),
        )));
    }

    public function test_installation_keeps_endpoint_disabled_and_inspection_available(): void
    {
        $this->assertFalse(config('kitukizuri-mcp.enabled'));
        $this->assertFalse(collect(Route::getRoutes())->contains(
            fn ($route): bool => $route->uri() === 'mcp/kitukizuri',
        ));
        $this->artisan('krud:mcp:inspect')->assertSuccessful();
    }
}
