<?php


namespace Microweber\rating;


class Model
{
    /**
     * An instance of the Microweber Application class
     *
     * @var $app
     */
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

        $this->app->db->build_table($this->table, $this->fields);
    }

    public function save($data)
    {
        return $this->app->db->save($this->table, $data);

    }

    public function get($params)
    {
        $params['table'] = $this->table;
      //  $params['debug'] = $this->table;
        return $this->app->db->get($params);

    }
}