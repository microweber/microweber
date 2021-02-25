<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use \Illuminate\Support\Facades\DB;

class MigrateNlToNlNl extends Migration
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
                ->where('translation_locale', 'like', 'nl')
                ->update(['translation_locale' => 'nl_NL']);
        }

        if (Schema::hasTable('multilanguage_translations')) {
            DB::table('multilanguage_translations')
                ->where('locale', 'like', 'nl')
                ->update(['locale' => 'nl_NL']);
        }

        if (Schema::hasTable('multilanguage_supported_locales')) {
            DB::table('multilanguage_supported_locales')
                ->where('locale', 'like', 'nl')
                ->update(['locale' => 'nl_NL', 'display_locale' => 'nl_NL']);
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
