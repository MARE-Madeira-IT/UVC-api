<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectUserHasPermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('project_user_has_permissions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('project_has_users_id')->unsigned();
            $table->integer('permission_id')->unsigned()->nullable();

            $table->unique(['project_has_users_id', 'permission_id'], 'unique_project_user_permission_pair');

            $table->timestamps();

            $table->foreign('project_has_users_id', 'user_has_permissions_on_project_foreign')->references('id')->on('project_has_users')->onDelete('cascade');
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
        Schema::dropIfExists('project_user_has_permissions');
    }
}
