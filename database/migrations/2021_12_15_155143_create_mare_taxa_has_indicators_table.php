<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMareTaxaHasIndicatorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mare_taxa_has_indicators', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique();
            $table->integer('taxa_id')->unsigned();
            $table->integer('indicator_id')->unsigned();
            $table->timestamps();

            $table->foreign('taxa_id')->references('id')->on('mare_taxas')->onDelete('cascade');
            $table->foreign('indicator_id')->references('id')->on('mare_indicators')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mare_taxa_has_indicators');
    }
}
