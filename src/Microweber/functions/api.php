<?php


function api_expose($function_name)
{
    static $index = ' ';
    if (is_bool($function_name)) {
        return $index;
    } else {
        $index .= ' ' . $function_name;
    }
}

function api_expose_admin($function_name)
{
    static $index = ' ';
    if (is_bool($function_name)) {
        return $index;
    } else {
        $index .= ' ' . $function_name;
    }
}



function api_hook($function_name, $next_function_name = false)
{
    static $mw_api_hooks;
    if (is_bool($function_name)) {
        if (is_array($mw_api_hooks)) {
            $index = ($mw_api_hooks);
            return $index;
        }

    } else {
        $function_name = trim($function_name);
        $mw_api_hooks[$function_name][] = $next_function_name;

    }
}

function document_ready($function_name)
{
    static $index = ' ';
    if (is_bool($function_name)) {

        return $index;
    } else {
        $index .= ' ' . $function_name;
    }
}

function execute_document_ready($l)
{

    $document_ready_exposed = (document_ready(true));

    if ($document_ready_exposed != false) {
        $document_ready_exposed = explode(' ', $document_ready_exposed);
        $document_ready_exposed = array_unique($document_ready_exposed);
        $document_ready_exposed = array_trim($document_ready_exposed);

        foreach ($document_ready_exposed as $api_function) {
            if (function_exists($api_function)) {
                $l = $api_function($l);
            }
        }
    }

    return $l;
}


function array_to_module_params($params, $filter = false)
{
    $str = '';
    if (is_array($params)) {
        foreach ($params as $key => $value) {

            if ($filter == false) {
                $str .= $key . '="' . $value . '" ';
            } else if (is_array($filter) and !empty($filter)) {
                if (in_array($key, $filter)) {
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


function parse_params($params)
{
    $params2 = array();
    if (is_string($params)) {
        $params = parse_str($params, $params2);
        $params = $params2;
        unset($params2);
    }
    return $params;
}


function mw_var($key, $new_val = false)
{
    static $mw_var_storage;
    $contstant = ($key);
    if ($new_val == false) {
        if (isset($mw_var_storage[$contstant]) != false) {
            return $mw_var_storage[$contstant];
        } else {
            return false;
        }
    } else {
        if (isset($mw_var_storage[$contstant]) == false) {
            $mw_var_storage[$contstant] = $new_val;
            return $new_val;
        }
    }
    return false;
}


function autoload_add($dirname)
{
    set_include_path($dirname .
        PATH_SEPARATOR . get_include_path());
}


function api_link($str = '')
{
    return mw('url')->api_link($str);

}