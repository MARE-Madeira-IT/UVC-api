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
        Schema::create('export_has_depths', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('export_id');
            $table->unsignedBigInteger('depth_id')->nullable();
            $table->foreign('export_id')->references('id')->on('exports')->onDelete('cascade');
            $table->foreign('depth_id')->references('id')->on('depths')->onDelete('set null');

            $table->unique(['export_id', 'depth_id']);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('export_has_depths');
    }
};
