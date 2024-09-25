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
        Schema::create('indicator_has_values', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('indicator_id')->nullable();
            $table->string("name");
            $table->timestamps();
            $table->softDeletes();


            $table->foreign('indicator_id')->references('id')->on('indicators')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('indicator_has_values');
    }
};
