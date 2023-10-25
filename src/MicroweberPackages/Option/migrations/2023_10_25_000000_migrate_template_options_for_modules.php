<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MigrateTemplateOptionsForModules extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('options')) {
            \DB::table('options')
                ->where('option_key', 'template')
                ->whereNotNull('option_value')
                ->whereNotNull('module')
                ->update(['option_key' => 'data-template']);
        }
    }




}
