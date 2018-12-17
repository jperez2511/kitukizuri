<?php

namespace Icebearsoft\Kitukizuri\Http\Middleware;

use Kitukizuri;
use Closure;
use Auth, Route;

class KituKizurimd 
{
	/**
	 * handle
	 *
	 * @param  mixed $request
	 * @param  mixed $next
	 *
	 * @return void
	 */
	public function handle($request, Closure $next) 
	{
		if (Auth::guest()) {
			return redirect()->guest('/login');
		}
		//== Validando Modulo empresa 
		$me = KituKizuri::validar(Route::currentRouteName());

		//== Vaidando Permisos
		$continue = KituKizuri::permiso(Route::currentRouteName());        
		
		if (!$me) {
			abort(401);
		}

		if (!$continue) {
			abort(401);
		}

        return $next($request);
    }
}
