<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;


class MigrateMorphClassStandard extends Migration
{
    public function up()
    {
        $tables = [
            'media',
            'content_data',
            'content_data_variants',
//            'content_fields',
//            'content_fields_drafts',
//            'content_revisions_history',
            //'forms_data',
            'custom_fields',
            'categories',
            'comments',
            'mail_subscribers',
            'multilanguage_translations',
            'rating'
        ];

        foreach ($tables as $table) {
            $getTableData = \Illuminate\Support\Facades\DB::table($table)->get();
            if (!empty($getTableData)) {
                foreach ($getTableData as $tableData) {
                    if ($tableData->rel_type == 'modules') {
                        $tableData->rel_type = \MicroweberPackages\Module\Models\Module::class;
                    }
                    if ($tableData->rel_type == 'categories') {
                        $tableData->rel_type = \MicroweberPackages\Category\Models\Category::class;
                    }
                    if ($tableData->rel_type == 'content') {
                        $tableData->rel_type = \MicroweberPackages\Content\Models\Content::class;
                    }
                    \Illuminate\Support\Facades\DB::table($table)->where('id', $tableData->id)->update([
                        'rel_type' => $tableData->rel_type
                    ]);
                }
            }
        }
    }

    public function down()
    {

    }
}

