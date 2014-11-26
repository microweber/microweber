<?php namespace Microweber\Install;

class Comments extends InstallData
{

    public function get()
    {
        parent::get();
        
        return [
            'comments' => [
                'rel_type' => 'string',
                'rel_id' => 'string',
                'updated_on' => 'dateTime',
                'created_on' => 'dateTime',
                'created_by' => 'integer',
                'edited_by' => 'integer',
                'comment_name' => 'string',
                'comment_body' => 'longText',
                'comment_email' => 'string',
                'comment_website' => 'string',
                'is_moderated' => "integer",
                'from_url' => 'string',
                'comment_subject' => 'string',

                'is_new' => "integer",

                'send_email' => "integer",
                'session_id' => 'string'
            ]
        ];
    }
}