<?php

namespace MicroweberPackages\Multilanguage;


use Illuminate\Support\Facades\DB;
use MicroweberPackages\Option\Repositories\OptionRepository;

class MultilanguageHelpers
{

    public static $isEnabled = false;

    public static function multilanguageIsEnabled()
    {

        if (!self::$isEnabled) {
            return false;
        }

        if (defined('MW_DISABLE_MULTILANGUAGE') and MW_DISABLE_MULTILANGUAGE == true) {
            return false;
        }

        return self::$isEnabled;
    }

    public static function setMultilanguageEnabled($enabled)
    {



        return self::$isEnabled = $enabled;
    }

    public static function getSupportedLanguages($onlyActive = true)
    {

        return get_supported_languages($onlyActive);
    }

    public static function getTranslatableModuleOptions()
    {
        $translatableModuleOptions = [];

        // legacy modules, will be removed in future
        $modules = mw()->module_manager->get_modules('ui=any&installed=1');
        if ($modules) {
            foreach ($modules as $module) {
                if (isset($module['settings']['translatable_options'])) {
                    $translatableModuleOptions[$module['module']] = $module['settings']['translatable_options'];
                }
            }
        }


        // new way to get translatable options from laravel modules
        $modulesFromRegistry = app()->microweber->getTranslatableOptionKeys();
        if ($modulesFromRegistry) {
            foreach ($modulesFromRegistry as $module => $keys) {
                if (!empty($keys)) {
                    $translatableModuleOptions[$module] = $keys;
                }
            }
        }

        return $translatableModuleOptions;
    }

}
