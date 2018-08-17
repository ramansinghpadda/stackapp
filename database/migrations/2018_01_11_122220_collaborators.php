<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Collaborators extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('collaborators', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('oID');
            $table->unsignedInteger('uID');
            $table->unsignedInteger('roleID');
            $table->enum('statusID', [0,1,2])->comment('0 - Pending, 1- Accepted/Active, 2- Revoked');
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
         Schema::dropIfExists('collaborators');
    }
}
