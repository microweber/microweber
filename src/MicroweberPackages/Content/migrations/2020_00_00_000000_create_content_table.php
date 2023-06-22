<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        app()->database_manager->build_tables($this->getSchema());
    }

    public function getSchema()
    {
        return [
            'content' => [

                'content_type' => 'string',
                'subtype' => 'string',
                'url' => 'text',

                'title' => 'text',
                'parent' => 'integer',
                'description' => 'text',
                'position' => 'integer',
                'content' => 'longText',
                'content_body' => 'longText',
                'is_active' => array('type' => 'integer', 'default' => 1),
                'subtype_value' => 'string',
                'custom_type' => 'string',
                'custom_type_value' => 'string',
                'active_site_template' => 'string',
                'layout_file' => 'string',
                'layout_name' => 'string',
                'layout_style' => 'string',
                'content_filename' => 'string',
                'original_link' => 'string',

                'is_home' => array('type' => 'integer', 'default' => 0),
                'is_pinged' => array('type' => 'integer', 'default' => 0),
                'is_shop' => array('type' => 'integer', 'default' => 0),
                'is_deleted' => array('type' => 'integer', 'default' => 0),

                'require_login' => array('type' => 'integer', 'default' => 0),
                'status' => 'string',
                'content_meta_title' => 'text',
                'content_meta_keywords' => 'text',
                'content_meta_description' => 'text',

                'session_id' => 'string',
                'updated_at' => 'dateTime',
                'created_at' => 'dateTime',
                'expires_at' => 'dateTime',

                'created_by' => 'integer',

                'edited_by' => 'integer',
                'posted_at' => 'dateTime',
                'draft_of' => 'integer',
                'copy_of' => 'integer',

                '$index' => ['url' => 'title'],
            ],

            'attributes' => [
                'attribute_name' => 'text',
                'attribute_value' => 'longText',
                'rel_type' => 'string',
                'rel_id' => 'string',
                'attribute_type' => 'string',
                'session_id' => 'string',
                'updated_at' => 'dateTime',
                'created_at' => 'dateTime',
                'created_by' => 'integer',
                'edited_by' => 'integer',
            ],

        ];
    }

}
