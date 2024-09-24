<?php


use Illuminate\Foundation\Vite;
use Illuminate\Support\Facades\Vite as ViteFacade;

if (! function_exists('template_path')) {
    function template_path($name, $path = '')
    {
        $module = app('templates')->find($name);
        return $module->getPath().($path ? DIRECTORY_SEPARATOR.$path : $path);

    }
}


if (! function_exists('template_vite')) {
    /**
     * support for vite
     * @alias module_vite
     */
    function template_vite($module, $asset): Vite
    {

        return module_vite($module, $asset);
    }
}
