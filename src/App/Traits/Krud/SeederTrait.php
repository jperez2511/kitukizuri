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
			DB::beginTransaction();
			
			$dateTime = now();
			$driver = DB::connection()->getDriverName();
			
			// 1. DETECTAR Y ELIMINAR MÓDULOS HUÉRFANOS
			// Obtener todas las rutas del array de entrada
			$rutasEnviadas = collect($modulos)->pluck('ruta')->toArray();
			
			// Encontrar módulos en BD que no están en el array
			$modulosHuerfanos = DB::table('modulos')
				->whereNotIn('ruta', $rutasEnviadas)
				->get(['moduloid']);
			
			foreach ($modulosHuerfanos as $moduloHuerfano) {
				// Obtener los modulopermisoid que pertenecen a este módulo
				$moduloPermisosIds = DB::table('moduloPermiso')
					->where('moduloid', $moduloHuerfano->moduloid)
					->pluck('modulopermisoid')
					->toArray();
				
				// Eliminar registros en rolModuloPermiso asociados a estos modulopermisoid
				if (!empty($moduloPermisosIds)) {
					DB::table('rolModuloPermiso')
						->whereIn('modulopermisoid', $moduloPermisosIds)
						->delete();
				}
				
				// Eliminar registros en moduloPermiso
				DB::table('moduloPermiso')
					->where('moduloid', $moduloHuerfano->moduloid)
					->delete();
				
				// Finalmente eliminar el módulo
				DB::table('modulos')
					->where('moduloid', $moduloHuerfano->moduloid)
					->delete();
			}
			
			// 2. ACTUALIZAR O INSERTAR MÓDULOS Y SINCRONIZAR PERMISOS
			foreach ($modulos as $modulo) {
				// Buscar si el módulo existe por ruta
				$moduloExistente = DB::table('modulos')
					->where('ruta', $modulo['ruta'])
					->first(['moduloid']);
				
				if ($moduloExistente) {
					// Actualizar módulo existente
					$moduloid = $moduloExistente->moduloid;
					
					DB::table('modulos')
						->where('moduloid', $moduloid)
						->update([
							'nombre' => $modulo['nombre'],
							'ruta' => $modulo['ruta'],
							'updated_at' => $dateTime
						]);
				} else {
					// Crear nuevo módulo - Compatible con todos los drivers
					if ($driver === 'pgsql') {
						$moduloid = DB::table('modulos')->insertGetId([
							'nombre' => $modulo['nombre'],
							'ruta' => $modulo['ruta'],
							'created_at' => $dateTime,
							'updated_at' => $dateTime
						], 'moduloid');
					} else {
						// MySQL y SQLite usan auto_increment
						$moduloid = DB::table('modulos')->insertGetId([
							'nombre' => $modulo['nombre'],
							'ruta' => $modulo['ruta'],
							'created_at' => $dateTime,
							'updated_at' => $dateTime
						]);
					}
				}
				
				// 3. SINCRONIZAR PERMISOS DEL MÓDULO
				// Obtener permisos actuales en BD
				$permisosActuales = DB::table('moduloPermiso')
					->where('moduloid', $moduloid)
					->get(['modulopermisoid', 'permisoid']);
				
				$permisosActualesIds = $permisosActuales->pluck('permisoid')->toArray();
				$permisosEnviados = $modulo['permisos'];
				
				// Identificar permisos a eliminar (están en BD pero no en el array)
				$permisosAEliminar = $permisosActuales->filter(function ($permisoActual) use ($permisosEnviados) {
					return !in_array($permisoActual->permisoid, $permisosEnviados);
				});
				
				foreach ($permisosAEliminar as $permisoEliminar) {
					// Primero eliminar en rolModuloPermiso
					DB::table('rolModuloPermiso')
						->where('modulopermisoid', $permisoEliminar->modulopermisoid)
						->delete();
					
					// Luego eliminar en moduloPermiso
					DB::table('moduloPermiso')
						->where('modulopermisoid', $permisoEliminar->modulopermisoid)
						->delete();
				}
				
				// Identificar permisos a agregar (están en el array pero no en BD)
				$permisosAAgregar = array_diff($permisosEnviados, $permisosActualesIds);
				
				foreach ($permisosAAgregar as $permisoNuevo) {
					DB::table('moduloPermiso')->insert([
						'moduloid' => $moduloid,
						'permisoid' => $permisoNuevo,
						'created_at' => $dateTime,
						'updated_at' => $dateTime
					]);
				}
			}
			
			DB::commit();
			
			return true;
			
		} catch (\Exception $e) {
			DB::rollBack();
			
			// Loguear el error o manejarlo según tus necesidades
			\Log::error('Error en saveModuleData: ' . $e->getMessage());
			
			throw $e;
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
			], 'menuid');

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