<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MetaPositionField extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('meta', function (Blueprint $table) {
            $table->string('position')->default(0);
        });
         Schema::table('meta_mapping', function (Blueprint $table) {
            $table->string('position')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('meta', function (Blueprint $table) {
            $table->dropColumn('position');
        });
        Schema::table('meta_mapping', function (Blueprint $table) {
            $table->dropColumn('position');
        });
    }
}
