<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ServiceCatalogFieldChange extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('service_catalogs', function (Blueprint $table) {
        $table->string('company')->nullable()->change();
        $table->string('catID')->nullable()->change();
        $table->text('description')->nullable()->change();
        $table->string('service_key')->nullable()->change();
        $table->string('domain')->nullable()->change();
        $table->string('url')->nullable()->change();
        $table->string('parentID')->nullable()->change();
        $table->string('is_custom')->nullable()->change();
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
