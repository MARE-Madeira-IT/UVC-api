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
        Schema::create('exports', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('survey_program_id');
            $table->string('url')->nullable();
            $table->enum('state', ['pending', 'generating', 'finished', 'failed'])->default('pending');
            $table->date('date_from')->nullable();
            $table->date('date_to')->nullable();

            $table->foreign('survey_program_id')->references('id')->on('survey_programs')->onDelete('cascade');


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exports');
    }
};
