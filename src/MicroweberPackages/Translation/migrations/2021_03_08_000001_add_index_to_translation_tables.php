<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIndexToTranslationTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        try {
            Schema::table('translation_texts', function (Blueprint $table) {
                $table->index('translation_key_id');
                $table->index('translation_text');
                $table->index('translation_locale');
                $table->unique(['translation_key_id', 'translation_locale'])->change();
            });
        } catch (Exception $e) {

        }

        try {
            Schema::table('translation_keys', function (Blueprint $table) {
                $table->index('translation_namespace');
                $table->index('translation_key');
                $table->index('translation_group');
                $table->unique(['translation_key', 'translation_group', 'translation_namespace'])->change();
            });
        } catch (Exception $e) {

        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

    }
}