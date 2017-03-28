<?php

$parent = get_option('fromcategory', $params['id']);
$show_category_header = get_option('show_category_header', $params['id']);

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
$cats = get_categories('no_limit=true&order_by=position asc&rel_id=[not_null]&parent_id=' . intval($parent));

if (!empty($cats)) {
    foreach ($cats as $k => $cat) {

        $cat['picture'] = get_picture($cat['id'], 'category');

        if ($cat['rel_type'] == 'content') {
            $latest = get_content("order_by=position desc&limit=30&category=" . $cat['id']);

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
$data = $cats;
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
        print lnotif('Selected categories return no results');
        return;
    }
    include($load_template);
} else {
    print lnotif('No template found');
}

