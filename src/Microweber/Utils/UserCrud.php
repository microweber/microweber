<?php

namespace Microweber\Utils;

class UserCrud extends Crud {

    public function get($params) {
        if (!is_logged()){
            return;
        }
        if (is_string($params)){
            $params = parse_params($params);
        }
        if ($params==false){
            return;
        }
        $params['created_by'] = user_id();

        $get = parent::get($params);
        return $get;
    }

    public function save($params) {

        if (is_string($params)){
            $params = parse_params($params);
        }
        if ($params==false){
            return;
        }

        if (!$this->has_permission($params)){
            return;
        }


        $table = $this->table;
        $params['table'] = $table;
        $save = parent::save($params);
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

        if (!$this->has_permission($data)){
            return;
        }

        return parent::delete($data);
    }


}