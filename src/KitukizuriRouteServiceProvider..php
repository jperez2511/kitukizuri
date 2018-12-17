<?php

namespace Icebearsoft\Kitukizuri;

use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Route;

class KitukizuriRouteServiceProvider extends RouteServiceProvider
{
    protected $namespace = 'Icebearsoft\Kitukizuri\Http\Controllers';

    /**
     * boot
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
    }

    /**
     * map
     *
     * @return void
     */
    public function map()
    {
        $this->mapWebRoutes();
    }

    /**
     * mapWebRoutes
     *
     * @return void
     */
    protected function mapWebRoutes()
    {
        Route::prefix('kk')
            ->middleware(['web', 'auth', 'kitukizuri'])
            ->namespace($this->namespace)
            ->group(__DIR__.'/Http/routes/kitukizuri.php');
    }
}