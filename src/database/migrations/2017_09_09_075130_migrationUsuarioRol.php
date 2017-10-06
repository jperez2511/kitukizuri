<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MigrationUsuarioRol extends Migration {
	public function up() {
		Schema::create('usuarioRol', function (Blueprint $table){
			$table->increments('usuariorolid');
			$table->integer('rolid')->unsigned();
			$table->integer('usuarioid')->unsigned();
			$table->foreign('rolid')->references('rolid')->on('roles');
			$table->nullableTimestamps();
		});

		Schema::table('usuarioRol', function (Blueprint $table) {
			$table->foreign('usuarioid')->references('id')->on('users');
		});
	}

	public function down() {
		Schema::drop('usuarioRol');
	}
}
