<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSurveyProgramsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('survey_programs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->text('description');
            $table->string('contact')->nullable();
            $table->string('geographic_area')->nullable();
            $table->string('start_period')->nullable();
            $table->string('end_period')->nullable();
            $table->string('stage')->default("Ongoing");
            $table->string('community_size');



            $table->boolean('public')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('survey_programs');
    }
}
