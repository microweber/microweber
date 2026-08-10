<?php

use MicroweberPackages\Database\Facades\DatabaseManager;


use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DatabaseManager::build_tables($this->getSchema());
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

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('content_fields');
        Schema::dropIfExists('content_fields_drafts');
    }


};
