<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->date('date');
            $table->integer('transect');
            $table->integer('daily_dive');
            $table->integer('time');
            $table->integer('replica');
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->integer('heading')->nullable();
            $table->string('heading_direction')->nullable();
            $table->string('dom_substrate')->nullable();
            $table->string('site_area')->nullable();
            $table->decimal('distance', 2, 1)->nullable();
            $table->unsignedBigInteger('survey_program_id');
            $table->unsignedBigInteger('site_id');
            $table->unsignedBigInteger('depth_id');
            $table->integer('surveyed_area');

            $table->timestamps();

            $table->foreign('survey_program_id')->references('id')->on('survey_programs')->onDelete('cascade');
            $table->foreign('site_id')->references('id')->on('sites')->onDelete('cascade');
            $table->foreign('depth_id')->references('id')->on('depths')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reports');
    }
}
