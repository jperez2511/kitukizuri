<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Seeder;
use Icebearsoft\Kitukizuri\App\Traits\Krud\SeederTrait;

class PermisosSeeder extends Seeder 
{
	use SeederTrait;
	
	/**
	 * run
	 *
	 * @return void
	 */
	public function run()
	{
		$dateTime = $this->sqlDateTime();
		$this->checkForeignKeys();
		DB::table('permisos')->truncate();

		DB::table('permisos')->insert([
			['permisoid' => 1,	'nombre' => 'Crear', 'nombreLaravel'=>'create', 'color' => 'has-success'],
			['permisoid' => 2, 	'nombre' => 'Leer', 'nombreLaravel'=>'show', 'color' => ''],
			['permisoid' => 3, 	'nombre' => 'Editar','nombreLaravel'=>'edit' , 'color' => 'has-warning'],
			['permisoid' => 4, 	'nombre' => 'Eliminar', 'nombreLaravel'=>'destroy', 'color' => 'has-danger'],
		]);

		DB::statement('UPDATE permisos SET created_at='.$dateTime.', updated_at='.$dateTime);		

		if($connectionName === 'mysql') {
			$this->checkForeignKeys(1);
		}
	}
}
