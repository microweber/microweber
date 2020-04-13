<?php


$settings = get_option('settings', 'init_scwCookiedefault');



d($params);
d($config);

if(!isset($params['variable'])){

}

if (isset($params['variable']) && !empty($params['variable'])) {

    $filter = array();
    $filter['single'] = 1;
    $filter['variable'] = $params['variable'];

    $dynamic_text = get_dynamic_text($filter);

    if ($dynamic_text) {
        if (isset($dynamic_text['content'])) {
            echo $dynamic_text['content'];
        }
    } else {
        $save = array();
        $save['variable'] = $params['variable'];
        $save['content'] = $params['content'];
        if (isset($dynamic_text['content'])) {
            $save['content'] = $params['content'];


        }
        save_dynamic_text($save);
        echo $save['content'];
    }
} else {
   print lnotif('Click here to edit dynamic text');
}

