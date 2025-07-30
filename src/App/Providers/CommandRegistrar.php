<?php

namespace Icebearsoft\Kitukizuri\App\Providers;

use Illuminate\Console\Command;
use Illuminate\Support\ServiceProvider;

Class CommandRegistrar extends ServiceProvider
{
    public function boot()
    {
        $directory = __DIR__ . '/../../App/Console/Command';
        $commands  = collect(glob($directory . '/*.php'))
            ->map(function ($file) {
                $class = 'Icebearsoft\Kitukizuri\App\Console\Command\\' . basename($file, '.php');
                return \class_exists($class) && \is_subclass_of($class, Command::class) ? $class : null;
            })->filter()
            ->values()
            ->all();

        if ($this->app->runningInConsole()) {
            $this->commands($commands);
        }
    }
}