<?php

$option_group = $params['id'];

if (isset($params['option-group'])) {
    $option_group = $params['option-group'];
}

$twitter_url = get_option('twitter_url', $option_group);

if ($twitter_url == false) {
    $twitter_url = get_option('twitter_url', 'website');
}

$twitter_url = str_ireplace('statuses/', 'status/', $twitter_url);
$statusID = explode('status/', $twitter_url);
if (!isset($statusID[1])) {
    print lnotif('Tweet Embed');
    return;
} else {
    $statusID = $status_id = $statusID[1];
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

if (is_file($template_file) != false) {
    include($template_file);
} else {
    print lnotif("No template found. Please choose template.");
}
?>
