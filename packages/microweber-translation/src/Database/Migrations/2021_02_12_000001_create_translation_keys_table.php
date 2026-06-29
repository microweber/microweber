<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (Schema::hasTable('translation_keys')) {
            return;
        }

        Schema::create('translation_keys', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('translation_namespace')->nullable();
            $table->string('translation_group');
            $table->text('translation_key');
            $table->text('translation_value_default')->nullable();
        });

        try {
            Schema::table('translation_keys', function (Blueprint $table) {
                $table->index('translation_group');
                $table->index('translation_namespace');
            });
        } catch (\Exception $e) {
        }
    }

    public function down()
    {
        Schema::dropIfExists('translation_keys');
    }
};