<?php

namespace Icebearsoft\Kitukizuri\App\Traits\Krud;

use DB;

trait SeederTrait
{
    protected function saveModuleData()
    {
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
		} catch (\Exception $e) {
			dd($e->getMessage());
		}
    }
}