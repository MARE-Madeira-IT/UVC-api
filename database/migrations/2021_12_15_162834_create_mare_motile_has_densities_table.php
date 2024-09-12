<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMareMotileHasDensitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mare_motile_has_densities', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('count');
            $table->integer('density_id')->unsigned();
            $table->integer('motile_id')->unsigned();
            $table->timestamps();

            $table->foreign('density_id')->references('id')->on('mare_densities')->onDelete('cascade');
            $table->foreign('motile_id')->references('id')->on('mare_motiles')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mare_motile_has_densities');
    }
}
