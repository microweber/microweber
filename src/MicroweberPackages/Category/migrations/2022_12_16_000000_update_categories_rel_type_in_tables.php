<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
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
                \DB::table($table)
                    ->where('rel_type', 'categories')
                    ->update(['rel_type' => 'category']);
            }
        }
    }

}
