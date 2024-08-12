<?php
namespace Icebearsoft\Kitukizuri\App\Http\Middleware;

use DB;
use Config;
use Closure;
use Session;
use Icebearsoft\Kitukizuri\App\Models\Tenant as Tts;

use Icebearsoft\Kitukizuri\App\Models\Empresa;

class Tenant
{

    public function handle($request, Closure $next)
    {

        if(Config::get('kitukizuri.multiTenants') !== true) {
            return $next($request);
        }

        $dominio = $request->server()['HTTP_HOST'];

        $tenant = Tts::where('dominio', $dominio)->where('activo', 1)->first();

        if (!$tenant) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'No se pudo encontrar ' . $dominio], 404);
            } else {
                return response()->view('errors.404', ['message' => 'La dirección que buscas no esta registrada']);
            }
        }

        $connection = [];
        
        Config::set("database.connections.".'mysql', [
            'driver'   => 'mysql',
            'database' => $tenant->db,
            'host'     => $tenant->db_host,
            'username' => $tenant->db_username,
            'password' => $tenant->db_password,
            'tenantid' => $tenant->tenant_id,
        ]);

        Config::set("database.connections.".'mongodb', [
            'driver'   => 'mongodb',
            'database' => $tenant->mongo_db,
            'host'     => $tenant->mongo_db_host,
            'username' => $tenant->mongo_db_username,
            'password' => $tenant->mongo_db_password,
            'tenantid' => $tenant->tenant_id,
        ]);

        DB::purge();
        DB::reconnect();
    
        $empresa = Empresa::orderBy('empresaid','desc')->first();
        Session::put('empresa', $empresa);

        return $next($request);
    }
}
