<?php
if (!user_can_access('module.marketplace.index')) {
    return;
}

$is_core_update = false;

$author = array_first(explode('/', $item['name']), function ($key, $value) {
    return $value;
});


$package_name = explode('/', $item['name']);
if(!isset($package_name[1])){
    return;
}

$package_name = $package_name[1];

if (isset($item['authors']) and isset($item['authors'][0]) and isset($item['authors'][0]['name'])) {
    $author = $item['authors'][0]['name'];

}
$license = false;
if (isset($item['license']) and isset($item['license'][0])) {
    $license = $item['license'][0];

}

$author_icon = false;
if (isset($item['extra']) and isset($item['extra']['_meta']) and isset($item['extra']['_meta']['avatar'])) {
    //$author_icon = $item['extra']['_meta']['avatar'];
}

$screenshot = false;
$changelog = false;

if (isset($item['latest_version']) and isset($item['latest_version']['extra']) and isset($item['latest_version']['extra']['_meta']['changelog'])) {
    $changelog = $item['latest_version']['extra']['_meta']['changelog'];
}

if (isset($item['latest_version']) and isset($item['latest_version']['extra']) and isset($item['latest_version']['extra']['_meta']['screenshot'])) {
    $screenshot = $item['latest_version']['extra']['_meta']['screenshot'];
}


if (!$screenshot and isset($item['extra']) and isset($item['extra']['_meta']) and isset($item['extra']['_meta']['screenshot'])) {
    $screenshot = $item['extra']['_meta']['screenshot'];

}
if ($item['name'] == 'microweber/update') {
    $screenshot = mw()->ui->admin_logo;
    $is_core_update = true;
}

if (isset($item['latest_version']['extra']['_meta'][$package_name])) {
    $screenshot = $item['latest_version']['extra']['_meta'][$package_name];
}

$key = $item['name'];
$vkey = 'latest';

if (isset($item['latest_version']) and isset($item['latest_version']['version'])) {
    $vkey = $item['latest_version']['version'];
}


$local_install = false;
$local_install_v = false;

if (isset($item['current_install']) and $item['current_install']) {

    $local_install = $item['current_install'];
    if (isset($local_install['version']) and $local_install['version']) {
        $local_install_v = $local_install['version'];
    }
}

$has_update = false;
if (isset($item['has_update']) and $item['has_update']) {
    $has_update = true;
}

if (!isset($box_class)) {
    $box_class = '';
}


$is_commercial = false;
if (isset($item['latest_version']) AND isset($item['latest_version']['dist_type']) AND $item['latest_version']['dist_type'] == 'license_key') {
    $is_commercial = true;
}
