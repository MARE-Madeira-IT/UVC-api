<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddReportMotileIdToMotilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('motiles', function (Blueprint $table) {
            $table->unsignedBigInteger('report_motile_id');

            $table->foreign('report_motile_id')->references('id')->on('report_motiles')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('motiles', function (Blueprint $table) {
            $table->dropColumn('report_motile_id');
        });
    }
}
