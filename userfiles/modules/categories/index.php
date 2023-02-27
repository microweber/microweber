<script>mw.moduleCSS("<?php print modules_url(); ?>categories/styles.css"); </script>

<?php
if (isset($params['class'])) {
    unset($params['class']);
}
if (isset($params['content-id'])) {
    $params['content_id'] = $params['content-id'];
}


if (isset($params['for-content-id'])) {
    $params['for-content-id'] = ($params['for-content-id']);
}

if (isset($params['for-current-content-id'])) {
    $params['for-content-id'] = CONTENT_ID;
}


$selected_max_depth = get_option('data-max-depth', $params['id']);
if (intval($selected_max_depth) > 0) {
    $params['max_level'] = intval($selected_max_depth);
}


if (isset($params['from-page']) and trim($params['from-page']) != 'false') {
    $params['content_id'] = PAGE_ID;
}


if (!isset($params['ul_class'])) {
    $params['ul_class'] = 'nav nav-list';
}
$params['rel_type'] = 'content';
$category_tree_parent_page = get_option('data-content-id', $params['id']);
$category_parent = get_option('data-category-id', $params['id']);


if ($category_tree_parent_page == false and isset($params['content_id'])) {
    $params['rel_id'] = $params['content_id'] = trim($params['content_id']);
} elseif ($category_tree_parent_page == false and isset($params['page-id'])) {
    $params['rel_id'] = $params['content_id'] = trim($params['page-id']);

}

if ($category_tree_parent_page != false and $category_tree_parent_page != '' and $category_tree_parent_page != 0) {
    $params['rel_id'] = $params['content_id'] = $category_tree_parent_page;
}

if ($category_tree_parent_page == false and isset($params['current-page']) and $params['current-page'] == true) {
    $params['rel_id'] = $params['content_id'] = PAGE_ID;

}

if (intval($category_parent) > 0) {
    $check_if_cat_is_in_page = get_page_for_category($category_parent);
    if (isset($check_if_cat_is_in_page['id']) and isset($params['rel_id']) and $params['rel_id'] != 0) {
        if ($check_if_cat_is_in_page['id'] == $params['rel_id']) {
            $params['parent'] = $category_parent;
        }
    }
}

$only_products_in_stock = get_option('only-products-in-stock', $params['id']);
if ($only_products_in_stock == 1) {
    $shop = get_content('single=1&content_type=page&is_shop=1');
    if ($shop) {

        $categories = get_categories('rel_id=' . $shop['id'] . '&is_deleted=0&is_hidden=0&order_by=position asc');
        foreach ($categories as $k => $v) {
            $has_products = app()->category_repository->hasProductsInStock($v['id']);
            if (!$has_products) {
                unset($categories[$k]);
            }
        }
        $params['tree_data'] = $categories;
    }
}

$module_template = get_option('data-template', $params['id']);
if ($module_template == false and isset($params['template'])) {
    $module_template = $params['template'];
}


if ($module_template != false) {
    $template_file = module_templates($config['module'], $module_template);
} else {
    $template_file = module_templates($config['module'], 'default');
}


if (isset($template_file) and is_file($template_file) != false) {
    include($template_file);
} else {
    $template_file = module_templates($config['module'], 'default');
    include($template_file);
}

