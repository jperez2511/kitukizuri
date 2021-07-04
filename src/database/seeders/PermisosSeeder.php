<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Seeder;

class PermisosSeeder extends Seeder 
{
	/**
	 * run
	 *
	 * @return void
	 */
	public function run()
	{
		DB::statement('SET FOREIGN_KEY_CHECKS=0');
		DB::table('permisos')->truncate();

		DB::table('permisos')->insert([
			['permisoid' => 1,	'nombre' => 'Crear', 'nombreLaravel'=>'create', 'color' => 'has-success'],
			['permisoid' => 2, 	'nombre' => 'Leer', 'nombreLaravel'=>'show', 'color' => ''],
			['permisoid' => 3, 	'nombre' => 'Editar','nombreLaravel'=>'edit' , 'color' => 'has-warning'],
			['permisoid' => 4, 	'nombre' => 'Eliminar', 'nombreLaravel'=>'destroy', 'color' => 'has-danger'],
		]);

		DB::statement('UPDATE permisos SET created_at=NOW(), updated_at=NOW()');
		DB::statement('SET FOREIGN_KEY_CHECKS=1');
	}
}
