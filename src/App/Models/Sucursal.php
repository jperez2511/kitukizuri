<?php

namespace Icebearsoft\Kitukizuri\App\Models;

use Illuminate\Database\Eloquent\Model;

class Sucursal extends Model
{
    protected $table      = "sucursales";
	protected $primaryKey = "sucursalid";
	protected $guarded    = ['sucursalid'];
}
