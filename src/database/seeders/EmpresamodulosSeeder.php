<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Seeder;
use Icebearsoft\Kitukizuri\App\Traits\Krud\SeederTrait;

class EmpresamodulosSeeder extends Seeder 
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

		$empresaId = 1;

		$modulos = DB::table('modulos')
			->orderBy('moduloid')
			->pluck('moduloid')
			->toArray();

		$modulosActuales = DB::table('moduloEmpresas')
			->where('empresaid', $empresaId)
			->pluck('moduloid')
			->toArray();

		$modulosEliminar = array_diff($modulosActuales, $modulos);
		if(!empty($modulosEliminar)) {
			DB::table('moduloEmpresas')
				->where('empresaid', $empresaId)
				->whereIn('moduloid', $modulosEliminar)
				->delete();
		}

		$rows = [];
		$modulosAgregar = array_diff($modulos, $modulosActuales);
		foreach ($modulosAgregar as $moduloId) {
			$rows[] = [
				'empresaid' => $empresaId,
				'moduloid'  => $moduloId,
				'created_at' => now(),
				'updated_at' => now(),
			];
		}

		if(!empty($rows)) {
			DB::table('moduloEmpresas')->insert($rows);
		}

		$this->checkForeignKeys(1);
	}
}
