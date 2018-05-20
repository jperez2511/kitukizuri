<?php

namespace Icebearsoft\Kitukizuri\Models;

use Illuminate\Database\Eloquent\Model;

class UsuarioRol extends Model
{
    protected $table      = "usuarioRol";
    protected $primaryKey = "usuariorolid";
    protected $guarded    = ['usuariorolid'];

    public function modulos()
    {
        return $this->hasMany(RolModuloPermiso::class, 'rolid', 'rolid');
    }
}
