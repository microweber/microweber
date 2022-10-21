<?php


use Illuminate\Database\Migrations\Migration;

class CreateContentFieldsTable extends Migration
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
            'content_fields' => [
                'updated_at' => 'dateTime',
                'created_at' => 'dateTime',
                'created_by' => 'integer',
                'edited_by' => 'integer',
                'rel_type' => 'string',

                'rel_id' => 'string',
                'field' => 'text',
                'value' => 'longText',

                '$index' => ['rel_type', 'rel_id'],
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
                'is_temp' => 'integer',
                'url' => 'longText',
                '$index' => ['rel_type', 'rel_id'],
            ],
        ];
    }


}
