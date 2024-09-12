<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMareReportHasFunctionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mare_report_has_functions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('function_id')->unsigned();
            $table->integer('report_id')->unsigned();
            $table->string('user')->nullable();
            $table->timestamps();

            $table->foreign('function_id')->references('id')->on('mare_functions')->onDelete('cascade');
            $table->foreign('report_id')->references('id')->on('mare_reports')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mare_report_has_functions');
    }
}
