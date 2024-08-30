<?php
$is_new = mw_var('new_page');


$data = array();
$data['id'] = 0;
$data['content_type'] = 'page';
$data['title'] = 'Title';
$data['url'] = '';
$data['thumbnail'] = '';
$data['is_active'] = 1;
$data['is_home'] = 0;
$data['is_shop'] = 0;
$data['subtype'] = 'static';
$data['description'] = '';
$data['active_site_template'] = '';
$data['subtype_value'] = '';
$data['parent'] = 0;
$data['layout_name'] = '';
$data['layout_file'] = '';

if ($is_new != false) {
    foreach ($is_new as $k => $v) {
        $data[$k] = $v;

    }
}