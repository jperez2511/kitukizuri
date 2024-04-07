<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Seeder;

use Icebearsoft\Kitukizuri\App\Traits\Krud\SeederTrait;

class MenuSeeder extends Seeder 
{
	use SeederTrait;

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

		$this->saveMenuData();
	}
}
