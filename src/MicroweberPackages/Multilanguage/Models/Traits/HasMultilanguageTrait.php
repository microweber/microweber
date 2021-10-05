<?php

namespace MicroweberPackages\Multilanguage\Models\Traits;

use Illuminate\Support\Facades\DB;
use MicroweberPackages\Multilanguage\Models\MultilanguageTranslations;
use MicroweberPackages\Multilanguage\MultilanguageHelpers;
use MicroweberPackages\Multilanguage\Observers\MultilanguageObserver;

trait HasMultilanguageTrait
{
    private $_addMultilanguage = [];

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
            if (isset($model->attributes['multilanguage'])) {
                $model->_addMultilanguage = $model->attributes['multilanguage'];
                unset($model->attributes['multilanguage']);
            }
        });

        static::retrieved(function ($model) {
            if (MultilanguageHelpers::multilanguageIsEnabled()) {
                $mlobs = new MultilanguageObserver();
                $mlobs->retrieved($model);
            }
        });

        static::saved(function ($model) {
            if (MultilanguageHelpers::multilanguageIsEnabled()) {
                $model->_saveMultilanguageTranslation();
            }
        });

    }

    public function translations() {
        return $this->hasMany(MultilanguageTranslations::class, 'rel_id');
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
}
