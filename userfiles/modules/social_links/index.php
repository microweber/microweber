<?php

$option_group = $params['id'];

if (isset($params['option-group'])) {
    $option_group = $params['option-group'];

}


$facebook_enabled_option = get_option('facebook_enabled', $option_group);
$twitter_enabled_option = get_option('twitter_enabled', $option_group);
$googleplus_enabled_option = get_option('googleplus_enabled', $option_group);
$pinterest_enabled_option = get_option('pinterest_enabled', $option_group);
$youtube_enabled_option = get_option('youtube_enabled', $option_group);
$linkedin_enabled_option = get_option('linkedin_enabled', $option_group);
$instagram_enabled_option = get_option('instagram_enabled', $option_group);

$facebook_enabled = $facebook_enabled_option == 'y';
$twitter_enabled = $twitter_enabled_option == 'y';
$googleplus_enabled = $googleplus_enabled_option == 'y';
$pinterest_enabled = $pinterest_enabled_option == 'y';
$youtube_enabled = $youtube_enabled_option == 'y';
$linkedin_enabled = $linkedin_enabled_option == 'y';
$instagram_enabled = $instagram_enabled_option == 'y';


if (isset($params['show-icons'])) {
    $all = explode(',', $params['show-icons']);
    foreach ($all as $item) {
        $icon = trim($item);
        if (strpos($icon, 'facebook') !== false and $facebook_enabled_option === false) {
            $facebook_enabled = true;
        } else if (strpos($icon, 'twitter') !== false and $twitter_enabled_option === false) {
            $twitter_enabled = true;
        } else if (strpos($icon, 'googleplus') !== false and $googleplus_enabled_option === false) {
            $googleplus_enabled = true;
        } else if (strpos($icon, 'pinterest') !== false and $pinterest_enabled_option === false) {
            $pinterest_enabled = true;
        } else if (strpos($icon, 'youtube') !== false and $youtube_enabled_option === false) {
            $youtube_enabled = true;
        } else if (strpos($icon, 'linkedin') !== false and $linkedin_enabled_option === false) {
            $linkedin_enabled = true;
        } else if (strpos($icon, 'instagram') !== false and $instagram_enabled_option == false) {
            $instagram_enabled = true;
        }
    }
}


$instagram_url = get_option('instagram_url', $option_group);
if ($instagram_url == false) {
    $instagram_url = get_option('instagram_url', 'website');
}


$facebook_url = get_option('facebook_url', $option_group);

if ($facebook_url == false) {
    $facebook_url = get_option('facebook_url', 'website');
}

$twitter_url = get_option('twitter_url', $option_group);

if ($twitter_url == false) {
    $twitter_url = get_option('twitter_url', 'website');
}


$googleplus_url = get_option('googleplus_url', $option_group);

if ($googleplus_url == false) {
    $googleplus_url = get_option('googleplus_url', 'website');
}


$pinterest_url = get_option('pinterest_url', $option_group);


if ($pinterest_url == false) {
    $pinterest_url = get_option('pinterest_url', 'website');
}


$youtube_url = get_option('youtube_url', $option_group);

if ($youtube_url == false) {
    $youtube_url = get_option('youtube_url', 'website');
}


$linkedin_url = get_option('linkedin_url', $option_group);

if ($linkedin_url == false) {
    $linkedin_url = get_option('linkedin_url', 'website');
}


$social_links_has_enabled = false;

if ($facebook_enabled or $twitter_enabled or $googleplus_enabled or $pinterest_enabled or $youtube_enabled or $linkedin_enabled) {
    $social_links_has_enabled = true;
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