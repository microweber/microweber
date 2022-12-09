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
// moved to migration in MicroweberPackages\ContentData\migrations\2020_00_00_000000_create_content_data_table.php
//            'content_data' => [
//                'updated_at' => 'dateTime',
//                'created_at' => 'dateTime',
//                'created_by' => 'integer',
//                'edited_by' => 'integer',
//                'content_id' => 'string',
//                'field_name' => 'text',
//                'field_value' => 'longText',
//                'session_id' => 'string',
//                'rel_type' => 'string',
//                'rel_id' => 'string',
//            ],

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

// moved to migration in MicroweberPackages\ContentField\migrations\2022_00_00_000000_create_content_fields_table.php
//            'content_fields' => [
//                'updated_at' => 'dateTime',
//                'created_at' => 'dateTime',
//                'created_by' => 'integer',
//                'edited_by' => 'integer',
//                'rel_type' => 'string',
//
//                'rel_id' => 'string',
//                'field' => 'text',
//                'value' => 'longText',
//
//                '$index' => ['rel_type', 'rel_id'],
//            ],
//
//            'content_fields_drafts' => [
//                'updated_at' => 'dateTime',
//                'created_at' => 'dateTime',
//                'created_by' => 'integer',
//                'edited_by' => 'integer',
//                'rel_type' => 'string',
//                'rel_id' => 'string',
//                'field' => 'text',
//                'value' => 'longText',
//                'session_id' => 'string',
//                'is_temp' => 'integer',
//                'url' => 'longText',
//                '$index' => ['rel_type', 'rel_id'],
//            ],

            /*
             *  ITS MOVED TO MIGRATIONS TABLE ON MEDIA PACKAGE
             * 'media' => [
                'updated_at' => 'dateTime',
                'created_at' => 'dateTime',
                'created_by' => 'integer',
                'edited_by' => 'integer',
                'session_id' => 'string',
                'rel_type' => 'string',

                'rel_id' => 'string',
                'media_type' => 'longText',
                'position' => 'integer',
                'title' => 'longText',
                'description' => 'text',
                'embed_code' => 'text',
                'filename' => 'text',
                'image_options' => 'text',

                '$index' => ['rel_type', 'rel_id', 'media_type'],
            ],*/

            'custom_fields' => [
                'rel_type' => 'string',
                'rel_id' => 'text',
                'position' => 'integer',
                'type' => 'string',
                'name' => 'text',
                'name_key' => 'text',
                'placeholder' => 'text',
                'error_text' => 'text',
                'updated_at' => 'dateTime',
                'created_at' => 'dateTime',
                'created_by' => 'integer',
                'edited_by' => 'integer',
                'session_id' => 'string',

                'options' => 'longText',
                'show_label' => 'integer',
                'is_active' => 'integer',
                'required' => 'integer',
                'copy_of_field' => 'integer',

                '$index' => ['rel_type', 'rel_id', 'type'],
            ],

            'custom_fields_values' => [
                'custom_field_id' => 'integer',

                'value' => 'text',
                'position' => 'integer',

                '$index' => ['custom_field_id', 'value'],
            ],

//            'menus' => [
//                'title' => 'text',
//                'item_type' => 'string',
//                'parent_id' => 'integer',
//                'content_id' => 'integer',
//                'categories_id' => 'integer',
//                'position' => 'integer',
//                'updated_at' => 'dateTime',
//                'created_at' => 'dateTime',
//                'is_active' => 'integer',
//                'auto_populate' => 'string',
//                'description' => 'text',
//                'url' => 'longText',
//                'url_target' => 'string',
//                'size' => 'text',
//                'default_image' => 'longText',
//                'rollover_image' => 'longText',
//            ],

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

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // delete
    }
}
