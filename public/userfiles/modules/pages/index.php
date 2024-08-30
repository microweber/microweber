<?php

/**
 * Print the site pages as tree
 *
 * @param string append_to_link
 *            You can pass any string to be appended to all pages urls
 * @param string link
 *            Replace the link href with your own. Ex: link="<?php print site_url('page_id:{id}'); ?>

"
 * @return string prints the site tree
 * @uses pages_tree($params);
 * @usage  type="pages" append_to_link="/editmode:y"
 */

if (get_option('include_parent', $params['id']) == 'y') {
    $params['include_first'] = true;
}

if (!isset($params['link'])) {
    if (isset($params['append_to_link'])) {
        $append_to_link = $params['append_to_link'];
    } else {
        $append_to_link = '';
    }

    $params['link'] = '<a data-page-id="{id}" class="{active_class} {active_parent_class} pages_tree_link {nest_level} {exteded_classes}" href="{link}' . $append_to_link . '">{title}</a>';

} else {

    $params['link'] = '<a data-page-id="{id}" class="{active_class} {active_parent_class} pages_tree_link {nest_level} {exteded_classes}"  href="' . $params['link'] . '">{title}</a>';
}

$option = get_option('data-parent', $params['id']);
if ($option != false and intval($option) > 0) {
$params['parent'] = $option;
} elseif (isset($params['data-parent'])) {
$params['parent'] = intval($params['data-parent']);
} elseif (isset($params['content_id'])) {
$params['parent'] = intval($params['content_id']);
} elseif (isset($params['content-id'])) {
$params['parent'] = intval($params['content-id']);
}elseif (isset($params['parent'])) {
$params['parent'] = intval($params['parent']);
} else {
$params['parent'] = 0;
}

$option = get_option('include_categories', $params['id']);



$include_categories = false;
if ($option != false and ($option) == 'y') {
$include_categories = $params['include_categories'] = true;
} elseif (isset($params['data-include_categories'])) {
$params['include_categories'] = intval($params['parent']);
}
$option = get_option('maxdepth', $params['id']);

if ($option != false and intval($option) > 0) {
    $params['maxdepth'] = $option;
}


if (isset($params['parent']) and $params['parent'] != 0) {
	if (isset($params['show-parent']) and ($params['show-parent'] == "false" or $params['show-parent'] == false)) {
    $params['include_first'] = false;
	} elseif (isset($params['show-parent']) and $params['show-parent'] == true) {

		    $params['include_first'] = true;

	}

 }


 $params['is_active'] = 1;

// loading the module template
$module_template = get_option('data-template', $params['id']);

if ($module_template == false and isset($params['template'])) {
    $module_template = $params['template'];
}
if ($module_template != false) {
    $template_file = module_templates($config['module'], $module_template);

} else {
    $template_file = module_templates($config['module'], 'default');

}

if (isset($template_file) and file_exists($template_file) and is_file($template_file)) {
     include($template_file);
} else {

    $template_file = module_templates($config['module'], 'default');

    include($template_file);
}
