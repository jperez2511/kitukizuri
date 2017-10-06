<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MigrationRoles extends Migration {
	public function up() {
		Schema::create('roles', function (Blueprint $table){
			$table->increments('rolid');
			$table->string('nombre');
			$table->string('descripcion');
			$table->nullableTimestamps();
		});
		Schema::create('modulos', function (Blueprint $table){
			$table->increments('moduloid');
			$table->string('nombre');
			$table->string('ruta');
			$table->nullableTimestamps();
		});
		Schema::create('permisos', function (Blueprint $table){
			$table->increments('permisoid');
			$table->string('nombre');
			$table->string('nombreLaravel');
			$table->string('color');
			$table->nullableTimestamps();
		});
		Schema::create('moduloPermiso', function (Blueprint $table){
			$table->increments('modulopermisoid');
			$table->integer('moduloid')->unsigned();
			$table->integer('permisoid')->unsigned();
			$table->foreign('permisoid')->references('permisoid')->on('permisos');
			$table->foreign('moduloid')->references('moduloid')->on('modulos');
			$table->nullableTimestamps();
		});
		Schema::create('rolModuloPermiso', function (Blueprint $table){
			$table->increments('rolmodulopermisoid');
			$table->integer('rolid')->unsigned();
			$table->integer('modulopermisoid')->unsigned();
			$table->foreign('rolid')->references('rolid')->on('roles');
			$table->foreign('modulopermisoid')->references('modulopermisoid')->on('moduloPermiso');
			$table->nullableTimestamps();
		});
	}

	public function down() {
		Schema::drop('rolModuloPermiso');
		Schema::drop('moduloPermiso');
		Schema::drop('permisos');
		Schema::drop('modulos');
		Schema::drop('roles');
	}
}
