<?php
namespace MicroweberPackages\Option\Models\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use MicroweberPackages\Multilanguage\Models\MultilanguageTranslations;
use MicroweberPackages\Multilanguage\MultilanguageHelpers;

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

        static::saved(function ($model) {
            if (MultilanguageHelpers::multilanguageIsEnabled()) {
                $model->_saveMultilanguageTranslation();
            }
        });
    }

    public function _saveMultilanguageTranslation()
    {
        if (empty($this->_addMultilanguage)) {
            return;
        }

        $model = $this;
        $defaultLocale = $this->__getDefaultLocale();

        DB::transaction(function () use ($model, $defaultLocale) {
            foreach ($this->_addMultilanguage as $fieldName => $field) {
                foreach ($field as $fieldLocale => $fieldValue) {
                    if ($fieldLocale == $defaultLocale) {
                        continue;
                    }

                    $findTranslate = MultilanguageTranslations::where('field_name', $fieldName)
                        ->where('rel_type', $model->getTable())
                        ->where('rel_id', $model->id)
                        ->where('locale', $fieldLocale)
                        ->first();
                    
                    if ($findTranslate == null) {
                        $findTranslate = new MultilanguageTranslations();
                        $findTranslate->field_name = $fieldName;
                        $findTranslate->rel_type = $model->getTable();
                        $findTranslate->rel_id = $model->id;
                        $findTranslate->locale = $fieldLocale;
                    }

                    $findTranslate->field_value = $fieldValue;
                    $findTranslate->save();
                }
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

    private static $__getDefaultLocale = false;
    protected function __getDefaultLocale()
    {
        if (self::$__getDefaultLocale) {
            return self::$__getDefaultLocale;
        }

        self::$__getDefaultLocale = mw()->lang_helper->default_lang();

        return self::$__getDefaultLocale;
    }

    private static $__getLocale = false;
    protected function __getLocale()
    {
        if (self::$__getLocale) {
            return self::$__getLocale;
        }
        self::$__getLocale = mw()->lang_helper->current_lang();
        return self::$__getLocale;
    }
}
