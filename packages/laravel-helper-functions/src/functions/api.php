<?php


function api_expose($function_name, $callback = null)
{
    static $index = ' ';
    if (is_bool($function_name)) {
        return $index;
    }
    if (is_callable($callback)) {
        $index .= ' ' . $function_name;

        return api_bind($function_name, $callback);
    } else {
        $index .= ' ' . $function_name;
    }
}

function api_expose_admin($function_name, $callback = null)
{

    static $index = ' ';
    if (is_bool($function_name)) {
        return $index;
    }
    if (is_callable($callback)) {
        $index .= ' ' . $function_name;

        return api_bind_admin($function_name, $callback);
    } else {
        $index .= ' ' . $function_name;
    }


}

function api_bind($function_name, $callback = false)
{
    static $mw_api_binds;
    if (is_bool($function_name)) {
        if (is_array($mw_api_binds)) {
            $index = ($mw_api_binds);

            return $index;
        }
    } else {
        $function_name = trim($function_name);
        $mw_api_binds[$function_name][] = $callback;
    }
}

function api_bind_admin($function_name, $callback = false)
{
    static $mw_api_binds;
    if (is_bool($function_name)) {
        if (is_array($mw_api_binds)) {
            $index = ($mw_api_binds);

            return $index;
        }
    } else {
        $function_name = trim($function_name);
        $mw_api_binds[$function_name][] = $callback;
    }
}


function api_bind_user($function_name, $callback = false)
{
    static $mw_api_binds_user;
    if (is_bool($function_name)) {
        if (is_array($mw_api_binds_user)) {
            $index = ($mw_api_binds_user);

            return $index;
        }
    } else {
        $function_name = trim($function_name);
        $mw_api_binds_user[$function_name][] = $callback;
    }
}


function api_expose_user($function_name, $callback = null)
{
    static $index = ' ';
    if (is_bool($function_name)) {
        return $index;
    }
    if (is_callable($callback)) {
        $index .= ' ' . $function_name;

        return api_bind_user($function_name, $callback);
    } else {
        $index .= ' ' . $function_name;
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
