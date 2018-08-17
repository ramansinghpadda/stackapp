<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ApplicationFieldChange extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->dropColumn('statusID');
            
        });

        Schema::table('applications', function (Blueprint $table) {
            $table->string('statusID')->default(1);
        });
        

        Schema::table('applications', function (Blueprint $table) {
            $table->renameColumn('service_catalog_ID','scID');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    
    }
}
