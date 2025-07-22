<?php

namespace Icebearsoft\Kitukizuri\App\Traits\Krud;

use DB;

trait SeederTrait
{
	private $menu = [];

	protected function checkForeignKeys($value = 0) {

		$connection = env('DB_CONNECTION', 'mysql');

		if(!empty($connection) && $connection === 'mysql') {
			DB::statement('SET FOREIGN_KEY_CHECKS='.$value);
		} else if($connection === 'sqlite') {
			if ($value === 1) {
				DB::statement('PRAGMA foreign_keys = ON');	
			} else {
				DB::statement('PRAGMA foreign_keys = OFF');	
			}
		} else if(!empty(env('TENANTS_CONNECTION'))) {
			DB::statement('SET FOREIGN_KEY_CHECKS='.$value);
		} else if ($connection === 'pgsql') {
			$schema = config('database.connections.pgsql.search_path', 'public');
			$tables = DB::select("SELECT tablename FROM pg_tables WHERE schemaname = ?", [$schema]);

			foreach ($tables as $table) {
				$tableName = $table->tablename;

				if ($value === 0) {
					DB::statement("ALTER TABLE \"$schema\".\"$tableName\" DISABLE TRIGGER ALL");
				} else {
					DB::statement("ALTER TABLE \"$schema\".\"$tableName\" ENABLE TRIGGER ALL");
				}
			}
		}
	}

	protected function sqlDateTime() {
		$connectionName = env('DB_CONNECTION');
		if($connectionName === 'pgsql' || $connectionName === 'mysql' || !empty(env('TENANTS_CONNECTION'))) {
			return 'NOW()';
		} else if($connectionName === 'sqlite') {
			return 'datetime(\'now\')';
		}
	}

    protected function saveModuleData($modulos)
    {
        try {
			$dateTime = $this->sqlDateTime();
			$this->checkForeignKeys();
			
			if ($connection === 'pgsql') {
				DB::statement('TRUNCATE TABLE modulos RESTART IDENTITY CASCADE');
			} else {
				DB::table('modulos')->truncate();
			}
			
			foreach($modulos as $modulo)	{
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
	
			DB::statement('UPDATE modulos SET created_at='.$dateTime.', updated_at='.$dateTime);
			DB::statement('UPDATE moduloPermiso SET created_at='.$dateTime.', updated_at='.$dateTime);
			$this->checkForeignKeys(1);
		} catch (\Exception $e) {
			dd($e->getMessage());
		}
    }

	protected function saveMenuData($menu = null, $padreID = null)
	{
		$dateTime = $this->sqlDateTime();

		if (empty($menu)) {
			$this->checkForeignKeys();
			DB::table('menu')->truncate();
		}

		$menu = $menu ?? $this->menu;

		foreach($menu as $item){
			$modulo          = null;
			$moduloPermisoID = null;

			if(!empty($item['ruta'])) {
				$modulo = DB::table('modulos')
					->where('ruta', $item['ruta'])
					->first();

				$moduloPermisoID = DB::table('moduloPermiso')
					->where('moduloid', $modulo->moduloid)
					->where('permisoid', 2)
					->value('modulopermisoid');
			}

			$menuID = DB::table('menu')->insertGetId([
				'padreid'         => $padreID,
				'modulopermisoid' => $moduloPermisoID,
				'orden'           => $item['orden'],
				'icono'           => $item['icono'],
				'ruta'            => $item['ruta'],
				'etiqueta'        => $item['etiqueta'] ?? $modulo->nombre,
				'catalogo'        => $item['catalogo'],
				'show'            => $item['show'],
			]);

			if(!empty($item['menu'])){
				$this->saveMenuData($item['menu'], $menuID);
			}
		}

		if (empty($menu)) {
			DB::statement('UPDATE menu SET created_at='.$dateTime.', updated_at='.$dateTime);
			$this->checkForeignKeys(1);
		}
	}
}