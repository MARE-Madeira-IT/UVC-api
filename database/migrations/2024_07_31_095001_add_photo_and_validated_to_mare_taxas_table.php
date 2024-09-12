<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPhotoAndValidatedToMareTaxasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('mare_taxas', function (Blueprint $table) {
            $table->string('photo_url')->nullable();
            $table->boolean('validated')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('mare_taxas', function (Blueprint $table) {
            $table->dropColumn(['photo_url', 'validated']);
        });
    }
}
