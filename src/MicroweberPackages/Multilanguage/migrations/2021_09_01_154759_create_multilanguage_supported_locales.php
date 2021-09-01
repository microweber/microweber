<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMultilanguageSupportedLocales extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('multilanguage_supported_locales', function (Blueprint $table) {
            $table->id();

            $table->string('locale');
            $table->string('language')->nullable();
            $table->string('display_locale')->nullable();
            $table->string('display_name')->nullable();
            $table->string('display_icon')->nullable();
            $table->integer('position')->nullable();
            $table->string('is_active')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('multilanguage_supported_locales');
    }
}
