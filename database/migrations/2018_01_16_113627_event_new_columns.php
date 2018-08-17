<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EventNewColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('event_logs', function (Blueprint $table) {
            $table->string('oID')->nullable();
            $table->string('action')->nullable();
            $table->string('controller')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
          Schema::table('event_logs', function (Blueprint $table) {
            $table->dropColumn('oID');
            $table->dropColumn('action');
            $table->dropColumn('controller');
        });
    }
}
