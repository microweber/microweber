<?php




function array_to_module_params($params, $filter = false)
{
    $str = '';
    if (is_array($params)) {
        foreach ($params as $key => $value) {
            if ($filter == false) {
                $str .= $key . '="' . $value . '" ';
            } elseif (is_array($filter) and !empty($filter)) {
                if (in_array($key, $filter, true)) {
                    $str .= $key . '="' . $value . '" ';
                }
            } else {
                if ($key == $filter) {
                    $str .= $key . '="' . $value . '" ';
                }
            }
        }
    }

    return $str;
}




function api_link($str = '')
{
    return mw()->url_manager->api_link($str);
}
