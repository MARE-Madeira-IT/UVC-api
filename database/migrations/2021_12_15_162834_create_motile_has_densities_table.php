<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMotileHasDensitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('motile_has_densities', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('count');
            $table->integer('density_id')->unsigned();
            $table->integer('motile_id')->unsigned();
            $table->timestamps();

            $table->foreign('density_id')->references('id')->on('densities')->onDelete('cascade');
            $table->foreign('motile_id')->references('id')->on('motiles')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('motile_has_densities');
    }
}