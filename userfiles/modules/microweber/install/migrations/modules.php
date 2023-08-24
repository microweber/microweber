<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Init extends Migration {

    function data()
    {
        $schema1 = [
            'updated_at' => 'dateTime',
            'created_at' => 'dateTime',
            'expires_on' => 'dateTime',

            'created_by' => 'integer',

            'edited_by' => 'integer',

            'name' => 'longText',
            'parent_id' => 'integer',
            'module_id' => 'longText',

            'module' => 'longText',
            'description' => 'longText',
            'icon' => 'longText',
            'author' => 'longText',
            'website' => 'longText',
            'help' => 'longText',
            'type' => 'longText',

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
            'modules' => $schema1,
            'elements' => array_merge($schema1, ['layout_type']),
            'module_templates' => [
                'updated_at' => 'dateTime',

                'created_at' => 'dateTime',
                'created_by' => 'integer',
                'edited_by' => 'integer',
                'module_id' => 'longText',
                'name' => 'longText',
                'module' => 'longText',
                'position' => 'integer',


            ],
            'system_licenses' => [
                'updated_at' => 'dateTime',
                'created_at' => 'dateTime',
                'created_by' => 'integer',
                'edited_by' => 'integer',
                'rel_type' => 'string',
                'rel_name' => 'longText',
                'local_key' => 'longText',
                'local_key_hash' => 'longText',
                'registered_name' => 'longText',
                'company_name' => 'longText',
                'domains' => 'longText',
                'status' => 'longText',
                'product_id' => 'integer',
                'service_id' => 'integer',
                'billing_cycle' => 'longText',
                'reg_on' => 'dateTime',
                'due_on' => 'dateTime'
            ]
        ];
    }

	public function up()
	{
        foreach($this->data() as $table => $columns)
        {
            if (!Schema::hasTable($table)) {
                Schema::create($table, function ($table) {
                    $table->increments('id');
                });
            }
            foreach ($columns as $name => $type) {
                if (is_array($type)) {
                    $name = array_shift($type);
                    $type = array_shift($type);
                }
                if (!Schema::hasColumn($table, $name)) {
                    Schema::table($table, function ($table) use ($name, $type) {
                        $table->$type($name);
                    });
                }
            }
        }
	}

	public function down()
	{
        foreach($this->data() as $table => $columns)
        {
            Schema::dropIfExists($table);
        }
	}

}
