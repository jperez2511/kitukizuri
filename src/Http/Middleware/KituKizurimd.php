<?php

namespace Icebearsoft\Kitukizuri\Http\Middleware;

use Kitukizuri;
use Closure;
use Auth, Route;

class KituKizurimd {
    public function handle($request, Closure $next) {
			if (Auth::guest())
					return redirect()->guest('/login');
			$continue = KituKizuri::permiso(Route::currentRouteName());
			if (!$continue)
				abort(401);
        return $next($request);
    }
}
