<?php

$option_group = $params['id'];

if (isset($params['option-group'])) {
    $option_group = $params['option-group'];
}


$settings = get_module_option('settings', $params['id']);
if (empty($settings)) {

    $newModuleDefaultSettingsApplied = new \MicroweberPackages\Module\ModuleDefaultSettingsApplier();
    $newModuleDefaultSettingsApplied->moduleName = 'social_links';
    $newModuleDefaultSettingsApplied->modulePath = __DIR__;
    $newModuleDefaultSettingsApplied->moduleId = $params['id'];
    $newModuleDefaultSettingsApplied->apply();

}


$social_links_options = [];
$social_links_options['facebook_enabled'] = '';
$social_links_options['twitter_enabled'] = '';
$social_links_options['googleplus_enabled'] = '';
$social_links_options['pinterest_enabled'] = '';
$social_links_options['youtube_enabled'] = '';
$social_links_options['linkedin_enabled'] = '';
$social_links_options['github_enabled'] = '';
$social_links_options['instagram_enabled'] = '';
$social_links_options['rss_enabled'] = '';
$social_links_options['soundcloud_enabled'] = '';
$social_links_options['mixcloud_enabled'] = '';
$social_links_options['medium_enabled'] = '';
$social_links_options['instagram_url'] = '';
$social_links_options['facebook_url'] = '';
$social_links_options['twitter_url'] = '';
$social_links_options['googleplus_url'] = '';
$social_links_options['pinterest_url'] = '';
$social_links_options['youtube_url'] = '';
$social_links_options['linkedin_url'] = '';
$social_links_options['github_url'] = '';
$social_links_options['soundcloud_url'] = '';
$social_links_options['mixcloud_url'] = '';
$social_links_options['medium_url'] = '';
$social_links_options['discord_url'] = '';
$social_links_options['discord_enabled'] = '';

$social_links_options['skype_url'] = '';
$social_links_options['skype_enabled'] = '';

$website_social_links_options = $social_links_options;

$get_social_links_options = \MicroweberPackages\Option\Models\Option::where('option_group', $option_group)->get();
if (!empty($get_social_links_options)) {
    foreach ($get_social_links_options as $social_links_option) {
        $social_links_options[$social_links_option['option_key']] = $social_links_option['option_value'];
    }
}

$get_website_social_links_options = \MicroweberPackages\Option\Models\Option::where('option_group', 'website')->get();
if (!empty($get_website_social_links_options)) {
    foreach ($get_website_social_links_options as $social_links_option) {
        $website_social_links_options[$social_links_option['option_key']] = $social_links_option['option_value'];
    }
}

$facebook_enabled_option = $social_links_options['facebook_enabled'];
$twitter_enabled_option = $social_links_options['twitter_enabled'];
$googleplus_enabled_option = $social_links_options['googleplus_enabled'];
$pinterest_enabled_option = $social_links_options['pinterest_enabled'];
$youtube_enabled_option = $social_links_options['youtube_enabled'];
$linkedin_enabled_option = $social_links_options['linkedin_enabled'];
$github_enabled_option = $social_links_options['github_enabled'];
$instagram_enabled_option = $social_links_options['instagram_enabled'];
$rss_enabled_option = $social_links_options['rss_enabled'];

$soundcloud_enabled_option = $social_links_options['soundcloud_enabled'];
$mixcloud_enabled_option = $social_links_options['mixcloud_enabled'];
$medium_enabled_option = $social_links_options['medium_enabled'];
$discord_enabled_option = $social_links_options['discord_enabled'];
$skype_enabled_option = $social_links_options['skype_enabled'];

$facebook_enabled = $facebook_enabled_option == '1';
$twitter_enabled = $twitter_enabled_option == '1';
$googleplus_enabled = $googleplus_enabled_option == '1';
$pinterest_enabled = $pinterest_enabled_option == '1';
$youtube_enabled = $youtube_enabled_option == '1';
$linkedin_enabled = $linkedin_enabled_option == '1';
$github_enabled = $github_enabled_option == '1';
$instagram_enabled = $instagram_enabled_option == '1';
$rss_enabled = $rss_enabled_option == '1';

$soundcloud_enabled = $soundcloud_enabled_option == '1';
$mixcloud_enabled = $mixcloud_enabled_option == '1';
$medium_enabled = $medium_enabled_option == '1';
$discord_enabled = $discord_enabled_option == '1';
$skype_enabled = $skype_enabled_option == '1';

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
        } else if (strpos($icon, 'github') !== false and $github_enabled_option === false) {
            $github_enabled = true;
        } else if (strpos($icon, 'instagram') !== false and $instagram_enabled_option == false) {
            $instagram_enabled = true;
        } else if (strpos($icon, 'rss') !== false and $rss_enabled_option == false) {
            $rss_enabled = true;
        } else if (strpos($icon, 'soundcloud') !== false and $soundcloud_enabled_option == false) {
            $soundcloud_enabled = true;
        } else if (strpos($icon, 'mixcloud') !== false and $mixcloud_enabled_option == false) {
            $mixcloud_enabled = true;
        } else if (strpos($icon, 'medium') !== false and $medium_enabled_option == false) {
            $medium_enabled = true;
        } else if (strpos($icon, 'discord') !== false and $discord_enabled_option == false) {
            $discord_enabled = true;
        } else if (strpos($icon, 'skype') !== false and $skype_enabled_option == false) {
            $skype_enabled = true;
        }
    }
}


$instagram_url = $social_links_options['instagram_url'];
if ($instagram_url == false) {
    $instagram_url = $website_social_links_options['instagram_url'];
}


$facebook_url = $social_links_options['facebook_url'];

if ($facebook_url == false) {
    $facebook_url = $website_social_links_options['facebook_url'];
}

$twitter_url = $social_links_options['twitter_url'];

if ($twitter_url == false) {
    $twitter_url = $website_social_links_options['twitter_url'];
}


$googleplus_url = $social_links_options['googleplus_url'];

if ($googleplus_url == false) {
    $googleplus_url = $website_social_links_options['googleplus_url'];
}


$pinterest_url = $social_links_options['pinterest_url'];


if ($pinterest_url == false) {
    $pinterest_url = $website_social_links_options['pinterest_url'];
}


$youtube_url = $social_links_options['youtube_url'];

if ($youtube_url == false) {
    $youtube_url = $website_social_links_options['youtube_url'];
}


$linkedin_url = $social_links_options['linkedin_url'];

if ($linkedin_url == false) {
    $linkedin_url = $website_social_links_options['linkedin_url'];
}

$github_url = $social_links_options['github_url'];

if ($github_url == false) {
    $github_url = $website_social_links_options['github_url'];
}

$soundcloud_url = $social_links_options['soundcloud_url'];

if ($soundcloud_url == false) {
    $soundcloud_url = $website_social_links_options['soundcloud_url'];
}

$mixcloud_url = $social_links_options['mixcloud_url'];

if ($mixcloud_url == false) {
    $mixcloud_url = $website_social_links_options['mixcloud_url'];
}

$medium_url = $social_links_options['medium_url'];

if ($medium_url == false) {
    $medium_url = $website_social_links_options['medium_url'];
}


$discord_url = $social_links_options['discord_url'];

if ($discord_url == false) {
    $discord_url = $website_social_links_options['discord_url'];
}

$skype_url = $social_links_options['skype_url'];

if ($skype_url == false) {
    $skype_url = $website_social_links_options['skype_url'];
}

$social_links_has_enabled = false;

if ($facebook_enabled or $twitter_enabled or $googleplus_enabled or $pinterest_enabled or $youtube_enabled or $linkedin_enabled or $soundcloud_enabled or $mixcloud_enabled) {
    $social_links_has_enabled = true;
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
