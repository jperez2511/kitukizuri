<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MigrationUsuarioRol extends Migration 
{
	/**
	 * up
	 *
	 * @return void
	 */
	public function up() 
	{
		Schema::create('usuarioRol', function (Blueprint $table){
			$table->bigIncrements('usuariorolid');
			$table->bigInteger('rolid')->unsigned();
			$table->bigInteger('usuarioid')->unsigned();
			$table->foreign('rolid')->references('rolid')->on('roles');
			$table->nullableTimestamps();
		});

		Schema::table('usuarioRol', function (Blueprint $table) {
			$table->foreign('usuarioid')->references('id')->on('users');
		});
	}

	/**
	 * down
	 *
	 * @return void
	 */
	public function down() 
	{
		Schema::drop('usuarioRol');
	}
}
