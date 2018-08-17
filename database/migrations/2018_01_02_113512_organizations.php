<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Organizations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::create('organizations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('uID');
            $table->string('name');
            $table->text('description')->nullable();
            $table->text('short_name')->nullable();
            $table->text('url')->nullable();
            $table->enum('status', ["Active", "Archived", "Deleted"]);
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
        Schema::dropIfExists('organizations');
    }
}
