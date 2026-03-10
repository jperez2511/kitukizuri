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
        if (!Schema::hasTable('personalizaciones')) {
            return;
        }

        Schema::table('personalizaciones', function (Blueprint $table) {
            if (!Schema::hasColumn('personalizaciones', 'direction')) {
                $table->string('direction', 3)->default('ltr')->after('surface_color');
            }

            if (!Schema::hasColumn('personalizaciones', 'ui_style')) {
                $table->string('ui_style', 20)->default('default')->after('direction');
            }

            if (!Schema::hasColumn('personalizaciones', 'sidebar_style')) {
                $table->string('sidebar_style', 20)->default('auto')->after('ui_style');
            }

            if (!Schema::hasColumn('personalizaciones', 'skin_mode')) {
                $table->string('skin_mode', 20)->default('light')->after('sidebar_style');
            }

            if (!Schema::hasColumn('personalizaciones', 'primary_skin')) {
                $table->string('primary_skin', 30)->default('custom')->after('skin_mode');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (!Schema::hasTable('personalizaciones')) {
            return;
        }

        Schema::table('personalizaciones', function (Blueprint $table) {
            if (Schema::hasColumn('personalizaciones', 'primary_skin')) {
                $table->dropColumn('primary_skin');
            }

            if (Schema::hasColumn('personalizaciones', 'skin_mode')) {
                $table->dropColumn('skin_mode');
            }

            if (Schema::hasColumn('personalizaciones', 'sidebar_style')) {
                $table->dropColumn('sidebar_style');
            }

            if (Schema::hasColumn('personalizaciones', 'ui_style')) {
                $table->dropColumn('ui_style');
            }

            if (Schema::hasColumn('personalizaciones', 'direction')) {
                $table->dropColumn('direction');
            }
        });
    }
};
