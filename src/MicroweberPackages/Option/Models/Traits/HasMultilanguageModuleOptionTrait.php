<?php
namespace MicroweberPackages\Option\Models\Traits;

trait HasMultilanguageModuleOptionTrait {

    private $_addMultilanguage = [];

    public static function bootHasMultilanguageModuleOptionTrait()
    {
        static::saving(function ($model) {
            /**
             * EXAMPLE:
             * multilanguage[title][en_US]	"Apple+iTunes+KSA+SAR+50"
             * multilanguage[title][ar]	"Apple Ar"
             */
            if (isset($model->attributes['lang']) && isset($model->attributes['module'])) {
                $translatableModuleOptions = self::getTranslatableModuleOptions();
                if (isset($translatableModuleOptions[$model->attributes['module']])) {
                    $translatableModuleOptionKeys = $translatableModuleOptions[$model->attributes['module']];
                    if (in_array($model->attributes['option_key'], $translatableModuleOptionKeys)) {
                        $model->_addMultilanguage['option_value'][$model->attributes['lang']] = $model->attributes['option_value'];
                    }
                }
                unset($model->attributes['lang']);
            }
        });
    }

    public static function getTranslatableModuleOptions() {
        $translatableModuleOptions = [];
        foreach (get_modules_from_db() as $module) {
            if (isset($module['settings']['translatable_options'])) {
                $translatableModuleOptions[$module['module']] = $module['settings']['translatable_options'];
            }
        }
        return $translatableModuleOptions;
    }
}
