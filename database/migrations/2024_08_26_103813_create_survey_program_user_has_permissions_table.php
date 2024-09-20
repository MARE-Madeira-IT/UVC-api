<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSurveyProgramUserHasPermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('survey_program_user_has_permissions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('survey_program_user_id');
            $table->unsignedBigInteger('permission_id')->nullable();

            $table->unique(['survey_program_user_id', 'permission_id'], 'unique_survey_program_user_permission_pair');

            $table->timestamps();

            $table->foreign('survey_program_user_id', 'user_has_permissions_on_survey_program_foreign')->references('id')->on('survey_program_users')->onDelete('cascade');
            $table->foreign('permission_id')->references('id')->on('permissions')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('survey_program_user_has_permissions');
    }
}
