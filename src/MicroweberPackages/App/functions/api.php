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


// stores vars in memory
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
        //if (isset($mw_var_storage[$contstant]) == false) {
        $mw_var_storage[$contstant] = $new_val;

        return $new_val;
        //}
    }

    return false;
}

function autoload_add_namespace($dirname,$namespace){
    spl_autoload_register(function ($class) use ($dirname,$namespace) {
        $prefix = $namespace;
        $base_dir = $dirname;
        $len = strlen($prefix);
        if (strncmp($prefix, $class, $len) !== 0) {
            return;
        }
        $relative_class = substr($class, $len);
        $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';
        if (file_exists($file)) {
            require $file;
        }
    });
}

function autoload_add($dirname)
{
    set_include_path($dirname .
        PATH_SEPARATOR . get_include_path());
}

function api_link($str = '')
{
    return mw()->url_manager->api_link($str);
}
