<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use Icebearsoft\Kitukizuri\App\Traits\Krud\SeederTrait;

class ModulosSeeder extends Seeder 
{

	use SeederTrait;
	
	/**
	 * run
	 *
	 * @return void
	 */
	public function run()
	{
		$modulos = [];

		$modulos[] = ['nombre' => 'Home', 				'ruta' => 'home', 				'permisos' => [2]];
		$modulos[] = ['nombre' => 'Módulos', 			'ruta' => 'modulos', 			'permisos' => [1,2,3,4]];
		$modulos[] = ['nombre' => 'Permisos', 			'ruta' => 'permisos', 			'permisos' => [1,2,3,4]];
		$modulos[] = ['nombre' => 'Roles', 				'ruta' => 'roles', 				'permisos' => [1,2,3,4]];
		$modulos[] = ['nombre' => 'Rol Permisos', 		'ruta' => 'rolpermisos',		'permisos' => [1,2,3,4]];
		$modulos[] = ['nombre' => 'Usuarios', 			'ruta' => 'usuarios', 			'permisos' => [1,2,3,4]];
		$modulos[] = ['nombre' => 'Asignar Permisos', 	'ruta' => 'asignarpermiso', 	'permisos' => [1,2,3,4]];
		$modulos[] = ['nombre' => 'Empresas', 			'ruta' => 'empresas', 			'permisos' => [1,2,3,4]];
		$modulos[] = ['nombre' => 'Empresas - Módulos', 'ruta' => 'moduloempresas', 	'permisos' => [1,2,3,4]];
		$modulos[] = ['nombre' => 'Dashboard', 			'ruta' => 'dashboard', 			'permisos' => [2]];
		$modulos[] = ['nombre' => 'Sucursales', 		'ruta' => 'sucursales', 		'permisos' => [1,2,3,4]];
		$modulos[] = ['nombre' => 'Base de datos', 		'ruta' => 'database', 			'permisos' => [1,2,3,4]];
		$modulos[] = ['nombre' => 'Logs', 				'ruta' => 'logs', 				'permisos' => [1,2]];

		$this->saveModuleData($modulos);
	}
}
