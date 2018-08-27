<?php

namespace Icebearsoft\Kitukizuri\Models;

use Illuminate\Database\Eloquent\Model;

class ModuloEmpresas extends Model
{
	protected $table      = 'moduloEmpresas';
	protected $primaryKey = 'moduloempresaid';
	protected $guarded    = ['moduloempresaid'];
}
