<?php

namespace Microweber\Install\Schema;

class Comments
{
    public function get()
    {
        return [
            'comments' => [
                'rel_type' => 'string',
                'rel_id' => 'string',
                'updated_at' => 'dateTime',
                'created_at' => 'dateTime',
                'created_by' => 'integer',
                'edited_by' => 'integer',
                'comment_name' => 'string',
                'comment_body' => 'longText',
                'comment_email' => 'string',
                'comment_website' => 'string',
                'is_moderated' => 'integer',
                'from_url' => 'string',
                'comment_subject' => 'string',

                'is_new' => 'integer',

                'send_email' => 'integer',
                'session_id' => 'string',
            ],
        ];
    }
}
