<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('logs', function (Blueprint $table) {
            $table->bigIncrements('id_log');
            $table->integer('id_user')->nullable()->unsigned();
            $table->text('params')->nullable();
            $table->text('url')->nullable();
            $table->string('method', 100)->nullable();
            $table->text('agent')->nullable();
            $table->string('memory', 100)->nullable();
            $table->dateTime('time')->nullable();
            $table->string('level', 100)->nullable();
            $table->text('message')->nullable();
            $table->text('context')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('logs');
    }
};
