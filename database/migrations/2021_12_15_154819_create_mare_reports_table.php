<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMareReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mare_reports', function (Blueprint $table) {
            $table->increments('id');
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
            $table->integer('project_id')->unsigned();
            $table->integer('site_id')->unsigned();
            $table->integer('depth_id')->unsigned();
            $table->integer('surveyed_area');

            $table->timestamps();

            $table->foreign('project_id')->references('id')->on('mare_projects')->onDelete('cascade');
            $table->foreign('site_id')->references('id')->on('mare_sites')->onDelete('cascade');
            $table->foreign('depth_id')->references('id')->on('mare_depths')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mare_reports');
    }
}
