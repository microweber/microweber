<?php
$template = get_option('template', $params['id']);

$search_string = get_option('search_string', $params['id']);
if ($search_string) {
    $search_string = $search_string;
} elseif (isset($params['search_string'])) {
    $search_string = $params['search_string'];
} else {
    $search_string = 'microweber';
}

$number_of_items = get_option('number_of_items', $params['id']);
if ($number_of_items) {
    $number_of_items = $number_of_items;
} elseif (isset($params['number_of_items'])) {
    $number_of_items = $params['number_of_items'];
} else {
    $number_of_items = '3';
}

$items = twitter_feed_get_items($search_string, $number_of_items);

if ($template == false and isset($params['template'])) {
    $template = $params['template'];
}
if ($template != false) {
    $template_file = module_templates($config['module'], $template);
} else {
    $template_file = module_templates($config['module'], 'default');

}
if ($template_file != false and is_file($template_file)) {
    include($template_file);
}
if (!$items and is_admin()) {
    print lnotif("Click here to edit Twitter feed");
}
