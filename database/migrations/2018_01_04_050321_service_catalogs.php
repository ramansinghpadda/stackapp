<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ServiceCatalogs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {   
        Schema::create('service_catalogs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('company');
            $table->integer('catID');
            $table->longText('description');
            $table->string('service_key');
            $table->string('domain');
            $table->string('url');
            $table->integer('statusID');
            $table->integer('uID');
            $table->integer('parentID');
            $table->integer('is_custom');
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
        Schema::dropIfExists('service_catalogs');
    }
}
