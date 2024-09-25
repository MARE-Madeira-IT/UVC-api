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
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('taxa_id');
            $table->unsignedBigInteger('indicator_id');
            $table->timestamps();
            $table->softDeletes();

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
