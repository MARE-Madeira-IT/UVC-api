<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBenthicsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('benthics', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('p##');
            $table->text('notes')->nullable();
            $table->integer('substrate_id')->unsigned();
            $table->integer('report_id')->unsigned();
            $table->integer('taxa_id')->unsigned()->nullable();
            $table->timestamps();

            $table->foreign('substrate_id')->references('id')->on('substrates')->onDelete('cascade');
            $table->foreign('report_id')->references('id')->on('reports')->onDelete('cascade');
            $table->foreign('taxa_id')->references('id')->on('taxas')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('benthics');
    }
}
