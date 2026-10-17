<?php

namespace MicroweberPackages\Module\Concerns;

/**
 * Extracted from ModuleManager to decompose the god class. Composed back via
 * `use`, so $this state and inter-method calls are unchanged.
 */
trait ResolvesModulePaths
{
    public function exists($module_name)
    {
        if (!is_string($module_name)) {
            return false;
        }

        if (!mw_is_installed()) {
            return false;
        }
        if (trim($module_name) == '') {
            return false;
        }

        if (isset($this->app->parser->module_registry[$module_name]) and $this->app->parser->module_registry[$module_name]) {
            return true;
        } else if (isset($this->app->parser->module_registry[$module_name . '/index']) and $this->app->parser->module_registry[$module_name . '/index']) {
            return true;

        }

        if (app()->bound('microweber')) {
            if (app()->microweber->hasModule($module_name)) {
                return true;
            }
        }

        if (app()->bound('modules')) {

            if (app()->modules->find($module_name)) {
                return true;
            }
        }

        global $mw_loaded_mod_memory;

        if (!isset($mw_loaded_mod_memory[$module_name])) {
            $ch = $this->locate($module_name, $custom_view = false);
            if ($ch != false) {
                $mw_loaded_mod_memory[$module_name] = true;
            } else {
                $mw_loaded_mod_memory[$module_name] = false;
            }
        }

        return $mw_loaded_mod_memory[$module_name];
    }

    /* @deprecated */
    public function locate($module_name, $custom_view = false, $no_fallback_to_view = false)
    {
        // Historically did template/view fallback resolution; that body became
        // unreachable (an early return) long ago. Resolve via dir() directly.
        return $this->dir($module_name);
    }

    public function info($module_name)
    {
        $module_name = preg_replace('/admin$/', '', $module_name);
        $module_name = rtrim($module_name, '/');

        $return = [];
        if (app()->bound('modules')) {
            $module = app()->modules->find($module_name);

            if ($module) {

                $name = $module->getName();
                $description = $module->getDescription();
                $return['module'] = $module->getLowerName();
                $return['name'] = $name;
                $return['description'] = $description;
               $return['icon'] = 'mw-general';
                // $return['iconSvg'] =$module->getModuleIcon();

                $checkIfWeHaveIconInconfig = config('modules.'.$return['module'].'.icon');

                 if($checkIfWeHaveIconInconfig){
                     $return['icon']  = $checkIfWeHaveIconInconfig;
                 }

            }
        }
        return $return;

    }

    /* @deprecated */
    public function path($module_name)
    {
        return $this->dir($module_name);
    }

    /* @deprecated */
    public function dir($module_name)
    {
        $mod = app()->modules->find($module_name);
        if ($mod) {
            return normalize_path($mod->getPath());
        }
    }

    public function is_installed($module_name)
    {

        if (!mw_is_installed()) {
            return false;
        }
        $module_name = trim($module_name);
        $module_namei = $module_name;
        if (strstr($module_name, 'admin')) {
            $module_namei = str_ireplace('\\admin', '', $module_namei);
            $module_namei = str_ireplace('/admin', '', $module_namei);
        }

        if (app()->bound('microweber')) {
            if (app()->microweber->hasModule($module_name)) {
                return true;
            }
        }

        if (app()->bound('modules')) {
            $module = app()->modules->find($module_name);
            if ($module && $module->isEnabled()) {
                return true;
            }
        }

    }
}
