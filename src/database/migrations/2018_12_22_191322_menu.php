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
            $table->increments('menuid');
            $table->integer('padreid')->nullable()->unsigned();
            $table->integer('modulopermisoid')->nullable()->unsigned();
            $table->integer('orden')->unsigned();
            $table->string('icono');
            $table->string('ruta');
            $table->string('etiqueta');
            $table->nullableTimestamps();
            //ForeignKeys
            $table->foreign('padreid')->references('padreid')->on('menu');
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
