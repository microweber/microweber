<?php

use Illuminate\Database\Migrations\Migration;

class CreateCustomFieldsTable extends Migration
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
            'custom_fields_values' => [
                'custom_field_id' => 'integer',
                'value' => 'text',
                'position' => 'integer',
                '$index' => ['custom_field_id', 'value'],
            ],
        ];
    }

}
