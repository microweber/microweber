<?php namespace Microweber\Install\Schema;

class Content
{

    public function get()
    {
        return [
            'content' => [
                'updated_at' => 'dateTime',
                'created_at' => 'dateTime',
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
                'updated_at' => 'dateTime',
                'created_at' => 'dateTime',
                'created_by' => 'integer',
                'edited_by' => 'integer',
                'content_id' => 'string',
                'field_name' => 'longText',
                'field_value' => 'longText',
                'session_id' => 'string',
                'rel_type' => 'string',

                'rel_id' => 'string'
            ],

            'content_fields' => [
                'updated_at' => 'dateTime',
                'created_at' => 'dateTime',
                'created_by' => 'integer',
                'edited_by' => 'integer',
                'rel_type' => 'string',

                'rel_id' => 'string',
                'field' => 'longText',
                'value' => 'longText',

                '$index' => ['rel_type', 'rel_id']
            ],

            'content_fields_drafts' => [
                'updated_at' => 'dateTime',
                'created_at' => 'dateTime',
                'created_by' => 'integer',
                'edited_by' => 'integer',
                'rel_type' => 'string',

                'rel_id' => 'string',
                'field' => 'longText',
                'value' => 'longText',

                'session_id' => 'string',
                'is_temp' => "integer",
                'url' => 'longText',

                '$index' => ['rel_type', 'rel_id']
            ],

            'media' => [
                'updated_at' => 'dateTime',
                'created_at' => 'dateTime',
                'created_by' => 'integer',
                'edited_by' => 'integer',
                'session_id' => 'string',
                'rel_type' => 'string',

                'rel_id' => "string",
                'media_type' => 'longText',
                'position' => 'integer',
                'title' => 'longText',
                'description' => 'longText',
                'embed_code' => 'longText',
                'filename' => 'longText',

                '$index' => ['rel_type', 'rel_id', 'media_type']
            ],

            'custom_fields' => [
                'rel_type' => 'string',
                'rel_id' => 'string',
                'position' => 'integer',
                'type' => 'string',
                'name' => 'longText',

                'updated_at' => 'dateTime',
                'created_at' => 'dateTime',
                'created_by' => 'integer',
                'edited_by' => 'integer',
                'session_id' => 'string',


                'options' => 'longText',


                'is_active' => "integer",
                'required' => "integer",
                'copy_of_field' => 'integer',

                '$index' => ['rel_type', 'rel_id', 'type']
            ],

            'custom_fields_values' => [
                'custom_field_id' => 'integer',

                'value' => 'text',
                'position' => 'integer',



                '$index' => ['custom_field_id', 'value']
            ],

            'menus' => [
                'title' => 'longText',
                'item_type' => 'string',
                'parent_id' => 'integer',
                'content_id' => 'integer',
                'categories_id' => 'integer',
                'position' => 'integer',
                'updated_at' => 'dateTime',
                'created_at' => 'dateTime',
                'is_active' => "integer",
                'description' => 'longText',
                'url' => 'longText',
            ],

            'categories' => [
                'updated_at' => 'dateTime',
                'created_at' => 'dateTime',
                'created_by' => 'integer',
                'edited_by' => 'integer',
                'data_type' => 'string',
                'title' => 'longText',
                'parent_id' => 'integer',
                'description' => 'longText',
                'content' => 'longText',
                'content_type' => 'string',
                'rel_type' => 'string',

                'rel_id' => 'integer',

                'position' => 'integer',
                'is_deleted' => "integer",
                'users_can_create_subcategories' => "integer",
                'users_can_create_content' => "integer",
                'users_can_create_content_allowed_usergroups' => 'string',

                'categories_content_type' => 'longText',
                'categories_silo_keywords' => 'longText',

                '$index' => ['rel_type', 'rel_id', 'parent_id']
            ],

            'categories_items' => [
                'parent_id' => 'integer',
                'rel_type' => 'string',

                'rel_id' => 'integer',
                'content_type' => 'string',
                'data_type' => 'string',

                '$index' => ['rel_id', 'parent_id']
            ]
        ];
    }
}