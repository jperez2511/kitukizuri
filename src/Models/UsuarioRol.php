<?php

namespace Icebearsoft\Kitukizuri\Models;

use Illuminate\Database\Eloquent\Model;

class UsuarioRol extends Model
{
    protected $table      = "usuarioRol";
    protected $primaryKey = "usuariorolid";
    protected $guarded    = ['usuariorolid'];

    public static function getPermisosAsignados($usuarioId, $moduloId, $permisoId)
    {
        return DB::table('usuarioRol as uR')
            ->leftJoin('roles as r', 'r.rolid', 'uR.rolid')
            ->leftJoin('rolModuloPermiso as rMP', 'r.rolid', 'rMP.rolid')
            ->leftJoin('moduloPermiso as mP', 'mP.modulopermisoid', 'rMP.modulopermisoid')
            ->leftJoin('permisos as p', 'mP.permisoid', 'p.permisoid')
            ->where('uR.usuarioid', $usuarioId)
            ->where('mP.moduloid', $moduloId)
            ->whereIn('p.nombreLaravel', $permisoId)
            ->exists();
    }

    /**
     * modulos
     *
     * @return void
     */
    public function modulos()
    {
        return $this->hasMany(RolModuloPermiso::class, 'rolid', 'rolid');
    }
}
