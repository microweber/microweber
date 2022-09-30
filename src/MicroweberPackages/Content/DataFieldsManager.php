<?php

namespace MicroweberPackages\Content;

use MicroweberPackages\Database\Crud;

class DataFieldsManager extends Crud
{
    /** @var \MicroweberPackages\App\LaravelApplication */
    public $app;

    public $table = 'content_data';


    public function get_values($params)
    {
        $get = [];
        if (isset($params['content_id'])) {


            return app()->content_repository->getContentDataValues($params['content_id']);


        } else {
            // get data for other than content
            $get = $this->get($params);

        }


        if (!empty($get)) {
            $res = array();
            foreach ($get as $item) {
                if (isset($item['field_name']) and isset($item['field_value'])) {
                    $res[$item['field_name']] = $item['field_value'];
                }
            }


            return $res;
        }
    }

    public function save($data)
    {
        if (!is_array($data)) {
            $data = parse_params($data);
        }
        if (!isset($data['id'])) {
            if (!isset($data['field_name'])) {
                return array('error' => "You must set 'field' parameter");
            }
            if (!isset($data['field_value'])) {
                return array('error' => "You must set 'value' parameter");
            }
        }
        if (!isset($data['rel_type']) and isset($data['content_id'])) {
            $data['rel_type'] = 'content';
            $data['rel_id'] = $data['content_id'];
        }
        if (isset($data['field_name']) and isset($data['rel_id']) and isset($data['rel_type'])) {
            $is_existing_data = array();
            $is_existing_data['field_name'] = $data['field_name'];
            $is_existing_data['rel_id'] = $data['rel_id'];
            $is_existing_data['rel_type'] = $data['rel_type'];
            $is_existing_data['one'] = true;
            $is_existing = $this->get($is_existing_data);
            if (is_array($is_existing) and isset($is_existing['id'])) {
                $data['id'] = $is_existing['id'];
            }
        }
        if (isset($data['content_id'])) {
            $data['rel_id'] = intval($data['content_id']);
        }
        if (isset($data['field_value']) and is_array($data['field_value'])) {
            $data['field_value'] = json_encode($data['field_value']);
        }
        if (!isset($data['rel_type'])) {
            $data['rel_type'] = 'content';
        }

        if (isset($data['rel_type']) and $data['rel_type'] == 'content') {
            if (isset($data['rel_id'])) {
                $data['content_id'] = $data['rel_id'];
            }
        }
        $save = parent::save($data);

        return $save;
    }
}
