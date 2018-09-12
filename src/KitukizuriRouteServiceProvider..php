<?php

namespace Icebearsoft\Kitukizuri;

use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Route;

class KitukizuriRouteServiceProvider extends RouteServiceProvider
{
    protected $namespace = 'Kitukizuri';

    public function boot()
    {
        parent::boot();
    }

    public function map()
    {
        $this->mapWebRoutes();
    }

    protected function mapWebRoutes()
    {
        Route::prefix('kk')
            ->middleware(['web', 'auth', 'kitukizuri'])
            ->namespace($this->namespace)
            ->group(__DIR__.'/Http/routes/web.php');
    }
}