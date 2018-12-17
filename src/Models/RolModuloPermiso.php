<?php

namespace Icebearsoft\Kitukizuri\Models;

use Illuminate\Database\Eloquent\Model;

class RolModuloPermiso extends Model
{
    protected $table = "rolModuloPermiso";
    protected $primaryKey = 'rolmodulopermisoid';
    protected $guarded = ['rolmodulopermisoid'];

    /**
     * rol
     *
     * @return void
     */
    public function rol()
    {
        return $this->hasOne(Rol::class, 'rolid', 'rolid');
    }
    
    /**
     * modulopermiso
     *
     * @return void
     */
    public function modulopermiso()
    {
        return $this->hasMany(ModuloPermiso::class, 'modulopermisoid', 'modulopermisoid');
    }
}
