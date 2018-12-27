<?php

namespace Icebearsoft\Kitukizuri\Models;

use DB;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $table      = "menu";
	protected $primaryKey = "menuid";
	protected $guarded    = ['menuid'];

	/**
	 * getPapas
	 * Obtiene todos los elementos del menu cuando
	 * el papa sea igual a nullab
	 * 
	 * @return void
	 */
	public static function getPapas()
	{
		return DB::table('menu')
		  ->whereNull('padreid')
		  ->orderBy('orden')
		  ->get();
	}

	/**
	 * getHijos
	 * Obtiene los elementos con padre id 
	 * 
	 * @param  mixed $menuid
	 *
	 * @return void
	 */
	public static function getHijos($menuid)
	{
		return DB::table('menu')
			->where('padreid', $menuid)
			->orderBy('orden')
		  	->get();
	}
}
