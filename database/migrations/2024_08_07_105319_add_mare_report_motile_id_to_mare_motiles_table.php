<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMareReportMotileIdToMareMotilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('mare_motiles', function (Blueprint $table) {
            $table->integer('mare_report_motile_id')->unsigned();

            $table->foreign('mare_report_motile_id')->references('id')->on('mare_report_motiles')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('mare_motiles', function (Blueprint $table) {
            $table->dropColumn('mare_report_motile_id');
        });
    }
}
