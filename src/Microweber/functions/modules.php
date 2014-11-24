<?php




function mw_get_all_functions_files_for_modules($options = false)
{
    $is_installed = Config::get('microweber.is_installed');

    if (!$is_installed) {
        return;
    }

    $modules = Module::cacheTags('modules')->where('installed', 1)->remember(50)->get();

    if(!$modules->isEmpty() ){
        dd('aaaaaaaaaaaaaa');
        dd($modules);
    }
    dd('zzzzzz');
    //$modules= Module::all();



}