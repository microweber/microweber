<?php

namespace MicroweberPackages\Multilanguage\Models\Traits;

use Illuminate\Support\Facades\DB;
use MicroweberPackages\Multilanguage\Models\MultilanguageTranslations;
use MicroweberPackages\Multilanguage\MultilanguageHelpers;
use MicroweberPackages\Multilanguage\Observers\MultilanguageObserver;
use MicroweberPackages\Option\Models\ModuleOption;

trait HasMultilanguageTrait
{
    public $_enableMultilanguageRetrieving = true;
    public $isMultiLanguage = true;
    private array $_addMultilanguage = [];

    public function withoutMultilanguageRetrieving()
    {

        $this->_enableMultilanguageRetrieving = false;
        return $this;
    }

    public function isEnabledMultilanguageRetriving()
    {
        return $this->_enableMultilanguageRetrieving;
    }

    /** @var array $fillable */

    public function initializeHasMultilanguageTrait()
    {
        $this->fillable[] = 'multilanguage';
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

    public static function bootHasMultilanguageTrait()
    {

        static::saving(function ($model) {
            if (!MultilanguageHelpers::multilanguageIsEnabled()) {
                if (isset($model->attributes['lang'])) {
                    unset($model->attributes['lang']);
                }
                if (isset($model->attributes['multilanguage'])) {
                    unset($model->attributes['multilanguage']);
                }
            }
            if (MultilanguageHelpers::multilanguageIsEnabled()) {
                $defaultLocale = mw()->lang_helper->default_lang();
                $modelClass = get_class($model);
                $skipModelClasses = [
                    ModuleOption::class,
                ];
                $isModuleOptions = false;
                if (in_array($modelClass, $skipModelClasses)) {
                    $isModuleOptions = true;

                }




                if ($isModuleOptions and !isset($model->attributes['multilanguage']) and isset($model->attributes['lang'])) {
                // When receive a save_option
                    // legacy save_option will not have multilanguage attribute, it will have lang attribute instead
                    if (isset($model->attributes['lang']) && isset($model->attributes['module']) and $model->attributes['lang'] != $defaultLocale) {
                        $translatableModuleOptions = self::getTranslatableModuleOptions();
                        if (isset($translatableModuleOptions[$model->attributes['module']])) {
                            $translatableModuleOptionKeys = $translatableModuleOptions[$model->attributes['module']];
                            if (in_array($model->attributes['option_key'], $translatableModuleOptionKeys)) {
                                $model->_addMultilanguage['option_value'][$model->attributes['lang']] = $model->attributes['option_value'];
                                unset($model->attributes['option_value']);
                            }
                        }

                    }


                    if (isset($model->attributes['lang'])) {
                        unset($model->attributes['lang']);
                    }
                    if (isset($model->attributes['multilanguage'])) {
                        unset($model->attributes['multilanguage']);
                    }

                }

                if (isset($model->attributes['lang'])) {
                    unset($model->attributes['lang']);
                }
                if (isset($model->attributes['multilanguage_translatons'])) {
                    unset($model->attributes['multilanguage_translatons']);
                }


                 /**
                 * When you add multilanguage fields
                 *
                 * EXAMPLE:
                 * multilanguage[title][en_US]    "Apple+iTunes+KSA+SAR+50"
                 * multilanguage[title][ar]    "Apple Ar"
                 */
                if (isset($model->attributes['multilanguage']) and empty($model->attributes['multilanguage'])) {
                    unset($model->attributes['multilanguage']);
                }
                if (isset($model->attributes['multilanguage']) and !empty($model->attributes['multilanguage'])) {

                    $model->_addMultilanguage = $model->attributes['multilanguage'];
                    unset($model->attributes['multilanguage']);

                }

                // Backup the original model fields
                if (isset($model->translatable)) {
                    foreach ($model->translatable as $translatableField) {
                        if (!isset($model->attributes[$translatableField])) {
                            $orig = $model->getOriginal($translatableField);
                            if ($orig) {
                                $model->$translatableField = $orig;
                            }
                        }
                    }
                    // Append to model if we want to save changes for original model
                    if (!empty($model->_addMultilanguage)) {

                        foreach ($model->_addMultilanguage as $field => $multilanguage) {
                            if (isset($multilanguage[$defaultLocale])) {
                                $model->$field = $multilanguage[$defaultLocale];
                            }
                        }
                    }
                }
            }
        });

        static::retrieved(function ($model) {


            if ($model->isEnabledMultilanguageRetriving()) {
                if (MultilanguageHelpers::multilanguageIsEnabled()) {
                    $mlobs = new MultilanguageObserver();
                    $mlobs->retrieved($model);
                }
            }
        });

        static::saved(function ($model) {
            if (MultilanguageHelpers::multilanguageIsEnabled()) {
                $model->_saveMultilanguageTranslation();
            }
        });

        static::deleted(function ($model) {

            if (MultilanguageHelpers::multilanguageIsEnabled()) {
                $mlobs = new MultilanguageObserver();
                $mlobs->deleted($model);
            }
        });
    }

    public function translations()
    {
        return $this->hasMany(MultilanguageTranslations::class, 'rel_id');
    }

    public function getTranslationsFormated()
    {
        $formated = [];

        $locale = mw()->lang_helper->default_lang();
        if (!empty($this->translatable)) {
            foreach ($this->translatable as $fieldName) {
                $formated[$locale][$fieldName] = $this->getOriginal($fieldName);
            }
        }

        if (!empty($this->translations)) {
            foreach ($this->translations as $translation) {
                $formated[$translation->locale][$translation->field_name] = $translation->field_value;
            }
        }

        return $formated;
    }

    public function _saveMultilanguageTranslation()
    {
        if (empty($this->_addMultilanguage)) {
            return;
        }

        $model = $this;
        $defaultLocale = $this->__getDefaultLocale();
        $localeFieldNames = array_keys($this->_addMultilanguage);

        if (!empty($localeFieldNames)) {
            // clean up saved values for default locale, as they are retreived from the model
            MultilanguageTranslations::whereIn('field_name', $localeFieldNames)
                ->where('rel_type', $model->getTable())
                ->where('rel_id', $model->id)
                ->where('locale', $defaultLocale)
                ->delete();
        }

        DB::transaction(function () use ($model, $defaultLocale) {
            foreach ($this->_addMultilanguage as $fieldName => $field) {
                foreach ($field as $fieldLocale => $fieldValue) {

                    if ($fieldLocale == $defaultLocale) {
                        // skip saving default locale , its saved in the model
                        continue;
                    }

                    $findTranslate = MultilanguageTranslations::where('field_name', $fieldName)
                        ->where('rel_type', $model->getTable())
                        ->where('rel_id', $model->id)
                        ->where('locale', $fieldLocale)
                        ->first();

                    if ($fieldValue == null and $findTranslate != null) {
                        $findTranslate->delete();
                        $model->refresh();
                        continue;
                    }

                    if ($fieldValue == null) {
                        continue;
                    }
                    if ($findTranslate == null) {
                        $findTranslate = new MultilanguageTranslations();
                        $findTranslate->field_name = $fieldName;
                        $findTranslate->rel_type = $model->getTable();
                        $findTranslate->rel_id = $model->id;
                        $findTranslate->locale = $fieldLocale;
                    }

                    $findTranslate->field_value = $fieldValue;
                    $findTranslate->save();
                    $model->refresh();
                }
            }
        });
    }

    public static function getTranslatableModuleOptions()
    {

        return MultilanguageHelpers::getTranslatableModuleOptions();
    }

}
