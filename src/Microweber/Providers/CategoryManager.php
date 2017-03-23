<?php

namespace Microweber\Providers;

use DB;
use Microweber\Providers\Categories\Helpers\LegacyCategoryTreeRenderer;

/**
 * Class to work with categories.
 */
class CategoryManager
{
    public $app;
    public $tables = array();
    public $table_prefix = false;


    public function __construct($app = null)
    {
        if (!is_object($this->app)) {
            if (is_object($app)) {
                $this->app = $app;
            } else {
                $this->app = mw();
            }
        }

        $prefix = $this->app->config->get('database.connections.mysql.prefix');
        $this->tables = $this->app->content_manager->tables;
        if (!isset($this->tables['categories'])) {
            $this->tables['categories'] = 'categories';
        }
        if (!isset($this->tables['categories_items'])) {
            $this->tables['categories_items'] = 'categories_items';
        }
        if (!defined('MW_DB_TABLE_TAXONOMY')) {
            define('MW_DB_TABLE_TAXONOMY', $this->tables['categories']);
        }

        if (!defined('MW_DB_TABLE_TAXONOMY_ITEMS')) {
            define('MW_DB_TABLE_TAXONOMY_ITEMS', $this->tables['categories_items']);
        }


    }

    /**
     * category_tree.
     *
     * @desc        prints category_tree of UL and LI
     *
     * @category    categories
     *
     * @author      Microweber
     *
     * @param  $params = array();
     * @param  $params ['parent'] = false; //parent id
     * @param  $params ['link'] = false; // the link on for the <a href
     * @param  $params ['active_ids'] = array(); //ids of active categories
     * @param  $params ['active_code'] = false; //inserts this code for the active ids's
     * @param  $params ['remove_ids'] = array(); //remove those caregory ids
     * @param  $params ['ul_class'] = false; //class name for the ul
     * @param  $params ['li_class'] = false; //class name for the li
     * @param  $params ['include_first'] = false; //if true it will include the main parent category
     * @param  $params ['content_type'] = false; //if this is set it will include only categories from desired type
     * @param  $params ['add_ids'] = array(); //if you send array of ids it will add them to the category
     * @param  $params ['orderby'] = array(); //you can order by such array $params['orderby'] = array('created_at','asc');
     * @param  $params ['content_type'] = false; //if this is set it will include only categories from desired type
     * @param  $params ['list_tag'] = 'select';
     * @param  $params ['list_item_tag'] = "option";
     * @param  $params ['class'];
     * @param  $params ['nest_level'];
     * @param  $params ['subtype_value'];
     * @param  $params ['remove_ids'];
     * @param  $params ['removed_ids_code'];
     * @param  $params ['content_id'];
     *
     *
     *
     * @param  $params ['for-content-id'];
     */
    public function tree($params = false)
    {
        $renderer = new LegacyCategoryTreeRenderer($this->app);
        return $renderer->render($params);
    }

    /**
     * `.
     *
     * Prints the selected categories as an <UL> tree, you might pass several
     * options for more flexibility
     *
     * @param
     *            array
     * @param
     *            boolean
     *
     * @version 1.0
     *
     * @since   Version 1.0
     */

    public function link($id)
    {
        if (intval($id) == 0) {
            return false;
        }

        $function_cache_id = __FUNCTION__;

        $id = intval($id);
        $cache_group = 'categories';

        $cache_content = $this->app->cache_manager->get($function_cache_id, $cache_group);

        if (($cache_content) != false and isset($cache_content[$id])) {
            return $cache_content[$id];
        } else {
            if ($cache_content == false) {
                $cache_content = array();
            }

            $table = $this->tables['categories'];
            $c_infp = $this->get_by_id($id);

            if (!isset($c_infp['rel_type'])) {
                return;
            }

            if (trim($c_infp['rel_type']) != 'content') {
                return;
            }

            $content = $this->get_page($id);

            if (!empty($content)) {
                $url = $this->app->content_manager->link($content['id']);
            }

            if (isset($url) == false and defined('PAGE_ID')) {
                $url = $this->app->content_manager->link(PAGE_ID);
            }

            if (isset($url) != false) {
                if (isset($c_infp['url']) and trim($c_infp['url']) != '') {
                    $url = $url . '/category:' . trim($c_infp['url']);
                } else {
                    $url = $url . '/category:' . $id;
                }
                $cache_content[$id] = $url;
                $this->app->cache_manager->save($cache_content, $function_cache_id, $cache_group);

                return $url;
            }

            return;
        }
    }

    public function get_page($category_id)
    {
        $category_id = intval($category_id);
        if ($category_id == 0) {
            return false;
        } else {
        }
        $category = $this->get_by_id($category_id);
        if ($category != false) {
            if (isset($category['rel_id']) and intval($category['rel_id']) > 0) {
                if ($category['rel_type'] == 'content') {
                    $res = $this->app->content_manager->get_by_id($category['rel_id']);
                    if (is_array($res)) {
                        return $res;
                    }
                }
            }

            if ((!isset($category['rel_id']) or (isset($category['rel_id']) and intval($category['rel_id']) == 0)) and intval($category['parent_id']) > 0) {
                $category1 = $this->get_parents($category['id']);
                if (is_array($category1)) {
                    foreach ($category1 as $value) {
                        if (intval($value) != 0) {
                            $category2 = $this->get_by_id($value);
                            if (isset($category2['rel_id']) and intval($category2['rel_id']) > 0) {
                                if ($category2['rel_type'] == 'content') {
                                    $res = $this->app->content_manager->get_by_id($category2['rel_id']);
                                    if (is_array($res)) {
                                        return $res;
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    public function get_parents($id = 0, $without_main_parrent = false, $data_type = 'category')
    {
        if (intval($id) == 0) {
            return false;
        }

        $table = $this->tables['categories'];

        $ids = array();

        $data = array();

        if (isset($without_main_parrent) and $without_main_parrent == true) {
            $with_main_parrent_q = ' and parent_id<>0 ';
        } else {
            $with_main_parrent_q = false;
        }
        $id = intval($id);
        $q = " select id, parent_id  from $table where id = $id and  data_type='{$data_type}' " . $with_main_parrent_q;

        $params = array();
        $params['table'] = $table;
        $params['id'] = $id;
        $params['data_type'] = $data_type;
        if (isset($without_main_parrent) and $without_main_parrent == true) {
            $params['parent_id'] = '[neq]0';
        }

        $taxonomies = $this->app->database_manager->get($params);

        //  $taxonomies = $this->app->database_manager->query($q, $cache_id = __FUNCTION__ . crc32($q), $cache_group = 'categories/' . $id);

        if (!empty($taxonomies)) {
            foreach ($taxonomies as $item) {
                if (intval($item['id']) != 0) {
                    $ids[] = $item['parent_id'];
                }
                if ($item['parent_id'] != $item['id']) {
                    $next = $this->get_parents($item['parent_id'], $without_main_parrent);

                    if (!empty($next)) {
                        foreach ($next as $n) {
                            if ($n != '') {
                                $ids[] = $n;
                            }
                        }
                    }
                }
            }
        }

        if (!empty($ids)) {
            $ids = array_unique($ids);

            return $ids;
        } else {
            return false;
        }
    }

    public function get_children($parent_id = 0, $type = false, $visible_on_frontend = false)
    {
        $categories_id = $parent_id = intval($parent_id);
        $cache_group = 'categories/' . $categories_id;

        $table = $this->tables['categories'];

        $db_t_content = $this->tables['content'];

        if (isset($orderby) == false) {
            $orderby = array();
            //$orderby[0] = 'updated_at';

            //$orderby[1] = 'DESC';

            $orderby[0] = 'position';

            $orderby[1] = 'asc';
        }
        if ($parent_id == 0) {
            return false;
        }

        $data = array();

        $data['parent_id'] = $parent_id;

        if ($type != false) {
            $data['data_type'] = $type;
        } else {
            $type = 'category_item';
            $data['data_type'] = $type;
        }

        $cache_group = 'categories/' . $parent_id;
        $q = " SELECT id,  parent_id FROM $table WHERE parent_id=$parent_id   ";

        $params = array();
        $params['table'] = $table;
        $params['no_limit'] = true;
        $params['parent_id'] = $parent_id;

        $save = $this->app->database_manager->get($params);

        $q_cache_id = __FUNCTION__ . crc32($q);
        // $save = $this->app->database_manager->query($q, $q_cache_id, $cache_group);
        if (empty($save)) {
            return false;
        }
        $to_return = array();
        if (is_array($save) and !empty($save)) {
            foreach ($save as $item) {
                $to_return[] = $item['id'];
            }
        }

        $to_return = array_unique($to_return);

        return $to_return;
    }

    public function get_for_content($content_id, $data_type = 'categories')
    {
        if (intval($content_id) == 0) {
            return false;
        }

        if ($data_type == 'categories') {
            $data_type = 'category';
        }
        if ($data_type == 'tags') {
            $data_type = 'tag';
        }
        $get_category_items = $this->get_items('group_by=parent_id&rel_type=content&rel_id=' . ($content_id));
        $include_parents = array();
        $include_parents_str = '';

        if (!empty($get_category_items)) {
            foreach ($get_category_items as $get_category_item) {
                if (isset($get_category_item['parent_id'])) {
                    $include_parents[] = $get_category_item['parent_id'];
                }
            }
        }
        $get_category = $this->get('order_by=position desc&data_type=' . $data_type . '&rel_type=content&rel_id=' . ($content_id));
        if (empty($get_category)) {
            $get_category = array();
        }

        if (!empty($include_parents)) {
            $include_parents_str = 'order_by=position desc&data_type=' . $data_type . '&rel_type=content&ids=' . implode(',', $include_parents);
            $get_category2 = $this->get($include_parents_str);

            if (!empty($get_category2)) {
                foreach ($get_category2 as $item) {
                    $get_category[] = $item;
                }
            }
        }

//        if (is_array($get_category) and !empty($get_category)) {
//            array_unique($get_category);
//        }

        if (empty($get_category)) {
            return false;
        }

        return $get_category;
    }

    /**
     * Gets category items count.
     *
     * @param mixed $params Array or string with parameters
     * @param string $data_type
     *
     * @return array|bool
     */
    public function get_items_count($id, $rel_type = false)
    {
        if ($id == false) {
            return false;
        }

        $table_items = $this->tables['categories_items'];

        $params = array();
        $params['table'] = $table_items;
        $params['parent_id'] = $id;
        if ($rel_type != false) {
            $params['rel_type'] = $rel_type;
        }

        $params['count'] = true;
        $data = $this->app->database_manager->get($params);

        return $data;
    }

    /**
     * Gets category items.
     *
     * @param mixed $params Array or string with parameters
     * @param string $data_type
     *
     * @return array|bool
     */
    public function get_items($params, $data_type = 'categories')
    {
        if (is_string($params)) {
            $params = parse_str($params, $params2);
            $params = $options = $params2;
        }
        $table_items = $this->tables['categories_items'];
        $data = $params;

        $data['table'] = $table_items;
        if (!isset($params['limit'])) {
            //$data['no_limit'] =true;
        }

        $data = $this->app->database_manager->get($data);

        return $data;
    }

    public function get($params)
    {
        $params2 = array();
        $rel_id = 0;
        if (is_string($params)) {
            $params = parse_str($params, $params2);
            $params = $params2;
            extract($params);
        }

        $table = $this->tables['categories'];
        $table_items = $this->tables['categories_items'];

        $data = $params;

        $data['table'] = $table;
        if (isset($params['id'])) {
            $data['cache_group'] = $cache_group = 'categories/' . $params['id'];
        } else {
            $data['cache_group'] = $cache_group = 'categories/global';
        }

        if (isset($data['parent']) and !isset($data['parent_id'])) {
            $data['parent_id'] = $data['parent'];
        }

        if (!isset($data['rel_type'])) {
            $data['rel_type'] = 'content';
        }

        if (isset($params['rel_id'])) {
            $data['rel_id'] = $params['rel_id'];

        }

        if (isset($data['parent_page'])) {
            $data['rel_type'] = 'content';
            $data['rel_id'] = $data['parent_page'];
        }

        if (isset($data['parent_id'])) {
            if (isset($data['rel_type'])) {
                unset($data['rel_type']);
            }
            if (isset($data['rel_id'])) {
                unset($data['rel_id']);
            }
        }
//dd($data);
        $data = $this->app->database_manager->get($data);


        return $data;
    }

    public function save($data, $preserve_cache = false)
    {
        $sid = $this->app->user_manager->session_id();

        if (is_string($data)) {
            $data = parse_params($data);
        }
        $orig_data = $data;

        if ((isset($data['id']) and ($data['id']) == 0) or !isset($data['id'])) {
            if (!isset($data['title']) or (isset($data['title']) and $data['title'] == false)) {
                return array('error' => 'Title is required');
            }
        } elseif ((isset($data['id']) and ($data['id']) != 0)) {
            if ((isset($data['title']) and $data['title'] == false)) {
                return array('error' => 'Title cannot be blank');
            }
        }

        if ((isset($data['id']) and ($data['id']) != 0) and isset($data['parent_id'])) {
            if ((intval($data['id']) == intval($data['parent_id']))) {
                return array('error' => 'Invalid parent category');
            }
        }

        $table = $this->tables['categories'];
        $table_items = $this->tables['categories_items'];

        $content_ids = false;
        $simple_save = false;
        if (isset($data['content_id']) and !isset($data['rel_type'])) {
            $data['rel_type'] = 'content';
            $data['rel_id'] = $data['content_id'];
        }

        if (isset($data['rel']) and !isset($data['rel_type'])) {
            $data['rel_type'] = $data['rel'];
        }
        if (!isset($data['data_type']) or !($data['data_type'])) {
            $data['data_type'] = 'category';
        }
        if (isset($data['users_can_create_content']) and ($data['users_can_create_content']) == 'y') {
            $data['users_can_create_content'] = 1;
        } elseif (isset($data['users_can_create_content']) and ($data['users_can_create_content']) == 'n') {
            $data['users_can_create_content'] = 0;
        }

        if (isset($data['rel_type']) and ($data['rel_type'] == '') or !isset($data['rel_type'])) {
            $data['rel_type'] = 'content';
        }
        if (isset($data['simple_save'])) {
            $simple_save = $data['simple_save'];
        }
        if (isset($data['content_id'])) {
            if (is_array($data['content_id']) and !empty($data['content_id']) and trim($data['data_type']) != '') {
                $content_ids = $data['content_id'];
            }
        }

        if (isset($data['position'])) {
            $data['position'] = intval($data['position']);
        }

        if (isset($data['category_subtype_settings'])) {
            $data['category_subtype_settings'] = @json_encode($data['category_subtype_settings']);
        }

        $no_position_fix = false;
        if (isset($data['rel_type']) and isset($data['rel_id']) and trim($data['rel_type']) != '' and trim($data['rel_id']) != '') {
            $no_position_fix = true;
        }

        if (isset($data['parent_page'])) {
            $data['rel_type'] = 'content';
            $data['rel_id'] = $data['parent_page'];
        }

        if (isset($data['table']) and ($data['table'] != '')) {
            $table = $data['table'];
        }
        if (isset($data['id']) and intval($data['id']) != 0 and isset($data['parent_id']) and intval($data['parent_id']) != 0) {
            if ($data['id'] == $data['parent_id']) {
                unset($data['parent_id']);
            }
        } elseif ((!isset($data['id']) or intval($data['id']) == 0) and !isset($data['parent_id'])) {
            $data['parent_id'] = 0;
        }

        if (isset($data['rel_type']) and isset($data['rel_id']) and trim($data['rel_type']) == 'content' and intval($data['rel_id']) != 0) {
            $cont_check = $this->app->content_manager->get_by_id($data['rel_id']);

            if ($cont_check != false and isset($cont_check['subtype']) and $cont_check['subtype'] == 'static') {
                $cs = array();
                $cs['id'] = intval($data['rel_id']);
                $cs['subtype'] = 'dynamic';
                $table_c = $this->tables['content'];

                $save = $this->app->database_manager->save($table_c, $cs);
            }
        }
        if ((!isset($data['id']) or $data['id'] == 0) and !isset($data['is_deleted'])) {
            $data['is_deleted'] = 0;
        }

        if ((!isset($data['id']) or $data['id'] == 0)
            and (!isset($data['url']) or trim($data['url']) == false)
            and isset($data['title'])
        ) {
            $data['url'] = $data['title'];
        }

        $old_parent = false;
        if (isset($data['id'])) {
            $old_category = $this->get_by_id($data['id']);
            if (isset($old_category['parent_id'])) {
                $old_parent = $old_category['parent_id'];
            }
        }

        if (isset($data['url']) and trim($data['url']) != false) {
            $possible_slug = $this->app->url_manager->slug($data['url']);
            if ($possible_slug) {
                $possible_slug_check = $this->get_by_slug($possible_slug);
                if (isset($possible_slug_check['id'])) {
                    if (isset($data['id']) and $data['id'] == $possible_slug_check['id']) {
                        //slug is the same
                    } else {
                        $possible_slug = $possible_slug . '-' . date('YmdHis');
                    }
                }
            }
            if ($possible_slug) {
                $data['url'] = $possible_slug;
            } else {
                $data['url'] = false;
            }
        } elseif (isset($data['url']) and trim($data['url']) != false) {
            $data['url'] = false;
        }

        /* if (!empty($orig_data)) {
             $data_str = 'data_';
             $data_str_l = strlen($data_str);
             foreach ($orig_data as $k => $v) {
                 if (is_string($k)) {
                     if (strlen($k) > $data_str_l) {
                         $rest = substr($k, 0, $data_str_l);
                         $left = substr($k, $data_str_l, strlen($k));
                         if ($rest == $data_str) {
                             if (!isset($data['data_fields'])) {
                                 $data['data_fields'] = array();
                             }
                             $data['data_fields'][ $left ] = $v;
                         }
                     }
                 }
             }
         }*/
        $data['allow_html'] = true;

        // \Log::info(print_r($data, true));
        $id = $save = $this->app->database_manager->extended_save($table, $data);


        if ($simple_save == true) {
            return $save;
        }

        if (intval($save) == 0) {
            return false;
        }

        DB::transaction(function () use ($sid, $id) {
            DB::table($this->tables['custom_fields'])
                ->whereSessionId($sid)
                ->where(function ($query) {
                    $query->whereRelId(0)->orWhere('rel_id', null);
                })
                ->whereRelType('categories')
                ->update(['rel_type' => 'categories', 'rel_id' => $id]);

            DB::table($this->tables['media'])
                ->whereSessionId($sid)
                ->where(function ($query) {
                    $query->whereRelId(0)->orWhere('rel_id', null);
                })
                ->whereRelType('categories')
                ->update(['rel_id' => $id]);
        });

        //$this->app->cache_manager->clear('media');

        // $this->app->database_manager->q($clean);

        if (isset($content_ids) and !empty($content_ids)) {
            $content_ids = array_unique($content_ids);
            $data_type = trim($data['data_type']) . '_item';

            $content_ids_all = implode(',', $content_ids);

            $q = "DELETE FROM $table WHERE rel_type='content'
		AND content_type='post'
		AND parent_id=$save
		AND  data_type ='{$data_type}' ";

            $this->app->database_manager->q($q);

            foreach ($content_ids as $id) {
                $item_save = array();

                $item_save['rel_type'] = 'content';

                $item_save['rel_id'] = $id;

                $item_save['data_type'] = $data_type;

                $item_save['content_type'] = 'post';

                $item_save['parent_id'] = intval($save);

                $item_save = $this->app->database_manager->save($table_items, $item_save);

            }
        }
        if ($old_parent != false) {
            // $this->app->cache_manager->clear('categories' . DIRECTORY_SEPARATOR . $old_parent);
        }

        // $this->app->cache_manager->clear('categories');

        return $save;
    }

    public function save_item($params)
    {
        $params = parse_params($params);
        $table = $this->tables['categories_items'];
        $params['table'] = $table;
        $save = $this->app->database_manager->save($params);
        if (intval($save) == 0) {
            return false;
        }
    }

    /**
     * @desc        Get a single row from the categories_table by given ID and returns it as one dimensional array
     *
     * @param int
     *
     * @return array
     *
     * @author      Peter Ivanov
     *
     * @version     1.0
     *
     * @since       Version 1.0
     */
    public function get_by_id($id = 0, $by_field_name = 'id')
    {
        if (!$id) {
            return;
        }
        if ($by_field_name == 'id' and intval($id) == 0) {
            return false;
        }
        if (is_numeric($id)) {
            $id = intval($id);
            $cache_group_suffix = ceil($id / 50) * 50;
        } else {
            $id = trim($id);
            $cache_group_suffix = substr($id, 0, 1);
        }

        $function_cache_id = __FUNCTION__ . '-' . $by_field_name . '-' . $cache_group_suffix;

        $cache_group = 'categories';

        $cache_content = $this->app->cache_manager->get($function_cache_id, $cache_group);

        if (($cache_content) != false and isset($cache_content[$id])) {
            return $cache_content[$id];
        } else {
            if ($cache_content == false) {
                $cache_content = array();
            }

            $table = $this->tables['categories'];

            $get = array();

            $get[$by_field_name] = $id;
            $get['no_cache'] = true;
            $get['single'] = true;
            $q = $this->app->database_manager->get($table, $get);

            if (isset($q['category_subtype_settings'])) {
                $q['category_subtype_settings'] = @json_decode($q['category_subtype_settings'], true);
            }


            $cache_content[$id] = $q;
            $this->app->cache_manager->save($cache_content, $function_cache_id, $cache_group);

            return $q;
        }
    }

    public function get_by_slug($slug)
    {
        return $this->get_by_id($slug, 'url');
    }

    public function delete($data)
    {
        if (is_array($data) and isset($data['id'])) {
            $c_id = intval($data['id']);
        } else {
            $c_id = intval($data);
        }

        $del = $this->app->database_manager->delete_by_id('categories', $c_id);
        $this->app->database_manager->delete_by_id('categories', $c_id, 'parent_id');
        $this->app->database_manager->delete_by_id('categories_items', $c_id, 'parent_id');
        if (defined('MODULE_DB_MENUS')) {
            $this->app->database_manager->delete_by_id('menus', $c_id, 'categories_id');
        }

        return $del;
    }

    public function delete_item($data)
    {
        if (is_array($data) and isset($data['id'])) {
            $c_id = intval($data['id']);
        } else {
            $c_id = intval($data);
        }

        return $this->app->database_manager->delete_by_id('categories_items', $c_id);
    }

    public function reorder($data)
    {
        $table = $this->tables['categories'];
        $res = array();
        foreach ($data as $value) {
            if (is_array($value)) {
                $indx = array();
                $i = 0;
                foreach ($value as $value2) {
                    $indx[$i] = $value2;
                    ++$i;
                }

                $res[] = $this->app->database_manager->update_position_field($table, $indx);
            }
        }

        return $res;
    }

    public function get_category_id_from_url($url = false)
    {
        if ($url) {
            $cat_url = $this->app->url_manager->param('category', true, $url);
        } else {
            $cat_url = $this->app->url_manager->param('category', true);
        }
        if ($cat_url != false and !is_numeric($cat_url)) {
            $cat_url_by_slug = $this->get_by_slug($cat_url);
            if (isset($cat_url_by_slug['id'])) {
                $cat_url = $cat_url_by_slug['id'];
            }
        }

        return intval($cat_url);
    }
}
