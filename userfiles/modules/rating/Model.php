<?php


namespace Microweber\rating;


class Model
{

    public $app;
    protected $table = 'rating';
    protected $fields = array(
        'rel' => 'text',
        'rel_id' => 'text',
        'session_id' => 'text',
        'rating' => 'int',
        'created_on' => 'datetime',
        'user_id' => 'int',
        'user_ip' => 'text'
    );

    public function __construct($app)
    {
        if (!is_object($this->app)) {
            if (is_object($app)) {
                $this->app = $app;
            } else {
                $this->app = \Microweber\Application::getInstance();
            }
        }

        $this->app->database->build_table($this->table, $this->fields);
    }

    public function save($data)
    {
        return $this->app->database->save($this->table, $data);

    }

    public function get($params)
    {
        $params['table'] = $this->table;
        return $this->app->database->get($params);

    }
}