<?php

if (!defined("MODULE_DB_MENUS")) {
    define('MODULE_DB_MENUS', MW_TABLE_PREFIX . 'menus');
}

action_hook('mw_edit_page_admin_menus', 'mw_print_admin_menu_selector');

function mw_print_admin_menu_selector($params = false)
{
    //d($params);
    $add = '';
    if (isset($params['id'])) {
        $add = '&content_id=' . $params['id'];
    }
    print module('view=edit_page_menus&type=menu' . $add);
}

function get_menu_items($params = false)
{
    $table = MODULE_DB_MENUS;
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
    return get($params);
}

function get_menu_id($params = false)
{

    $table = MODULE_DB_MENUS;

    $params2 = array();
    if ($params == false) {
        $params = array();
    }
    if (is_string($params)) {
        $params = parse_str($params, $params2);
        $params = $params2;
    }
    $params['table'] = $table;
    $params['item_type'] = 'menu';
    $params['limit'] = 1;
    $params['one'] = 1;
    $params = get($params);
    if (isset($params['id'])) {
        return $params['id'];
    }
}

function get_menu($params = false)
{

    $table = MODULE_DB_MENUS;

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
    $menus = get($params);
    if (!empty($menus)) {
        return $menus;
    } else {
        if (isset($params['make_on_not_found']) and ($params['make_on_not_found']) == true and isset($params['title'])) {
            add_new_menu('id=0&title=' . $params['title']);
        }

    }

}

api_expose('add_new_menu');
function add_new_menu($data_to_save)
{
    $params2 = array();
    if ($data_to_save == false) {
        $data_to_save = array();
    }
    if (is_string($data_to_save)) {
        $params = parse_str($data_to_save, $params2);
        $data_to_save = $params2;
    }

    $id = is_admin();
    if ($id == false) {
        //error('Error: not logged in as admin.'.__FILE__.__LINE__);
    } else {

        if (isset($data_to_save['menu_id'])) {
            $data_to_save['id'] = intval($data_to_save['menu_id']);
        }
        $table = MODULE_DB_MENUS;

        $data_to_save['table'] = $table;
        $data_to_save['item_type'] = 'menu';

        $save = save_data($table, $data_to_save);

        cache_clean_group('menus/global');

        return $save;
    }

}

api_expose('menu_delete');
function menu_delete($id = false)
{
    $params = parse_params($id);


    $is_admin = is_admin();
    if ($is_admin == false) {
        error('Error: not logged in as admin.' . __FILE__ . __LINE__);
    }


    if (!isset($params['id'])) {
        error('Error: id param is required.');
    }

    $id = $params['id'];

    $id = db_escape_string($id);
    $id = htmlspecialchars_decode($id);
    $table = MODULE_DB_MENUS;

    db_delete_by_id($table, trim($id), $field_name = 'id');

    cache_clean_group('menus/global');

    return true;

}

api_expose('delete_menu_item');
function delete_menu_item($id)
{

    $is_admin = is_admin();
    if ($is_admin == false) {
        error('Error: not logged in as admin.' . __FILE__ . __LINE__);
    }

    $table = MODULE_DB_MENUS;

    db_delete_by_id($table, intval($id), $field_name = 'id');

    cache_clean_group('menus/global');

    return true;

}

function get_menu_item($id)
{

    $is_admin = is_admin();
    if ($is_admin == false) {
        error('Error: not logged in as admin.' . __FILE__ . __LINE__);
    }
    $id = intval($id);

    $table = MODULE_DB_MENUS;

    return get("one=1&limit=1&table=$table&id=$id");

}

api_expose('edit_menu_item');
function edit_menu_item($data_to_save)
{

    $id = is_admin();
    if ($id == false) {
        error('Error: not logged in as admin.' . __FILE__ . __LINE__);
    }

    if (isset($data_to_save['menu_id'])) {
        $data_to_save['id'] = intval($data_to_save['menu_id']);
        cache_clean_group('menus/' . $data_to_save['id']);

    }

    if (!isset($data_to_save['id']) and isset($data_to_save['link_id'])) {
        $data_to_save['id'] = intval($data_to_save['link_id']);
    }

    if (isset($data_to_save['id'])) {
        $data_to_save['id'] = intval($data_to_save['id']);
        cache_clean_group('menus/' . $data_to_save['id']);
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
        cache_clean_group('menus/' . $data_to_save['parent_id']);
    }

    $table = MODULE_DB_MENUS;

    $data_to_save['table'] = $table;
    $data_to_save['item_type'] = 'menu_item';
    // d($data_to_save);
    $save = save_data($table, $data_to_save);

    cache_clean_group('menus/global');

    return $save;

}

api_expose('reorder_menu_items');

function reorder_menu_items($data)
{

    $adm = is_admin();
    if ($adm == false) {
        error('Error: not logged in as admin.' . __FILE__ . __LINE__);
    }
    $table = MODULE_DB_MENUS;

    if (isset($data['ids_parents'])) {
        $value = $data['ids_parents'];
        if (is_arr($value)) {

            foreach ($value as $value2 => $k) {
                $k = intval($k);
                $value2 = intval($value2);

                $sql = "UPDATE $table SET
				parent_id=$k
				WHERE id=$value2 AND id!=$k
				AND item_type='menu_item'
				";
                // d($sql);
                $q = db_q($sql);
                cache_clean_group('menus/' . $k);
            }

        }
    }

    if (isset($data['ids'])) {
        $value = $data['ids'];
        if (is_arr($value)) {
            $indx = array();
            $i = 0;
            foreach ($value as $value2) {
                $indx[$i] = $value2;
                $i++;
            }

            db_update_position($table, $indx);
            return true;
            // d($indx);
        }
    }

}

function menu_tree($menu_id, $maxdepth = false)
{

    static $passed_ids;
    if (!is_array($passed_ids)) {
        $passed_ids = array();
    }
    $menu_params = '';
    if (is_string($menu_id)) {
        $menu_params = parse_params($menu_id);
        if (is_array($menu_params)) {
            extract($menu_params);
        }
    }

    if (is_array($menu_id)) {
        $menu_params = $menu_id;
        extract($menu_id);
    }
    $params_o = $menu_params;
    $cache_group = 'menus/global';
    $function_cache_id = false;

    /*
     $function_cache_id = __FUNCTION__ . crc32(serialize($menu_id));
     $cache_content = cache_get_content($function_cache_id, $cache_group);
     if (($cache_content) != false) {
     print $cache_content;
     return;

     }*/

    $params = array();
    $params['item_parent'] = $menu_id;
    // $params ['item_parent<>'] = $menu_id;
    $menu_id = intval($menu_id);
    $params_order = array();
    $params_order['position'] = 'ASC';

    $menus = MODULE_DB_MENUS;

    $sql = "SELECT * FROM {$menus}
	WHERE parent_id=$menu_id

	ORDER BY position ASC ";
    //d($sql); and item_type='menu_item'
    $menu_params = array();
    $menu_params['parent_id'] = $menu_id;
    $menu_params['table'] = $menus;
    $menu_params['orderby'] = "position ASC";

    //$q = get($menu_params);
    // d($q);
    $q = db_query($sql, __FUNCTION__ . crc32($sql), 'menus/global/' . $menu_id);

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

    if (!isset($depth) or $depth == false) {
        $depth = 0;
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

    if (isset($params_o['maxdepth']) != false) {
        $maxdepth = $params_o['maxdepth'];
    }


    if (!isset($link) or $link == false) {
        $link = '<a data-item-id="{id}" class="menu_element_link {active_class} {exteded_classes} {nest_level}" href="{url}">{title}</a>';
    }
    //d($link);
    // $to_print = '<ul class="menu" id="menu_item_' .$menu_id . '">';
    $to_print = '<' . $ul_tag . ' role="menu" class="{ul_class}' . ' menu_' . $menu_id . ' {exteded_classes}" >';

    $cur_depth = 0;
    $res_count = 0;
    foreach ($q as $item) {
        $full_item = $item;

        $title = '';
        $url = '';
        $is_active = true;
        if (intval($item['content_id']) > 0) {
            $cont = get_content_by_id($item['content_id']);
            if (isarr($cont)) {
                $title = $cont['title'];
                $url = content_link($cont['id']);

                if ($cont['is_active'] != 'y') {
                    $is_active = false;
                }

            }
        } else if (intval($item['categories_id']) > 0) {
            $cont = get_category_by_id($item['categories_id']);
            if (isarr($cont)) {
                $title = $cont['title'];
                $url = category_link($cont['id']);
            } else {
                db_delete_by_id($menus, $item['id']);
                $title = false;
                $item['title'] = false;
            }
        } else {
            $title = $item['title'];
            $url = $item['url'];
        }

        if (trim($item['url'] != '')) {
            $url = $item['url'];
            //d($url);
        }

        if ($item['title'] == '') {
            $item['title'] = $title;
        } else {
            $title = $item['title'];
        }

        $active_class = '';
        if (trim($item['url'] != '') and intval($item['content_id']) == 0 and intval($item['categories_id']) == 0) {
            $surl = site_url();
            $cur_url = curent_url(1);
            $item['url'] = str_replace_once('{SITE_URL}', $surl, $item['url']);
            if ($item['url'] == $cur_url) {
                $active_class = 'active';
            } else {
                $active_class = '';
            }
        } else if (CONTENT_ID != 0 and $item['content_id'] == CONTENT_ID) {
            $active_class = 'active';
        } elseif (PAGE_ID != 0 and $item['content_id'] == PAGE_ID) {
            $active_class = 'active';
        } elseif (POST_ID != 0 and $item['content_id'] == POST_ID) {
            $active_class = 'active';
        } elseif (CATEGORY_ID != false and intval($item['categories_id']) != 0 and $item['categories_id'] == CATEGORY_ID) {
            $active_class = 'active';
        } else {
            $active_class = '';
        }
        if ($is_active == false) {
            $title = '';
        }
        if ($title != '') {
            $item['url'] = $url;
            //$full_item['the_url'] = page_link($full_item['content_id']);
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
            //	$to_print .= '<a data-item-id="' . $item['id'] . '" class="menu_element_link ' . ' ' . $active_class . '" href="' . $url . '">' . $title . '</a>';

            $ext_classes = '';
            if ($res_count == 0) {
                $ext_classes .= ' first-child';
                $ext_classes .= ' child-' . $res_count . '';
            } else if (!isset($q[$res_count + 1])) {
                $ext_classes .= ' last-child';
                $ext_classes .= ' child-' . $res_count . '';
            } else {
                $ext_classes .= ' child-' . $res_count . '';
            }

            if (in_array($item['id'], $passed_ids) == false) {

                if ($maxdepth == false) {

                    if (isset($params) and isarr($params)) {
                        //$menu_params = $params;
                        //d($params);
                        $menu_params['menu_id'] = $item['id'];
                        $menu_params['link'] = $link;
                        //	$menu_params['link'] = $link;
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

                        //$depth++;
                        if (isset($depth)) {
                            $menu_params['depth'] = $depth + 1;
                        }


                        $test1 = menu_tree($menu_params);
                    } else {
                        $test1 = menu_tree($item['id']);

                    }
                    //$test1 = menu_tree($item['id']);

                } else {

                    if (($maxdepth != false) and intval($maxdepth) > 1 and ($cur_depth <= $maxdepth)) {

                        if (isset($params) and isarr($params)) {
                            $test1 = menu_tree($menu_params);

                        } else {
                            $test1 = menu_tree($item['id']);

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
            }

            $res_count++;
            $to_print .= '</' . $li_tag . '>';

        }

        $passed_ids[] = $item['id'];
        // }
        // }
        $cur_depth++;
    }

    // print "[[ $time ]]seconds\n";
    $to_print .= '</' . $ul_tag . '>';
    // cache_save($to_print, $function_cache_id, $cache_group);
    return $to_print;
}

function is_in_menu($menu_id = false, $content_id = false)
{
    if ($menu_id == false or $content_id == false) {
        return false;
    }

    $menu_id = intval($menu_id);
    $content_id = intval($content_id);
    $check = get_menu_items("limit=1&count=1&parent_id={$menu_id}&content_id=$content_id");
    $check = intval($check);
    if ($check > 0) {
        return true;
    } else {
        return false;
    }
}

api_hook('save_content', 'add_content_to_menu');

function add_content_to_menu($content_id)
{
    $id = is_admin();
    if ($id == false) {
        error('Error: not logged in as admin.' . __FILE__ . __LINE__);
    }
    $content_id = intval($content_id);
    if ($content_id == 0) {
        return;
    }
    $menus = MODULE_DB_MENUS;
    if (isset($_REQUEST['add_content_to_menu']) and is_array($_REQUEST['add_content_to_menu'])) {
        $add_to_menus = $_REQUEST['add_content_to_menu'];
        $add_to_menus_int = array();
        foreach ($add_to_menus as $value) {
            if ($value == 'remove_from_all') {
                $sql = "DELETE FROM {$menus}
				WHERE
				item_type='menu_item'
				AND content_id={$content_id}
				";
                //d($sql);
                cache_clean_group('menus');
                $q = db_q($sql);
                return;
            }

            $value = intval($value);
            if ($value > 0) {
                $add_to_menus_int[] = $value;
            }
        }

    }

    if (isset($add_to_menus_int) and isarr($add_to_menus_int)) {
        $add_to_menus_int_implode = implode(',', $add_to_menus_int);
        $sql = "DELETE FROM {$menus}
		WHERE parent_id NOT IN ($add_to_menus_int_implode)
		AND item_type='menu_item'
		AND content_id={$content_id}
		";

        $q = db_q($sql);

        foreach ($add_to_menus_int as $value) {
            $check = get_menu_items("limit=1&count=1&parent_id={$value}&content_id=$content_id");
            //d($check);
            if ($check == 0) {
                $save = array();
                $save['item_type'] = 'menu_item';
                //	$save['debug'] = $menus;
                $save['parent_id'] = $value;
                $save['url'] = '';
                $save['content_id'] = $content_id;
                save_data($menus, $save);
            }
        }
        cache_clean_group('menus/global');
    }

}
