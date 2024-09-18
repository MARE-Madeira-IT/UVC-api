<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMotilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('motiles', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('taxa_id')->unsigned();
            $table->integer('size_category_id')->unsigned()->nullable();
            $table->integer('size')->nullable();
            $table->integer('ntotal')->default(0);
            $table->string('notes')->nullable();

            $table->double('density/1', 5, 2)->nullable();
            $table->double('biomass/1', 5, 2)->nullable();
            $table->timestamps();

            $table->foreign('taxa_id')->references('id')->on('taxas')->onDelete('cascade');
            $table->foreign('size_category_id')->references('id')->on('size_categories')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('motiles');
    }
}