<?php
$is_new = template_var('new_page');


$data = array();
$data['id'] = 0;
$data['content_type'] = 'page';
$data['title'] = false;
$data['content'] = false;
$data['url'] = '';

$data['thumbnail'] = '';

$data['is_active'] = 1;
$data['is_home'] = 0;

$data['is_shop'] = 0;

$data['require_login'] = 'n';

$data['subtype'] = 'static';
$data['description'] = '';
$data['active_site_template'] = '';
$data['subtype_value'] = '';
$data['parent'] = 0;
$data['layout_name'] = '';
$data['layout_file'] = '';
$data['original_link'] = '';
if ($is_new == false) {

} else {
    foreach ($is_new as $k => $v) {
        $data[$k] = $v;

    }

}


if (!isset($title_placeholder)) {
    $title_placeholder = false;
}


if (isset($params['subtype'])) {
    $data['subtype'] = $params['subtype'];
    $title_placeholder = "New {$data['subtype']} title";
    if ($params['subtype'] == 'product') {
        $data['content_type'] = 'post';
        $data['subtype'] = 'product';
    } elseif ($params['subtype'] == 'post') {
        $data['content_type'] = 'post';
        $data['subtype'] = 'post';

    } elseif ($params['subtype'] == 'static' or $params['subtype'] == 'dynamic') {
        $title_placeholder = "New page title";

    }
}
if ($title_placeholder == false and isset($params['content_type'])) {
    $title_placeholder = "New " . $params['content_type'];
} else if ($title_placeholder == false and isset($data['subtype'])) {
    $title_placeholder = "New " . $data['subtype'];
    if ($data['subtype'] == 'static' or $data['subtype'] == 'dynamic') {
        $title_placeholder = "New page title";
    }
}