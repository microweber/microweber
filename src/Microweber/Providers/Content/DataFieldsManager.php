<?php


namespace Microweber\Providers\Content;

use Microweber\Utils\Crud;


class DataFieldsManager extends Crud {

    public $table = 'content_data';


    public function get_values($params) {

        $get = $this->get($params);

        if (!empty($get)){
            $res = array();
            foreach ($get as $item) {
                if (isset($item['field_name']) and isset($item['field_value'])){
                    $res[ $item['field_name'] ] = $item['field_value'];
                }
            }

            return $res;
        }
    }
}