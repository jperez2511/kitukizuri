<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder 
{

	private $menu = [];

	/**
	 * run
	 *
	 * @return void
	 */
	public function run()
	{
		$this->menu[] = ['orden' => 0, 'ruta' => 'home', 'icono' => 'class="fa fa-star"', 'catalogo' => false, 'show' => true];
		
		// Ejemplo de elemento de menu con sub elementos 

		// $this->menu[] = ['orden' => 100, 'ruta' => '', 	   'icono' => 'class="fa fa-bars"', 'catalogo' => false, 'show' => true, 'etiqueta' => 'Catálogos',
		// 	'menu' => [
		// 		['orden' => 100, 'ruta' => 'presentations', 'icono' => '', 'catalogo' => false, 'show' => true],
		// 		['orden' => 200, 'ruta' => 'products', 		'icono' => '', 'catalogo' => false, 'show' => true],
		// 		['orden' => 400, 'ruta' => 'warehouses', 	'icono' => '', 'catalogo' => false, 'show' => true],
		// 		['orden' => 500, 'ruta' => 'suppliers', 	'icono' => '', 'catalogo' => false, 'show' => true],
		// 	]
		// ];

		$this->saveData();
	}

	private function saveData($menu = null, $padreID = null)
	{
		if (empty($menu)) {
			DB::statement('SET FOREIGN_KEY_CHECKS=0');
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
				$this->saveData($item['menu'], $menuID);
			}
		}

		if (empty($menu)) {
			DB::statement('UPDATE menu SET created_at=NOW(), updated_at=NOW()');
			DB::statement('SET FOREIGN_KEY_CHECKS=1');
		}
	}
}
