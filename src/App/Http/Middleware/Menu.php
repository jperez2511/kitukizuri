<?php

namespace Icebearsoft\Kitukizuri\App\Http\Middleware;

use Route;
use Session;
use Closure;

// Controller
use Icebearsoft\Kitukizuri\Http\Controllers\MenuController;

class Menu
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $menu = new MenuController;
        $tree = $menu->construirMenu();
        Session::put('menu', $tree);    
        return $next($request);
    }
}
