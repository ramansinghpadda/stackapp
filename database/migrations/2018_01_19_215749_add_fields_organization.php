<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsOrganization extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::table('organizations', function (Blueprint $table) {
            $table->text('industry')->nullable();
            $table->text('size')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        Schema::table('organizations', function (Blueprint $table) {
            $table->dropColumn('industry');
            $table->dropColumn('size');
        });
    }
}
