<?php
/**
 * Created by PhpStorm.
 * User: jperez
 * Date: 7/09/17
 * Time: 12:18 AM
 */

use Illuminate\Database\Seeder;

class ModuloPermisosSeeder extends Seeder 
{
	/**
	 * run
	 *
	 * @return void
	 */
	public function run()
	{
		DB::statement('SET FOREIGN_KEY_CHECKS=0');
		DB::table('moduloPermiso')->truncate();

		DB::table('moduloPermiso')->insert([
			//== Home
			['modulopermisoid' => 1, 'moduloid' => 1, 'permisoid'=> 2],
			//== Modulos
			['modulopermisoid' => 2, 'moduloid' => 2, 'permisoid'=> 1],
			['modulopermisoid' => 3, 'moduloid' => 2, 'permisoid'=> 2],
			['modulopermisoid' => 4, 'moduloid' => 2, 'permisoid'=> 3],
			['modulopermisoid' => 5, 'moduloid' => 2, 'permisoid'=> 4],
			//== Permisos
			['modulopermisoid' => 6, 'moduloid' => 3, 'permisoid'=> 1],
			['modulopermisoid' => 7, 'moduloid' => 3, 'permisoid'=> 2],
			['modulopermisoid' => 8, 'moduloid' => 3, 'permisoid'=> 3],
			['modulopermisoid' => 9, 'moduloid' => 3, 'permisoid'=> 4],
			//== Roles
			['modulopermisoid' => 10, 'moduloid' => 4, 'permisoid'=> 1],
			['modulopermisoid' => 11, 'moduloid' => 4, 'permisoid'=> 2],
			['modulopermisoid' => 12, 'moduloid' => 4, 'permisoid'=> 3],
			['modulopermisoid' => 13, 'moduloid' => 4, 'permisoid'=> 4],
			//== Rol Permisos
			['modulopermisoid' => 14, 'moduloid' => 5, 'permisoid'=> 1],
			['modulopermisoid' => 15, 'moduloid' => 5, 'permisoid'=> 2],
			['modulopermisoid' => 16, 'moduloid' => 5, 'permisoid'=> 3],
			['modulopermisoid' => 17, 'moduloid' => 5, 'permisoid'=> 4],
			//== Usuarios
			['modulopermisoid' => 18, 'moduloid' => 6, 'permisoid'=> 1],
			['modulopermisoid' => 19, 'moduloid' => 6, 'permisoid'=> 2],
			['modulopermisoid' => 20, 'moduloid' => 6, 'permisoid'=> 3],
			['modulopermisoid' => 21, 'moduloid' => 6, 'permisoid'=> 4],
			//== AsignarPermisos
			['modulopermisoid' => 22, 'moduloid' => 7, 'permisoid'=> 1],
			['modulopermisoid' => 23, 'moduloid' => 7, 'permisoid'=> 2],
			['modulopermisoid' => 24, 'moduloid' => 7, 'permisoid'=> 3],
			['modulopermisoid' => 25, 'moduloid' => 7, 'permisoid'=> 4],
			//== Empresas
			['modulopermisoid' => 26, 'moduloid' => 8, 'permisoid'=> 1],
			['modulopermisoid' => 27, 'moduloid' => 8, 'permisoid'=> 2],
			['modulopermisoid' => 28, 'moduloid' => 8, 'permisoid'=> 3],
			['modulopermisoid' => 29, 'moduloid' => 8, 'permisoid'=> 4],
			//== Modulos Empresas
			['modulopermisoid' => 30, 'moduloid' => 9, 'permisoid'=> 1],
			['modulopermisoid' => 31, 'moduloid' => 9, 'permisoid'=> 2],
			['modulopermisoid' => 32, 'moduloid' => 9, 'permisoid'=> 3],
			['modulopermisoid' => 33, 'moduloid' => 9, 'permisoid'=> 4],
			//== Dashboard
			['modulopermisoid' => 34, 'moduloid' => 10, 'permisoid'=> 2],
		]);

		DB::statement('UPDATE moduloPermiso SET created_at=NOW(), updated_at=NOW()');
		DB::statement('SET FOREIGN_KEY_CHECKS=1');
	}
}
