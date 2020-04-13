<?php

$parent = $selected_category = get_option('fromcategory', $params['id']);
$selected_page = get_option('frompage', $params['id']);

$show_only_for_parent = get_option('single-only', $params['id']);

$show_category_header = get_option('show_category_header', $params['id']);
$show_subcats = get_option('show-subcats', $params['id']);
$hide_pages = get_option('hide-pages', $params['id']);

if ($parent == 'current') {
    $parent = CATEGORY_ID;
}

if (!$parent) {
    $parent = url_param('collection');
}
if (!$parent) {
    $parent = get_category_id_from_url();
}

if (!isset($parent) or $parent == '') {
    $parent = 0;
}


$cat_ids = array();
$content_ids = array();


//$cats = get_categories('no_limit=true&order_by=position asc&rel_id=[not_null]&parent_id=' . intval($parent));
//if(!$cats or $show_only_for_parent){
//    $cats = get_categories('no_limit=true&order_by=position asc&rel_id=[not_null]&id=' . intval($parent));
//}
//if ($selected_page and !$parent) {
//    $cats = get_categories('no_limit=true&order_by=position asc&rel_id=' . intval($selected_page));
//}

$selected_cats = array();
$selected_cats2 = array();
$selected_pages = array();
$cats = array();


if ($selected_page) {
    $selected_page_explode = explode(',', $selected_page);
    foreach ($selected_page_explode as $sel) {
        $selected_pages[] = $sel;
    }
}


if ($selected_category) {
    $selected_category_explode = explode(',', $selected_category);
    foreach ($selected_category_explode as $sel) {
        $selected_cats[] = $sel;
    }
}

if ($selected_pages) {
    foreach ($selected_pages as $sel_p) {
        $pp = get_content_by_id($sel_p);
        $pp['is_page'] = true;
        $cats[] = $pp;


        if ($selected_cats) {
            foreach ($selected_cats as $sk => $sel_c) {
                $category_page_check = get_page_for_category($sel_c);

                $cat_data = get_category_by_id($sel_c);
                if (isset($category_page_check['id']) and $category_page_check['id'] == $sel_p) {
                    if (!in_array($cat_data['id'], $cat_ids)) {
                        $cats[] = $cat_data;
                        $cat_ids [] = $cat_data['id'];
                    }

                    if ($show_subcats and !$show_only_for_parent) {
                        $sub_cats = app()->category_manager->get_children($cat_data['id']);
                        if ($sub_cats) {
                            foreach ($sub_cats as $sub_cat) {
                                $cat_data2 = get_category_by_id($sub_cat);
                                if ($cat_data2) {
                                    if (!in_array($cat_data2['id'], $cat_ids)) {
                                        $cats[] = $cat_data2;
                                        $cat_ids [] = $cat_data2['id'];
                                    }
                                }
                            }
                        }
                    }

                //    unset($selected_cats[$sk]);
                } else {
                    //   $selected_cats2[] = $cat_data;
                }
            }
        }


        if ($show_subcats) {
            $subcats = app()->category_manager->get_for_content($sel_p);

            if ($subcats) {
                foreach ($subcats as $subcat) {
                    if (!in_array($subcat['id'], $cat_ids)) {
                        $cats[] = $subcat;
                        $cat_ids [] = $subcat['id'];
                    }
                    //   $cats[] = $subcat;
                }
            }

        }


        //   $cats=    array_unique_recursive($cats);

    }
}

if ($selected_cats) {
    $selected_cats = array_unique($selected_cats);

    $selected_cats_ids = $selected_cats;
    foreach ($selected_cats_ids as $selected_cats_id) {
        $cat_data = get_category_by_id($selected_cats_id);
        if ($cat_data) {
            if (!in_array($cat_data['id'], $cat_ids)) {
                $cats[] = $cat_data;
                $cat_ids [] = $cat_data['id'];
            }

            if($show_subcats and !$show_only_for_parent){
                $sub_cats = app()->category_manager->get_children($cat_data['id']);
                if ($sub_cats) {
                    foreach ($sub_cats as $sub_cat) {
                        $cat_data2 = get_category_by_id($sub_cat);
                        if ($cat_data2) {
                            if (!in_array($cat_data2['id'], $cat_ids)) {
                                $cats[] = $cat_data2;
                                $cat_ids [] = $cat_data2['id'];
                            }
                        }
                    }
                }
             }

        }
    }
}


if (!empty($cats)) {
    foreach ($cats as $k => $cat) {

        if (isset($cat['is_page'])) {

            if($hide_pages){
                unset($cats[$k]);
                continue;
            }

            $cat['picture'] = get_picture($cat['id'], 'content');
            $cat['url'] = content_link($cat['id']);
            $cats[$k] = $cat;
        } else {

            $cat['picture'] = get_picture($cat['id'], 'category');
            $cat['url'] = category_link($cat['id']);

            if (isset($cat['rel_type']) and $cat['rel_type'] == 'content') {
                $latest = get_content("order_by=position desc&limit=30&is_active=1&category=" . $cat['id']);
                if (!$cat['picture'] and isset($latest[0])) {
                    $latest_product = $latest[0];
                    $cat['picture'] = get_picture($latest_product['id']);
                }
                if ($latest) {
                    $cat['content_items'] = $latest;
                }
            }
            $cats[$k] = $cat;
        }



    }
}
//$cats=    array_unique_recursive($cats);

$selected_cats = $cats;
if (!$selected_cats) {
    print lnotif('Categories not found');
}

$data = $selected_cats;
$module_template = get_option('data-template', $params['id']);

if ($module_template != false and $module_template != 'none') {
    $template_file = module_templates($config['module'], $module_template);
} else {
    if (isset($params['template'])) {
        $template_file = module_templates($config['module'], $params['template']);
    } else {
        $template_file = module_templates($config['module'], 'default');
    }

}
$load_template = false;
$template_file_def = module_templates($config['module'], 'default');
if (isset($template_file) and is_file($template_file) != false) {
    $load_template = $template_file;
} elseif (isset($template_file_def) and is_file($template_file_def) != false) {
    $load_template = $template_file_def;
}

if (isset($load_template) and is_file($load_template) != false) {
    if (!$data) {
        print lnotif(_e('Selected categories return no results'), true);
        return;
    }
    include($load_template);
} else {
    print lnotif(_e('No template found'), true);
}

