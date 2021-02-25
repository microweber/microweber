<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use \Illuminate\Support\Facades\DB;

class MigrateFrToFrFr extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('translations')) {
            DB::table('translations')
                ->where('translation_locale', 'like', 'fr')
                ->update(['translation_locale' => 'fr_FR']);
        }

        if (Schema::hasTable('multilanguage_translations')) {
            DB::table('multilanguage_translations')
                ->where('locale', 'like', 'fr')
                ->update(['locale' => 'fr_FR']);
        }

        if (Schema::hasTable('multilanguage_supported_locales')) {
            DB::table('multilanguage_supported_locales')
                ->where('locale', 'like', 'fr')
                ->update(['locale' => 'fr_FR', 'display_locale' => 'fr_FR']);
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
