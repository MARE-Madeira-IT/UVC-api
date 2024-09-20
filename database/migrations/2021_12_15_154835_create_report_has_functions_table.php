<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReportHasFunctionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('report_has_functions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('function_id');
            $table->unsignedBigInteger('report_id');
            $table->string('user')->nullable();
            $table->timestamps();

            $table->foreign('function_id')->references('id')->on('survey_program_functions')->onDelete('cascade');
            $table->foreign('report_id')->references('id')->on('reports')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('report_has_functions');
    }
}
