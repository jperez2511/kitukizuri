<?php

namespace Icebearsoft\Kitukizuri\App\Providers;

use Iluminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

class ForceHttpsProvider extends ServiceProvider
{
    
    public function register(): void
    {
        if(env('APP_FORCE_HTTPS', false)){
            $this->app['request']->server->set('HTTPS', true);
            URL::forceScheme('https');
        }
    }
}