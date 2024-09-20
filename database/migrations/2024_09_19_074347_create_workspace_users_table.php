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
        Schema::create('workspace_users', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('workspace_id');
            $table->unsignedInteger('user_id')->nullable();
            $table->boolean('active')->default(false);
            $table->boolean("accepted")->default(false);
            $table->unique(['user_id', 'workspace_id']);

            $table->foreign('workspace_id')->references('id')->on('workspaces')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('wave.users')->onDelete('set null');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('workspace_users');
    }
};
