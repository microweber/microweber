<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeEnUkToEnGbLocale extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('multilanguage_supported_locales')) {

            Schema::table('multilanguage_supported_locales', function (Blueprint $table) {
                \DB::table('multilanguage_supported_locales')
                    ->where('locale', 'LIKE', "%en_uk%")
                    ->update(['locale' => 'en_GB']);
            });
            Schema::table('multilanguage_translations', function (Blueprint $table) {
                \DB::table('multilanguage_translations')
                    ->where('locale', 'LIKE', "%en_uk%")
                    ->update(['locale' => 'en_GB']);
            });
        }


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('en_gb_locale', function (Blueprint $table) {
            //
        });
    }
}
