<?php


$selected_dynamic_text_name = get_option('selected_dynamic_text_name', $params['id']);






if($selected_dynamic_text_name){
    $params['name'] = $selected_dynamic_text_name;
}



if (isset($params['name']) && !empty($params['name'])) {

    $filter = array();
    $filter['single'] = 1;
    $filter['name'] = $params['name'];

    $dynamic_text = get_dynamic_text($filter);

    if ($dynamic_text) {
        if (isset($dynamic_text['content'])) {
            echo $dynamic_text['content'];
        }
    } else {
        $save = array();
        $save['name'] = $params['name'];
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

