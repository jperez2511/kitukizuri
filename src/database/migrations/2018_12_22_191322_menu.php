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
        Schema::create('menu', function (Blueprint $table) {
            $table->increments('menuid');
            $table->integer('padreid')->nullable()->unsigned();
            $table->string('ruta');
            $table->string('etiqueta');
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
        Schema::drop('menu');
    }
}
