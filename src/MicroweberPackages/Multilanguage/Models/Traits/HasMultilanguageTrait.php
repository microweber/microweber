<?php

namespace MicroweberPackages\Multilanguage\Models\Traits;


use Illuminate\Support\Facades\DB;
use MicroweberPackages\Multilanguage\Models\MultilanguageTranslations;
use MicroweberPackages\Multilanguage\Observers\MultilanguageObserver;

trait HasMultilanguageTrait
{

    private $_addMultilanguage = [];

    public function initializeHasMultilanguageTrait()
    {
        $this->fillable[] = 'multilanguage';
    }

    protected function __getDefaultLocale()
    {
        return mw()->lang_helper->default_lang();
    }

    protected function __getLocale()
    {
        return mw()->lang_helper->current_lang();
    }

    public static function bootHasMultilanguageTrait()
    {

        static::retrieved(function ($model) {
            $mlobs = new MultilanguageObserver();
            $mlobs->retrieved($model);
        });

        static::saving(function ($model) {
            if (isset($model->attributes['multilanguage'])) {
                $model->_addMultilanguage = $model->attributes['multilanguage'];
                unset($model->attributes['multilanguage']);
            }
        });

        static::created(function ($model) {
            $model->_saveMultilanguageTranslation();
        });

        static::saved(function ($model) {
            $model->_saveMultilanguageTranslation();
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
}
