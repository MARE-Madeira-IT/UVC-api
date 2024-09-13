<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTaxaHasIndicatorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('taxa_has_indicators', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique();
            $table->integer('taxa_id')->unsigned();
            $table->integer('indicator_id')->unsigned();
            $table->timestamps();

            $table->foreign('taxa_id')->references('id')->on('taxas')->onDelete('cascade');
            $table->foreign('indicator_id')->references('id')->on('indicators')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('taxa_has_indicators');
    }
}
