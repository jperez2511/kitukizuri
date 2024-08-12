<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AllTableTenants extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('tenants')->create('empresas', function($table){
            $table->increments('empresa_id');
            $table->string('razon_social')->unique();
            $table->string('nit')->unique();
            $table->string('nombre_contacto');
            $table->string('email_contacto');
            $table->string('telefono');
            $table->string('direccion');
            $table->boolean('activo')->default(true);
            $table->nullableTimestamps();
        });

        Schema::connection('tenants')->create('tenants', function($table){
            $table->increments('tenant_id');
            $table->integer('empresa_id')->unsigned();
            $table->string('dominio');
            $table->string('db');
            $table->string('db_host');
            $table->string('db_username');
            $table->string('db_password');
            $table->string('mongo_db');
            $table->string('mongo_db_host');
            $table->string('mongo_db_username');
            $table->string('mongo_db_password');
            $table->string('token')->unique()->nullable();
            $table->boolean('activo')->default(true);
            $table->nullableTimestamps();
            $table->foreign('empresa_id')->references('empresa_id')->on('empresas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('tenants')->dropIfExists('tenants');
        Schema::connection('tenants')->dropIfExists('empresas');
    }
}
