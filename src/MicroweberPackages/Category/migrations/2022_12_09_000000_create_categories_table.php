<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        app()->database_manager->build_tables($this->getSchema());



        \DB::table('categories')
            ->whereNull('is_active')
            ->update(['is_active' => 1]);

        \DB::table('categories')
            ->whereNull('is_hidden')
            ->update(['is_hidden' => 0]);


        \DB::table('categories')
            ->whereNull('is_deleted')
            ->update(['is_deleted' => 0]);




    }

    public function getSchema()
    {
        return [
            'categories' => [
                'updated_at' => 'dateTime',
                'created_at' => 'dateTime',
                'created_by' => 'integer',
                'edited_by' => 'integer',
                'data_type' => 'string',
                'title' => 'text',
                'url' => 'longText',

                'parent_id' => 'integer',
                'description' => 'text',
                'content' => 'longText',

                'rel_type' => 'string',
                'rel_id' => 'integer',

                'position' => 'integer',
                'is_deleted' => array('type' => 'integer', 'default' => 0),
                'is_active' => array('type' => 'integer', 'default' => 1),
                'is_hidden' => array('type' => 'integer', 'default' => 0),
                'users_can_create_subcategories' => 'integer',
                'users_can_create_content' => 'integer',
                'users_can_create_content_allowed_usergroups' => 'string',

                //  'categories_content_type' => 'longText',
                'category_meta_title' => 'text',
                'category_meta_keywords' => 'text',
                'category_meta_description' => 'text',
                'category_subtype' => 'string',
                'category_subtype_settings' => 'longText',

                '$index' => ['rel_type', 'rel_id', 'parent_id'],
            ],

            'categories_items' => [
                'parent_id' => 'integer',
                'rel_type' => 'string',

                'rel_id' => 'integer',

                '$index' => ['rel_id', 'parent_id'],
            ],
        ];
    }
}
