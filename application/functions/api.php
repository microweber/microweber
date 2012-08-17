<?php

function api_expose($function_name) {
    static $index = ' ';
    if (is_bool($function_name)) {

        return $index;
    } else {
        $index .= ' ' . $function_name; 
    }
}

