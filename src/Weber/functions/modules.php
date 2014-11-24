<?php




function load_all_functions_files_for_modules($options = false)
{
    $is_installed = Config::get('weber.is_installed');

    if (!$is_installed) {
        return;
    }
    $modules = wb()->modules->get('ui=any&installed=1');
   // $modules = wb()->modules->where('installed', 1)->remember(50)->get();
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

function module_css_class($module_name)
{
    $slug = Str::slug($module_name);
    return $slug;
}

function module_info($module_name)
{
   return wb()->modules->info($module_name);
}