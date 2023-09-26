<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Seeder;

class InicialSeeder extends Seeder 
{
	/**
	 * run
	 *
	 * @return void
	 */
	public function run()
	{
		DB::statement('SET FOREIGN_KEY_CHECKS=0');

		$empresa = DB::table('empresas')->select('empresaid')->where('empresaid', 1)->first();

		if (empty($empresa)) {
			DB::table('empresas')->insert([
				'empresaid'     => 1,
				'nombre'        => 'Empresa',
				'telefono'      => '123456789',
				'correo'        => 'admin@mail.com',
				'nit'           => 'C/F',
				'logo'          => '.',
				'direccion'     => 'Guatemala',
				'activo'        => true,
			]);

			$this->call(EmpresamodulosSeeder::class);
		}

		$usuario = DB::table('users')->select('id')->where('id', 1)->first();

		if(empty($usuario)) {
			DB::table('users')->insert([
				'id'        => 1,
				'empresaid' => 1,
				'name'      => 'Administrator',
				'email'     => 'admin@mail.com',
				'password'  => '$2y$10$IFloXJUzFURcTFo.DQNZhO2FYGpWoLRZ9rgEf6cXiFK2Se8VZRyw2'
			]);
		}

		$roles = DB::table('roles')->select('rolid')->where('rolid', 1)->first();

		if(empty($roles)){
			DB::table('roles')->insert([
				['rolid' => 1,'nombre' => 'Super Usuario','descripcion' => 'Rol con todos los permisos',],
				['rolid' => 2,'nombre' => 'Cliente','descripcion' => 'rol para todos clientes inciales',]
			]);

			$this->call(RolModuloPermisosSeeder::class);

			DB::table('usuarioRol')->insert([
				'usuariorolid' => 1,
				'rolid'        => 1,
				'usuarioid'    => 1
			]);
		}

		DB::statement('UPDATE users SET created_at=NOW(), updated_at=NOW()');
		DB::statement('UPDATE roles SET created_at=NOW(), updated_at=NOW()');
		DB::statement('UPDATE usuarioRol SET created_at=NOW(), updated_at=NOW()');
		DB::statement('UPDATE empresas SET created_at=NOW(), updated_at=NOW()');
		DB::statement('SET FOREIGN_KEY_CHECKS=1');
	}
}
