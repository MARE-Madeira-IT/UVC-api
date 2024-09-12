<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMareProjectUserHasPermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mare_project_user_has_permissions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('mare_project_has_users_id')->unsigned();
            $table->integer('permission_id')->unsigned()->nullable();

            $table->unique(['mare_project_has_users_id', 'permission_id'], 'unique_project_user_permission_pair');

            $table->timestamps();

            $table->foreign('mare_project_has_users_id', 'mare_user_has_permissions_on_project_foreign')->references('id')->on('mare_project_has_users')->onDelete('cascade');
            $table->foreign('permission_id')->references('id')->on('mare_permissions')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mare_project_user_has_permissions');
    }
}
