<?php
if (isset($params['variable']) && !empty($params['variable'])) {

    $filter = array();
    $filter['single'] = 1;
    $filter['variable'] = $params['variable'];

    $dynamic_text = get_dynamic_text($filter);

    if ($dynamic_text) {
        echo $dynamic_text['content'];
    }
}

