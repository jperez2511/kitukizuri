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
        Schema::create('personalizaciones', function (Blueprint $table) {
            $table->bigIncrements('personalizacionid');
            $table->string('primary_color', 7)->default('#6576ff');
            $table->string('secondary_color', 7)->default('#0d7195');
            $table->string('accent_color', 7)->default('#b3c2ff');
            $table->string('surface_color', 7)->default('#f5f6fa');
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
        Schema::dropIfExists('personalizaciones');
    }
};
