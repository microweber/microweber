<?php


use Illuminate\Database\Migrations\Migration;

class CreateContentDataTable extends Migration
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
            'content_data' => [
                'updated_at' => 'dateTime',
                'created_at' => 'dateTime',
                'created_by' => 'integer',
                'edited_by' => 'integer',
               // 'content_id' => 'string',
                'field_name' => 'text',
                'field_value' => 'longText',
              //  'session_id' => 'string',
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