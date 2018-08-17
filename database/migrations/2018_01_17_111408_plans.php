<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Plans extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plans', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('stripe_plan_id')->comment("Stripe Plan Id - mapped from Stripe");
            $table->text('description');
            $table->string('currency');
            $table->double('price');
            $table->string('num_organizations_limit')->comment("No of organization user can create ");
            $table->string('num_applications_limit')->comment("No of applications user can create ");
            $table->string('num_collaborators')->comment("No of collaborators user can create ");;
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
        Schema::dropIfExists('plans');
    }
}
