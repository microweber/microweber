<?php

namespace Microweber\Traits;

use Cache;
use DB;

trait ExtendedSave
{

    private $_extended_save_is_admin = false;

    public function extended_save($table_name_or_params, $params = null)
    {
        if ($params === null) {
            $params = $table_name_or_params;
        } else {
            if ($params != false) {
                $params = parse_params($params);
            } else {
                $params = array();
            }
            $params['table'] = $table_name_or_params;
        }
        if (is_string($params)) {
            $params = parse_params($params);
        }
        if (!isset($params['table'])) {
            return false;
        }


        $this->_extended_save_is_admin = $this->app->user_manager->is_admin();


        $ext_params = $params;

        $saved_id = $this->save($params);

        if ($saved_id == false) {
            return false;
        }
        if (!is_array($saved_id) and $saved_id != 0) {
            $ext_params['id'] = $saved_id;
            event_trigger('mw.database.extended_save', $ext_params);
            if (isset($ext_params['attributes'])) {
                $this->extended_save_attributes($ext_params);
            }
            if (isset($ext_params['categories'])) {
                $this->extended_save_categories($ext_params);
            }
            if (isset($ext_params['data_fields'])) {
                $this->extended_save_data_fields($ext_params);
            }

            return $saved_id;
        } else {
            return $params;
        }
    }

    function extended_save_attributes($params)
    {
        event_trigger('mw.database.extended_save_attributes', $params);
        $data_to_save = $params;
        if (isset($data_to_save['attributes'])) {
            $data_fields = $data_to_save['attributes'];
            if (is_array($data_fields) and !empty($data_fields)) {
                foreach ($data_fields as $k => $v) {
                    $save_cat_item = array();
                    $save_cat_item['rel_type'] = $data_to_save['table'];
                    $save_cat_item['rel_id'] = $data_to_save['id'];
                    $save_cat_item["attribute_name"] = $k;
                    $save_cat_item["attribute_value"] = $v;
                    $this->app->content_manager->save_content_attribute($save_cat_item);
                }
            }
        }
    }


    function extended_save_data_fields($params)
    {
        if ($this->_extended_save_is_admin) {
            event_trigger('mw.database.extended_save_data_fields', $params);
            $data_to_save = $params;
            $modified = false;
            if (isset($data_to_save['data_fields'])) {
                $data_fields = $data_to_save['data_fields'];
                if (is_array($data_fields) and !empty($data_fields)) {
                    foreach ($data_fields as $k => $v) {
                        $save_cat_item = array();
                        $save_cat_item['rel_type'] = $data_to_save['table'];
                        $save_cat_item['rel_id'] = $data_to_save['id'];
                        $save_cat_item["field_name"] = $k;
                        $save_cat_item["field_value"] = $v;
                        $this->app->content_manager->save_content_data_field($save_cat_item);
                    }
                }
            }
        }
    }


    function extended_save_categories($params)
    {
        event_trigger('mw.database.extended_save_categories', $params);
        $data_to_save = $params;
        $cats_modified = false;
        if (isset($data_to_save['categories'])) {
            if (is_string($data_to_save['categories'])) {
                $data_to_save['categories'] = explode(',', $data_to_save['categories']);
            }
            $categories = $data_to_save['categories'];
            if (is_array($categories)) {
                $save_cat_item = array();
                $save_cat_item['rel_type'] = $data_to_save['table'];
                $save_cat_item['rel_id'] = $data_to_save['id'];
                $check = $this->app->category_manager->get_items($save_cat_item);
                if (is_array($check) and !empty($check)) {
                    foreach ($check as $item) {
                        if (!in_array($item['parent_id'], $categories)) {
                            $this->app->category_manager->delete_item($item['id']);
                        }
                    }
                }
                $cats_modified = true;
                foreach ($categories as $category) {
                    if (intval($category) != 0) {
                        $save_cat_item = array();
                        $save_cat_item['rel_type'] = $data_to_save['table'];
                        $save_cat_item['rel_id'] = $data_to_save['id'];
                        $save_cat_item['parent_id'] = $category;
                        $check = $this->app->category_manager->get_items($save_cat_item);
                        if ($check == false) {
                            $this->app->category_manager->save_item($save_cat_item);
                        }
                    }
                }

            }
        }
        if ($cats_modified != false) {
            $this->app->cache_manager->delete('categories');
            $this->app->cache_manager->delete('categories_items');
        }
    }
}