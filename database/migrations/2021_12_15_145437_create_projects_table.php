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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('workspace_id');
            $table->string('name');
            $table->text('description');
            $table->string('contact')->nullable();
            $table->string('geographic_area')->nullable();
            $table->string('start_period')->nullable();
            $table->string('end_period')->nullable();
            $table->string('stage')->default("Ongoing");
            $table->string('community_size');
            $table->boolean('public')->default(true);

            $table->foreign('workspace_id')->references('id')->on('workspaces')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
