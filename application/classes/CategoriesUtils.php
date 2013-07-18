<?php



class CategoriesUtils {


    /**
     * `
     *
     * Prints the selected categories as an <UL> tree, you might pass several
     * options for more flexibility
     *
     * @param
     *        	array
     *
     * @param
     *        	boolean
     *
     * @author Peter Ivanov
     *
     * @version 1.0
     *
     * @since Version 1.0
     *
     */
    static function html_tree($parent, $link = false, $active_ids = false, $active_code = false, $remove_ids = false, $removed_ids_code = false, $ul_class_name = false, $include_first = false, $content_type = false, $li_class_name = false, $add_ids = false, $orderby = false, $only_with_content = false, $visible_on_frontend = false, $depth_level_counter = 0, $max_level = false, $list_tag = false, $list_item_tag = false, $active_code_tag = false) {

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

                $sql = "SELECT * from $table where id=$parent ";
                $sql = $sql. " and data_type='category'   and is_deleted='n'  ";
                $sql = $sql. "$remove_ids_q  $add_ids_q $inf_loop_fix  ";
                $sql = $sql. " group by id order by {$orderby [0]}  {$orderby [1]}  $hard_limit";

                // $sql = "SELECT * from $table where id=$parent  and data_type='category'   $remove_ids_q    $inf_loop_fix group by id   ";

            } else {

                $sql = "SELECT * from $table where parent_id=$parent and data_type='category' and is_deleted='n' ";
                $sql = $sql. "$remove_ids_q $add_ids_q $inf_loop_fix group by id order by {$orderby [0]}  {$orderby [1]}   $hard_limit";

            }
        } else {

            if ($include_first == true) {

                $sql = "SELECT * from $table where id=$parent  and is_deleted='n'  ";
                $sql = $sql. "$remove_ids_q $add_ids_q   $inf_loop_fix group by id order by {$orderby [0]}  {$orderby [1]}  $hard_limit";
            } else {

                $sql = "SELECT * from $table where parent_id=$parent and is_deleted='n' and data_type='category' and (categories_content_type='$content_type' or categories_content_type='inherit' ) ";
                $sql = $sql." $remove_ids_q  $add_ids_q $inf_loop_fix group by id order by {$orderby [0]}  {$orderby [1]}   $hard_limit";
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
        $q = db_query($sql, $cache_id = 'html_tree_parent_cats_q_' . crc32($sql), 'categories/' . intval($parent));
        //$q = db_query($sql);

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

                            $output= "<{$list_item_tag} class='$li_class_name  category_element depth-{$depth_level_counter} item_{$iid}'  value='{$item['id']}' data-item-id='{$item['id']}' data-category-id='{$item['id']}'  data-to-table='{$item['rel']}'  data-to-table-id='{$item['rel_id']}'  data-categories-type='{$item['data_type']}'  {active_code_tag}  >";
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

                                print $output.$item['title'];
                            } else {

                                print $output.$to_print;
                            }
                        } else {

                            print $output.$item['title'];
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

}