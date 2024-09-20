<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('project_user_has_permissions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('project_user_id');
            $table->unsignedBigInteger('permission_id');

            $table->foreign('project_user_id')->references('id')->on('project_users')->onDelete('cascade');
            $table->foreign('permission_id')->references('id')->on('permissions')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_user_has_permissions');
    }
};
