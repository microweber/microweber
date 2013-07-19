<?php
namespace mw;


if (!defined("MW_DB_TABLE_TAXONOMY")) {
    define('MW_DB_TABLE_TAXONOMY', MW_TABLE_PREFIX . 'categories');
}

if (!defined("MW_DB_TABLE_TAXONOMY_ITEMS")) {
    define('MW_DB_TABLE_TAXONOMY_ITEMS', MW_TABLE_PREFIX . 'categories_items');
}



class Category
{


    static function get_items($params, $data_type = 'categories')
    {

        $rel_id = 0;
        if (is_string($params)) {
            $params = parse_str($params, $params2);
            $params = $options = $params2;
        }

        $table = MW_TABLE_PREFIX . 'categories';
        $table_items = MW_TABLE_PREFIX . 'categories_items';

        $data = $params;
        $data_type_q = false;
        if ($data_type == 'categories') {
            $data['data_type'] = 'category_item';
            $data_type_q = "and data_type = 'category_item' ";
        }

        if ($data_type == 'tags') {
            $data['data_type'] = 'tag_item';
            $data_type_q = "and data_type = 'tag_item' ";
        }
        $data['table'] = $table_items;
        //$data['debug'] = $table;
        //$data['cache_group'] = $cache_group = 'categories/' . $rel_id;
        //$data['only_those_fields'] = array('parent_id');

        $data = get($data);
        return $data;

        $results = false;
        if (!empty($data)) {
            $results = array();
            foreach ($data as $item) {
                $results[] = $item['parent_id'];
            }
            $results = array_unique($results);
        }
        //cache_save($results, $function_cache_id, $cache_group);
        return $results;
    }

    static function link($id)
    {

        if (intval($id) == 0) {

            return false;
        }

        $function_cache_id = '';

        $args = func_get_args();

        foreach ($args as $k => $v) {

            $function_cache_id = $function_cache_id . serialize($k) . serialize($v);
        }
        $function_cache_id = __FUNCTION__ . crc32($function_cache_id);

        $categories_id = intval($id);
        $cache_group = 'categories/' . $categories_id;

        $cache_content = cache_get_content($function_cache_id, $cache_group);

        if (($cache_content) != false) {

            return $cache_content;
        } else {
            $table = MW_TABLE_PREFIX . 'categories';
            $c_infp = get_category_by_id($id);
            if (!isset($c_infp['rel'])) {
                return;
            }

            if (trim($c_infp['rel']) != 'content') {
                return;
            }

            $content = get_page_for_category($id);

            if (!empty($content)) {
                $url = $content['url'];
                if ($content['content_type'] == 'page') {
                    if (function_exists('page_link')) {
                        $url = page_link($content['id']);
                    }
                }

                if ($content['content_type'] == 'post') {
                    if (function_exists('post_link')) {
                        $url = post_link($content['id']);
                    }
                }
            } else {
                if (!empty($c_infp) and isset($c_infp['rel']) and trim($c_infp['rel']) == 'content') {
                    \mw\Db::delete_by_id($table, $id);
                }
            }

            if (isset($url) != false) {
                $url = $url . '/category:' . $id;
                cache_save($url, $function_cache_id, $cache_group);

                return $url;
            }

            return;
        }

        //todo delete

        $function_cache_id = '';

        $args = func_get_args();

        foreach ($args as $k => $v) {

            $function_cache_id = $function_cache_id . serialize($k) . serialize($v);
        }
        $function_cache_id = __FUNCTION__ . crc32($function_cache_id);

        $categories_id = intval($id);
        $cache_group = 'categories/' . $categories_id;

        $cache_content = cache_get_content($function_cache_id, $cache_group);

        if (($cache_content) != false) {

            return $cache_content;
        } else {

            $data = array();

            $data['id'] = $id;

            $data = get_category_by_id($id);

            if (empty($data)) {

                return false;
            }
            //$this->load->model ( 'Content_model', 'content_model' );

            $table = MW_TABLE_PREFIX . 'categories';
            $db_t_content = MW_TABLE_PREFIX . 'content';

            $content = array();

            $content['subtype'] = 'dynamic';

            $content['subtype_value'] = $id;

            //$orderby = array ('id', 'desc' );

            $q = " SELECT * FROM $db_t_content WHERE subtype ='dynamic' AND subtype_value={$id} LIMIT 0,1";
            //p($q,1);
            $q = \mw\Db::query($q, __FUNCTION__ . crc32($q), $cache_group);

            //$content = $this->content_model->getContentAndCache ( $content, $orderby );

            $content = $q[0];

            $url = false;

            $parent_ids = get_category_parents($data['id']);
            $parent_ids = array_rpush($parent_ids, $data['id']);
            foreach ($parent_ids as $item) {

                $content = array();

                $content['subtype'] = 'dynamic';

                $content['subtype_value'] = $item;

                $orderby = array('id', 'desc');

                $q = " SELECT * FROM $db_t_content WHERE subtype ='dynamic' AND subtype_value={$item} LIMIT 0,1";
                //p($q);
                $q = \mw\Db::query($q, __FUNCTION__ . crc32($q), $cache_group);

                //$content = $this->content_model->getContentAndCache ( $content, $orderby );

                $content = $q[0];

                //$content = $content [0];

                $url = false;

                if (!empty($content)) {

                    if ($content['content_type'] == 'page') {
                        if (function_exists('page_link')) {
                            $url = page_link($content['id']);
                            //$url = $url . '/category:' . $data ['title'];

                            $str = $data['title'];
                            if (function_exists('mb_strtolower')) {
                                $str = mb_strtolower($str, "UTF-8");
                            } else {
                                $str = strtolower($str);
                            }

                            $string1 = ($str);

                            $url = $url . '/' . url_title($string1) . '/categories:' . $data['id'];

                            //$url = $url . '/categories:' . $data ['id'];
                        }
                    }
                    if ($content['content_type'] == 'post') {
                        if (function_exists('post_link')) {
                            $url = post_link($content['id']);
                        }
                    }
                }

                //if ($url != false) {
                cache_save($url, $function_cache_id, $cache_group);
                return $url;
                //}
            }

            return false;
        }

        //var_dump ( $parent_ids );
    }

    static function get($params, $data_type = 'categories')
    {
        $params2 = array();
        $rel_id = 0;
        if (is_string($params)) {
            $params = parse_str($params, $params2);
            $params = $options = $params2;
            extract($params);
        }
        if (isset($params['rel_id'])) {
            $rel_id = $params['rel_id'];
        }

        $table = MW_TABLE_PREFIX . 'categories';
        $table_items = MW_TABLE_PREFIX . 'categories_items';

        $data = $params;
        $data_type_q = false;

        $data['table'] = $table;
        if (isset($params['id'])) {
            $data['cache_group'] = $cache_group = 'categories/' . $params['id'];
        } else {
            $data['cache_group'] = $cache_group = 'categories/global';

        }
        //$data['only_those_fields'] = array('parent_id');

        $data = get($data);
        return $data;

    }


    /**
     * @desc Get a single row from the categories_table by given ID and returns it as one dimensional array
     * @param int
     * @return array
     * @author      Peter Ivanov
     * @version 1.0
     * @since Version 1.0
     */
    static function get_by_id($id = 0)
    {

        if ($id == 0) {
            return false;
        }

        $id = intval($id);

        $function_cache_id = false;

        $args = func_get_args();

        foreach ($args as $k => $v) {

            $function_cache_id = $function_cache_id . serialize($k) . serialize($v);
        }

        $function_cache_id = __FUNCTION__ . crc32($function_cache_id);

        $categories_id = intval($id);
        $cache_group = 'categories/' . $categories_id;
        $cache_content = false;
        $cache_content = cache_get_content($function_cache_id, $cache_group);

        if (($cache_content) != false) {

            return $cache_content;
        }

        $table = MW_TABLE_PREFIX . 'categories';

        $id = intval($id);

        $q = " SELECT * FROM $table WHERE id = $id LIMIT 0,1";

        $q = \mw\Db::query($q);

        $q = $q[0];

        if (!empty($q)) {

            cache_save($q, $function_cache_id, $cache_group);
            //return $to_cache;

            return $q;
        } else {

            return false;
        }
    }


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


    /**
     * `
     *
     * Prints the selected categories as an <UL> tree, you might pass several
     * options for more flexibility
     *
     * @param
     *            array
     *
     * @param
     *            boolean
     *
     * @author Peter Ivanov
     *
     * @version 1.0
     *
     * @since Version 1.0
     *
     */
    static function html_tree($parent, $link = false, $active_ids = false, $active_code = false, $remove_ids = false, $removed_ids_code = false, $ul_class_name = false, $include_first = false, $content_type = false, $li_class_name = false, $add_ids = false, $orderby = false, $only_with_content = false, $visible_on_frontend = false, $depth_level_counter = 0, $max_level = false, $list_tag = false, $list_item_tag = false, $active_code_tag = false)
    {

        $db_t_content = MW_TABLE_PREFIX . 'content';

        $table = $db_categories = MW_TABLE_PREFIX . 'categories';

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

        if (!empty($remove_ids)) {

            $remove_ids_q = implode(',', $remove_ids);

            $remove_ids_q = " and id not in ($remove_ids_q) ";
        } else {

            $remove_ids_q = false;
        }

        if (!empty($add_ids)) {

            $add_ids_q = implode(',', $add_ids);

            $add_ids_q = " and id in ($add_ids_q) ";
        } else {

            $add_ids_q = false;
        }
        //$add_ids_q = '';
        //$remove_ids_q =   '';
        if ($max_level != false and $depth_level_counter != false) {

            if (intval($depth_level_counter) >= intval($max_level)) {
                print '';
                return;
            }
        }

        if (isset($list_tag) == false or $list_tag == false) {
            $list_tag = 'ul';
        }

        if (isset($active_code_tag) == false or $active_code_tag == false) {
            $active_code_tag = '';
        }

        if (isset($list_item_tag) == false or $list_item_tag == false) {
            $list_item_tag = 'li';
        }

        if (empty($limit)) {
            $limit = array(0, 10);
        }

        $content_type = addslashes($content_type);
        $hard_limit = " LIMIT 300 ";
        $inf_loop_fix = "  and $table.id!=$table.parent_id  ";
        //	$inf_loop_fix = "     ";
        if ($content_type == false) {

            if ($include_first == true) {

                $sql = "SELECT * FROM $table WHERE id=$parent ";
                $sql = $sql . " and data_type='category'   and is_deleted='n'  ";
                $sql = $sql . "$remove_ids_q  $add_ids_q $inf_loop_fix  ";
                $sql = $sql . " group by id order by {$orderby [0]}  {$orderby [1]}  $hard_limit";

                // $sql = "SELECT * from $table where id=$parent  and data_type='category'   $remove_ids_q    $inf_loop_fix group by id   ";

            } else {

                $sql = "SELECT * FROM $table WHERE parent_id=$parent AND data_type='category' AND is_deleted='n' ";
                $sql = $sql . "$remove_ids_q $add_ids_q $inf_loop_fix group by id order by {$orderby [0]}  {$orderby [1]}   $hard_limit";

            }
        } else {

            if ($include_first == true) {

                $sql = "SELECT * FROM $table WHERE id=$parent  AND is_deleted='n'  ";
                $sql = $sql . "$remove_ids_q $add_ids_q   $inf_loop_fix group by id order by {$orderby [0]}  {$orderby [1]}  $hard_limit";
            } else {

                $sql = "SELECT * FROM $table WHERE parent_id=$parent AND is_deleted='n' AND data_type='category' AND (categories_content_type='$content_type' OR categories_content_type='inherit' ) ";
                $sql = $sql . " $remove_ids_q  $add_ids_q $inf_loop_fix group by id order by {$orderby [0]}  {$orderby [1]}   $hard_limit";
            }
        }

        if (!empty($limit)) {

            $my_offset = $limit[1] - $limit[0];

            $my_limit_q = " limit  {$limit[0]} , $my_offset  ";
        } else {

            $my_limit_q = false;
        }
        $output = '';
        //$children_of_the_main_parent = get_category_items($parent, $type = 'category_item', $visible_on_frontend, $limit);
        //

        //
        $q = \mw\Db::query($sql, $cache_id = 'html_tree_parent_cats_q_' . crc32($sql), 'categories/' . intval($parent));
        //$q = \mw\Db::query($sql);

        // $q = $this->core_model->dbQuery ( $sql, $cache_id =
        // 'self::html_tree_parent_cats_q_' . md5 ( $sql ),
        // 'categories/' . intval ( $parent ) );

        $result = $q;

        //

        $only_with_content2 = $only_with_content;

        $do_not_show_next = false;

        $chosen_categories_array = array();

        if (isset($result) and is_array($result) and !empty($result)) {

            // $output = "<ul>";
            $depth_level_counter++;
            $i = 0;

            $do_not_show = false;

            if ($do_not_show == false) {

                $print1 = false;
                if (trim($list_tag) != '') {
                    if ($ul_class_name == false) {

                        $print1 = "<{$list_tag}  class='category_tree depth-{$depth_level_counter}'>";
                    } else {

                        $print1 = "<{$list_tag} class='$ul_class_name depth-{$depth_level_counter}'>";
                    }
                }
                print $print1;
                // print($type);
                foreach ($result as $item) {

                    if ($only_with_content == true) {

                        $do_not_show = false;

                        $check_in_content = false;
                        $childern_content = array();

                        $do_not_show = false;
                        // print($type);

                        if (!empty($childern_content)) {

                            $do_not_show = false;
                        } else {

                            $do_not_show = true;
                        }
                    } else {

                        $do_not_show = false;
                    }
                    $iid = $item['id'];
                    if ($do_not_show == false) {

                        $output = $output . $item['title'];

                        if ($li_class_name == false) {

                            $output = "<{$list_item_tag} class='category_element depth-{$depth_level_counter} item_{$iid}'   value='{$item['id']}' data-category-id='{$item['id']}' data-category-parent-id='{$item['parent_id']}' data-item-id='{$item['id']}'  data-to-table='{$item['rel']}'  data-to-table-id='{$item['rel_id']}'    data-categories-type='{$item['data_type']}' {active_code_tag}>";
                        } else {

                            $output = "<{$list_item_tag} class='$li_class_name  category_element depth-{$depth_level_counter} item_{$iid}'  value='{$item['id']}' data-item-id='{$item['id']}' data-category-id='{$item['id']}'  data-to-table='{$item['rel']}'  data-to-table-id='{$item['rel_id']}'  data-categories-type='{$item['data_type']}'  {active_code_tag}  >";
                        }
                    }

                    if ($do_not_show == false) {

                        if ($link != false) {

                            $to_print = false;

                            $empty1 = intval($depth_level_counter);
                            $empty = '';
                            for ($i1 = 0; $i1 < $empty1; $i1++) {
                                $empty = $empty . '&nbsp;&nbsp;';
                            }

                            $ext_classes = '';

                            $to_print = str_replace('{id}', $item['id'], $link);

                            $to_print = str_ireplace('{url}', category_link($item['id']), $to_print);
                            $to_print = str_replace('{exteded_classes}', $ext_classes, $to_print);

                            $to_print = str_ireplace('{categories_url}', category_link($item['id']), $to_print);
                            $to_print = str_ireplace('{nest_level}', 'depth-' . $depth_level_counter, $to_print);

                            $to_print = str_ireplace('{title}', $item['title'], $to_print);

                            $active_parent_class = '';
                            //if(isset($item['parent']) and intval($item['parent']) != 0){
                            if (intval($item['parent_id']) != 0 and intval($item['parent_id']) == intval(CATEGORY_ID)) {
                                $active_parent_class = 'active-parent';
                            } else {
                                $active_parent_class = '';
                            }
                            $active_class = 'active';

                            $to_print = str_replace('{active_class}', $active_class, $to_print);
                            $to_print = str_replace('{active_parent_class}', $active_parent_class, $to_print);

                            //$to_print = str_ireplace('{title2}', $item ['title2'], $to_print);
                            // $to_print = str_ireplace('{title3}', $item ['title3'], $to_print);

                            $to_print = str_ireplace('{categories_content_type}', trim($item['categories_content_type']), $to_print);
                            $to_print = str_replace('{empty}', $empty, $to_print);

                            //   $to_print = str_ireplace('{content_count}', $item ['content_count'], $to_print);
                            $active_found = false;

                            if (is_string($active_ids)) {
                                $active_ids = explode(',', $active_ids);
                            }

                            if (is_array($active_ids) == true) {
                                $active_ids = array_trim($active_ids);
                                //d($active_ids);

                                foreach ($active_ids as $value_active_cat) {
                                    if ($value_active_cat != '') {
                                        $value_active_cat = intval($value_active_cat);
                                        if (intval($item['id']) == $value_active_cat) {
                                            $active_found = $value_active_cat;
                                            // d($value_active_cat);
                                        }
                                    }
                                }

                                if ($active_found == true) {

                                    $to_print = str_replace('{active_code}', $active_code, $to_print);
                                    $to_print = str_replace('{active_class}', $active_class, $to_print);
                                    $to_print = str_replace('{active_code_tag}', $active_code_tag, $to_print);
                                    $output = str_replace('{active_code_tag}', $active_code_tag, $output);

                                    //d($output);

                                } else {

                                    $to_print = str_replace('{active_code}', '', $to_print);
                                }
                            } else {

                                $to_print = str_ireplace('{active_code}', '', $to_print);
                            }
                            $output = str_replace('{active_code_tag}', '', $output);
                            $output = str_replace('{exteded_classes}', $ext_classes, $output);


                            $to_print = str_replace('{active_class}', '', $to_print);
                            $to_print = str_replace('{active_code_tag}', '', $to_print);

                            if (is_array($remove_ids) == true) {

                                if (in_array($item['id'], $remove_ids)) {

                                    if ($removed_ids_code == false) {

                                        $to_print = false;
                                    } else {

                                        $to_print = str_ireplace('{removed_ids_code}', $removed_ids_code, $to_print);
                                    }
                                } else {

                                    $to_print = str_ireplace('{removed_ids_code}', '', $to_print);
                                }
                            }

                            if (strval($to_print) == '') {

                                print $output . $item['title'];
                            } else {

                                print $output . $to_print;
                            }
                        } else {

                            print $output . $item['title'];
                        }

                        // $parent, $link = false, $active_ids = false,
                        // $active_code = false, $remove_ids = false,
                        // $removed_ids_code = false, $ul_class_name = false,
                        // $include_first = false, $content_type = false,
                        // $li_class_name = false) {
                        // $li_class_name = false, $add_ids = false, $orderby =
                        // false, $only_with_content = false
                        $children_of_the_main_parent1 = array();
                        // $children_of_the_main_parent1 = get_category_children($item ['id'], $type = 'category', $visible_on_frontend = false);
                        // p($children_of_the_main_parent1 );
                        $remove_ids[] = $item['id'];
                        if (!empty($children_of_the_main_parent1)) {
                            foreach ($children_of_the_main_parent1 as $children_of_the_main_par) {

                                // $remove_ids[] = $children_of_the_main_par;
                                // $children = CI::model ( 'content'
                                // )->self::html_tree (
                                // $children_of_the_main_par, $link, $active_ids,
                                // $active_code, $remove_ids, $removed_ids_code,
                                // $ul_class_name, false, $content_type,
                                // $li_class_name, $add_ids, $orderby,
                                // $only_with_content, $visible_on_frontend );
                            }
                        }

                        $children = self::html_tree($item['id'], $link, $active_ids, $active_code, $remove_ids, $removed_ids_code, $ul_class_name, false, $content_type, $li_class_name, $add_ids = false, $orderby, $only_with_content, $visible_on_frontend, $depth_level_counter, $max_level, $list_tag, $list_item_tag, $active_code_tag);

                        print "</{$list_item_tag}>";
                    }
                }
                if (trim($list_tag) != '') {
                    print "</{$list_tag}>";
                }
            }
        } else {

        }
    }

    static function get_page($category_id)
    {
        $category_id = intval($category_id);
        if ($category_id == 0) {
            return false;
        } else {

        }
        $category = get_category_by_id($category_id);
        if ($category != false) {
            if (isset($category["rel_id"]) and intval($category["rel_id"]) > 0) {
                if ($category["rel"] == 'content') {
                    $res = get_content_by_id($category["rel_id"]);
                    if (isarr($res)) {
                        return $res;
                    }
                }

            }

            if (isset($category["rel_id"]) and intval($category["rel_id"]) == 0 and intval($category["parent_id"]) > 0) {
                $category1 = get_category_parents($category["id"]);
                if (isarr($category1)) {
                    foreach ($category1 as $value) {
                        if (intval($value) != 0) {
                            $category2 = get_category_by_id($value);
                            if (isset($category2["rel_id"]) and intval($category2["rel_id"]) > 0) {
                                if ($category2["rel"] == 'content') {
                                    $res = get_content_by_id($category2["rel_id"]);
                                    if (isarr($res)) {
                                        return $res;
                                    }
                                }

                            }
                            //	d($category2);
                        }
                    }
                }

            }
        }

        //d($res);

    }

    static function get_parents($id = 0, $without_main_parrent = false, $data_type = 'category')
    {

        if (intval($id) == 0) {

            return FALSE;
        }

        $table = MW_TABLE_PREFIX . 'categories';

        $ids = array();

        $data = array();

        if (isset($without_main_parrent) and $without_main_parrent == true) {

            $with_main_parrent_q = " and parent_id<>0 ";
        } else {

            $with_main_parrent_q = false;
        }
        $id = intval($id);
        $q = " select id, parent_id  from $table where id = $id and  data_type='{$data_type}' " . $with_main_parrent_q;

        $taxonomies = \mw\Db::query($q, $cache_id = __FUNCTION__ . crc32($q), $cache_group = 'categories/' . $id);

        //var_dump($q);
        //  var_dump($taxonomies);
        //  exit;

        if (!empty($taxonomies)) {

            foreach ($taxonomies as $item) {

                if (intval($item['id']) != 0) {

                    $ids[] = $item['parent_id'];
                }
                if ($item['parent_id'] != $item['id']) {
                    $next = self::get_parents($item['parent_id'], $without_main_parrent);

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

    static function get_children($parent_id = 0, $type = false, $visible_on_frontend = false)
    {

        $categories_id = intval($parent_id);
        $cache_group = 'categories/' . $categories_id;

        $table = MW_TABLE_PREFIX . 'categories';

        $db_t_content = MW_TABLE_PREFIX . 'content';

        if (isset($orderby) == false) {
            $orderby = array();
            //$orderby[0] = 'updated_on';

            //$orderby[1] = 'DESC';

            $orderby[0] = 'position';

            $orderby[1] = 'asc';
        }

        if (intval($parent_id) == 0) {

            return false;
        }

        $data = array();

        $data['parent_id'] = $parent_id;

        if ($type != FALSE) {

            $data['data_type'] = $type;

            $type_q = " and data_type='$type'   ";
        } else {
            $type = 'category_item';
            $data['data_type'] = $type;

            $type_q = " and data_type='$type'   ";
        }

        $visible_on_frontend_q = false;
        //$save = $this->categoriesGet ( $data = $data, $orderby = $orderby );

        $cache_group = 'categories/' . $parent_id;
        $q = " SELECT id,  parent_id FROM $table WHERE parent_id=$parent_id   ";
        //var_dump($q);
        $q_cache_id = __FUNCTION__ . crc32($q);
        //var_dump($q_cache_id);
        $save = \mw\Db::query($q, $q_cache_id, $cache_group);

        //$save = $this->getSingleItem ( $parent_id );
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


    static function get_for_content($content_id, $data_type = 'categories')
    {
        if (intval($content_id) == 0) {

            return false;
        }


        $get_category_items = get_category_items('&rel=content&rel_id=' . ($content_id));
        $include_parents = array();
        $include_parents_str = '';
        if (!empty($get_category_items)) {
            foreach ($get_category_items as $get_category_item) {
                if (isset($get_category_item['parent_id'])) {
                    $include_parents[] = $get_category_item['parent_id'];
                }
            }

        }


        // d($include_parents);
        $get_category = get_categories('data_type=category&rel=content&rel_id=' . ($content_id));
        if (empty($get_category)) {
            $get_category = array();
        }
        if (!empty($include_parents)) {
            $include_parents_str = 'data_type=category&rel=content&ids=' . implode(',', $include_parents);
            $get_category2 = get_categories($include_parents_str);

            if (!empty($get_category2)) {
                foreach ($get_category2 as $item) {
                    $get_category[] = $item;
                }

            }


            //  d($get_category2 );
        }

        array_unique($get_category);
        if (empty($get_category)) {
            return false;
        }

        return $get_category;
        $function_cache_id = false;

        $args = func_get_args();

        foreach ($args as $k => $v) {

            $function_cache_id = $function_cache_id . serialize($k) . serialize($v);
        }
        $content_id = intval($content_id);
        $cache_group = 'content/' . $content_id;

        $function_cache_id = __FUNCTION__ . crc32($function_cache_id);

        $cache_content = cache_get_content($function_cache_id, $cache_group);

        if (($cache_content) != false) {

            return $cache_content;
        }

        $table = MW_TABLE_PREFIX . 'categories';
        $table_items = MW_TABLE_PREFIX . 'categories_items';

        $data = array();

        $data['rel'] = 'content';

        $data['rel_id'] = $content_id;
        $data_type_q = false;
        if ($data_type == 'categories') {
            $data['data_type'] = 'category_item';
            $data_type_q = "and data_type = 'category_item' ";
        }

        if ($data_type == 'tags') {
            $data['data_type'] = 'tag_item';
            $data_type_q = "and data_type = 'tag_item' ";
        }

        $q = "select parent_id from $table_items where  rel='content' and rel_id=$content_id  " . $data_type_q;
        // var_dump($q);
        $data = \mw\Db::query($q, __FUNCTION__ . crc32($q), $cache_group = 'content/' . $content_id);
        // var_dump ( $data );
        $results = false;
        if (!empty($data)) {
            $results = array();
            foreach ($data as $item) {
                $results[] = $item['parent_id'];
            }
            $results = array_unique($results);
        }
        cache_save($results, $function_cache_id, $cache_group);
        return $results;
    }










}