<?php

use Illuminate\Database\Migrations\Migration;

class AddPriceModifierToCustomFieldsValuesTable extends Migration
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
                'price_modifier' => 'integer',
            ],
        ];
    }

}
