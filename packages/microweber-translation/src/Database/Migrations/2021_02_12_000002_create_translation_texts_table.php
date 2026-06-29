<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (Schema::hasTable('translation_texts')) {
            return;
        }

        Schema::create('translation_texts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('translation_key_id');
            $table->text('translation_text');
            $table->string('translation_locale');
            $table->timestamps();

            $table->index('translation_key_id');
            $table->index('translation_locale');
        });
    }

    public function down()
    {
        Schema::dropIfExists('translation_texts');
    }
};