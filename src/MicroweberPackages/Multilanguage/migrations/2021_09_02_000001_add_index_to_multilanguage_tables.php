<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIndexToMultilanguageTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        try {
            Schema::table('multilanguage_translations', function (Blueprint $table) {
                $table->index('locale');
                $table->index('rel_id');
                $table->index('rel_type');
                $table->index('field_name');
            });
        } catch (Exception $e) {

        }

        try {
            Schema::table('multilanguage_supported_locales', function (Blueprint $table) {
                $table->index('locale');
                $table->index('language');
                $table->index('is_active');
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
