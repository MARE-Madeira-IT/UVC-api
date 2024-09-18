<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTaxasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('taxas', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('genus')->nullable();
            $table->string('species')->nullable();
            $table->string('phylum')->nullable();
            $table->integer('project_id')->unsigned();
            $table->integer('category_id')->unsigned();
            $table->timestamps();

            $table->unique(['project_id', 'name']);

            $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
            $table->foreign('category_id')->references('id')->on('taxa_categories')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('taxas');
    }
}