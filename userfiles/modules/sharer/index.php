<?php

$facebook_enabled = get_option('facebook_enabled', $params['id']) == 'y';

$twitter_enabled = get_option('twitter_enabled', $params['id']) == 'y';

$googleplus_enabled = get_option('googleplus_enabled', $params['id']) == 'y';

$pinterest_enabled = get_option('pinterest_enabled', $params['id']) == 'y';

$viber_enabled = get_option('viber_enabled', $params['id']) == 'y';

$whatsapp_enabled = get_option('whatsapp_enabled', $params['id']) == 'y';

$linkedin_enabled = get_option('linkedin_enabled', $params['id']) == 'y';

if ($facebook_enabled == 'y') {
    $facebook_enabled = true;
}

if ($twitter_enabled == 'y') {
    $twitter_enabled = true;
}

if ($googleplus_enabled == 'y') {
    $googleplus_enabled = true;
}

if ($pinterest_enabled == 'y') {
    $pinterest_enabled = true;
}

if ($viber_enabled == 'y') {
    $viber_enabled = true;
}

if ($whatsapp_enabled == 'y') {
    $whatsapp_enabled = true;
}

if ($linkedin_enabled == 'y') {
    $linkedin_enabled = true;
}

?>


<?php
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

<?php if ($facebook_enabled == '' AND $twitter_enabled == '' AND $googleplus_enabled == '' AND $pinterest_enabled == '' AND $viber_enabled == '' AND $whatsapp_enabled == '' AND $linkedin_enabled == '') {
    print lnotif('Click here to edit Social Media Share');
}
?>