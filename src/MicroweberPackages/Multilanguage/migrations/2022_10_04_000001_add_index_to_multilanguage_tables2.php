<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class AddIndexToMultilanguageTables2 extends Migration
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
                $table->fullText('field_value');
            });
        } catch (Exception $e) {

        }


        try {
            Schema::table('multilanguage_supported_locales', function (Blueprint $table) {
                $table->index('display_locale');

            });
        } catch (Exception $e) {

        }
    }

}
