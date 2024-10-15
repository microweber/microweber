<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
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
            DB::table('multilanguage_supported_locales')
                ->where('locale', 'LIKE', "%en_uk%")
                ->update(['locale' => 'en_GB']);

        }

        if (Schema::hasTable('multilanguage_translations')) {
            DB::table('multilanguage_translations')
                ->where('locale', 'LIKE', "%en_uk%")
                ->update(['locale' => 'en_GB']);
        }

    }


}
