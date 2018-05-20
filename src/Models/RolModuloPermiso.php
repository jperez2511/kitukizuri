<?php

namespace Icebearsoft\Kitukizuri\Models;

use Illuminate\Database\Eloquent\Model;

class RolModuloPermiso extends Model
{
    protected $table = "rolModuloPermiso";
    protected $primaryKey = 'rolmodulopermisoid';
    protected $guarded = ['rolmodulopermisoid'];

    public function rol()
    {
        return $this->hasOne(Rol::class, 'rolid', 'rolid');
    }
    public function modulopermiso()
    {
        return $this->hasMany(ModuloPermiso::class, 'modulopermisoid', 'modulopermisoid');
    }
}
