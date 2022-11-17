<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Seeder;

class ModulosSeeder extends Seeder 
{
	private $modulos = [];
	
	/**
	 * run
	 *
	 * @return void
	 */
	public function run()
	{
		$this->modulos[] = ['nombre' => 'Home', 				'ruta' => 'home', 				'permisos' => [2]];
		$this->modulos[] = ['nombre' => 'Módulos', 				'ruta' => 'modulos', 			'permisos' => [1,2,3,4]];
		$this->modulos[] = ['nombre' => 'Permisos', 			'ruta' => 'permisos', 			'permisos' => [1,2,3,4]];
		$this->modulos[] = ['nombre' => 'Roles', 				'ruta' => 'roles', 				'permisos' => [1,2,3,4]];
		$this->modulos[] = ['nombre' => 'Rol Permisos', 		'ruta' => 'rolpermisos',		'permisos' => [1,2,3,4]];
		$this->modulos[] = ['nombre' => 'Usuarios', 			'ruta' => 'usuarios', 			'permisos' => [1,2,3,4]];
		$this->modulos[] = ['nombre' => 'Asignar Permisos', 	'ruta' => 'asignarpermiso', 	'permisos' => [1,2,3,4]];
		$this->modulos[] = ['nombre' => 'Empresas', 			'ruta' => 'empresas', 			'permisos' => [1,2,3,4]];
		$this->modulos[] = ['nombre' => 'Empresas - Módulos', 	'ruta' => 'moduloempresas', 	'permisos' => [1,2,3,4]];
		$this->modulos[] = ['nombre' => 'Dashboard', 			'ruta' => 'dashboard', 			'permisos' => [2]];
		$this->modulos[] = ['nombre' => 'Sucursales', 			'ruta' => 'sucursales', 		'permisos' => [1,2,3,4]];
		$this->modulos[] = ['nombre' => 'Base de datos', 		'ruta' => 'database', 			'permisos' => [1,2,3,4]];
		$this->modulos[] = ['nombre' => 'Logs', 				'ruta' => 'logs', 				'permisos' => [1,2]];

		$this->saveData();
	}

	private function saveData()
	{
		DB::beginTransaction();

		try {
			DB::statement('SET FOREIGN_KEY_CHECKS=0');
			DB::table('modulos')->truncate();
			
			foreach($this->modulos as $modulo)	{
				// validando si existe el modulo
				$moduloid = DB::table('modulos')
					->where('ruta',$modulo['ruta'])
					->value('moduloid');
	
				if(empty($moduloid)) {
					$moduloid = DB::table('modulos')->insertGetId([
						'nombre' => $modulo['nombre'],
						'ruta'   => $modulo['ruta']
					]);
				} else {
					DB::table('modulos')
						->where('moduloid',$moduloid)
						->update(['nombre' => $modulo['nombre'], 'ruta' => $modulo['ruta']]);
				} 
	
				// validando permisos
				$permisosActuales = DB::table('moduloPermiso')
					->select('modulopermisoid', 'permisoid')
					->where('moduloid', $moduloid)
					->get();
				
				// eliminando permisos si ya no existen
				foreach($permisosActuales as $permisoActual) {
					if(!in_array($permisoActual->permisoid, $modulo['permisos'])) {
						
						DB::table('moduloPermiso')
							->where('moduloid', $moduloid)
							->where('permisoid', $permisoActual->permisoid)
							->delete();
						
						// quitando asignación de permiso al rol
						DB::table('rolModuloPermiso')
							->where('modulopermisoid', $permisoActual->modulopermisoid)
							->delete();
					}
				}
	
				// agregando nuevos permisos
				$actuales = $permisosActuales->pluck('permisoid')->toArray();
				foreach($modulo['permisos'] as  $permiso) {
					if(!in_array($permiso, $actuales)) {
						DB::table('moduloPermiso')->insert([
							'moduloid' => $moduloid,
							'permisoid' => $permiso
						]);
					}
				}
			}
	
			DB::statement('UPDATE modulos SET created_at=NOW(), updated_at=NOW()');
			DB::statement('UPDATE moduloPermiso SET created_at=NOW(), updated_at=NOW()');
			DB::statement('SET FOREIGN_KEY_CHECKS=1');
			
			DB::commit();
		} catch (\Exception $e) {
			DB::rollback();
			dd($e->getMessage());
		}
	}
}
