<?php


//action_hook('mw_db_init_default', '\Content::db_init');
//action_hook('on_load', '\Content::db_init');


class Categories
{


    /**
     * category_tree
     *
     * @desc prints category_tree of UL and LI
     * @access      public
     * @category    categories
     * @author      Microweber
     * @param $params = array();
     * @param  $params['parent'] = false; //parent id
     * @param  $params['link'] = false; // the link on for the <a href
     * @param  $params['active_ids'] = array(); //ids of active categories
     * @param  $params['active_code'] = false; //inserts this code for the active ids's
     * @param  $params['remove_ids'] = array(); //remove those caregory ids
     * @param  $params['ul_class_name'] = false; //class name for the ul
     * @param  $params['include_first'] = false; //if true it will include the main parent category
     * @param  $params['content_type'] = false; //if this is set it will include only categories from desired type
     * @param  $params['add_ids'] = array(); //if you send array of ids it will add them to the category
     * @param  $params['orderby'] = array(); //you can order by such array $params['orderby'] = array('created_on','asc');
     * @param  $params['content_type'] = false; //if this is set it will include only categories from desired type
     * @param  $params['list_tag'] = 'select';
     * @param  $params['list_item_tag'] = "option";
     *
     *
     */
    static function tree($params = false)
    {

        $function_cache_id = false;
        $args = func_get_args();
        foreach ($args as $k => $v) {
            $function_cache_id = $function_cache_id . serialize($k) . serialize($v);
        }
        $function_cache_id = __FUNCTION__ . crc32($function_cache_id);

        $cache_group = 'categories/global';
        $cache_content = cache_get_content($function_cache_id, $cache_group);
        $cache_content = false;
        //if (!isset($_GET['debug'])) {
        if (($cache_content) != false) {
            print $cache_content;
            return;
            //  return $cache_content;
        }
        //}
        $p2 = array();
        // d($params);
        if (!is_array($params)) {
            if (is_string($params)) {
                parse_str($params, $p2);
                $params = $p2;
            }
        }
        if (isset($params['parent'])) {
            $parent = ($params['parent']);
        } else if (isset($params['subtype_value'])) {
            $parent = ($params['subtype_value']);
        } else {
            $parent = 0;
        }

        $link = isset($params['link']) ? $params['link'] : false;

        if ($link == false) {
            $link = "<a href='{categories_url}' data-category-id='{id}'  class='{active_code} {nest_level}'  >{title}</a>";
        }
        $link = str_replace('data-page-id', 'data-category-id', $link);

        $active_ids = isset($params['active_ids']) ? $params['active_ids'] : array(CATEGORY_ID);
        if (isset($params['active_code'])) {
            $active_code = $params['active_code'];
        } else {
            $active_code = " active ";
        }

        if (isset($params['remove_ids'])) {
            $remove_ids = $params['remove_ids'];
        } else {
            $remove_ids = false;
        }

        if (isset($params['removed_ids_code'])) {
            $removed_ids_code = $params['removed_ids_code'];
        } else {
            $removed_ids_code = false;
        }
        $ul_class_name = '';
        if (isset($params['class'])) {
            $ul_class_name = $params['class'];
        }
        if (isset($params['ul_class'])) {

            $ul_class_name = $params['ul_class'];
        }

        if (isset($params['ul_class_name'])) {

            $ul_class_name = $params['ul_class_name'];
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

        $table = MW_TABLE_PREFIX . 'categories';
        if (isset($params['content_id'])) {
            $params['for_page'] = $params['content_id'];

        }

        if (isset($params['for_page']) and $params['for_page'] != false) {
            $page = get_content_by_id($params['for_page']);
            //d($page);
            $parent = $page['subtype_value'];
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

                $table_assoc_name = db_get_assoc_table_name($params['for']);
                $skip123 = true;

                $str0 = 'is_deleted=n&orderby=position asc&table=' . $table . '&limit=1000&data_type=category&what=categories&' . 'parent_id=0&rel=' . $table_assoc_name;
                $fors = get($str0);

            }

            if (!isset($params['content_id']) and isset($params['try_rel_id']) and intval($params['try_rel_id']) != 0) {
                $skip123 = true;

                $str1 = 'is_deleted=n&orderby=position asc&table=' . $table . '&limit=1000&parent_id=0&rel_id=' . $params['try_rel_id'];
                $fors1 = get($str1);
                if (isarr($fors1)) {
                    $fors = array_merge($fors, $fors1);

                }

            }
        }

        if (isset($params['not_for_page']) and $params['not_for_page'] != false) {
            $page = get_page($params['not_for_page']);
            $remove_ids = array($page['subtype_value']);
        }

        if (isset($params['nest_level'])) {
            $depth_level_counter = $params['nest_level'];
        } else {
            $depth_level_counter = 0;
        }

        $max_level = false;
        if (isset($params['max_level'])) {
            $max_level = $params['max_level'];
        }
        $list_tag = false;
        if (isset($params['list_tag'])) {
            $list_tag = $params['list_tag'];
        }
        $list_item_tag = false;
        if (isset($params['list_item_tag'])) {
            $list_item_tag = $params['list_item_tag'];
        }

        $params['table'] = $table;
        // $add_ids1 = false;
        if (is_string($add_ids)) {
            $add_ids = explode(',', $add_ids);
        }

        if (isset($params['rel']) and $params['rel'] != false and isset($params['rel_id'])) {

            $table_assoc_name = db_get_assoc_table_name($params['rel']);
            $skip123 = true;

            $str0 = 'is_deleted=n&orderby=position asc&table=' . $table . '&limit=1000&data_type=category&what=categories&' . 'rel_id=' . intval($params['rel_id']) . '&rel=' . $table_assoc_name;
            $fors = get($str0);

        }

        if (isset($params['debug'])) {

        }

        ob_start();

        //  cache_save($fields, $function_cache_id, $cache_group = 'db');

        if ($skip123 == false) {

            content_helpers_getCaregoriesUlTree($parent, $link, $active_ids, $active_code, $remove_ids, $removed_ids_code, $ul_class_name, $include_first, $content_type, $li_class_name = false, $add_ids, $orderby, $only_with_content = false, $visible_on_frontend = false, $depth_level_counter, $max_level, $list_tag, $list_item_tag, $active_code_tag);
        } else {

            if ($fors != false and is_array($fors) and !empty($fors)) {
                foreach ($fors as $cat) {
                    content_helpers_getCaregoriesUlTree($cat['id'], $link, $active_ids, $active_code, $remove_ids, $removed_ids_code, $ul_class_name, $include_first = true, $content_type, $li_class_name = false, $add_ids, $orderby, $only_with_content = false, $visible_on_frontend = false, $depth_level_counter, $max_level, $list_tag, $list_item_tag, $active_code_tag);
                }
            }
        }

        $content = ob_get_contents();
        //if (!isset($_GET['debug'])) {
        cache_save($content, $function_cache_id, $cache_group);
        //}
        ob_end_clean();
        print $content;
        return;
    }

}