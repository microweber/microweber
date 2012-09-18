<?php

function api_expose($function_name) {
    static $index = ' ';
    if (is_bool($function_name)) {

        return $index;
    } else {
        $index .= ' ' . $function_name;
    }
}

function document_ready($function_name) {
    static $index = ' ';
    if (is_bool($function_name)) {

        return $index;
    } else {
        $index .= ' ' . $function_name;
    }
}

function execute_document_ready($l) {

    $document_ready_exposed = (document_ready(true));

    if ($document_ready_exposed != false) {
        $document_ready_exposed = explode(' ', $document_ready_exposed);
        $document_ready_exposed = array_unique($document_ready_exposed);
        $document_ready_exposed = array_trim($document_ready_exposed);

        foreach ($document_ready_exposed as $api_function) {
            if (function_exists($api_function)) {
//                for ($index = 0; $index < 1; $index++) {
//                    //   $l = $api_function($l);
//                }
                $l = $api_function($l);
            }
        }
    }
    $l = parse_micrwober_tags($l, $options = false);

    return $l;
}

