<?php
$template = get_option('data-template', $params['id']);

$search_string = get_option('search_string', $params['id']);
$number_of_items = get_option('number_of_items', $params['id']);


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