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
            $table->integer('survey_program_id')->unsigned();
            $table->integer('category_id')->unsigned();
            $table->timestamps();

            $table->unique(['survey_program_id', 'name']);

            $table->foreign('survey_program_id')->references('id')->on('survey_programs')->onDelete('cascade');
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
