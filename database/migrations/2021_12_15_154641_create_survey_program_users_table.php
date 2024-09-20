<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSurveyProgramUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('survey_program_users', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('survey_program_id');
            $table->unsignedInteger('user_id')->nullable();
            $table->boolean('active')->default(false);
            $table->boolean("accepted")->default(false);
            $table->timestamps();
            $table->unique(['user_id', 'survey_program_id']);

            $table->foreign('survey_program_id')->references('id')->on('survey_programs')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('wave.users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('survey_program_users');
    }
}
