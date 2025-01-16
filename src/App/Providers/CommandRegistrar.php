<?php

namespace Icebearsoft\Kitukizuri\App\Providers;

use Illuminate\Support\ServiceProvider;

Class CommandRegistrar extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                \Icebearsoft\Kitukizuri\App\Console\Command\MakeModule::class,
                \Icebearsoft\Kitukizuri\App\Console\Command\KrudInstall::class,
                \Icebearsoft\Kitukizuri\App\Console\Command\VueInstall::class,
                \Icebearsoft\Kitukizuri\App\Console\Command\TsInstall::class,
                \Icebearsoft\Kitukizuri\App\Console\Command\LogInstall::class,
                \Icebearsoft\Kitukizuri\App\Console\Command\DefaultData::class,
                \Icebearsoft\Kitukizuri\App\Console\Command\SetDocker::class,
                \Icebearsoft\Kitukizuri\App\Console\Command\LibsInstall::class,
                \Icebearsoft\Kitukizuri\App\Console\Command\UiConfig::class,
                \Icebearsoft\Kitukizuri\App\Console\Command\MigrateTTS::class,
                \Icebearsoft\Kitukizuri\App\Console\Command\SeedTTS::class,
            ]);
        }
    }
}