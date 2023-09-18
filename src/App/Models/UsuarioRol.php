<?php

namespace Icebearsoft\Kitukizuri\App\Models;

use DB;
use Illuminate\Database\Eloquent\Model;

class UsuarioRol extends Model
{
    protected $table      = "usuarioRol";
    protected $primaryKey = "usuariorolid";
    protected $guarded    = ['usuariorolid'];

    public static function getPermisosAsignados($usuarioId, $moduloId, $permisoId = null)
    {
        $query =  DB::table('usuarioRol as uR')
            ->select('p.nombreLaravel')
            ->leftJoin('roles as r', 'r.rolid', 'uR.rolid')
            ->leftJoin('rolModuloPermiso as rMP', 'r.rolid', 'rMP.rolid')
            ->leftJoin('moduloPermiso as mP', 'mP.modulopermisoid', 'rMP.modulopermisoid')
            ->leftJoin('permisos as p', 'mP.permisoid', 'p.permisoid')
            ->where('uR.usuarioid', $usuarioId)
            ->where('mP.moduloid', $moduloId)
            ->groupBy('mP.moduloid', 'p.nombreLaravel');

        $query = !empty($permisoId) ?  
            $query->whereIn('p.nombreLaravel', $permisoId)->exists() : 
            $query->get();
        
        return $query;
    }

    public static function getModuloPermisoID($usuarioId, $nombreLaravel) 
    {
        return DB::table('usuarioRol as uR')
            ->select('mP.modulopermisoid')
            ->leftJoin('roles as r', 'r.rolid', 'uR.rolid')
            ->leftJoin('rolModuloPermiso as rMP', 'r.rolid', 'rMP.rolid')
            ->leftJoin('moduloPermiso as mP', 'mP.modulopermisoid', 'rMP.modulopermisoid')
            ->leftJoin('permisos as p', 'mP.permisoid', 'p.permisoid')
            ->where('uR.usuarioid', $usuarioId)
            ->where('p.nombreLaravel', $nombreLaravel)
            ->orderBy('mP.modulopermisoid')
            ->groupBy('mP.modulopermisoid')
            ->get();        
    }

    /**
     * módulos
     *
     * @return void
     */
    public function modulos()
    {
        return $this->hasMany(RolModuloPermiso::class, 'rolid', 'rolid');
    }
}
