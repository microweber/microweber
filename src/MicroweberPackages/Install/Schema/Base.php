<?php

namespace MicroweberPackages\Install\Schema;

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
            'allow_caching' => 'integer',
            'ui_admin' => 'integer',
            'ui_admin_iframe' => 'integer',
            'is_system' => 'integer',
            'is_integration' => 'integer',
            'version' => 'string',
            'notifications' => 'integer',
            'settings' => 'text',
            'categories' => 'text',
            'keywords'=>'text'
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
                'module_attrs' => 'text',
                'position' => 'integer',

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
                'due_on' => 'dateTime',
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
                'is_system' => 'string',
            ],

//            'notifications' => [
//                'updated_at' => 'dateTime',
//                'created_at' => 'dateTime',
//                'created_by' => 'integer',
//                'edited_by' => 'integer',
//                'rel_id' => 'string',
//                'rel_type' => 'string',
//                'notif_count' => 'integer',
//                'is_read' => 'integer',
//            	'is_mail_sent' => 'integer',
//                'module' => 'text',
//                'title' => 'text',
//                'description' => 'text',
//                'content' => 'text',
//                'notification_data' => 'text',
//            ],
            'terms_accept_log' => [
                'updated_at' => 'dateTime',
                'created_at' => 'dateTime',
                'created_by' => 'integer',
                'edited_by' => 'integer',
                'tos_name' => 'string',
                'user_email' => 'string',
                'user_id' => 'integer',
                'user_ip' => 'text',
             ],

            'tagging_tagged'=> [
                'taggable_id'=>'integer',
                'taggable_type'=>'string',
                'tag_name'=>'string',
                'tag_slug'=>'string',
                'tag_description'=>'text',
            ],

            'tagging_tags'=> [
                'tag_group_id'=>'integer',
                'slug'=>'string',
                'name'=>'string',
                'description'=>'text',
                'suggest'=>'integer',
                'count'=>'integer',
            ]
        ];
    }
}
