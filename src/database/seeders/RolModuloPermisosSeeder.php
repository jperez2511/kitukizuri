<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Seeder;
use Icebearsoft\Kitukizuri\App\Traits\Krud\SeederTrait;

class RolModuloPermisosSeeder extends Seeder 
{
	use SeederTrait;
	
	/**
	 * run
	 *
	 * @return void
	 */
	public function run()
	{
		$this->checkForeignKeys();
		$rolId = 1;

		$moduloPermisos = DB::table('moduloPermiso')
			->orderBy('modulopermisoid')
			->pluck('modulopermisoid')
			->toArray();

		$actuales = DB::table('rolModuloPermiso')
			->where('rolid', $rolId)
			->pluck('modulopermisoid')
			->toArray();

		$rows = [];
		$moduloPermisosAgregar = array_diff($moduloPermisos, $actuales);
		foreach ($moduloPermisosAgregar as $moduloPermisoId) {
			$rows[] = [
				'rolid'           => $rolId,
				'modulopermisoid' => $moduloPermisoId,
				'created_at'      => now(),
				'updated_at'      => now(),
			];
		}

		if(!empty($rows)) {
			DB::table('rolModuloPermiso')->insert($rows);
		}

		$this->checkForeignKeys(1);
	}
}
