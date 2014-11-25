<?php




function load_all_functions_files_for_modules($options = false)
{
    $is_installed = Config::get('weber.is_installed');

    if (!$is_installed) {
        return;
    }
    $modules = mw()->modules->get('ui=any&installed=1&limit=99999');

    // $modules = mw()->modules->where('installed', 1)->remember(50)->get();
    $files = array();
    if (!empty($modules)) {
        foreach ($modules as $module) {
            if (isset($module['module'])) {
                $is_function = normalize_path(modules_path() . $module['module'] . DS . 'functions.php', false);
                if (is_file($is_function)) {
                    include_once($is_function);
                    $files[] = ($is_function);
                }

            }
        }
        return $files;
    }

}


function module_info($module_name)
{
    return mw()->modules->info($module_name);
}


function module_name_decode($module_name)
{
    $module_name = str_replace('__', '/', $module_name);
    return $module_name;

}

function module_name_encode($module_name)
{
    $module_name = str_replace('/', '__', $module_name);
    $module_name = str_replace('\\', '__', $module_name);
    return $module_name;

}
