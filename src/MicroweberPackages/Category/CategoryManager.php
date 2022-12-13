<?php

namespace MicroweberPackages\Category;

use DB;
use MicroweberPackages\Category\HelperRenders\KnpCategoryTreeRenderer;
use MicroweberPackages\Category\Models\Category;

/**
 * Class to work with categories.
 */
class CategoryManager
{
    public $app;
    public $tables = array();
    public $table_prefix = false;
    public $useCache = true;


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
        $params = parse_params($params);


        $renderer = new KnpCategoryTreeRenderer($this->app);
        //  $renderer->setUseCache(false);

        if(isset($params['use_cache']) and intval($params['use_cache']) == 0){
            $renderer->setUseCache(0);

        } else {
            $renderer->setUseCache(1);

        }
        //  $renderer->setUseCache(0);


//        if (isset($params['tree_data']) && is_array($params['tree_data'])) {
//            return $renderer->render($params, $params['tree_data']);
//        }
        // $renderer = new LegacyCategoryTreeRenderer($this->app);
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
        return mw()->permalink_manager->link($id, 'category');
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

    public $_get_parents = [];
    public function get_parents($id = 0, $without_main_parrent = false, $data_type = 'category')
    {

        if (intval($id) == 0) {
            return false;
        }

        if (isset($this->_get_parents[$id][$without_main_parrent][$data_type])) {
            return $this->_get_parents[$id][$without_main_parrent][$data_type];
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
        } else {
            $ids = false;
        }

        $this->_get_parents[$id][$without_main_parrent][$data_type] = $ids;

        return $ids;
    }

    public function get_children($parent_id = 0, $type = false, $visible_on_frontend = false)
    {

//        if($type == false and $visible_on_frontend==false){
        // bug in get_admin_js_tree_json
//         return app()->category_repository->getSubCategories($parent_id);
//        }

        $cache_id = __CLASS__ . __FUNCTION__ . crc32(json_encode($parent_id) . $visible_on_frontend . $type . current_lang());
        $cache_group = 'categories';

         $results = cache_get($cache_id, $cache_group, 6000);
        if ($results) {
            return $results;
        }

        $categories_id = $parent_id = intval($parent_id);


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
        $params['order_by'] = 'position asc';
        $params['fields'] = 'id,parent_id';

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


        cache_save($to_return, $cache_id, $cache_group);


        return $to_return;
    }


    public $_get_for_content_memory = [];

    public function get_for_content($content_id, $data_type = 'categories')
    {
        if (intval($content_id) == 0) {
            return false;
        }

        return app()->content_repository->getCategories($content_id);
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
            $data['no_limit'] = true;
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
            $data['cache_group'] = $cache_group = 'categories';
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


  //  $data = $this->app->database_manager->get($data);
     $data = $this->app->category_repository->getByParams($data);






        return $data;
    }

    public function save($data, $preserve_cache = false)
    {
        $sid = $this->app->user_manager->session_id();

        if (is_string($data)) {
            $data = parse_params($data);
        }
        $orig_data = $data;

        $this->useCache = false;

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


        if (isset($data['rel_id'])) {
            $data['rel_id'] = intval($data['rel_id']);
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
            $data['url'] = url_title($data['title']);

        }

        $old_parent = false;
        if (isset($data['id'])) {
            $old_category = $this->get_by_id($data['id']);
            if (isset($old_category['parent_id'])) {
                $old_parent = $old_category['parent_id'];
            }
        }

        if (isset($data['url']) and trim($data['url']) != false) {
            //  $possible_slug = $this->app->url_manager->slug($data['url']);
            $possible_slug = mb_strtolower($data['url']);
            $possible_slug = str_ireplace(' ', '-', $possible_slug);
            if ($possible_slug) {
                $possible_slug_check = $this->get_by_url($possible_slug);
                if (isset($possible_slug_check['id'])) {
                    if (isset($data['id']) and $data['id'] == $possible_slug_check['id']) {
                        //slug is the same
                    } else {
                        $possible_slug = $possible_slug . '-' . date('YmdHis');
                    }
                }
            }



            if ($possible_slug) {

                if ($possible_slug != '') {
                    $cont_get = array();
                    $cont_get['url'] = $possible_slug;
                    $cont_get['single'] = true;
                    $cont_get['no_cache'] = true;
                    $check_cont_wth_slug =  $this->app->content_manager->get($cont_get);

                    // $check_cont_wth_slug = $this->app->content_manager->get_by_url($possible_slug);
                    if ($check_cont_wth_slug) {
                        $possible_slug = $possible_slug . '-' . date('YmdHis');
                    }
                }

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
        $data['categories'] = false;

        //    $data['categories'] = false;
        if (isset($data['parent_id'])) {
//dd($data);
        }

        //$data = mw()->format->clean_xss($data);

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

        /* $data['id'] = $save;
         $this->app->event_manager->trigger('category.after.save', $data);
         */
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
        return $save;
    }

    /**
     * @desc        Get cateroy by slug
     *
     * @param string
     *
     * @return array
     *
     */
    public function get_by_url($slug)
    {
        $id = $this->get_by_id($slug, 'url');
        $override = $this->app->event_manager->trigger('app.category.get_by_url', $slug);
        if ($override and is_array($override) && isset($override[0])) {
            $id = $override[0];
        }

        return $id;
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
        if($by_field_name == 'id'){
            return app()->category_repository->getById($id);
        }
        return app()->category_repository->getByColumnNameAndColumnValue($by_field_name, $id);
    }


    /**
     * @desc        Get cateroy by slug
     *
     * @param string
     *
     * @return array
     *
     */
//    public function get_by_url($slug)
//    {
//        $id = app()->category_repository->getByUrl($slug);
//
//        $override = $this->app->event_manager->trigger('app.category.get_by_url', $slug);
//        if ($override and is_array($override) && isset($override[0])) {
//            $id = $override[0];
//        }
//
//        return $id;
//    }

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

        return true;
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
        $this->app->cache_manager->clear('categories');
        return $res;
    }

    public function get_category_id_from_url($url = false)
    {
        $cat_id = false;

        if ($url) {
            $cat_url = $this->app->url_manager->param('category', true, $url);
        } else {
            $cat_url = $this->app->url_manager->param('category', true);
        }

        if (!$cat_url) {
            $cat_url = mw()->permalink_manager->slug($url, 'category');
        }

        if ($cat_url != false and !is_numeric($cat_url)) {
            $cats = explode(',', $cat_url);
            if (!empty($cats)) {
                $cat_url = array_shift($cats);
            }
        }
        if ($cat_url != false and !is_numeric($cat_url)) {
            $cat_url_by_slug = $this->get_by_url($cat_url);

            if (isset($cat_url_by_slug['id'])) {
                $cat_id = $cat_url_by_slug['id'];
                return $cat_id;
            }
        }

        if (!$cat_url) {
            if (isset($_GET['categories']) and is_array($_GET['categories']) and !empty($_GET['categories'])) {
                $cats_from_get_param = array_values($_GET['categories']);
                $cats_from_get_param = array_filter($_GET['categories']);
                if (!empty($cats_from_get_param)) {
                    $get_first_val = array_shift($cats_from_get_param);
                    if (is_numeric($get_first_val)) {
                        $cat_id = intval($get_first_val);
                    }

                }
            }
        }


        $override = $this->app->event_manager->trigger('app.category.get_category_id_from_url', $cat_url);
        if (is_array($override) && isset($override[0])) {
            $cat_id = $override[0];
        }

        if (!$cat_id) {

            if ($url == false and defined('PAGE_ID')) {
                $url = $this->app->content_manager->link(PAGE_ID);
            }

            if ($url) {
                $cur_url = url_current(true);
                $cur_url = str_replace($url, '', $cur_url);
                if ($cur_url) {
                    $cur_url = trim($cur_url, '/');
                }

                if ($cur_url) {
                    $cur_url_cat = explode('/', $cur_url);

                    if (isset($cur_url_cat[0])) {
                        $cat_url = $cur_url_cat[0];
                        if ($cat_url != false and !is_numeric($cat_url)) {
                            $cat_url_by_slug = $this->get_by_url($cat_url);
                            if (isset($cat_url_by_slug['id'])) {
                                $cat_id = $cat_url_by_slug['id'];
                            }
                        }
                    }


                }
            }
        }

        return intval($cat_id);
    }



    public function get_category_childrens($cat_id)
    {

        $data = array();
        $childrens = $this->get_category_children_recursive($cat_id);

        if ($childrens) {
            foreach ($childrens as $children) {
                $data[] = array(
                    'id' => $children['id'],
                    'type' => 'category',
                    'title' => $children['title'],
                    'parent_id' => intval($children['parent_id']),
                    'position' => intval($children['position']),
                    'parent_type' => 'category',
                    'url' => category_link($children['id']),
                    'subtype' => 'sub_category'

                );
            }
        }

        return $data;
    }

    public function get_category_children_recursive($cat_id)
    {
        $childrens = array();

        $has_children = get_category_children($cat_id);
        if ($has_children) {
            if ($has_children) {
                foreach ($has_children as $cat_sub_id) {

                    if($cat_sub_id == $cat_id){
                        // no loop
                        continue;
                    }

                    $cat_sub = get_category_by_id($cat_sub_id);
                    if ($cat_sub) {
                        $childrens[] = $cat_sub;
                        $cat_sub_has_children = $this->get_category_children_recursive($cat_sub_id);
                        if ($cat_sub_has_children) {
                            foreach ($cat_sub_has_children as $cat_sub_children) {
                                $childrens[] = $cat_sub_children;
                            }
                        }
                    }
                }
            }
        }

        return $childrens;
    }

    public function get_admin_js_tree_json($params)
    {

  /*     $tree = new \MicroweberPackages\Category\AdminJsCategoryTree();
       $tree->filters($params);

       //return $tree->get();*/

        $json = array();

        //    $kw = false;

        $pages_params = array();
        $pages_params['no_limit'] = 1;
        $pages_params['order_by'] = 'position desc';

        if (isset($params['from_content_id'])) {
            $pages_params['id'] = intval($params['from_content_id']);
        }
        if (isset($params['is_shop'])) {
            $pages_params['is_shop'] = trim($params['is_shop']);
        }

        if (isset($params['keyword'])) {
            $pages_params['keyword'] = ($params['keyword']);
        }

        if (isset($params['exclude_ids'])) {
            $pages_params['exclude_ids'] = trim($params['exclude_ids']);
        }

        if (isset($params['content_type'])) {
            $pages_params['content_type'] = ($params['content_type']);
        }
        $show_cats = true;
        if (isset($params['content_type'])) {
            $show_cats = false;
        }

        $pages = get_pages($pages_params);
        if ($pages) {
            foreach ($pages as $page) {
                $item = array();
                $item['id'] = $page['id'];
                $item['type'] = $page['content_type'];
                $item['parent_id'] = intval($page['parent']);
                $item['parent_type'] = 'page';
                $item['title'] = $page['title'];
                $item['url'] = content_link($page['id']);
                $item['is_active'] = intval($page['is_active']);
                // $item['has_children'] = 0;

                $item['subtype'] = $page['subtype'];
                $item['order_by'] = 'position asc';

                if ($page['is_shop']) {
                    $item['subtype'] = 'shop';
                }

                if ($page['is_home']) {
                    $item['subtype'] = 'home';
                }
                $item['position'] = intval($page['position']);
                $json[] = $item;

                if($show_cats) {
                    $cat_params = [];
                    $cat_params['parent_page'] = intval($page['id']);
                    $cat_params['no_limit'] = 1;
                    $cat_params['order_by'] = 'position asc';
                    if (isset($params['keyword'])) {
                        $cat_params['keyword'] = ($params['keyword']);
                    }
                    $pages_cats = get_categories($cat_params);
                    if ($pages_cats) {
                        foreach ($pages_cats as $cat) {

                            $item = array();
                            $item['id'] = intval($cat['id']);
                            $item['type'] = 'category';
                            $item['parent_id'] = intval($page['id']);
                            $item['parent_type'] = 'page';
                            $item['title'] = $cat['title'];
                            $item['subtype'] = 'category';
                            $item['position'] = intval($cat['position']);
                            $item['url'] = category_link($cat['id']);
                            $item['is_active'] = 1;
                            if (isset($cat['is_hidden']) and $cat['is_hidden'] == 1) {
                                $item['is_active'] = 0;
                            }

                            $json[] = $item;

                            $childrens = $this->get_category_childrens($cat['id']);
                            if ($childrens) {
                                foreach ($childrens as $children) {
                                    $children['is_active'] = 1;
                                    if (isset($children['is_hidden']) and $children['is_hidden'] == 1) {
                                        $children['is_active'] = 0;
                                    }
                                    $json[] = $children;
                                }
                            }

                        }
                    }
                }
            }
        }

        return $json;
    }

}
