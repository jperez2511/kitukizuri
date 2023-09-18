<?php

namespace Icebearsoft\Kitukizuri\App\Models;

use Illuminate\Database\Eloquent\Model;

class ModuloEmpresas extends Model
{
	protected $table      = 'moduloEmpresas';
	protected $primaryKey = 'moduloempresaid';
	protected $guarded    = ['moduloempresaid'];
}
