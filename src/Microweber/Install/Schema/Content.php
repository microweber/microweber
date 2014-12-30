<?php namespace Microweber\Install\Schema;

class Content
{
    public function get()
    {
        return [
            'content' => [
                'updated_at' => 'dateTime',
                'created_at' => 'dateTime',
                'expires_at' => 'dateTime',

                'created_by' => 'integer',

                'edited_by' => 'integer',


                'content_type' => 'string',
                'url' => 'text',
                'content_filename' => 'string',
                'title' => 'text',
                'parent' => 'integer',
                'description' => 'text',
                'content_meta_title' => 'text',

                'content_meta_keywords' => 'text',
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

                'status' => 'string',

                'subtype' => 'string',
                'subtype_value' => 'string',


                'custom_type' => 'string',
                'custom_type_value' => 'string',


                'original_link' => 'string',
                'layout_file' => 'string',
                'layout_name' => 'string',
                'layout_style' => 'string',
                'active_site_template' => 'string',
                'session_id' => 'string',
                'posted_at' => 'dateTime',

                '$index' => ['url' => 'title']
            ],

            'content_data' => [
                'updated_at' => 'dateTime',
                'created_at' => 'dateTime',
                'created_by' => 'integer',
                'edited_by' => 'integer',
                'content_id' => 'string',
                'field_name' => 'text',
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
                'field' => 'text',
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
                'field' => 'text',
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
                'description' => 'text',
                'embed_code' => 'text',
                'filename' => 'text',

                '$index' => ['rel_type', 'rel_id', 'media_type']
            ],

            'custom_fields' => [
                'rel_type' => 'string',
                'rel_id' => 'text',
                'position' => 'integer',
                'type' => 'string',
                'name' => 'text',
                'name_key' => 'text',

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
                'title' => 'text',
                'item_type' => 'string',
                'parent_id' => 'integer',
                'content_id' => 'integer',
                'categories_id' => 'integer',
                'position' => 'integer',
                'updated_at' => 'dateTime',
                'created_at' => 'dateTime',
                'is_active' => "integer",
                'description' => 'text',
                'url' => 'longText',
            ],

            'categories' => [
                'updated_at' => 'dateTime',
                'created_at' => 'dateTime',
                'created_by' => 'integer',
                'edited_by' => 'integer',
                'data_type' => 'string',
                'title' => 'text',
                'parent_id' => 'integer',
                'description' => 'text',
                'content' => 'longText',
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


                '$index' => ['rel_id', 'parent_id']
            ]
        ];
    }
}