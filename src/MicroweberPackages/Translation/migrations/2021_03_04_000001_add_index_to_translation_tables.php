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
            });
        } catch (Exception $e) {

        }

        try {
            Schema::table('translation_keys', function (Blueprint $table) {
                $table->index('translation_namespace');
                $table->string('translation_key' )->unique()->change();
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