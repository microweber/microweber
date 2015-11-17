<?php


namespace Microweber\Providers\Content;

use Microweber\Utils\Crud;


class AttributesManager extends Crud {

    /** @var \Microweber\Application */
    public $app;

    public $table = 'attributes';


    function __construct($app = null) {
        if (is_object($app)){
            $this->app = $app;
        } else {
            $this->app = mw();
        }
    }

    public function get_values($params) {

        $get = $this->get($params);

        if (!empty($get)){
            $res = array();
            foreach ($get as $item) {
                if (isset($item['attribute_name']) and isset($item['attribute_value'])){
                    $res[ $item['attribute_name'] ] = $item['attribute_value'];
                }
            }

            return $res;
        }
    }


    public function get($data = false) {
        if (is_string($data)){
            $data = parse_params($data);
        }
        if (!is_array($data)){
            $data = array();
        }

        $get = parent::get($data);
        if (!empty($get)){
            foreach ($get as $k => $data) {
                if (isset($data['attribute_value']) and isset($data['attribute_type']) and ($data['attribute_type']=='array')){
                    $data['attribute_value'] = json_decode($data['attribute_value'], true);
                    $get[ $k ] = $data;
                }
            }
        }

        return $get;
    }

    public function save($data) {
        if (!is_array($data)){
            $data = parse_params($data);
        }
        if (!isset($data['id'])){
            if (!isset($data['attribute_name'])){
                return array('error' => "You must set 'field' parameter");
            }
            if (!isset($data['attribute_value'])){
                return array('error' => "You must set 'value' parameter");
            }
        }
        if (!isset($data['rel_type']) and isset($data['content_id'])){
            $data['rel_type'] = 'content';
            $data['rel_id'] = $data['content_id'];
        }
        if (isset($data['attribute_name']) and isset($data['rel_id']) and isset($data['rel_type'])){
            $is_existing_data = array();
            $is_existing_data['attribute_name'] = $data['attribute_name'];
            $is_existing_data['rel_id'] = $data['rel_id'];
            $is_existing_data['rel_type'] = $data['rel_type'];
            $is_existing_data['one'] = true;
            $is_existing = $this->get($is_existing_data);
            if (is_array($is_existing) and isset($is_existing['id'])){
                $data['id'] = $is_existing['id'];
            }
        }
        if (isset($data['content_id'])){
            $data['rel_id'] = intval($data['content_id']);
        }
        if (isset($data['attribute_value']) and is_array($data['attribute_value'])){
            $data['attribute_value'] = json_encode($data['attribute_value']);
            $data['attribute_type'] = 'array';
        }
        if (!isset($data['rel_type'])){
            $data['rel_type'] = 'content';
        }

        if (isset($data['rel_type']) and $data['rel_type']=='content'){
            if (isset($data['rel_id'])){
                $data['content_id'] = $data['rel_id'];
            }
        }
        $save = parent::save($data);
         return $save;
    }


}