<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIndicatorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('indicators', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('type', ["number", "text", "select"]);
            $table->unsignedBigInteger('survey_program_id');
            $table->timestamps();
            $table->softDeletes();

            $table->unique(["name", "survey_program_id"]);

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
        Schema::dropIfExists('indicators');
    }
}
