<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommentsTable extends Migration
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
//
//    'rel_type' => 'string',
//    'rel_id' => 'string',
//    'updated_at' => 'dateTime',
//    'created_at' => 'dateTime',
//    'created_by' => 'integer',
//    'edited_by' => 'integer',
//    'comment_name' => 'string',
//    'comment_body' => 'longText',
//    'comment_email' => 'string',
//    'comment_website' => 'string',
//    'is_moderated' => 'integer',
//    'from_url' => 'string',
//    'comment_subject' => 'string',
//    'is_new' => 'integer',
//    'is_sent_email' => 'integer',
//    'is_subscribed_for_notification' => 'integer',
//    'session_id' => 'string',

    public function getSchema()
    {
        return [
            'comments' => [
                'rel_type' => 'text',
                'rel_id' => 'text',
                'session_id' => 'text',
                'comment_name' => 'text',
                'comment_body' => 'text',
                'comment_email' => 'text',
                'comment_website' => 'text',
                'from_url' => 'text',
                'comment_subject' => 'text',
                'is_moderated' => "integer",
                'is_spam' => "integer",
                'for_newsletter' => "integer",
                'is_new' => "integer",
                'created_at' => 'datetime',
                'updated_at' => 'datetime',
                'created_by' => 'integer',
                'edited_by' => 'integer',
                'user_ip' => 'text',
                'reply_to_comment_id' => "integer"
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
