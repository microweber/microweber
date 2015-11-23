<?php

namespace Microweber\Utils;

class Crud {

    /** @var \Microweber\Application */
    public $app;

    public $table = 'your_table_name_here';

    function __construct($app = null) {
        if (is_object($app)){
            $this->app = $app;
        } else {
            $this->app = mw();
        }
    }

    public function get($params) {
        if (is_string($params)){
            $params = parse_params($params);
        }
        if($params==false){
            return;
        }
        $table = $this->table;
        $params['table'] = $table;

        $get = $this->app->database_manager->get($params);

        return $get;
    }

    public function save($params) {
        if (is_string($params)){
            $params = parse_params($params);
        }
        if($params==false){
            return;
        }


        $table = $this->table;
        $params['table'] = $table;
        $save = $this->app->database_manager->save($params);
        return $save;
    }


    public function delete($data) {
        if (!is_array($data)){
            $id = intval($data);
            $data = array('id' => $id);
        }
        if (!isset($data['id']) or $data['id']==0){
            return false;
        }
        $table = $this->table;
        return $this->app->database_manager->delete_by_id($table, $id = $data['id'], $field_name = 'id');
    }
    public function get_by_id($id = 0, $field_name = 'id')
    {
        $id = intval($id);
        if ($id == 0) {
            return false;
        }
        if ($field_name == false) {
            $field_name = "id";
        }
        $table = $this->table;
        $params = array();
        $params[$field_name] = $id;
        $params['table'] = $table;
        $params['single'] = true;
        $data = $this->get($params);
        return $data;
    }

}