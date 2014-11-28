<?php namespace Microweber\Install\Schema;

class Content 
{

    public function get()
    {        
        return [
            'content' => [
                'updated_on' => 'dateTime',
                'created_on' => 'dateTime',
                'expires_on' => 'dateTime',

                'created_by' => 'integer',

                'edited_by' => 'integer',


                'content_type' => 'longText',
                'url' => 'longText',
                'content_filename' => 'longText',
                'title' => 'longText',
                'parent' => 'integer',
                'description' => 'longText',
                'content_meta_title' => 'longText',

                'content_meta_keywords' => 'longText',
                'position' => 'integer',

                'content' => 'longText',
                'content_body' => 'longText',

                'is_active' => "integer",
                'is_home' => "integer",
                'is_pinged' => "integer",
                'is_shop' => "integer",
                'is_deleted' => "integer",
                'draft_of' => 'integer',

                'require_login' => "integer",

                'status' => 'longText',

                'subtype' => 'longText',
                'subtype_value' => 'longText',


                'custom_type' => 'longText',
                'custom_type_value' => 'longText',


                'original_link' => 'longText',
                'layout_file' => 'longText',
                'layout_name' => 'longText',
                'layout_style' => 'longText',
                'active_site_template' => 'longText',
                'session_id' => 'string',
                'posted_on' => 'dateTime',

                '$index' => ['url' => 'title']
            ],

            'content_data' => [
                'updated_on' => 'dateTime',
                'created_on' => 'dateTime',
                'created_by' => 'integer',
                'edited_by' => 'integer',
                'content_id' => 'string',
                'field_name' => 'longText',
                'field_value' => 'longText',
                'session_id' => 'string',
                'rel' => 'string',

                'rel_id' => 'string'
            ],

            'content_fields' => [
                'updated_on' => 'dateTime',
                'created_on' => 'dateTime',
                'created_by' => 'integer',
                'edited_by' => 'integer',
                'rel' => 'string',

                'rel_id' => 'string',
                'field' => 'longText',
                'value' => 'longText',

                '$index' => ['rel' => 'rel_id']
            ],

            'content_fields_drafts' => [
                'session_id' => 'string',
                'is_temp' => "integer",
                'url' => 'longText',

                '$index' => ['rel' => 'rel_id']
            ],

            'media' => [
                'updated_on' => 'dateTime',
                'created_on' => 'dateTime',
                'created_by' => 'integer',
                'edited_by' => 'integer',
                'session_id' => 'string',
                'rel' => 'string',

                'rel_id' => "string",
                'media_type' => 'longText',
                'position' => 'integer',
                'title' => 'longText',
                'description' => 'longText',
                'embed_code' => 'longText',
                'filename' => 'longText',

                '$index' => ['rel', 'rel_id', 'media_type']
            ],

            'custom_fields' => [
                'rel' => 'string',
                'rel_id' => 'string',
                'position' => 'integer',
                'type' => 'longText',
                'name' => 'longText',
                'value' => 'longText',
                'values' => 'longText',
                'num_value' => 'float',


                'updated_on' => 'dateTime',
                'created_on' => 'dateTime',
                'created_by' => 'integer',
                'edited_by' => 'integer',
                'session_id' => 'string',


                'custom_field_name' => 'longText',
                'custom_field_name_plain' => 'longText',


                'custom_field_value' => 'longText',


                'custom_field_type' => 'longText',
                'custom_field_values' => 'longText',
                'custom_field_values_plain' => 'longText',

                'field_for' => 'longText',
                'custom_field_field_for' => 'longText',
                'custom_field_help_text' => 'longText',
                'options' => 'longText',


                'custom_field_is_active' => "integer",
                'custom_field_required' => "integer",
                'copy_of_field' => 'integer',

                '$index' => ['rel', 'rel_id', 'custom_field_type']
            ],

            'menus' => [
                'title' => 'longText',
                'item_type' => 'string',
                'parent_id' => 'integer',
                'content_id' => 'integer',
                'categories_id' => 'integer',
                'position' => 'integer',
                'updated_on' => 'dateTime',
                'created_on' => 'dateTime',
                'is_active' => "integer",
                'description' => 'longText',
                'url' => 'longText',
            ],

            'categories' => [
                'updated_on' => 'dateTime',
                'created_on' => 'dateTime',
                'created_by' => 'integer',
                'edited_by' => 'integer',
                'data_type' => 'longText',
                'title' => 'longText',
                'parent_id' => 'integer',
                'description' => 'longText',
                'content' => 'longText',
                'content_type' => 'longText',
                'rel' => 'string',

                'rel_id' => 'integer',

                'position' => 'integer',
                'is_deleted' => "integer",
                'users_can_create_subcategories' => "integer",
                'users_can_create_content' => "integer",
                'users_can_create_content_allowed_usergroups' => 'longText',

                'categories_content_type' => 'longText',
                'categories_silo_keywords' => 'longText',

                '$index' => ['rel', 'rel_id', 'parent_id']
            ],

            'categories_items' => [
                'parent_id' => 'integer',
                'rel' => 'string',

                'rel_id' => 'integer',
                'content_type' => 'longText',
                'data_type' => 'longText',

                '$index' => ['rel_id', 'parent_id']
            ]
        ];
    }
}