<?php

namespace Microweber\comments;


class Model
{
    public $app;
    protected $table = 'comments';
    protected $fields = array(
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
        'for_newsletter' => "integer",
        'is_new' => "integer",
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'created_by' => 'int',
        'edited_by' => 'int',
        'user_ip' => 'text'
    );

    public function __construct($app)
    {
        if (!is_object($this->app)) {
            if (is_object($app)) {
                $this->app = $app;
            } else {
                $this->app = mw();
            }
        }

     //   $this->app->database_manager->build_table($this->table, $this->fields);
    }

    public function save($data)
    {
        return $this->app->database_manager->save($this->table, $data);

    }

    public function get($params)
    {
        $params['table'] = $this->table;
        return $this->app->database_manager->get($params);

    }
}