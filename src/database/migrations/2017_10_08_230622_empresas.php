<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Empresas extends Migration
{
    /**
     * up
     *
     * @return void
     */
    public function up()
    {   
        Schema::create('empresas', function (Blueprint $table) {
            $table->bigIncrements('empresaid');
            $table->string('nombre');
            $table->string('telefono')->nullable();
            $table->string('correo')->nullable();
            $table->string('nit')->nullable();
            $table->longtext('logo')->nullable();
            $table->text('direccion')->nullable();
            $table->boolean('activo')->default(true);
            $table->nullableTimestamps();
        });
        
        Schema::table('users', function($table) {
            $table->bigInteger('empresaid')->unsigned()->nullable()->after('id');
            $table->foreign('empresaid')->references('empresaid')->on('empresas');
        });

        Schema::table('roles', function($table) {
            $table->bigInteger('empresaid')->unsigned()->nullable()->after('rolid');
            $table->foreign('empresaid')->references('empresaid')->on('empresas');
        });
        
        Schema::create('moduloEmpresas', function (Blueprint $table) {
            $table->bigIncrements('moduloempresaid');
            $table->bigInteger('empresaid')->unsigned();
            $table->bigInteger('moduloid')->unsigned();
            $table->foreign('empresaid')->references('empresaid')->on('empresas');
            $table->foreign('moduloid')->references('moduloid')->on('modulos');
            $table->nullableTimestamps();
        });     
    }

    /**
     * down
     *
     * @return void
     */
    public function down()
    {   
        Schema::table('users', function($table) {
            $table->dropForeign(['empresaid']);
            $table->dropColumn('empresaid');
        });

        Schema::table('roles', function($table) {
            $table->dropForeign(['empresaid']);
            $table->dropColumn('empresaid');
        });

        Schema::drop('moduloEmpresas');

        Schema::drop('empresas');
    }
}
