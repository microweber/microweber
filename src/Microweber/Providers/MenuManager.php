<?php

namespace Microweber\Providers;

use Microweber\Utils\Adapters\Cache\LaravelCache;

use Content;
use Menu;

/**
 * Content class is used to get and save content in the database.
 *
 * @package Content
 * @category Content
 * @desc  These functions will allow you to get and save content in the database.
 *
 */
class MenuManager
{


    public $app = null;

    /**
     *  Boolean that indicates the usage of cache while making queries
     *
     * @var $no_cache
     */
    public $no_cache = false;

    public $tables = array();

    function __construct($app = null)
    {
        if (!is_object($this->app)) {
            if (is_object($app)) {
                $this->app = $app;
            } else {
                $this->app = mw();
            }
        }
        $this->set_table_names();

    }

    /**
     * Sets the database table names to use by the class
     *
     * @param array|bool $tables
     */
    public function set_table_names($tables = false)
    {


        if (!isset($tables['menus'])) {
            $tables['menus'] = 'menus';
        }
        $this->tables['menus'] = $tables['menus'];
    }

    public function menu_create($data_to_save)
    {
        $params2 = array();
        if ($data_to_save == false) {
            $data_to_save = array();
        }
        if (is_string($data_to_save)) {
            $params = parse_str($data_to_save, $params2);
            $data_to_save = $params2;
        }

        $id = $this->app->user_manager->is_admin();
        if (defined("MW_API_CALL") and $id == false) {
            return false;
            //error('Error: not logged in as admin.'.__FILE__.__LINE__);
        } else {

            if (isset($data_to_save['menu_id'])) {
                $data_to_save['id'] = intval($data_to_save['menu_id']);
            }
            $table = $this->tables['menus'];

            $data_to_save['table'] = $table;
            $data_to_save['item_type'] = 'menu';

            if (!isset($data_to_save['id']) or $data_to_save['id'] == 0) {
                $data_to_save['is_active'] = 1;
            }


            $save = $this->app->database->save($table, $data_to_save);

            $this->app->cache_manager->delete('menus/global');

            return $save;
        }

    }

    public function  menu_item_save($data_to_save)
    {


        $id = $this->app->user_manager->is_admin();
        if ($id == false) {
            mw_error('Error: not logged in as admin.' . __FILE__ . __LINE__);
        }

        if (isset($data_to_save['menu_id'])) {
            $data_to_save['id'] = intval($data_to_save['menu_id']);
            $this->app->cache_manager->delete('menus/' . $data_to_save['id']);

        }

        if (!isset($data_to_save['id']) and isset($data_to_save['link_id'])) {
            $data_to_save['id'] = intval($data_to_save['link_id']);
        }

        if (isset($data_to_save['id'])) {
            $data_to_save['id'] = intval($data_to_save['id']);
            $this->app->cache_manager->delete('menus/' . $data_to_save['id']);
        }

        if (!isset($data_to_save['id']) or intval($data_to_save['id']) == 0) {
            $data_to_save['position'] = 99999;
        }

        $url_from_content = false;
        if (isset($data_to_save['content_id']) and intval($data_to_save['content_id']) != 0) {
            $url_from_content = 1;
        }
        if (isset($data_to_save['categories_id']) and intval($data_to_save['categories_id']) != 0) {
            $url_from_content = 1;
        }
        if (isset($data_to_save['content_id']) and intval($data_to_save['content_id']) == 0) {
            unset($data_to_save['content_id']);
        }

        if (isset($data_to_save['categories_id']) and intval($data_to_save['categories_id']) == 0) {
            unset($data_to_save['categories_id']);
            //$url_from_content = 1;
        }

        if ($url_from_content != false) {
            if (isset($data_to_save['title'])) {
                $data_to_save['title'] = '';
            }
        }

        if (isset($data_to_save['categories'])) {
            unset($data_to_save['categories']);
        }

        if ($url_from_content == true and isset($data_to_save['url'])) {
            $data_to_save['url'] = '';
        }

        if (isset($data_to_save['parent_id'])) {
            $data_to_save['parent_id'] = intval($data_to_save['parent_id']);
            $this->app->cache_manager->delete('menus/' . $data_to_save['parent_id']);
        }

        $table = $this->tables['menus'];


        $data_to_save['table'] = $table;
        $data_to_save['item_type'] = 'menu_item';

        $save = $this->app->database->save($table, $data_to_save);

        $this->app->cache_manager->delete('menus/global');

        return $save;

    }

    public function get_menu($params = false)
    {
        $params2 = array();
        if ($params == false) {
            $params = array();
        }
        if (is_string($params)) {
            $params = parse_str($params, $params2);
            $params = $params2;
        }
        $params['one'] = true;
        $params['limit'] = 1;
        $menu = $this->get_menus($params);
        return $menu;


    }

    public function get_menus($params = false)
    {

        $table = $this->tables['menus'];

        $params2 = array();
        if ($params == false) {
            $params = array();
        }
        if (is_string($params)) {
            $params = parse_str($params, $params2);
            $params = $params2;
        }

        //$table = MODULE_DB_SHOP_ORDERS;
        $params['table'] = $table;
        $params['item_type'] = 'menu';
        //$params['debug'] = 'menu';
        $menus = $this->app->database->get($params);
        if (!empty($menus)) {
            return $menus;
        } else {

            if (!defined("MW_MENU_IS_ALREADY_MADE_ONCE")) {
                if (isset($params['make_on_not_found']) and ($params['make_on_not_found']) == true and isset($params['title'])) {
                    $new_menu = $this->menu_create('id=0&title=' . $params['title']);
                    $params['id'] = $new_menu;
                    $menus = $this->app->database->get($params);
                }
                define('MW_MENU_IS_ALREADY_MADE_ONCE', true);
            }
        }
        if (!empty($menus)) {
            return $menus;
        }

    }


    public function menu_tree($menu_id, $maxdepth = false)
    {

        static $passed_ids;
        static $passed_actives;
        static $main_menu_id;
        if (!is_array($passed_actives)) {
            $passed_actives = array();
        }
        if (!is_array($passed_ids)) {
            $passed_ids = array();
        }
        $menu_params = '';
        if (is_string($menu_id)) {
            $menu_params = parse_params($menu_id);
            if (is_array($menu_params)) {
                extract($menu_params);
            }
        } elseif (is_array($menu_id)) {
            extract($menu_id);
        }

        if (is_array($menu_id)) {
            $menu_params = $menu_id;
            extract($menu_id);
        }

        $cache_group = 'menus/global';
        $function_cache_id = false;
        $args = func_get_args();
        foreach ($args as $k => $v) {
            $function_cache_id = $function_cache_id . serialize($k) . serialize($v);
        }


        $function_cache_id = __FUNCTION__ . crc32($function_cache_id . site_url());
        if (defined('PAGE_ID')) {
            $function_cache_id = $function_cache_id . PAGE_ID;
        }
        if (defined('CATEGORY_ID')) {
            $function_cache_id = $function_cache_id . CATEGORY_ID;
        }


        if (!isset($depth) or $depth == false) {
            $depth = 0;
        }
        $orig_depth = $depth;
        $params_o = $menu_params;

        if ($orig_depth == 0) {

            $cache_content = $this->app->cache_manager->get($function_cache_id, $cache_group);
            if (!isset($no_cache) and ($cache_content) != false) {
                //  return $cache_content;
            }

        }

        //$function_cache_id = false;


        $params = array();
        $params['item_parent'] = $menu_id;
        // $params ['item_parent<>'] = $menu_id;
        $menu_id = intval($menu_id);
        $params_order = array();
        $params_order['position'] = 'ASC';

        $menus = $this->tables['menus'];

        $sql = "SELECT * FROM {$menus}
	WHERE parent_id=$menu_id
    AND   id!=$menu_id
	ORDER BY position ASC ";




        //and item_type='menu_item'
        $menu_params = array();
        $menu_params['parent_id'] = $menu_id;
        //  $menu_params['id'] = '[neq]'.$menu_id;

        $menu_params['table'] = $menus;
        $menu_params['order_by'] = "position ASC";


        $q = $this->app->database->get($menu_params);


//        $q = Menu::where('parent_id', '=', $menu_id);
//        $q = $q->where('id', '!=', $menu_id)->get()->toArray();

        //$q = $this->app->database->get($menu_params);

        //
//        if ($depth < 2) {
//            $q = $this->app->database_manager->query($sql, 'query_' . __FUNCTION__ . crc32($sql), 'menus/global');
//
//        } else {
//            $q = $this->app->database_manager->query($sql);
//        }

// @todo cleanup old code
        //Menu::cacheTags('menus-global')->remember(5)->get();


        // $data = $q;
        if (empty($q)) {

            return false;
        }
        $active_class = '';
        if (!isset($ul_class)) {
            $ul_class = 'menu';
        }

        if (!isset($li_class)) {
            $li_class = 'menu_element';
        }


        if (isset($ul_class_deep)) {
            if ($depth > 0) {
                $ul_class = $ul_class_deep;
            }
        }

        if (isset($li_class_deep)) {
            if ($depth > 0) {
                $li_class = $li_class_deep;
            }
        }

        if (isset($ul_tag) == false) {
            $ul_tag = 'ul';
        }

        if (isset($li_tag) == false) {
            $li_tag = 'li';
        }

        if (isset($params['maxdepth']) != false) {
            $maxdepth = $params['maxdepth'];
        }
        if (isset($params['depth']) != false) {
            $maxdepth = $params['depth'];
        }
        if (isset($params_o['depth']) != false) {
            $maxdepth = $params_o['depth'];
        }
        if (isset($params_o['maxdepth']) != false) {
            $maxdepth = $params_o['maxdepth'];
        }


        if (!isset($link) or $link == false) {
            $link = '<a itemprop="url" data-item-id="{id}" class="menu_element_link {active_class} {exteded_classes} {nest_level}"  href="{url}">{title}</a>';
        }

        $to_print = '<' . $ul_tag . ' role="menu" class="{ul_class}' . ' menu_' . $menu_id . ' {exteded_classes}" >';

        $cur_depth = 0;
        $res_count = 0;


        foreach ($q as $item) {
            $full_item = $item;

            $title = '';
            $url = '';
            $is_active = true;
            if (intval($item['content_id']) > 0) {


                $cont = $this->app->content_manager->get_by_id($item['content_id']);
                if (is_array($cont) and isset($cont['is_deleted']) and $cont['is_deleted'] == 1) {
                    $is_active = false;
                    $cont = false;
                }


                if (is_array($cont)) {
                    $title = $cont['title'];
                    $url = $this->app->content_manager->link($cont['id']);

                    if ($cont['is_active'] != 1) {
                        $is_active = false;
                        $cont = false;
                    }

                }
            } else if (intval($item['categories_id']) > 0) {
                $cont = $this->app->category_manager->get_by_id($item['categories_id']);
                if (is_array($cont)) {
                    $title = $cont['title'];
                    $url = $this->app->category_manager->link($cont['id']);
                } else {
                    $this->app->database_manager->delete_by_id($menus, $item['id']);
                    $title = false;
                    $item['title'] = false;
                }
            } else {
                $title = $item['title'];
                $url = $item['url'];
            }

            if (trim($item['url'] != '')) {
                $url = $item['url'];
            }

            if ($item['title'] == '') {
                $item['title'] = $title;
            } else {
                $title = $item['title'];
            }

            $active_class = '';
            $site_url = $this->app->url_manager->site();
            $cur_url = $this->app->url_manager->current(1);
            if (trim($item['url'] != '')) {
                $item['url'] = $this->app->format->replace_once('{SITE_URL}', $site_url, $item['url']);
            }
            if (trim($item['url'] != '') and intval($item['content_id']) == 0 and intval($item['categories_id']) == 0) {
                if ($item['url'] == $cur_url) {
                    $active_class = 'active';
                } else {
                    $active_class = '';
                }

            } elseif (trim($item['url'] == '') and defined('CONTENT_ID') and CONTENT_ID != 0 and $item['content_id'] == CONTENT_ID) {
                $active_class = 'active';

            } elseif (trim($item['url'] == '') and defined('PAGE_ID') and PAGE_ID != 0 and $item['content_id'] == PAGE_ID) {
                $active_class = 'active';
            } elseif (trim($item['url'] == '') and defined('POST_ID') and POST_ID != 0 and $item['content_id'] == POST_ID) {
                $active_class = 'active';
            } elseif (trim($item['url'] == '') and defined('CATEGORY_ID') and CATEGORY_ID != false and intval($item['categories_id']) != 0 and $item['categories_id'] == CATEGORY_ID) {
                $active_class = 'active';
            } elseif (isset($cont['parent']) and defined('PAGE_ID') and PAGE_ID != 0 and $cont['parent'] == PAGE_ID) {
                // $active_class = 'active';
            } elseif (trim($item['url'] == '') and isset($cont['parent']) and defined('MAIN_PAGE_ID') and MAIN_PAGE_ID != 0 and $item['content_id'] == MAIN_PAGE_ID) {
                $active_class = 'active';
            } elseif (trim($item['url'] != '') and $item['url'] == $cur_url) {
                $active_class = 'active';
            } elseif (trim($item['url'] != '') and $item['content_id'] != 0 and defined('PAGE_ID') and PAGE_ID != 0) {
                $cont_link = $this->app->content_manager->link(PAGE_ID);
                if ($item['content_id'] == PAGE_ID and $cont_link == $item['url']) {
                    $active_class = 'active';
                } elseif ($cont_link == $item['url']) {
                    $active_class = 'active';
                }
            } else {

                $active_class = '';
            }


            if ($is_active == false) {
                $title = '';
            }
            if (isset($item['id'])) {
                if ($active_class == 'active') {
                    $passed_actives[] = $item['id'];
                } elseif ($active_class == '') {
                    if (isset($cont['content_id'])) {
                        if (in_array($item['content_id'], $passed_actives)) {
                            $active_class = 'active';
                        }
                    }
                }
            }

            if ($title != '') {
                //$url = $this->app->format->prep_url($url);
                //$url = $this->app->format->auto_link($url);
                $item['url'] = $url;
                $to_print .= '<' . $li_tag . '  class="{li_class}' . ' ' . $active_class . ' {nest_level}" data-item-id="' . $item['id'] . '" >';

                $ext_classes = '';

                if (isset($item['parent']) and intval($item['parent']) > 0) {
                    $ext_classes .= ' have-parent';
                }

                if (isset($item['subtype_value']) and intval($item['subtype_value']) != 0) {
                    $ext_classes .= ' have-category';
                }

                $ext_classes = trim($ext_classes);

                $menu_link = $link;
                foreach ($item as $key => $value) {
                    $menu_link = str_replace('{' . $key . '}', $value, $menu_link);
                }
                $menu_link = str_replace('{active_class}', $active_class, $menu_link);
                $to_print .= $menu_link;

                $ext_classes = '';
                //  if ($orig_depth > 0) {


                if ($main_menu_id == false) {
                    $main_menu_id = $menu_id;
                    $ext_classes .= ' menu-root';


                } else {
                    if ($res_count == 0) {

                        // if($main_menu_id == false){
                        $ext_classes .= ' first-child';
                        $ext_classes .= ' child-' . $res_count . '';
                        // }

                    } else if (!isset($q[$res_count + 1])) {
                        $ext_classes .= ' last-child';
                        $ext_classes .= ' child-' . $res_count . '';
                    } else {
                        $ext_classes .= ' child-' . $res_count . '';
                    }
                }


                // }
                if (in_array($item['parent_id'], $passed_ids) == false) {

                    if ($maxdepth == false) {

                        if (isset($params) and is_array($params)) {

                            $menu_params['menu_id'] = $item['id'];
                            $menu_params['link'] = $link;
                            if (isset($menu_params['item_parent'])) {
                                unset($menu_params['item_parent']);
                            }
                            if (isset($ul_class)) {
                                $menu_params['ul_class'] = $ul_class;
                            }
                            if (isset($li_class)) {
                                $menu_params['li_class'] = $li_class;
                            }

                            if (isset($maxdepth)) {
                                $menu_params['maxdepth'] = $maxdepth;
                            }

                            if (isset($li_tag)) {
                                $menu_params['li_tag'] = $li_tag;
                            }
                            if (isset($ul_tag)) {
                                $menu_params['ul_tag'] = $ul_tag;
                            }
                            if (isset($ul_class_deep)) {
                                $menu_params['ul_class_deep'] = $ul_class_deep;
                            }
                            if (isset($li_class_empty)) {
                                $menu_params['li_class_empty'] = $li_class_empty;
                            }

                            if (isset($li_class_deep)) {
                                $menu_params['li_class_deep'] = $li_class_deep;
                            }

                            if (isset($depth)) {
                                $menu_params['depth'] = $depth + 1;
                            }


                            $test1 = $this->menu_tree($menu_params);
                        } else {

                            $test1 = $this->menu_tree($item['id']);

                        }


                    } else {

                        if (($maxdepth != false) and intval($maxdepth) > 1 and ($cur_depth <= $maxdepth)) {

                            if (isset($params) and is_array($params)) {

                                $test1 = $this->menu_tree($menu_params);

                            } else {

                                $test1 = $this->menu_tree($item['id']);

                            }

                        }
                    }
                }
                if (isset($li_class_empty) and isset($test1) and trim($test1) == '') {
                    if ($depth > 0) {
                        $li_class = $li_class_empty;
                    }
                }

                $to_print = str_replace('{ul_class}', $ul_class, $to_print);
                $to_print = str_replace('{li_class}', $li_class, $to_print);
                $to_print = str_replace('{exteded_classes}', $ext_classes, $to_print);
                $to_print = str_replace('{nest_level}', 'depth-' . $depth, $to_print);

                if (isset($test1) and strval($test1) != '') {
                    $to_print .= strval($test1);
                    $res_count++;
                }

                $to_print .= '</' . $li_tag . '>';

                // $passed_ids[] = $item['id'];
            }

            //  $res_count++;
            $cur_depth++;
        }

        $to_print .= '</' . $ul_tag . '>';
        if ($orig_depth == 0) {
            $this->app->cache_manager->save($to_print, $function_cache_id, $cache_group);
        }
        return $to_print;
    }


    public function menu_delete($id = false)
    {
        $params = parse_params($id);


        $is_admin = $this->app->user_manager->is_admin();
        if ($is_admin == false) {
            mw_error('Error: not logged in as admin.' . __FILE__ . __LINE__);
        }


        if (!isset($params['id'])) {
            mw_error('Error: id param is required.');
        }

        $id = $params['id'];

        $id = $this->app->database_manager->escape_string($id);
        $id = htmlspecialchars_decode($id);
        $table = $this->tables['menus'];

        $this->app->database_manager->delete_by_id($table, trim($id), $field_name = 'id');

        $this->app->cache_manager->delete('menus/global');

        return true;

    }

    public function menu_item_get($id)
    {

        $is_admin = $this->app->user_manager->is_admin();
        if ($is_admin == false) {
            mw_error('Error: not logged in as admin.' . __FILE__ . __LINE__);
        }
        $id = intval($id);

        $table = $this->tables['menus'];

        return get("one=1&limit=1&table=$table&id=$id");

    }


    public function menu_item_delete($id = false)
    {

        if (is_array($id)) {
            extract($id);
        }
        if (!isset($id) or $id == false or intval($id) == 0) {
            return false;
        }

        $is_admin = $this->app->user_manager->is_admin();
        if ($is_admin == false) {
            mw_error('Error: not logged in as admin.' . __FILE__ . __LINE__);
        }

        $table = $this->tables['menus'];

        $this->app->database_manager->delete_by_id($table, intval($id), $field_name = 'id');

        $this->app->cache_manager->delete('menus/global');

        return true;

    }

    public function menu_items_reorder($data)
    {

        $return_res = false;
        $adm = $this->app->user_manager->is_admin();
        if (defined("MW_API_CALL") and $adm == false) {
            mw_error('Error: not logged in as admin.' . __FILE__ . __LINE__);
        }
        $table = $this->tables['menus'];
        $table = $this->app->database_manager->real_table_name($table);
        if (isset($data['ids_parents'])) {
            $value = $data['ids_parents'];
            if (is_array($value)) {

                foreach ($value as $value2 => $k) {
                    $k = intval($k);
                    $value2 = intval($value2);

                    $sql = "UPDATE $table SET
				parent_id=$k
				WHERE id=$value2 AND id!=$k
				AND item_type='menu_item'
				";
                    $q = $this->app->database->q($sql);
                    $this->app->cache_manager->delete('menus/' . $k);
                    $this->app->cache_manager->delete('menus/' . $value2);
                }

            }
        }

        if (isset($data['ids'])) {
            $value = $data['ids'];
            if (is_array($value)) {
                $indx = array();
                $i = 0;
                foreach ($value as $value2) {
                    $indx[$i] = $value2;
                    $this->app->cache_manager->delete('menus/' . $value2);

                    $i++;
                }

                $this->app->database_manager->update_position_field($table, $indx);
                //return true;
                $return_res = $indx;
            }
        }
        $this->app->cache_manager->delete('menus/global');

        $this->app->cache_manager->delete('menus');
        return $return_res;
    }

    public function is_in_menu($menu_id = false, $content_id = false)
    {
        if ($menu_id == false or $content_id == false) {
            return false;
        }

        $menu_id = intval($menu_id);
        $content_id = intval($content_id);
        $check = $this->get_menu_items("limit=1&count=1&parent_id={$menu_id}&content_id=$content_id");
        $check = intval($check);
        if ($check > 0) {
            return true;
        } else {
            return false;
        }
    }


    public function get_menu_items($params = false)
    {
        $table = $this->tables['menus'];
        $params2 = array();
        if ($params == false) {
            $params = array();
        }
        if (is_string($params)) {
            $params = parse_str($params, $params2);
            $params = $params2;
        }
        $params['table'] = $table;
        $params['item_type'] = 'menu_item';
        return $this->app->database->get($params);
    }
}

