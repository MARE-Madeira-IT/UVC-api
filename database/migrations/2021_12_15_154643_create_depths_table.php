<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDepthsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('depths', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique();
            $table->integer('survey_program_id')->unsigned();
            $table->timestamps();

            $table->foreign('survey_program_id')->references('id')->on('survey_programs')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('depths');
    }
}
