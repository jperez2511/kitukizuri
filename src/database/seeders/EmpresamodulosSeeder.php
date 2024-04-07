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
		$dateTime = $this->sqlDateTime();
		$this->checkForeignKeys();

		DB::table('moduloEmpresas')->truncate();

		DB::table('moduloEmpresas')->insert([
			['moduloempresaid' => 1, 'empresaid' => 1, 'moduloid' => 1,	 ],
			['moduloempresaid' => 2, 'empresaid' => 1, 'moduloid' => 2,	 ],
			['moduloempresaid' => 3, 'empresaid' => 1, 'moduloid' => 3,	 ],
			['moduloempresaid' => 4, 'empresaid' => 1, 'moduloid' => 4,	 ],
			['moduloempresaid' => 5, 'empresaid' => 1, 'moduloid' => 5,	 ],
			['moduloempresaid' => 6, 'empresaid' => 1, 'moduloid' => 6,	 ],
			['moduloempresaid' => 7, 'empresaid' => 1, 'moduloid' => 7,	 ],
			['moduloempresaid' => 8, 'empresaid' => 1, 'moduloid' => 8,	 ],
			['moduloempresaid' => 9, 'empresaid' => 1, 'moduloid' => 9,	 ],
			['moduloempresaid' => 10, 'empresaid' => 1, 'moduloid' => 10, ],
			['moduloempresaid' => 11, 'empresaid' => 1, 'moduloid' => 11, ],
			['moduloempresaid' => 12, 'empresaid' => 1, 'moduloid' => 12, ],
			['moduloempresaid' => 13, 'empresaid' => 1, 'moduloid' => 13, ],
			['moduloempresaid' => 14, 'empresaid' => 1, 'moduloid' => 14, ],
			['moduloempresaid' => 15, 'empresaid' => 1, 'moduloid' => 15, ],
		]);

		DB::statement('UPDATE moduloEmpresas SET created_at='.$dateTime.', updated_at='.$dateTime);
		$this->checkForeignKeys(1);
	}
}
