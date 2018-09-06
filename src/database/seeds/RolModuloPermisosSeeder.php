<?php
/**
 * Created by PhpStorm.
 * User: jperez
 * Date: 7/09/17
 * Time: 12:18 AM
 */

use Illuminate\Database\Seeder;

class RolModuloPermisosSeeder extends Seeder {
	public function run(){
		DB::statement('SET FOREIGN_KEY_CHECKS=0');
		DB::table('rolModuloPermiso')->truncate();

		DB::table('rolModuloPermiso')->insert([
			['rolmodulopermisoid' => 1, 'rolid' => 1, 'modulopermisoid'=> 1],
			['rolmodulopermisoid' => 2, 'rolid' => 1, 'modulopermisoid'=> 2],
			['rolmodulopermisoid' => 3, 'rolid' => 1, 'modulopermisoid'=> 3],
			['rolmodulopermisoid' => 4, 'rolid' => 1, 'modulopermisoid'=> 4],
			['rolmodulopermisoid' => 5, 'rolid' => 1, 'modulopermisoid'=> 5],
			['rolmodulopermisoid' => 6, 'rolid' => 1, 'modulopermisoid'=> 6],
			['rolmodulopermisoid' => 7, 'rolid' => 1, 'modulopermisoid'=> 7],
			['rolmodulopermisoid' => 8, 'rolid' => 1, 'modulopermisoid'=> 8],
			['rolmodulopermisoid' => 9, 'rolid' => 1, 'modulopermisoid'=> 9],
			['rolmodulopermisoid' => 10, 'rolid' => 1, 'modulopermisoid'=> 10],
			['rolmodulopermisoid' => 11, 'rolid' => 1, 'modulopermisoid'=> 11],
			['rolmodulopermisoid' => 12, 'rolid' => 1, 'modulopermisoid'=> 12],
			['rolmodulopermisoid' => 13, 'rolid' => 1, 'modulopermisoid'=> 13],
			['rolmodulopermisoid' => 14, 'rolid' => 1, 'modulopermisoid'=> 14],
			['rolmodulopermisoid' => 15, 'rolid' => 1, 'modulopermisoid'=> 15],
			['rolmodulopermisoid' => 16, 'rolid' => 1, 'modulopermisoid'=> 16],
			['rolmodulopermisoid' => 17, 'rolid' => 1, 'modulopermisoid'=> 17],
			['rolmodulopermisoid' => 18, 'rolid' => 1, 'modulopermisoid'=> 18],
			['rolmodulopermisoid' => 19, 'rolid' => 1, 'modulopermisoid'=> 19],
			['rolmodulopermisoid' => 20, 'rolid' => 1, 'modulopermisoid'=> 20],
			['rolmodulopermisoid' => 21, 'rolid' => 1, 'modulopermisoid'=> 21],
			['rolmodulopermisoid' => 22, 'rolid' => 1, 'modulopermisoid'=> 22],
			['rolmodulopermisoid' => 23, 'rolid' => 1, 'modulopermisoid'=> 23],
			['rolmodulopermisoid' => 24, 'rolid' => 1, 'modulopermisoid'=> 24],
			['rolmodulopermisoid' => 25, 'rolid' => 1, 'modulopermisoid'=> 25],
			['rolmodulopermisoid' => 26, 'rolid' => 1, 'modulopermisoid'=> 26],
			['rolmodulopermisoid' => 27, 'rolid' => 1, 'modulopermisoid'=> 27],
			['rolmodulopermisoid' => 28, 'rolid' => 1, 'modulopermisoid'=> 28],
			['rolmodulopermisoid' => 29, 'rolid' => 1, 'modulopermisoid'=> 29],
			['rolmodulopermisoid' => 30, 'rolid' => 1, 'modulopermisoid'=> 30],
			['rolmodulopermisoid' => 31, 'rolid' => 1, 'modulopermisoid'=> 31],
			['rolmodulopermisoid' => 32, 'rolid' => 1, 'modulopermisoid'=> 32],
			['rolmodulopermisoid' => 33, 'rolid' => 1, 'modulopermisoid'=> 33],
		]);

		DB::statement('UPDATE rolModuloPermiso SET created_at=NOW(), updated_at=NOW()');
		DB::statement('SET FOREIGN_KEY_CHECKS=1');
	}
}
