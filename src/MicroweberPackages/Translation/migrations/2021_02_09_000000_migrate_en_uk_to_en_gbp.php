<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use \Illuminate\Support\Facades\DB;

class MigrateEnUkToEnGbp extends Migration
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
                ->where('translation_locale', 'like', 'en_uk')
                ->update(['translation_locale' => 'en_GB']);
        }

        if (Schema::hasTable('multilanguage_translations')) {
            DB::table('multilanguage_translations')
                ->where('locale', 'like', 'en_uk')
                ->update(['locale' => 'en_GB']);
        }

        if (Schema::hasTable('multilanguage_supported_locales')) {
            DB::table('multilanguage_supported_locales')
                ->where('locale', 'like', 'en_uk')
                ->update(['locale' => 'en_GB', 'display_locale'=>'en_GB']);
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
