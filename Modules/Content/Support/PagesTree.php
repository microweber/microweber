<?php

namespace Modules\Content\Support;

use Illuminate\Support\Facades\DB;

class PagesTree
{
    /** @var \MicroweberPackages\App\LaravelApplication */
    public $app;

    public static $skip_pages_starting_with_url = ['admin', 'api', 'module'];
    public static $precached_links = array();

    public function __construct($app = null)
    {
        if (is_object($app)) {
            $this->app = $app;
        } else {
            $this->app = mw();
        }
    }

    public function get($parent = 0, $link = false, $active_ids = false, $active_code = false, $remove_ids = false, $removed_ids_code = false, $ul_class_name = false, $include_first = false)
    {
        $params2 = array();
        $params = false;
        $output = '';
        if (is_integer($parent)) {
        } else {
            $params = $parent;
            if (is_string($params)) {
                $params = parse_str($params, $params2);
                $params = $params2;
                extract($params);
            }
            if (is_array($params)) {
                $parent = 0;
                extract($params);
            }
        }
        if (!defined('CONTENT_ID')) {
            $this->app->content_manager->define_constants();
        }

        $cache_id_params = $params;
        if (isset($cache_id_params['link']) and is_callable($cache_id_params['link'])) {
            unset($cache_id_params['link']);
            $params['no_cache'] = true;
        }

        $function_cache_id = false;
        $args = func_get_args();
        foreach ($args as $k => $v) {
            $function_cache_id = $function_cache_id . serialize($k) . serialize($v);
        }
        $function_cache_id = $function_cache_id . serialize($cache_id_params);

        $function_cache_id = __FUNCTION__ . crc32($function_cache_id) . PAGE_ID . $parent;
        if ($parent == 0) {
            $cache_group = 'content/global';
        } else {
            $cache_group = 'categories/global';
        }
        if (isset($include_categories) and $include_categories == true) {
            $cache_group = 'categories/global';
        }

        $nest_level = 0;

        if (isset($params['nest_level'])) {
            $nest_level = $params['nest_level'];
        }

        $nest_level_orig = $nest_level;

        $max_level = false;
        if (isset($params['max_level'])) {
            $max_level = $params['max_level'];
        } elseif (isset($params['maxdepth'])) {
            $max_level = $params['max_level'] = $params['maxdepth'];
        } elseif (isset($params['depth'])) {
            $max_level = $params['max_level'] = $params['depth'];
        }

        if ($max_level != false) {
            if (intval($nest_level) >= intval($max_level)) {
                return '';
            }
        }

        $is_shop = '';
        if (isset($params['is_shop'])) {
            if ($params['is_shop'] == 'y') {
                $params['is_shop'] = 1;
            } elseif ($params['is_shop'] == 'n') {
                $params['is_shop'] = 0;
            }

            $is_shop = $this->app->database_manager->escape_string($params['is_shop']);
            $is_shop = " and is_shop='{$is_shop} '";
            $include_first = false;
        }
        $ul_class = 'pages_tree';
        if (isset($params['ul_class'])) {
            $ul_class_name = $ul_class = $params['ul_class'];
        }
        $content_link_class = 'mw-tree-content-link';
        if (isset($params['content_link_class'])) {
            $content_link_class = $params['content_link_class'];
        }

        $li_class = 'pages_tree_item';
        if (isset($params['li_class'])) {
            $li_class = $params['li_class'];
        }
        if (isset($params['ul_tag'])) {
            $list_tag = $params['ul_tag'];
        }
        if (isset($params['li_tag'])) {
            $list_item_tag = $params['li_tag'];
        }
        if (isset($params['include_categories'])) {
            $include_categories = $params['include_categories'];
        }
        $include_all_content = false;
        if (isset($params['include_all_content'])) {
            $include_all_content = $params['include_all_content'];
        }
        ob_start();

        $table = 'content';
        $par_q = '';
        if ($parent == false) {
            $parent = (0);
        } else {
            $parent = intval($parent);
            $par_q = " parent=$parent    and  ";
        }

        if ($include_first == true) {
            $content_type_q = " and content_type='page'  ";
            if ($include_all_content) {
                $content_type_q = ' ';
            }

            $sql = "SELECT * from $table where  id={$parent}    and   is_deleted=0 " . $content_type_q . $is_shop . '  order by position desc  limit 0,1';
        } else {
            $content_type_q = "  content_type='page'  ";
            if ($include_all_content) {
                $content_type_q = ' ';
            }

            $sql = "SELECT * from $table where  " . $par_q . $content_type_q . "   and   is_deleted=0 $is_shop  order by position desc limit 0,100";
        }

        $cid = __FUNCTION__ . crc32($sql);
        $cidg = 'content/' . $parent;
        if (!is_array($params)) {
            $params = array();
        }
        if (isset($append_to_link) == false) {
            $append_to_link = '';
        }
        if (isset($id_prefix) == false) {
            $id_prefix = '';
        }

        if (isset($link) == false) {
            $link = '<span data-page-id="{id}" class="pages_tree_link {nest_level} {active_class} {active_parent_class}" href="{link}' . $append_to_link . '">{title}</span>';
        }

        if (isset($list_tag) == false) {
            $list_tag = 'ul';
        }

        if (isset($active_code_tag) == false) {
            $active_code_tag = '';
        }

        if (isset($list_item_tag) == false) {
            $list_item_tag = 'li';
        }

        if (isset($params['remove_ids'])) {
            $remove_ids = $params['remove_ids'];
        }

        if (isset($remove_ids) and is_string($remove_ids)) {
            $remove_ids = explode(',', $remove_ids);
        }

        if (isset($active_ids)) {
            $active_ids = $active_ids;
        }

        if (isset($active_ids) and is_string($active_ids)) {
            $active_ids = explode(',', $active_ids);
            if (is_array($active_ids) == true) {
                foreach ($active_ids as $idk => $idv) {
                    $active_ids[$idk] = intval($idv);
                }
            }
        }

        $the_active_class = 'active';
        if (isset($params['active_class'])) {
            $the_active_class = $params['active_class'];
        }

        if (!$include_all_content) {
            $params['content_type'] = 'page';
        }

        $include_first_set = false;

        if ($include_first == true) {
            $include_first_set = 1;
            $include_first = false;
            $include_first_set = $parent;
            if (isset($params['include_first'])) {
                unset($params['include_first']);
            }
        } else {
            $params['parent'] = $parent;
        }
        if (isset($params['is_shop']) and $params['is_shop'] == 1) {
            if (isset($params['parent']) and $params['parent'] == 0) {
                unset($params['parent']);
            }

            if (isset($params['parent']) and $params['parent'] == 'any') {
                unset($params['parent']);
            }
        } else {
            if (isset($params['parent']) and $params['parent'] == 'any') {
                $params['parent'] = 0;
            }
        }

        $params['limit'] = 500;
        $params['orderby'] = 'position desc';
        $params['curent_page'] = 1;
        $params['is_deleted'] = 0;
        $params['cache_group'] = false;
        $params['no_cache'] = true;

        $skip_pages_with_no_categories = false;
        $skip_pages_from_tree = false;

        if (isset($params['skip_sub_pages']) and $params['skip_sub_pages'] != '') {
            $skip_pages_from_tree = $params['skip_sub_pages'];
        }
        if (isset($params['skip-static-pages']) and $params['skip-static-pages'] != false) {
            $skip_pages_with_no_categories = 1;
        }

        $params2 = $params;

        if (isset($params2['id'])) {
            unset($params2['id']);
        }
        if (isset($params2['link'])) {
            unset($params2['link']);
        }

        if ($include_first_set != false) {
            $q = $this->app->content_manager->get('id=' . $include_first_set);
        } else {
            $q = $this->app->content_manager->get($params2);
        }
        $result = $q;
        if (is_array($result) and !empty($result)) {
            ++$nest_level;
            if (trim($list_tag) != '') {
                if ($ul_class_name == false) {
                    echo "<{$list_tag} class='pages_tree depth-{$nest_level}'>";
                } else {
                    echo "<{$list_tag} class='{$ul_class_name} depth-{$nest_level}'>";
                }
            }
            $res_count = 0;
            foreach ($result as $item) {
                if (is_array($item) != false and isset($item['title']) and $item['title'] != null) {
                    $skip_me_cause_iam_removed = false;
                    if (is_array($remove_ids) == true) {
                        foreach ($remove_ids as $idk => $idv) {
                            $remove_ids[$idk] = intval($idv);
                        }

                        if (in_array($item['id'], $remove_ids)) {
                            $skip_me_cause_iam_removed = true;
                        }
                    }

                    if ($skip_pages_with_no_categories == true) {
                        if (isset($item ['subtype']) and $item ['subtype'] != 'dynamic') {
                            $skip_me_cause_iam_removed = true;
                        }
                    }
                    if ($skip_me_cause_iam_removed == false) {
                        $output = $output . $item['title'];
                        $content_type_li_class = false;
                        switch ($item ['subtype']) {
                            case 'dynamic' :
                                $content_type_li_class = 'have_category';
                                break;
                            case 'module' :
                                $content_type_li_class = 'is_module';
                                break;
                            default :
                                $content_type_li_class = 'is_page';
                                break;
                        }

                        if (isset($item ['layout_file']) and stristr($item ['layout_file'], 'blog')) {
                            $content_type_li_class .= ' is_blog';
                        }

                        if ($item['is_home'] == 1) {
                            $content_type_li_class .= ' is_home';
                        }
                        $st_str = '';
                        $st_str2 = '';
                        $st_str3 = '';
                        if (isset($item['subtype']) and trim($item['subtype']) != '') {
                            $st_str = " data-subtype='{$item['subtype']}' ";
                        }

                        if (isset($item['subtype_value']) and trim($item['subtype_value']) != '') {
                            $st_str2 = " data-subtype-value='{$item['subtype_value']}' ";
                        }

                        if (isset($item['is_shop']) and trim($item['is_shop']) == 1) {
                            $st_str3 = ' data-is-shop=true ';
                            $content_type_li_class .= ' is_shop';
                        }
                        $iid = $item['id'];

                        $to_pr_2 = "<{$list_item_tag} class='{$li_class} $content_type_li_class {active_class} {active_parent_class} depth-{$nest_level} item_{$iid} {exteded_classes} menu-item-id-{$item['id']}' data-page-id='{$item['id']}' value='{$item['id']}'  data-item-id='{$item['id']}'  {active_code_tag} data-parent-page-id='{$item['parent']}' {$st_str} {$st_str2} {$st_str3}  title='" . addslashes($item['title']) . "' >";

                        if ($link != false) {
                            $active_parent_class = '';
                            if (intval($item['parent']) != 0 and intval($item['parent']) == intval(main_page_id())) {
                                $active_parent_class = 'active-parent';
                            } elseif (intval($item['id']) == intval(main_page_id())) {
                                $active_parent_class = 'active-parent';
                            } else {
                                $active_parent_class = '';
                            }

                            if ($item['id'] == content_id()) {
                                $active_class = 'active';
                            } elseif (isset($active_ids) and !is_array($active_ids) and $item['id'] == $active_ids) {
                                $active_class = 'active';
                            }
                            if (isset($active_ids) and is_array($active_ids) and in_array($item['id'], $active_ids)) {
                                $active_class = 'active';
                            } elseif ($item['id'] == page_id()) {
                                $active_class = 'active';
                            } elseif ($item['id'] == post_id()) {
                                $active_class = 'active';
                            } elseif (category_id() != false and intval($item['subtype_value']) != 0 and $item['subtype_value'] == category_id()) {
                                $active_class = 'active';
                            } else {
                                $active_class = '';
                            }

                            $ext_classes = '';
                            if ($res_count == 0) {
                                $ext_classes .= ' first-child ';
                                $ext_classes .= ' child-' . $res_count . '';
                            } elseif (!isset($result[$res_count + 1])) {
                                $ext_classes .= ' last-child';
                                $ext_classes .= ' child-' . $res_count . '';
                            } else {
                                $ext_classes .= ' child-' . $res_count . '';
                            }

                            if (isset($item['parent']) and intval($item['parent']) > 0) {
                                $ext_classes .= ' have-parent';
                            }

                            if (isset($item['subtype_value']) and intval($item['subtype_value']) != 0) {
                                $ext_classes .= ' have-category';
                            }

                            if (isset($item['is_active']) and $item['is_active'] == 'n') {
                                $ext_classes = $ext_classes . ' content-unpublished ';
                            }

                            $ext_classes = trim($ext_classes);
                            $the_active_class = $active_class;

                            if (is_callable($link)) {
                                $to_print = call_user_func_array($link, array($item));
                            } else {
                                $to_print = $link;
                            }

                            $to_print = str_replace('{id}', $item['id'], $to_print);
                            $to_print = str_replace('{active_class}', $active_class, $to_print);
                            $to_print = str_replace('{active_parent_class}', $active_parent_class, $to_print);
                            $to_print = str_replace('{exteded_classes}', $ext_classes, $to_print);

                            $to_pr_2 = str_replace('{exteded_classes}', $ext_classes, $to_pr_2);
                            $to_pr_2 = str_replace('{active_class}', $active_class, $to_pr_2);
                            $to_pr_2 = str_replace('{active_parent_class}', $active_parent_class, $to_pr_2);

                            $to_print = str_replace('{title}', $item['title'], $to_print);
                            $to_print = str_replace('{nest_level}', 'depth-' . $nest_level, $to_print);
                            $to_print = str_replace('{content_link_class}', $content_link_class, $to_print);
                            $to_print = str_replace('{empty}', '', $to_print);

                            if (strstr($to_print, '{link}')) {
                                $to_print = str_replace('{link}', page_link($item['id']), $to_print);
                            }

                            $empty1 = intval($nest_level);
                            $empty = '';
                            for ($i1 = 0; $i1 < $empty1; ++$i1) {
                                $empty = $empty . '&nbsp;&nbsp;';
                            }
                            $to_print = str_replace('{empty}', $empty, $to_print);

                            if (strstr($to_print, '{tn}')) {
                                $content_img = get_picture($item['id']);
                                if ($content_img) {
                                    $to_print = str_replace('{tn}', $content_img, $to_print);
                                } else {
                                    $to_print = str_replace('{tn}', '', $to_print);
                                }
                            }
                            foreach ($item as $item_k => $item_v) {
                                if (!is_string($item_k) || !is_string($item_v)) {
                                    continue;
                                }
                                $to_print = str_replace('{' . $item_k . '}', $item_v, $to_print);
                            }
                            ++$res_count;
                            if (isset($active_ids) and is_array($active_ids) == true) {
                                $is_there_active_ids = false;
                                foreach ($active_ids as $active_id) {
                                    if (intval($item['id']) == intval($active_id)) {
                                        $is_there_active_ids = true;
                                        $to_print = str_ireplace('{active_code}', $active_code, $to_print);
                                        $to_print = str_ireplace('{active_class}', $the_active_class, $to_print);
                                        $to_pr_2 = str_ireplace('{active_class}', $the_active_class, $to_pr_2);
                                        $to_pr_2 = str_ireplace('{active_code_tag}', $active_code_tag, $to_pr_2);
                                        $to_pr_2 = str_replace('{empty}', '', $to_pr_2);
                                    }
                                }
                            } elseif (isset($active_ids) and !is_array($active_ids)) {
                                if (intval($item['id']) == intval($active_ids)) {
                                    $is_there_active_ids = true;
                                    $to_print = str_ireplace('{active_code}', $active_code, $to_print);
                                    $to_print = str_ireplace('{active_class}', $the_active_class, $to_print);
                                    $to_pr_2 = str_ireplace('{active_class}', $the_active_class, $to_pr_2);
                                    $to_pr_2 = str_ireplace('{active_code_tag}', $active_code_tag, $to_pr_2);
                                    $to_pr_2 = str_replace('{empty}', '', $to_pr_2);
                                }
                            }

                            $to_print = str_ireplace('{active_code}', '', $to_print);
                            $to_print = str_ireplace('{active_class}', '', $to_print);
                            $to_pr_2 = str_ireplace('{active_class}', '', $to_pr_2);
                            $to_pr_2 = str_ireplace('{active_code_tag}', '', $to_pr_2);
                            $to_pr_2 = str_ireplace('{content_link_class}', '', $to_pr_2);
                            $to_pr_2 = str_replace('{empty}', '', $to_pr_2);

                            $to_print = str_replace('{exteded_classes}', '', $to_print);
                            $to_print = str_replace('{content_link_class}', '', $to_print);

                            $to_print = str_replace('{empty}', '', $to_print);

                            if ($item['id'] == $item['parent']) {
                                $remove_ids[] = intval($item['id']);
                            }

                            if (is_array($remove_ids) == true) {
                                if (in_array($item['id'], $remove_ids)) {
                                    if ($removed_ids_code == false) {
                                        $to_print = false;
                                    } else {
                                        $remove_ids[] = intval($item['id']);
                                        $to_print = str_ireplace('{removed_ids_code}', $removed_ids_code, $to_print);
                                    }
                                } else {
                                    $to_print = str_ireplace('{removed_ids_code}', '', $to_print);
                                }
                            }
                            $to_pr_2 = str_replace('{active_class}', '', $to_pr_2);
                            $to_pr_2 = str_replace('{exteded_classes}', '', $to_pr_2);

                            echo $to_pr_2;
                            $to_pr_2 = false;
                            echo $to_print;
                        } else {
                            $to_pr_2 = str_ireplace('{active_class}', '', $to_pr_2);
                            $to_pr_2 = str_replace('{exteded_classes}', '', $to_pr_2);
                            $to_pr_2 = str_replace('{active_parent_class}', '', $to_pr_2);

                            echo $to_pr_2;
                            $to_pr_2 = false;
                            echo $item['title'];
                        }

                        if (is_array($params)) {
                            $params['parent'] = $item['id'];
                            if ($max_level != false) {
                                $params['max_level'] = $max_level;
                            }
                            if (isset($params['is_shop'])) {
                                unset($params['is_shop']);
                            }

                            $params['nest_level'] = $nest_level;
                            $params['ul_class_name'] = false;
                            $params['ul_class'] = false;
                            if (isset($include_categories)) {
                                $params['include_categories'] = $include_categories;
                            }

                            if (isset($params['ul_class_deep'])) {
                                $params['ul_class'] = $params['ul_class_deep'];
                            }

                            if (isset($maxdepth)) {
                                $params['maxdepth'] = $maxdepth;
                            }

                            if (isset($params['li_class_deep'])) {
                                $params['li_class'] = $params['li_class_deep'];
                            }

                            if (isset($params['return_data'])) {
                                unset($params['return_data']);
                            }

                            $params['remove_ids'] = $remove_ids;
                            if ($skip_pages_from_tree == false) {
                                if ($item['id'] != $item['parent']) {
                                    $children = $this->get($params);
                                }
                            }
                        } else {
                            if ($skip_pages_from_tree == false) {
                                if ($item['id'] != $item['parent']) {
                                    $children = $this->get(intval($item['id']), $link, $active_ids, $active_code, $remove_ids, $removed_ids_code, $ul_class_name = false);
                                }
                            }
                        }

                        if (isset($include_categories) and $include_categories == true) {
                            $content_cats = array();
                            if (isset($item['subtype_value']) and intval($item['subtype_value']) == true) {
                            }

                            $cat_params = array();
                            if (isset($item['subtype_value']) and intval($item['subtype_value']) != 0) {
                            }

                            if (isset($categories_link)) {
                                $cat_params['link'] = $categories_link;
                            } else {
                                $cat_params['link'] = $link;
                            }

                            if (isset($categories_active_ids)) {
                                $cat_params['active_ids'] = $categories_active_ids;
                            }

                            if (isset($categories_removed_ids)) {
                                $cat_params['remove_ids'] = $categories_removed_ids;
                            }

                            if (isset($active_code)) {
                                $cat_params['active_code'] = $active_code;
                            }

                            if (isset($params['categories_extra_attributes'])) {
                                $cat_params['extra_attributes'] = $params['categories_extra_attributes'];
                            }

                            $cat_params['list_tag'] = $list_tag;
                            $cat_params['list_item_tag'] = $list_item_tag;
                            $cat_params['rel_type'] = 'content';
                            $cat_params['rel_id'] = $item['id'];

                            $cat_params['include_first'] = 1;
                            $cat_params['nest_level'] = $nest_level + 1;
                            if ($max_level != false) {
                                $cat_params['max_level'] = $max_level;
                            }

                            if ($nest_level > 1) {
                                if (isset($params['ul_class_deep'])) {
                                    $cat_params['ul_class'] = $params['ul_class_deep'];
                                }
                                if (isset($params['li_class_deep'])) {
                                    $cat_params['li_class'] = $params['li_class_deep'];
                                }
                            } else {
                                if (isset($params['ul_class'])) {
                                    $cat_params['ul_class'] = $params['ul_class'];
                                }
                                if (isset($params['li_class'])) {
                                    $cat_params['li_class'] = $params['li_class'];
                                }
                            }

                            if (isset($params['categories_ul_class'])) {
                                $cat_params['ul_class'] = $params['categories_ul_class'];
                            }

                            if (isset($params['categories_link_class'])) {
                                $cat_params['link_class'] = $params['categories_link_class'];
                            }

                            if (isset($params['categories_li_class'])) {
                                $cat_params['li_class'] = $params['categories_li_class'];
                            }
                            if (isset($params['categories_ul_class_deep'])) {
                                $cat_params['ul_class_deep'] = $params['categories_ul_class_deep'];
                            }

                            if (isset($params['categories_li_class_deep'])) {
                                $cat_params['li_class_deep'] = $params['categories_li_class_deep'];
                            }

                            if (isset($params['active_class'])) {
                                $cat_params['active_class'] = $params['active_class'];
                            }

                            $this->app->category_manager->tree($cat_params);
                        }
                    }
                    echo "</{$list_item_tag}>";
                }
            }
            if (trim($list_tag) != '') {
                echo "</{$list_tag}>";
            }
        }
        $content = ob_get_contents();
        if ($nest_level_orig == 0) {
        }

        if (isset($list_item_tag) and $list_item_tag and $list_item_tag == 'option') {
            $content = str_replace('</option></option>', '</option>', $content);
        }

        ob_end_clean();
        if (isset($params['return_data'])) {
            return $content;
        } else {
            echo $content;
        }

        return false;
    }
}
