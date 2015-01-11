<?php namespace Microweber\Install\Schema;

class Base
{
    public function get()
    {
        $modulesSchema = [
            'updated_at' => 'dateTime',
            'created_at' => 'dateTime',
            'expires_on' => 'dateTime',
            'created_by' => 'integer',
            'edited_by' => 'integer',
            'name' => 'text',
            'parent_id' => 'integer',
            'module_id' => 'text',
            'module' => 'text',
            'description' => 'text',
            'icon' => 'text',
            'author' => 'text',
            'website' => 'text',
            'help' => 'text',
            'type' => 'text',
            'installed' => 'integer',
            'ui' => 'integer',
            'position' => 'integer',
            'as_element' => 'integer',
            'ui_admin' => 'integer',
            'ui_admin_iframe' => 'integer',
            'is_system' => 'integer',
            'version' => 'string',
            'notifications' => 'integer'
        ];

        return [
            'modules' => $modulesSchema,

            'elements' => array_merge($modulesSchema, ['layout_type' => 'string']),

            'module_templates' => [
                'updated_at' => 'dateTime',
                'created_at' => 'dateTime',
                'created_by' => 'integer',
                'edited_by' => 'integer',
                'module_id' => 'text',
                'name' => 'text',
                'module' => 'text',
            ],

            'system_licenses' => [
                'updated_at' => 'dateTime',
                'created_at' => 'dateTime',
                'created_by' => 'integer',
                'edited_by' => 'integer',
                'rel_type' => 'text',
                'rel_id' => 'text',
                'local_key' => 'text',
                'local_key_hash' => 'text',
                'registered_name' => 'text',
                'company_name' => 'text',
                'domains' => 'text',
                'status' => 'text',
                'product_id' => 'integer',
                'service_id' => 'integer',
                'billing_cycle' => 'text',
                'reg_on' => 'dateTime',
                'due_on' => 'dateTime'
            ],

            'users' => [
                'updated_at' => 'dateTime',
                'created_at' => 'dateTime',
                'expires_on' => 'dateTime',
                'last_login' => 'dateTime',
                'last_login_ip' => 'string',
                'created_by' => 'integer',
                'edited_by' => 'integer',
                'username' => 'string',
                'password' => 'string',
                'email' => 'string',
                'remember_token' => 'string',
                'is_active' => "integer",
                'is_admin' => "integer",
                'is_verified' => "integer",
                'is_public' => "integer",
                'basic_mode' => "string",
                'first_name' => 'string',
                'last_name' => 'string',
                'thumbnail' => 'string',
                'parent_id' => 'integer',
                'api_key' => 'string',
                'user_information' => 'text',
                'subscr_id' => 'string',
                'role' => 'string',
                'medium' => 'string',
                'oauth_uid' => 'string',
                'oauth_provider' => 'string',
                'oauth_token' => 'text',
                'oauth_token_secret' => 'text',
                'profile_url' => 'string',
                'website_url' => 'string',
                'password_reset_hash' => 'string',
                '$index' => ['username', 'email']
            ],

            'users_oauth' => [
                'user_id' => 'integer',
                'provider' => 'string',
                'data_id' => 'string',
                'data_name' => 'string',
                'data_email' => 'string',
                'data_token' => 'string',
                'data_avatar' => 'string',
                'data_raw' => 'string'
            ],

            'log' => [
                'updated_at' => 'dateTime',
                'created_at' => 'dateTime',
                'created_by' => 'integer',
                'edited_by' => 'integer',
                'rel_type' => 'text',
                'rel_id' => 'text',
                'position' => 'integer',
                'field' => 'text',
                'value' => 'text',
                'module' => 'text',
                'data_type' => 'text',
                'title' => 'text',
                'description' => 'text',
                'content' => 'text',
                'user_ip' => 'text',
                'session_id' => 'text',
                'is_system' => "string"
            ],

            'notifications' => [
                'updated_at' => 'dateTime',
                'created_at' => 'dateTime',
                'created_by' => 'integer',
                'edited_by' => 'integer',
                'rel_id' => 'integer',
                'rel_type' => 'string',
                'notif_count' => 'integer',
                'is_read' => 'integer',
                'module' => 'text',
                'title' => 'text',
                'description' => 'text',
                'content' => 'text'
            ]
        ];
    }
}