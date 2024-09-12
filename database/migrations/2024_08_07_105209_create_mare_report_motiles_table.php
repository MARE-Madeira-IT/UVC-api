<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMareReportMotilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mare_report_motiles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('type');
            $table->integer('report_id')->unsigned();


            $table->foreign('report_id')->references('id')->on('mare_reports')->onDelete('cascade');

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
        Schema::dropIfExists('mare_report_motiles');
    }
}
