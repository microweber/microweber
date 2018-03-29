<?php

namespace Microweber\Providers\Categories\Helpers;


// this class need to be replaced with KnpCategoryTreeRenderer class when all features are implemented in the new class


use Knp\Menu\MenuFactory;
use Microweber\Providers\Categories\Helpers\CategoryTreeData;
use Microweber\Providers\Categories\Helpers\KnpCustomListRenderer as ListRenderer;


class LegacyCategoryTreeRenderer
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
    public function render($params = false)
    {


        //return $this->renderer->render($params);


        //this whole code must be reworked

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

//        if (!isset($params['no_cache'])) {
//            if ($nest_level_orig == 0) {
//                $cache_content = $this->app->cache_manager->get($function_cache_id, $cache_group);
//                $cache_content = false;
//                if (($cache_content) != false) {
//                  echo $cache_content;
//
//                 return;
//                }
//            }
//        }

        $link = isset($params['link']) ? $params['link'] : false;
        if ($link == false) {
            $link = "<a href='{categories_url}' data-category-id='{id}'  {active_code} class='{active_class} {nest_level} {active_class}'>{title}</a>";
        }
        $link = str_replace('data-page-id', 'data-category-id', $link);

        $active_ids = isset($params['active_ids']) ? $params['active_ids'] : array($active_cat);
        if (isset($params['active_code'])) {
            $active_code = $params['active_code'];
        } else {
            $active_code = ' active ';
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
        $ul_class_name_deep = '';
        if (isset($params['class'])) {
            $ul_class_name = $params['class'];
        }
        if (isset($params['ul_class'])) {
            $ul_class_name = $params['ul_class'];
        }

        if (isset($params['ul_class_name'])) {
            $ul_class_name = $params['ul_class_name'];
        }
        if (isset($params['ul_class_name_deep'])) {
            $ul_class_name_deep = $params['ul_class_name_deep'];
        }
        if (isset($params['li_class'])) {
            $li_class_name = $params['li_class'];
        }
        if (isset($params['users_can_create_content'])) {
            $users_can_create_content = $params['users_can_create_content'];
        } else {
            $users_can_create_content = false;
        }
        if (isset($params['li_class_name'])) {
            $li_class_name = $params['li_class_name'];
        }

        if (!isset($li_class_name)) {
            $li_class_name = false;
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
                    $str0 = $str0 . '&users_can_create_content=' . $users_can_create_content;
                    // unset( $cat_get_params['parent_id']);
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
        $list_tag = false;
        if (isset($params['list_tag'])) {
            $list_tag = $params['list_tag'];
        }
        $list_item_tag = false;
        if (isset($params['list_item_tag'])) {
            $list_item_tag = $params['list_item_tag'];
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

             $cont_check = true;
            if ($cat_get_params['rel_type'] == 'content' and $cat_get_params['rel_id']) {
                $cont_check = $this->app->content_manager->get_by_id($cat_get_params['rel_id']);
                if (!$cont_check) {
                   return;
                }
            }
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

            // $cat_get_params['no_cache'] = 1;

            if ($users_can_create_content != false) {
                $cat_get_params['users_can_create_content'] = $users_can_create_content;
            }

            //  $fors = array();
            if ($cont_check != false) {
                $fors = $this->app->database_manager->get($cat_get_params);

            } else {

                return;
            }

        }


       //d($fors);
        if ($fors != false and is_array($fors) and !empty($fors)) {
            foreach ($fors as $cat) {
                $tree_only_ids[] = $cat['id'];
            }
        }

        ob_start();

        if ($tree_only_ids != false) {
            if(!$parent){

                foreach ($tree_only_ids as $tree_only_id){
                    $this->html_tree($tree_only_id, $link, $active_ids, $active_code, $remove_ids, $removed_ids_code, $ul_class_name, $include_first, $content_type, $li_class_name, $add_ids, $orderby, $only_with_content = false, $visible_on_frontend = false, $depth_level_counter, $max_level, $list_tag, $list_item_tag, $active_code_tag, $ul_class_name_deep, $tree_only_ids);

                }
            }else {
                $this->html_tree($parent, $link, $active_ids, $active_code, $remove_ids, $removed_ids_code, $ul_class_name, $include_first, $content_type, $li_class_name, $add_ids, $orderby, $only_with_content = false, $visible_on_frontend = false, $depth_level_counter, $max_level, $list_tag, $list_item_tag, $active_code_tag, $ul_class_name_deep, $tree_only_ids);

            }


        } elseif ($skip123 == false) {



            $this->html_tree($parent, $link, $active_ids, $active_code, $remove_ids, $removed_ids_code, $ul_class_name, $include_first, $content_type, $li_class_name, $add_ids, $orderby, $only_with_content = false, $visible_on_frontend = false, $depth_level_counter, $max_level, $list_tag, $list_item_tag, $active_code_tag, $ul_class_name_deep);
        }/* else if (!$parent  and !$fors) {
d($fors);
            $this->html_tree($parent, $link, $active_ids, $active_code, $remove_ids, $removed_ids_code, $ul_class_name, $include_first, $content_type, $li_class_name, $add_ids, $orderby, $only_with_content = false, $visible_on_frontend = false, $depth_level_counter, $max_level, $list_tag, $list_item_tag, $active_code_tag, $ul_class_name_deep, $tree_only_ids);
        }*/ else {

            if ($fors != false and is_array($fors) and !empty($fors)) {

                //    $this->html_tree($parent, $link, $active_ids, $active_code, $remove_ids, $removed_ids_code, $ul_class_name, $include_first, $content_type, $li_class_name, $add_ids, $orderby, $only_with_content = false, $visible_on_frontend = false, $depth_level_counter, $max_level, $list_tag, $list_item_tag, $active_code_tag, $ul_class_name_deep);

                //

                foreach ($fors as $cat) {

                    $this->html_tree($cat['id'], $link, $active_ids, $active_code, $remove_ids, $removed_ids_code, $ul_class_name, $include_first = true, $content_type, $li_class_name, $add_ids, $orderby, $only_with_content = false, $visible_on_frontend = false, $depth_level_counter, $max_level, $list_tag, $list_item_tag, $active_code_tag, $ul_class_name_deep);
                }
            }
        }

        $content = ob_get_contents();
        if ($nest_level_orig == 0) {
            //  $this->app->cache_manager->save($content, $function_cache_id, $cache_group);
        }

        ob_end_clean();
        echo $content;

        return;
    }

    private $passed_parent_ids = array();
    private $passed_ids = array();

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
    private function html_tree($parent, $link = false, $active_ids = false, $active_code = false, $remove_ids = false, $removed_ids_code = false, $ul_class_name = false, $include_first = false, $content_type = false, $li_class_name = false, $add_ids = false, $orderby = false, $only_with_content = false, $visible_on_frontend = false, $depth_level_counter = 0, $max_level = false, $list_tag = false, $list_item_tag = false, $active_code_tag = false, $ul_class_deep = false, $only_ids = false)
    {
        $db_t_content = $this->app->category_manager->tables['content'];

        $table = $db_categories = $this->app->category_manager->tables['categories'];

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
        $remove_ids_q = false;
        if (isset($remove_ids) and !is_array($remove_ids)) {
            $temp = intval($remove_ids);

            $remove_ids_q = " and id not in ($temp) ";

        }

        if(!empty($this->passed_ids)){
            if(!is_array($remove_ids)){
                $remove_ids = array();
            }
           $remove_ids = array_merge($remove_ids, $this->passed_parent_ids);
    //    $remove_ids = array_merge($remove_ids, $this->passed_ids);

        }
        $this->passed_parent_ids[] = $parent;


        if (is_array($remove_ids) and !empty($remove_ids)) {

            $remove_ids_q_in = implode(',', $remove_ids);
            if ($remove_ids_q_in != '') {
                $remove_ids_q = $remove_ids_q." and id not in ($remove_ids_q_in) ";
            }
        } else {
            $remove_ids_q = false;
        }





        if (!empty($add_ids)) {
            $add_ids_q = implode(',', $add_ids);

            $add_ids_q = " and id in ($add_ids_q) ";
        } else {
            $add_ids_q = false;
        }

        if ($max_level != false and $depth_level_counter != false) {
            if (intval($depth_level_counter) >= intval($max_level)) {
                echo '';

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


        $table = $this->app->database_manager->real_table_name($table);
        $content_type = addslashes($content_type);
        $hard_limit = ' LIMIT 300 ';
        $inf_loop_fix = "  and $table.id!=$table.parent_id  ";
        //	$inf_loop_fix = "     ";

        if ($only_ids != false) {
            if (is_string($only_ids)) {
                $only_ids = explode(',', $only_ids);
            }

            $sql = "SELECT * FROM $table WHERE id IN (" . implode(',', $only_ids) . ') ';
            $sql = $sql . " and data_type='category'   and is_deleted=0  ";
            $sql = $sql . "$remove_ids_q  $add_ids_q $inf_loop_fix  ";
            $sql = $sql . " group by id order by {$orderby [0]}  {$orderby [1]}  $hard_limit";
        } elseif ($content_type == false) {
            if ($include_first == true) {
                $sql = "SELECT * FROM $table WHERE id=$parent ";
                $sql = $sql . " and data_type='category'   and is_deleted=0  ";
                $sql = $sql . "$remove_ids_q  $add_ids_q $inf_loop_fix  ";
                $sql = $sql . " group by id order by {$orderby [0]}  {$orderby [1]}  $hard_limit";
            } else {
                $sql = "SELECT * FROM $table WHERE parent_id=$parent AND data_type='category' AND is_deleted=0 ";
                $sql = $sql . "$remove_ids_q $add_ids_q $inf_loop_fix group by id order by {$orderby [0]}  {$orderby [1]}   $hard_limit";
                //   d($sql);

            }

        } else {

            if ($include_first == true) {
                $sql = "SELECT * FROM $table WHERE id=$parent  AND is_deleted=0  ";
                $sql = $sql . "$remove_ids_q $add_ids_q   $inf_loop_fix group by id order by {$orderby [0]}  {$orderby [1]}  $hard_limit";
            } else {
                $sql = "SELECT * FROM $table WHERE parent_id=$parent AND is_deleted=0 AND data_type='category' AND (category_subtype='$content_type' OR category_subtype='inherit' ) ";
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


        $q = $this->app->database_manager->query($sql, $cache_id = 'html_tree_parent_cats_q_' . md5($sql), 'categories/' . intval($parent));
        //$q = $this->app->database_manager->query($sql, false);

        $result = $q;

        $only_with_content2 = $only_with_content;

        $do_not_show_next = false;

        $chosen_categories_array = array();

        if (isset($result) and is_array($result) and !empty($result)) {
            ++$depth_level_counter;
            $i = 0;

            $do_not_show = false;

            if ($do_not_show == false) {
                $print1 = false;
                if (trim($list_tag) != '') {
                    if ($ul_class_name == false) {
                        $print1 = "<{$list_tag}  class='{active_class} category_tree depth-{$depth_level_counter}'>";
                    } else {
                        $cl_name = $ul_class_name;
                        if ($depth_level_counter > 1) {
                            $cl_name = $ul_class_deep;
                        }
                        $print1 = "<{$list_tag} class='{active_class} $cl_name depth-{$depth_level_counter}'>";
                    }
                }

                if (intval($parent) != 0 and intval($parent) == intval(CATEGORY_ID)) {
                    $print1 = str_replace('{active_class}', 'active', $print1);
                }
                $print1 = str_replace('{active_class}', '', $print1);

                echo $print1;

                foreach ($result as $item) {
                    if ($only_with_content == true) {
                        $do_not_show = false;

                        $check_in_content = false;
                        $children_content = array();

                        $do_not_show = false;
                        if (!empty($children_content)) {
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
//$safe_title = url_title( $item['title']);
                        if ($li_class_name == false) {
                            $output = "<{$list_item_tag} class='{active_class} category_element depth-{$depth_level_counter} item_{$iid}' data-filter='filter-{$item['id']}'   value='{$item['id']}' data-category-id='{$item['id']}' data-category-parent-id='{$item['parent_id']}' data-item-id='{$item['id']}'  data-to-table='{$item['rel_type']}'  data-to-table-id='{$item['rel_id']}'    data-categories-type='{$item['data_type']}' {active_code_tag} title='{title_slashes}'>";
                        } else {
                            $output = "<{$list_item_tag} class='{active_class} $li_class_name  category_element depth-{$depth_level_counter} item_{$iid}'  data-filter='filter-{$item['id']}'   value='{$item['id']}' data-item-id='{$item['id']}' data-category-id='{$item['id']}'  data-to-table='{$item['rel_type']}'  data-to-table-id='{$item['rel_id']}'  data-categories-type='{$item['data_type']}'  {active_code_tag} title='{title_slashes}' >";
                        }
                    }

                    if (intval($item['id']) != 0 and intval($item['id']) == intval(CATEGORY_ID)) {
                        $output = str_replace('{active_class}', 'active', $output);
                    } else {
                        $output = str_replace('{active_class}', '', $output);
                    }

                    if ($do_not_show == false) {
                        if ($link != false) {
                            $to_print = false;

                            $empty1 = intval($depth_level_counter);
                            $empty = '';
                            for ($i1 = 0; $i1 < $empty1; ++$i1) {
                                $empty = $empty . '&nbsp;&nbsp;';
                            }

                            $ext_classes = '';


                            if (isset($item['parent_id']) and intval($item['parent_id']) > 0) {
                                $ext_classes .= ' have-parent';
                            }


                            $to_print = str_replace('{id}', $item['id'], $link);

                            if (stristr($link, '{items_count}')) {
                                $to_print = str_ireplace('{items_count}', $this->app->category_manager->get_items_count($item['id']), $to_print);
                            }

                            $to_print = str_ireplace('{url}', $this->app->category_manager->link($item['id']), $to_print);
                            $to_print = str_ireplace('{link}', $this->app->category_manager->link($item['id']), $to_print);

                            $to_print = str_replace('{exteded_classes}', $ext_classes, $to_print);

                            $to_print = str_ireplace('{categories_url}', $this->app->category_manager->link($item['id']), $to_print);
                            $to_print = str_ireplace('{nest_level}', 'depth-' . $depth_level_counter, $to_print);

                            $to_print = str_ireplace('{title}', $item['title'], $to_print);
                            $to_print = str_ireplace('{title_slashes}', addslashes($item['title']), $to_print);
                            $to_print = str_replace('{content_link_class}', '', $to_print);

                            $output = str_replace('{title_slashes}', addslashes($item['title']), $output);

                            $output = str_replace('{content_link_class}', '', $output);

                            $active_class = ' ';

                            $active_parent_class = '';
                            //if(isset($item['parent']) and intval($item['parent']) != 0){
                            if (intval($item['parent_id']) != 0 and intval($item['parent_id']) == intval(CATEGORY_ID)) {
                                $active_parent_class = 'active-parent';
                                $active_class = '';
                            } elseif (intval($item['id']) != 0 and intval($item['id']) == intval(CATEGORY_ID)) {
                                $active_parent_class = 'active-parent';
                                $active_class = 'active';
                            } else {
                                $active_parent_class = '';
                            }
                            $active_class = str_replace('"', ' ', $active_class);

                            $to_print = str_replace('{active_class}', $active_class, $to_print);
                            $to_print = str_replace('{active_parent_class}', $active_parent_class, $to_print);

                            if (isset($item['category_subtype'])) {
                                $to_print = str_ireplace('{category_subtype}', trim($item['category_subtype']), $to_print);
                            }

                            $to_print = str_replace('{empty}', $empty, $to_print);

                            $active_found = false;

                            if (is_string($active_ids)) {
                                $active_ids = explode(',', $active_ids);
                            }

                            if (is_array($active_ids) == true) {
                                $active_ids = array_trim($active_ids);

                                foreach ($active_ids as $value_active_cat) {
                                    if ($value_active_cat != '') {
                                        $value_active_cat = intval($value_active_cat);
                                        if (intval($item['id']) == $value_active_cat) {
                                            $active_found = $value_active_cat;
                                        }
                                    }
                                }

                                if ($active_found == true) {
                                    $to_print = str_replace('{active_code}', $active_code, $to_print);
                                    $to_print = str_replace('{active_class}', $active_class, $to_print);
                                    $to_print = str_replace('{active_code_tag}', $active_code_tag, $to_print);
                                    $output = str_replace('{active_code_tag}', $active_code_tag, $output);
                                } else {
                                    $to_print = str_replace('{active_code}', '', $to_print);
                                }
                            } else {
                                $to_print = str_ireplace('{active_code}', '', $to_print);
                            }
                            $output = str_replace('{active_code_tag}', '', $output);
                            $output = str_replace('{title_slashes}', '', $output);

                            $output = str_replace('{exteded_classes}', $ext_classes, $output);

                            $to_print = str_replace('{items_count}', '', $to_print);
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
                                echo $output . $item['title'];
                            } else {
                                echo $output . $to_print;
                            }
                        } else {
                            echo $output . $item['title'];
                        }

                        $children_of_the_main_parent1 = array();

                        if (!isset($remove_ids) or !is_array($remove_ids)) {
                            $remove_ids = array();
                        }
                        $remove_ids[] = $item['id'];

                        if ($only_ids == false) {
                            if (!in_array($item['id'], $this->passed_parent_ids)) {
                                $this->passed_parent_ids[] = $item['id'];

                            } else {
                                return;
                            }
                            $this->html_tree($item['id'], $link, $active_ids, $active_code, $remove_ids, $removed_ids_code, $ul_class_name, false, $content_type = false, $li_class_name, $add_ids = false, $orderby, $only_with_content, $visible_on_frontend, $depth_level_counter, $max_level, $list_tag, $list_item_tag, $active_code_tag, $ul_class_deep);
                        }
                        echo "</{$list_item_tag}>";
                    }
                }
                if (trim($list_tag) != '') {
                    echo "</{$list_tag}>";
                }
            }
        }
    }


}


