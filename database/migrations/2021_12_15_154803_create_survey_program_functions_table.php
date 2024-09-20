<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSurveyProgramFunctionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('survey_program_functions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('survey_program_id');
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
        Schema::dropIfExists('survey_program_functions');
    }
}
