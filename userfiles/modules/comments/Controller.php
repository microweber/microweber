<?php
namespace Microweber\comments;


class Controller
{
    var $model = null;

    function __construct($app = null)
    {
        $this->model = new Model($app);
    }

    function index()
    {
        print 1234;
    }

    function save($item)
    {
        if (!isset($item['rel_id']) and !isset($item['rel_type']) and !isset($item['rating'])) {
            return false;
        }
        $save = $this->model->save($item);
        return $save;
    }

    function show_rating($item)
    {
        $ratings = $this->model->get($item);
    }
}