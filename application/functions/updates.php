<?php

function mw_check_for_update() {
    $data = array();
    $data['mw_version'] = c('version');



    $t = templates_list();
    $data['templates'] = $t;

    $t = modules_list();
    $data['modules'] = $t;


    $t = get_elements();
    $data['elements'] = $t;


    $serv = 'http://microweber.us/update.php';
    $p = url_download($serv, $data);
    d($p);
}