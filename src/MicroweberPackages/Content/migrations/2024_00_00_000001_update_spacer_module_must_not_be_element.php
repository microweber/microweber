<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class UpdateSpacerModuleMustNotBeElement extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        if (Schema::hasTable('modules')) {
            // get spacer module
            $module = DB::table('modules')->where('module', 'spacer')->first();
            if ($module) {
                DB::table('modules')->where('module', 'spacer')->update(['as_element' => 0]);
            }
        }


    }

}
