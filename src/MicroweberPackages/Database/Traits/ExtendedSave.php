<?php

namespace MicroweberPackages\Database\Traits;

trait ExtendedSave
{
    private $_extended_save_has_permission = false;

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

        $ext_params = $params;
        $ext_params = $params;

        $saved_id = $this->save($params);

        if ($saved_id == false) {
            return false;
        }

        if (!empty($ext_params)) {

//            $data_str = 'attribute_';
//            $data_str_l = strlen($data_str);
//            foreach ($ext_params as $k => $v) {
//                if (is_string($k)){
//                    if (strlen($k) > $data_str_l){
//                        $rest = substr($k, 0, $data_str_l);
//                        $left = substr($k, $data_str_l, strlen($k));
//                        if ($rest==$data_str){
//                            if (!isset($ext_params['attributes'])){
//                                $ext_params['attributes'] = array();
//                            }
//                            $ext_params['attributes'][ $left ] = $v;
//                        }
//                    }
//                }
//            }
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
//            if (isset($ext_params['tags'])) {
//                $this->extended_save_tags($ext_params);
//            }

            if (isset($ext_params['data_fields'])) {
                $this->extended_save_data_fields($ext_params);
            }
            if (isset($ext_params['images'])) {
                $this->extended_save_images($ext_params);
            }

            if (isset($ext_params['custom_fields_advanced'])) {
                 $this->extended_save_custom_fields($ext_params);
                 unset($ext_params['custom_fields_advanced']);
            }

            if (isset($ext_params['tag_names'])) {
                $this->extended_save_tags($ext_params);
            }

            return $saved_id;
        } else {
            return $params;
        }
    }

    public function extended_save_images($params)
    {
        if ($this->extended_save_has_permission()) {
            event_trigger('mw.database.extended_save_images', $params);

            $data_to_save = $params;
            if (isset($data_to_save['images'])) {
                $data_fields = $data_to_save['images'];

                if (is_array($data_fields) and !empty($data_fields)) {
                    foreach ($data_fields as $k => $v) {
                        if (is_string($v)) {
                            $v = array('filename' => $v);
                        }

                        if (isset($v['filename'])) {
                            $save_cat_item = array();
                            $save_cat_item['rel_type'] = $data_to_save['table'];
                            $save_cat_item['rel_id'] = $data_to_save['id'];

                            /*   if (isset($data_to_save['download_remote_images']) and $data_to_save['download_remote_images']!=false){
                                   $is_url = false;
                                   if (filter_var($v['filename'], FILTER_VALIDATE_URL)){
                                       if (!stristr($v['filename'], site_url())){
                                           $image_src = $v['filename'];
                                           $to_download = false;
                                           $image_src = strtok($image_src, '?');
                                           $ext = get_file_extension($image_src);
                                           switch (strtolower($ext)) {
                                               case 'jpg':
                                               case 'jpeg':
                                               case 'png':
                                               case 'gif':
                                               case 'svg':
                                                   $to_download = $image_src;
                                                   break;
                                               default:
                                                   break;
                                           }


                                           if ($to_download!=false){
                                               $output_fn = 'ext_save' . crc32($to_download) . '.' . $ext;
                                               $relative = 'downloaded' . DS . $save_cat_item['rel_type'] . DS . $save_cat_item['rel_id'] . DS;
                                               $output = media_base_path() . $relative;
                                               $output_relative = media_base_url() . str_replace(DS, '/', $relative);
                                               $output = normalize_path($output, true);
                                               if (!is_dir($output)){
                                                   mkdir_recursive($output);
                                               }
                                               $output_file = $output . $output_fn;
                                               if (!is_file($output_file)){
                                                   $download = new \MicroweberPackages\Utils\Http();
                                                   $download->set_url($image_src);
                                                   $download->download($output_file);

                                               }

                                               $v['filename'] = $output_relative . $output_fn;
                                               $v['filename'] = str_replace(site_url(), '{SITE_URL}', $v['filename']);

                                           }
                                       }

                                   }
                               }*/

                            $v['filename'] = str_replace(site_url(), '{SITE_URL}', $v['filename']);
                            $save_cat_item['filename'] = $v['filename'];

                            $check = $this->app->media_manager->get($save_cat_item);
                            if ($check == false) {
                                if (isset($v['position'])) {
                                    $save_cat_item['position'] = $v['position'];
                                }
                                $save = $this->app->media_manager->save($save_cat_item);
                            }
                        }
                    }
                }
            }
        }
    }

    public function extended_save_attributes($params)
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
                    $save_cat_item['attribute_name'] = $k;
                    $save_cat_item['attribute_value'] = $v;
                    $this->app->attributes_manager->save($save_cat_item);
                }
            }
        }
    }


    public function extended_save_custom_fields($params)
    {
        if ($this->extended_save_has_permission()) {
            event_trigger('mw.database.extended_save_custom_fields', $params);
            $data_to_save = $params;
            if (isset($data_to_save['custom_fields_advanced'])) {
                $custom_fields = $data_to_save['custom_fields_advanced'];
                if (is_array($custom_fields) and !empty($custom_fields)) {
                    foreach ($custom_fields as $k => $v) {
                        $save_cat_item = array();
                        $save_cat_item['rel_type'] = $data_to_save['table'];
                        $save_cat_item['rel_id'] = $data_to_save['id'];

                        if (isset($v['name']) and !isset($v['type'])) {
                            if ($v['name'] == 'price') {
                                $v['type'] = 'price';
                            }
                        }

                        if (isset($v['type'])) {
                            $save_cat_item['type'] = $v['type'];
                            if (isset($v['name'])) {
                                $save_cat_item['name'] = $v['name'];
                            }
                            $check = $save_cat_item;
                            $check['single'] = true;

                            $check = $this->app->fields_manager->getAll($check);

                            if (isset($check['id'])) {
                                $save_cat_item['id'] = $check['id'];
                            }
                            if (isset($v['value'])) {
                                $save_cat_item['value'] = $v['value'];
                            }
                            if (isset($v['values'])) {
                                $save_cat_item['values'] = $v['values'];
                            }

                            $save_cat_item = array_merge($save_cat_item, $v);
                            $save_field = $this->app->fields_manager->save($save_cat_item);

                        }
                    }
                }
            }
        }
    }

    public function extended_save_data_fields($params)
    {
        if ($this->extended_save_has_permission()) {

            event_trigger('mw.database.extended_save_data_fields', $params);
            $data_to_save = $params;
            $modified = false;
            if (isset($data_to_save['data_fields'])) {
                $data_fields = $data_to_save['data_fields'];
                if (is_array($data_fields) and !empty($data_fields)) {
                    foreach ($data_fields as $k => $v) {

                        $save_content_data_item = array();
                        $save_content_data_item['rel_type'] = $data_to_save['table'];
                        $save_content_data_item['rel_id'] = $data_to_save['id'];
                        $save_content_data_item['field_name'] = $k;
                        $save_content_data_item['field_value'] = $v;

                        $current_data = \DB::table('content_data')
                            ->where('rel_type', $data_to_save['table'])
                            ->where('rel_id', $data_to_save['id'])
                            ->where('field_name', $k)
                             ->first();
                        if (is_object($current_data) and $current_data->id) {
                            $save_content_data_item['id'] = $current_data->id;
                        }



                        $this->app->data_fields_manager->save($save_content_data_item);
                    }
                }
            }
        }
    }

    public function extended_save_categories($params)
    {


        if ($params and  $this->extended_save_has_permission()) {
            event_trigger('mw.database.extended_save_categories', $params);
            $data_to_save = $params;
            $cats_modified = false;
            if (isset($data_to_save['categories']) and $data_to_save['categories']) {
                if (is_string($data_to_save['categories'])) {
                    $data_to_save['categories'] = explode(',', $data_to_save['categories']);
                }
                $categories = $data_to_save['categories'];
                if (is_array($categories) and empty($categories)) {
                    return;

                }

                if (is_array($categories)) {
                    $save_cat_item = array();
                    $save_cat_item['rel_type'] = $data_to_save['table'];
                    $save_cat_item['rel_id'] = $data_to_save['id'];
                    $save_cat_item['data_type'] = 'category';
                    $save_cat_item['no_cache'] = true;

                    $check = $this->app->category_manager->get_items($save_cat_item);
                    if (is_array($check) and !empty($check)) {
                        foreach ($check as $item) {
                            if (!in_array($item['parent_id'], $categories) and !array_search($item['parent_id'], $categories)) {
                                $this->app->category_manager->delete_item($item['id']);
                            }
                        }
                    }
                    $cats_modified = true;
                    foreach ($categories as $category) {

                        if(!$category){
                            return;
                        }

                        $cat_id = false;

                        if ((is_string($category) or is_int($category)) and intval($category) != 0) {
                            // case where we pass array of category ids
                            $save_cat_item = array();
                            $save_cat_item['rel_type'] = $data_to_save['table'];
                            $save_cat_item['rel_id'] = $data_to_save['id'];
                            $save_cat_item['parent_id'] = $category;
                            $save_cat_item['no_cache'] = true;

                            $check = $this->app->category_manager->get_items($save_cat_item);
                            if ($check == false) {
                                $this->app->category_manager->save_item($save_cat_item);
                            }
                        } elseif(is_string($category)) {
                            $category = str_replace(array("\r\n", "\r", "\n", "\t"), '', $category);

                            $save_cat_item = array();
                            $save_cat_item['single'] = true;
                            $save_cat_item['no_cache'] = true;
                            $save_cat_item['fields'] = 'id';
                            $save_cat_item['rel_type'] = $data_to_save['table'];
                            $save_cat_item['title'] = $category;



                            $check = $this->app->category_manager->get($save_cat_item);
                            if (!$check) {
                                $save_cat_item['title'] =htmlentities($category);
                                $check = $this->app->category_manager->get($save_cat_item);
                            }

                            if (!$check) {
                                if (isset($data_to_save['parent'])) {
                                    $save_cat_item['rel_id'] = $data_to_save['parent'];
                                } else {
                                    $save_cat_item['rel_id'] = $data_to_save['id'];
                                }
                                $cat_id = $this->app->category_manager->save($save_cat_item);
                            } else {
                                $cat_id = $check['id'];
                            }

                        }  elseif (is_array($category)) {
                            if (isset($category['title']) and isset($data_to_save['id'])) {
                                $save_cat_item = array();
                                $save_cat_item['single'] = true;
                                $save_cat_item['no_cache'] = true;
                                $save_cat_item['rel_type'] = $data_to_save['table'];

                                $category['title'] = str_replace(array("\r\n", "\r", "\n", "\t"), '', $category['title']);


                                if (isset($data_to_save['parent'])) {
                                    $save_cat_item['rel_id'] = $data_to_save['parent'];
                                } else {
                                    $save_cat_item['rel_id'] = $data_to_save['id'];
                                }
                                $save_cat_item['title'] = $category['title'];
                                if (isset($category['parent_id'])) {
                                    $save_cat_item['parent_id'] = $category['parent_id'];
                                }
                                $check = $this->app->category_manager->get($save_cat_item);
                                if ($check == false) {
                                    $category['parent_id'] = $cat_id = $this->app->category_manager->save($save_cat_item);
                                } elseif (isset($check['id'])) {
                                    $cat_id = $check['id'];
                                    $category['parent_id'] = $cat_id;
                                }
                            }
                        }

                        if ($cat_id != false) {

                            $save_cat_item = array();
                            $save_cat_item['rel_type'] = $data_to_save['table'];
                            $save_cat_item['rel_id'] = $data_to_save['id'];
                            if (isset($category['parent_id'])) {
                                $save_cat_item['parent_id'] = $category['parent_id'];
                            } elseif($cat_id) {
                                $save_cat_item['parent_id'] = $cat_id;
                            }

                            $check = $this->app->category_manager->get_items($save_cat_item);
                            if ($check == false) {
                                $save_item = $this->app->category_manager->save_item($save_cat_item);
                            }
                        }

                    }
                }
            } else if (isset($data_to_save['categories']) and !$data_to_save['categories']) {

                $save_cat_item = array();
                $save_cat_item['rel_type'] = $data_to_save['table'];
                $save_cat_item['rel_id'] = $data_to_save['id'];
                $save_cat_item['data_type'] = 'category';
                $check = $this->app->category_manager->get_items($save_cat_item);
                if (is_array($check) and !empty($check)) {
                    foreach ($check as $item) {
                        $this->app->category_manager->delete_item($item['id']);

                    }
                }
                $cats_modified = true;




            }
            if ($cats_modified != false) {
                $this->app->cache_manager->delete('categories');
                $this->app->cache_manager->delete('categories_items');
            }
        }
    }


    public function extended_save_tags($params)
    {
        if ($this->extended_save_has_permission()) {
            $supports_tags = false;

            if (isset($params['table'])) {
                $model = $this->table($params['table']);
                $supports_tags = $this->supports($params['table'],'tags');
            }

            if (!$supports_tags) {
                return;
            }

            event_trigger('mw.database.extended_save_tags', $params);
            $data_to_save = $params;
            $tags_modified = false;
            if (isset($data_to_save['tag_names'])
                and isset($params['id']) and $params['id']
            ) {
                if (is_string($data_to_save['tag_names'])) {
                    $data_to_save['tag_names'] = explode(',', $data_to_save['tag_names']);
                }
                $article = $model->whereId(intval($params['id']))->first();
                if ($article) {
                    $tags_modified = true;
                    $tags = $data_to_save['tag_names'];
                    if (is_array($tags)) {
                        $tags = array_unique($tags);
                        $tags = array_filter($tags);

                        if (!empty($tags)) {
                            if (isset($params['id']) and $params['id']) {
                                if(method_exists($article,'retag')){
                                    $article->retag($tags);
                                    $article->save();

                                }
                            }
                        } else {
                            if(method_exists($article,'untag')) {
                                $article->untag(); // remove all tags
                                $article->save();
                            }
                        }
                    } else {
                        $tags = trim($tags);
                        if (!$tags) {
                            if(method_exists($article,'untag')){
                                $article->untag(); // remove all tags
                                $article->save();                            }
                        }
                    }
                }
            }
            if ($tags_modified != false) {
                $this->app->cache_manager->delete('tags');
                $this->app->cache_manager->delete('tags_items');

                $this->app->cache_manager->delete('categories');
                $this->app->cache_manager->delete('categories_items');

            }
        }
    }

    public function extended_save_has_permission()
    {
        if ($this->_extended_save_has_permission) {
            return $this->_extended_save_has_permission;
        }

        return $this->app->user_manager->is_admin();
    }

    public function extended_save_set_permission($boolean)
    {
        $this->_extended_save_has_permission = $boolean;
    }
}
