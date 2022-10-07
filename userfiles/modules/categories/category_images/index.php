<?php

use \MicroweberPackages\Option\Models\Option;

$cats = [];
$options = Option::where('option_group', $params['id'])->get();

$parent = $selected_category = Option::fetchFromCollection($options, 'fromcategory');

$selected_page = Option::fetchFromCollection($options, 'frompage');

$show_only_for_parent = Option::fetchFromCollection($options, 'single-only');

$show_category_header = Option::fetchFromCollection($options, 'show_category_header');
$show_subcats = Option::fetchFromCollection($options, 'show-subcats');
$hide_pages = Option::fetchFromCollection($options, 'hide-pages');


$cfg_filter_in_stock = false;


if(isset($params['filter-only-in-stock'])){
    $cfg_filter_in_stock = $params['filter-only-in-stock'];
} else {
    $cfg_filter_in_stock =  Option::fetchFromCollection($options, 'filter-only-in-stock') == '1';
}

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

$cache_ttl = 3600;

$cache_id = __CLASS__ . __FUNCTION__ .'category_images'. crc32(json_encode($params) . $hide_pages . $show_subcats . $cfg_filter_in_stock.$show_category_header . $show_only_for_parent . $selected_page . $parent . current_lang());
$cache_group = 'categories';

//$results = cache_get($cache_id, $cache_group, $cache_ttl);
 $results = false;
if ($results) {
    $cats = $results;
} else {

//cache_save($tree, $cache_id, $cache_group);

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
            if($pp) {
                $pp['is_page'] = true;
                $cats[] = $pp;
            }


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

                    }
                }

            }




        }
    }


    if ($selected_cats) {
        $selected_cats = array_unique($selected_cats);

        $selected_cats_ids = $selected_cats;
        $selectedCategories = \MicroweberPackages\Category\Models\Category::whereIn('id', $selected_cats_ids)->with('children')->get();

        if(!empty($selectedCategories)) {
            foreach ($selectedCategories as $catData) {

                if (isset($catData['id'])) {
                    if (!in_array($catData->id, $cat_ids)) {
                        $cats[] = $catData->toArray();
                        $cat_ids [] = $catData->id;
                    }

                    if ($show_subcats and !$show_only_for_parent) {
                        if ($catData->children) {
                            foreach ($catData->children as $sub_cat) {

                                if (!in_array($sub_cat->id, $cat_ids)) {
                                    $cats[] = $sub_cat->toArray();
                                    $cat_ids [] = $sub_cat->id;
                                }
                            }
                        }
                    }

                }
            }
        }
    }


    if (!empty($cats)) {

        usort($cats, function($a, $b) {
            if (isset($a['position']) && isset($b['position'])) {
                return $a['position'] - $b['position'];
            }
            return 0;
        });

        foreach ($cats as $k => $cat) {
            $cat['content_items'] = false;
            $cat['content_items_count'] = false;
            if (isset($cat['is_page'])) {

                if ($hide_pages) {
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

                    $cont_params = [];
                    $cont_params['get_extra_data'] = true;
                    $cont_params['order_by'] = 'position desc';
                    $cont_params['limit'] = '30';
                    $cont_params['is_active'] = '1';
                    $cont_params['category'] = $cat['id'];

                    if($cfg_filter_in_stock){
                        $cont_params['filter-only-in-stock'] = $cfg_filter_in_stock;
                    }

                    $cont_params2 = $cont_params;
                    $cont_params2['count'] = 1;


                    //get_extra_data=true&order_by=position desc&limit=30&is_active=1&category=" . $cat['id']
//

                    $latest = get_content($cont_params);
                    $latest_count = get_content($cont_params2);

                    if (!$cat['picture'] and isset($latest[0])) {
                        $latest_product = $latest[0];
                        $cat['picture'] = get_picture($latest_product['id']);
                    }
                    if ($latest) {
                        $cat['content_items'] = $latest;
                        $cat['content_items_count'] = $latest_count;
                    }
                }
               // $cat['has_posts'] = get_content('count=1&is_active=1&category=' . $cat['id']);
                 $cats[$k] = $cat;
            }


        }


        if(is_array($cats)){
            // prepare categories for template
            $cat_ids = array_column($cats,'id');

            dd($cat_ids);

//            $allInStockNum = app()->category_repository->countProductsInStock($selectedCat);




        }
    }
    cache_save($cats, $cache_id, $cache_group,$cache_ttl);

}


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

