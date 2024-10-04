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
            $table->id();
            $table->integer('code');
            $table->string('name');
            $table->unsignedBigInteger('survey_program_id');
            $table->timestamps();


            $table->unique(['survey_program_id', 'name']);
            $table->unique(['survey_program_id', 'code']);
            $table->foreign('survey_program_id')->references('id')->on('survey_programs')->onDelete('cascade');
            $table->softDeletes();
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
