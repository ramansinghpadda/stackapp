<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ApplicationAttachments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('application_attachments', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('oID');
            $table->unsignedInteger('appID');
            $table->unsignedInteger('uID');
            $table->text("file_name");
            $table->string('file_type');
            $table->string("file_id");
            $table->unsignedInteger('statusID')->default(1);
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
         Schema::dropIfExists('application_attachments');
    }  //
    
}
