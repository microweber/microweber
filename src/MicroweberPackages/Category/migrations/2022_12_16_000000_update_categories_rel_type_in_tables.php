<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class UpdateCategoriesRelTypeInTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        $tables = [
            'media',
            'content_fields',
            'content_data',
            'custom_fields',
            'multilanguage_translations'
        ];
        foreach ($tables as $table) {
            if (Schema::hasTable($table)) {
                DB::table($table)
                    ->where('rel_type', 'category')
                    ->update(['rel_type' => morph_name(\Modules\Category\Models\Category::class)]);
            }
        }
    }

}
