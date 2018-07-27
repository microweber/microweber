<?php

use Microweber\Providers\Database\Crud;



class CalendarGroupsCrud extends Crud
{
    /** @var \Microweber\Application */
    public $app;

    public $table = 'calendar_groups';

    public function __construct($app = null)
    {
        if (is_object($app)) {
            $this->app = $app;
        } else {
            $this->app = mw();
        }
    }


    public function get($data = false)
    {
        if (is_string($data)) {
            $data = parse_params($data);
        }
        if (!is_array($data)) {
            $data = array();
        }

        $get = parent::get($data);

        return $get;
    }

    public function save($data)
    {
        if (!is_array($data)) {
            $data = parse_params($data);
        }


        $save = parent::save($data);

        return $save;
    }
}
