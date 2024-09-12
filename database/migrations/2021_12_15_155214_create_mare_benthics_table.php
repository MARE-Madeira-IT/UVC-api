<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMareBenthicsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mare_benthics', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('p##');
            $table->text('notes')->nullable();
            $table->integer('substrate_id')->unsigned();
            $table->integer('report_id')->unsigned();
            $table->integer('taxa_id')->unsigned()->nullable();
            $table->timestamps();

            $table->foreign('substrate_id')->references('id')->on('mare_substrates')->onDelete('cascade');
            $table->foreign('report_id')->references('id')->on('mare_reports')->onDelete('cascade');
            $table->foreign('taxa_id')->references('id')->on('mare_taxas')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mare_benthics');
    }
}
