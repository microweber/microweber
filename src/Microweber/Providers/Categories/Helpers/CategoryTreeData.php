<?php

namespace Microweber\Providers\Categories\Helpers;

class CategoryTreeData
{


    /** @var \Microweber\Application */
    public $app;


    public function __construct($app = null)
    {
        if (is_object($app)) {
            $this->app = $app;
        } else {
            $this->app = mw();
        }
    }


    public function get($params)
    {
        $p2 = array();
        if (!is_array($params)) {
            if (is_string($params)) {
                parse_str($params, $p2);
                $params = $p2;
            }
        }
        if (isset($params['parent'])) {
            $parent = ($params['parent']);
        } elseif (isset($params['subtype_value'])) {
            $parent = ($params['subtype_value']);
        } else {
            $parent = 0;
        }

        asort($params);
        $function_cache_id = false;
        $function_cache_id = __FUNCTION__ . crc32(serialize($params));

        $active_cat = false;
        if (defined('CATEGORY_ID')) {
            $function_cache_id .= CATEGORY_ID;
            $active_cat = CATEGORY_ID;
        }

        $cat_url = $this->app->category_manager->get_category_id_from_url();
        if ($cat_url != false) {
            $function_cache_id .= $cat_url;
            $active_cat = $cat_url;
        } else {
            $cat_url = $this->app->url_manager->param('categories', true);
            if ($cat_url != false) {
                $function_cache_id .= $cat_url;
            }
        }

        $cache_group = 'categories/global';
        if (isset($params['nest_level'])) {
            $depth_level_counter = $params['nest_level'];
        } else {
            $depth_level_counter = 0;
        }
        $nest_level_orig = $depth_level_counter;


        if (isset($params['remove_ids'])) {
            $remove_ids = $params['remove_ids'];
        } else {
            $remove_ids = false;
        }


        if (isset($params['users_can_create_content'])) {
            $users_can_create_content = $params['users_can_create_content'];
        } else {
            $users_can_create_content = false;
        }

        if (isset($params['include_first'])) {
            $include_first = $params['include_first'];
        } else {
            $include_first = false;
        }

        if (isset($params['content_type'])) {
            $content_type = $params['content_type'];
        } else {
            $content_type = false;
        }

        if (isset($params['add_ids'])) {
            $add_ids = $params['add_ids'];
        } else {
            $add_ids = false;
        }

        if (isset($params['orderby'])) {
            $orderby = $params['orderby'];
        } else {
            $orderby = false;
        }

        $table = $this->app->category_manager->tables['categories'];
        if (isset($params['content_id'])) {
            $params['for_page'] = $params['content_id'];
        }
        if (isset($params['content_id'])) {
            $params['for_page'] = $params['content_id'];
        }

        if (isset($params['for_page']) and $params['for_page'] != false) {
            $page = $this->app->content_manager->get_by_id($params['for_page']);

            if ($page['subtype'] == 'dynamic' and intval($page['subtype_value']) > 0) {
                $parent = $page['subtype_value'];
            } else {
                $params['rel_type'] = 'content';
                $params['rel_id'] = $params['for_page'];
                $parent = 0;
            }
        }
        $active_code_tag = false;
        if (isset($params['active_code_tag']) and $params['active_code_tag'] != false) {
            $active_code_tag = $params['active_code_tag'];
        }

        if (isset($params['subtype_value']) and $params['subtype_value'] != false) {
            $parent = $params['subtype_value'];
        }

        $skip123 = false;
        $fors = array();
        if (isset($params['parent']) and $params['parent'] != false) {
            $parent = intval($params['parent']);

        } else {
            if (!isset($params['for'])) {
                $params['for'] = 'content';
            }

            if (!isset($params['content_id']) and isset($params['for']) and $params['for'] != false) {
                $table_assoc_name = $this->app->database_manager->assoc_table_name($params['for']);
                $skip123 = true;
                $str0 = 'no_cache=true&is_deleted=0&orderby=position asc&table=' . $table . '&limit=1000&data_type=category&what=categories&' . 'parent_id=0&rel_type=' . $table_assoc_name;
                $cat_get_params = array();
                $cat_get_params['is_deleted'] = 0;
                $cat_get_params['order_by'] = 'position asc';
                $cat_get_params['limit'] = '1000';
                $cat_get_params['data_type'] = 'category';
                $cat_get_params['no_cache'] = 1;
                $cat_get_params['parent_id'] = '0';
                $cat_get_params['table'] = $table;
                $cat_get_params['rel_type'] = $table_assoc_name;
                if ($users_can_create_content != false) {
                    $cat_get_params['users_can_create_content'] = $users_can_create_content;
                }


                $fors = $this->app->database_manager->get($cat_get_params);
            }

            if (!isset($params['content_id']) and isset($params['try_rel_id']) and intval($params['try_rel_id']) != 0) {
                $skip123 = true;
                $str1 = 'no_cache=true&is_deleted=0&orderby=position asc&table=' . $table . '&limit=1000&parent_id=0&rel_id=' . $params['try_rel_id'];
                $fors1 = $this->app->database_manager->get($str1);
                if (is_array($fors1)) {
                    $fors = array_merge($fors, $fors1);
                }
            }
        }

        if (isset($params['not_for_page']) and $params['not_for_page'] != false) {
            $page = $this->app->content_manager->get_page($params['not_for_page']);
            $remove_ids = array($page['subtype_value']);
        }

        $max_level = false;
        if (isset($params['max_level'])) {
            $max_level = $params['max_level'];
        }

        $only_with_content = false;
        if (isset($params['only_with_content'])) {
            $only_with_content = $params['only_with_content'];
        }


        $visible_on_frontend = false;
        if (isset($params['visible_on_frontend'])) {
            $visible_on_frontend = $params['visible_on_frontend'];
        }

        $params['table'] = $table;
        if (is_string($add_ids)) {
            $add_ids = explode(',', $add_ids);
        }

        $tree_only_ids = false;

        if (isset($params['for-content-id'])) {
            $content_cats = $this->app->category_manager->get_for_content($params['for-content-id']);
            $fors = array();
            if (is_array($content_cats) and !empty($content_cats)) {
                if (!is_array($add_ids)) {
                    $add_ids = array();
                }
                foreach ($content_cats as $content_cat_item) {
                    if (isset($content_cat_item['id'])) {
                        $add_ids[] = $content_cat_item['id'];
                        $tree_only_ids[] = $content_cat_item['id'];
                    }
                }
            }
        } elseif (isset($params['rel_type']) and $params['rel_type'] != false and isset($params['rel_id'])) {
            $table_assoc_name = $this->app->database_manager->assoc_table_name($params['rel_type']);
            $skip123 = true;
            $users_can_create_content_q = false;
            $cat_get_params = array();
            $cat_get_params['is_deleted'] = 0;
            $cat_get_params['order_by'] = 'position asc';
            $cat_get_params['limit'] = '1000';
            $cat_get_params['data_type'] = 'category';
            $cat_get_params['rel_id'] = ($params['rel_id']);
            $cat_get_params['table'] = $table;
            $cat_get_params['rel_type'] = $table_assoc_name;
            if (isset($parent) and $parent != false) {
                $page_for_parent = $this->app->category_manager->get_page($parent);
                $cats_for_content = $this->app->category_manager->get_for_content($params['rel_id']);
                if ($cats_for_content) {
                    foreach ($cats_for_content as $cat_for_content) {
                        if ($parent == $cat_for_content['id']) {
                            $cat_get_params['parent_id'] = $parent;
                            unset($cat_get_params['rel_type']);
                            unset($cat_get_params['rel_id']);

                        }
                    }
                }

            }
            if ($users_can_create_content != false) {
                $cat_get_params['users_can_create_content'] = $users_can_create_content;
            }
            $fors = $this->app->database_manager->get($cat_get_params);

        }

        $tree_data = array();
        if ($fors) {
            foreach ($fors as $cat) {

                if (isset($cat['id'])) {
                    $tree = $this->_build_children_array($cat['id'],
                        $remove_ids,
                        $add_ids,
                        $include_first,
                        $content_type,
                        $orderby,
                        $only_with_content,
                        $visible_on_frontend,
                        $depth_level_counter = 0,
                        $max_level
                    );

//                    if (isset($tree[0]) and $tree[0]['id'] == $cat['id']) {
//                        unset($tree[0]);
//                    }
                    if ($tree) {
                        $cat['children'] = $tree;
                    }
                    $tree_data[] = $cat;
                }
            }
        }
        return $tree_data;
    }


    private function _build_children_array($parent,
                                           $remove_ids = false,
                                           $add_ids = false,
                                           $include_first = false,
                                           $content_type = false,
                                           $orderby = false,
                                           $only_with_content = false,
                                           $visible_on_frontend = false,
                                           $depth_level_counter = 0,
                                           $max_level = false,
                                           $only_ids = false)
    {


        if ($max_level != false and $depth_level_counter != false) {
            if (intval($depth_level_counter) >= intval($max_level)) {
                // return if max depth
                return;
            }
        }


        $db_t_content = $this->app->category_manager->tables['content'];

        $table = $db_categories = $this->app->category_manager->tables['categories'];

        $ids_add_q = array();
        $ids_remove_q = array($parent);
        if ($parent == false) {
            $parent = (0);

            $include_first = false;
        } else {
            $parent = (int)$parent;
        }

        if (!is_array($orderby)) {
            $orderby[0] = 'position';

            $orderby[1] = 'ASC';
        }

        if (isset($remove_ids) and !is_array($remove_ids)) {
            $temp = intval($remove_ids);
            $ids_remove_q[] = $temp;
        } elseif (is_array($remove_ids) and !empty($remove_ids)) {
            $ids_remove_q = array_merge($ids_remove_q, $remove_ids);
         }

        if (is_array($add_ids) and !empty($add_ids)) {
            $ids_add_q = array_merge($ids_add_q, $add_ids);
        }


        $cat_get_params = array();
        $cat_get_params['is_deleted'] = 0;
        $cat_get_params['order_by'] = "{$orderby [0]}  {$orderby [1]}";
        $cat_get_params['limit'] = '1000';
        $cat_get_params['table'] = $table;

        $cat_get_params['parent_id'] = $parent;
        $cat_get_params['loop_fix_q'] = function ($query) use ($table) {
            $query = $query->where($table . '.id', '!=', $table . '.parent_id');
            return $query;
        };

        if ($ids_add_q) {
            $cat_get_params['add_ids_q'] = function ($query) use ($ids_add_q) {

                $query = $query->whereIn('id', $ids_add_q);

                return $query;
            };

        }
 
        if ($ids_remove_q) {
            $cat_get_params['remove_ids_q'] = function ($query) use ($ids_remove_q) {
                $query = $query->whereNotIn('id', $ids_remove_q);
                return $query;
            };
        }


        $result = $this->app->database_manager->get($cat_get_params);

        $output = '';


        $only_with_content2 = $only_with_content;
        if (isset($remove_ids) and !is_array($remove_ids)) {
            $remove_ids = array(intval($remove_ids));
        }

        if (isset($result) and is_array($result) and !empty($result)) {
            $return = array();
            ++$depth_level_counter;
            $i = 0;
            foreach ($result as $item) {
                $remove_ids[] = $item['id'];

                $item['children'] = $this->_build_children_array($item['id'], $remove_ids, $add_ids, $include_first = false, $content_type, $orderby, $only_with_content, $visible_on_frontend, $depth_level_counter, $max_level, $only_ids);
                $return[] = $item;
            }
            return $return;

        } else {
            return false;
        }
    }


}


