<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMareSitesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mare_sites', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('locality_id')->unsigned();
            $table->string('name');
            $table->string('code');
            $table->timestamps();

            $table->foreign('locality_id')->references('id')->on('mare_localities')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mare_sites');
    }
}
