<?php


use Illuminate\Database\Migrations\Migration;

class CreateContentVariantsDataTable extends Migration
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
            'content_variants_data' => [
                'custom_field_id' => 'string',
                'custom_field_value_id' => 'string',
                'rel_type' => 'string',
                'rel_id' => 'string',
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
