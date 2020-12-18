<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOptionsTable extends Migration
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
            'options' => [
                'updated_at' => 'dateTime',
                'created_at' => 'dateTime',

                'option_key' => 'string',
                'option_value' => 'longText',
                'option_key2' => 'string',
                'option_value2' => 'text',
                'position' => 'integer',
                'option_group' => 'string',
                'name' => 'string',
                'help' => 'string',
                'field_type' => 'string',
                'field_values' => 'string',
                'module' => 'string',
                'is_system' => 'integer',
                'option_value_prev' => 'longText',

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
        Schema::drop('options');
    }
}