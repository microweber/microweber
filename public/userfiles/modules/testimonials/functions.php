<?php

function get_testimonials($params = array())
{
    if (is_string($params)) {
        $params = parse_params($params);
    }

    $params['table'] = "testimonials";
    $params['order_by'] = "position asc";

    return db_get($params);
}
