<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Menu extends Migration
{
    /**
     * up
     *
     * @return void
     */
    public function up()
    {   
        Schema::create('menu', function (Blueprint $table) {
            $table->bigIncrements('menuid');
            $table->bigInteger('padreid')->nullable()->unsigned();
            $table->bigInteger('modulopermisoid')->nullable()->unsigned();
            $table->bigInteger('orden')->unsigned();
            $table->string('icono');
            $table->string('ruta');
            $table->string('etiqueta');
            $table->boolean('catalogo')->default(true);
            $table->boolean('show')->default(true);
            $table->nullableTimestamps();
            //ForeignKeys
            $table->foreign('modulopermisoid')->references('modulopermisoid')->on('moduloPermiso');
        });
    }

    /**
     * down
     *
     * @return void
     */
    public function down()
    {   
        Schema::drop('menu');
    }
}
