<?php
/**
 * Created by PhpStorm.
 * User: jperez
 * Date: 7/09/17
 * Time: 12:18 AM
 */

use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder 
{
	/**
	 * run
	 *
	 * @return void
	 */
	public function run()
	{
		DB::statement('SET FOREIGN_KEY_CHECKS=0');
		DB::table('menu')->truncate();

		DB::table('menu')->insert([
			['menuid' => 1, 'padreid' => null, 'modulopermisoid' => 1, 'orden' => 0, 'icono' => null, 'ruta' => null, 'etiqueta' => '1'	 ],
		]);

		DB::statement('UPDATE menu SET created_at=NOW(), updated_at=NOW()');
		DB::statement('SET FOREIGN_KEY_CHECKS=1');
	}
}
