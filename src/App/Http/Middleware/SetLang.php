<?php

namespace Icebearsoft\Kitukizuri\App\Http\Middleware;

use DB;
use Auth;
use Config;
use Session;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class SetLang
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if( !empty(session('lang')) ) {
            App::setLocale(session('lang'));
        } else {
            Session::put('lang', Auth::user()->defalut_lang ?? 'es');
            App::setLocale(Auth::user()->defalut_lang ?? 'es');
        }

        return $next($request);
    }
}
