<?php
/**
 * Created by PhpStorm.
 * User: jperez
 * Date: 7/09/17
 * Time: 12:18 AM
 */

use Illuminate\Database\Seeder;

class ModulosSeeder extends Seeder {
	public function run(){
		DB::statement('SET FOREIGN_KEY_CHECKS=0');
		DB::table('modulos')->truncate();

		DB::table('modulos')->insert([
			['moduloid' => 1,	'nombre' => 'Home', 'ruta'=>'home'],
			['moduloid' => 2,	'nombre' => 'Modulos', 'ruta'=>'modulos'],
			['moduloid' => 3,	'nombre' => 'Permisos', 'ruta'=>'permisos'],
			['moduloid' => 4,	'nombre' => 'Roles', 'ruta'=>'roles'],
			['moduloid' => 5,	'nombre' => 'Roles', 'ruta'=>'rolpermisos'],
			['moduloid' => 6,	'nombre' => 'Rol Permisos', 'ruta'=>'usuarios'],
			['moduloid' => 7,	'nombre' => 'AsignarPermisos', 'ruta'=>'asignarpermiso'],
		]);

		DB::statement('UPDATE modulos SET created_at=NOW(), updated_at=NOW()');
		DB::statement('SET FOREIGN_KEY_CHECKS=1');
	}
}
