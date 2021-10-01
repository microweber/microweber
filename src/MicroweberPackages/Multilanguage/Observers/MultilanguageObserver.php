<?php
/**
 * Created by PhpStorm.
 * Page: Bojidar
 * Date: 8/19/2020
 * Time: 2:53 PM
 */

namespace MicroweberPackages\Multilanguage\Observers;


use Illuminate\Database\Eloquent\Model;
use MicroweberPackages\Multilanguage\Models\MultilanguageTranslations;
use MicroweberPackages\Multilanguage\Repositories\MultilanguageRepository;

class MultilanguageObserver
{
    protected static $multipleTranslationsToSave = false;
    protected static $langToSave = false;
    protected static $fieldsToSave = [];

    public function retrieved(Model $model)
    {
        $multilanguage = [];

        /**
         * Translatable module options
         *  This will append translatable fields in model option
         **/
        if (strpos($model->getMorphClass(), 'ModuleOption') !== false) {
            if (!empty($model->module)) {
                $translatableModuleOptions = $this->getTranslatableModuleOptions();
                if (isset($translatableModuleOptions[$model->module]) && in_array($model->option_key, $translatableModuleOptions[$model->module])) {
                    $model->translatable = ['option_value'];
                }
            }
        }

        if (!empty($model->translatable)) {

            $findTranslations = app()->multilanguage_repository->getTranslationsByRelTypeAndRelId($model->getTable(), $model->id);

            foreach ($model->translatable as $fieldName) {

                if (empty($model->$fieldName)) {
                    continue;
                }

                $multilanguage[$this->getDefaultLocale()][$fieldName] = $model->$fieldName;

                if ($findTranslations !== null) {
                    foreach ($findTranslations as $findedTranslation) {
                        if ($findedTranslation['field_name'] == $fieldName) {

                            $multilanguage[$findedTranslation['locale']][$fieldName] = $this->_decodeCastValue($model, $fieldName, $findedTranslation['field_value']);

                            // Replace model fields if the default lang is different from current lang
                            if ($this->getLocale() !== $this->getDefaultLocale()) {
                                if (isset($multilanguage[$this->getLocale()][$fieldName])) {
                                    $model->$fieldName = $multilanguage[$this->getLocale()][$fieldName];
                                }
                            }
                        }
                    }
                }
            }
        }

        $model->multilanguage = $multilanguage;
        $model->makeHidden(['multilanguage', 'translatable']);
    }
/*
    public function saving(Model $model)
    {
        if (isset($model->multilanguage)) {
            self::$multipleTranslationsToSave = $model->multilanguage;
            unset($model->multilanguage);
        }

        // Bug with custom field value
        if (strpos($model->getMorphClass(), 'CustomFieldValue') !== false) {
            return;
        }

        $langToSave = $this->getLocale();
        if (isset($model->lang)) {
            self::$langToSave = $model->lang;
            $langToSave = $model->lang;
            unset($model->lang);
        }

        if ($langToSave == $this->getDefaultLocale()) {
            return;
        }

        // Translatable module options
        if (strpos($model->getMorphClass(), 'ModuleOption') !== false) {
            if (!empty($model->module)) {
                $translatableModuleOptions = $this->getTranslatableModuleOptions();
                if (isset($translatableModuleOptions[$model->module]) && in_array($model->option_key, $translatableModuleOptions[$model->module])) {
                    $model->translatable = ['option_value'];
                }
            }
        }

        if (!empty($model->translatable)) {
            foreach ($model->translatable as $fieldName) {
                self::$fieldsToSave[$model->getTable()][$fieldName] = $model->$fieldName;
                $fieldValue = $model->getOriginal($fieldName);
                if (!empty($fieldValue)) {
                    $model->$fieldName = $fieldValue;
                }
            }
        }
    }*/

    /**
     * Handle the Page "saving" event.
     *
     * @param  \Illuminate\Database\Eloquent\Model $model
     * @return void
     */
    public function saved(Model $model)
    {
        $langToSave = $this->getLocale();
        if (self::$langToSave) {
            $langToSave = self::$langToSave;
        }

        if ($langToSave == $this->getDefaultLocale()) {
            return;
        }

        // Bug with custom field value
        if (strpos($model->getMorphClass(), 'CustomFieldValue') !== false) {
            return;
        }

        if (!empty($model->translatable)) {
            foreach ($model->translatable as $fieldName) {

                if (!isset(self::$fieldsToSave[$model->getTable()][$fieldName])) {
                    continue;
                }

                $findTranslate = MultilanguageTranslations::where('field_name', $fieldName)
                    ->where('rel_type', $model->getTable())
                    ->where('rel_id', $model->id)
                    ->where('locale', $langToSave)
                    ->first();

                $fieldValue = self::$fieldsToSave[$model->getTable()][$fieldName];
                $fieldValue = $this->_encodeCastValue($model, $fieldName, $fieldValue);

                if ($findTranslate) {
                    if (!is_null($fieldValue)) {
                        $findTranslate->field_value = $fieldValue;
                        $findTranslate->save();
                    }
                } else {
                    if (!is_null($fieldValue)) {
                        MultilanguageTranslations::create([
                            'field_name' => $fieldName,
                            'field_value' => $fieldValue,
                            'rel_type' => $model->getTable(),
                            'rel_id' => $model->id,
                            'locale' => $langToSave
                        ]);
                    }
                }
            }
            self::$fieldsToSave = [];
        }
        clearcache();
    }

    private function getTranslatableModuleOptions() {
        $translatableModuleOptions = [];
        foreach (get_modules_from_db() as $module) {
            if (isset($module['settings']['translatable_options'])) {
                $translatableModuleOptions[$module['module']] = $module['settings']['translatable_options'];
            }
        }
        return $translatableModuleOptions;
    }


    protected function getDefaultLocale()
    {
        return mw()->lang_helper->default_lang();
    }

    protected function getLocale()
    {
        return mw()->lang_helper->current_lang();
    }

    private function _catValue($model, $fieldName, $fieldValue, $type = 'encode')
    {
        if (isset($model->casts[$fieldName])) {
            $castType = $model->casts[$fieldName];
            if ($castType == 'json') {
                if ($type == 'encode') {
                    $fieldValue = json_encode($fieldValue );
                } else {
                    $fieldValue = json_decode($fieldValue, true);
                }
            }
        }

        return $fieldValue;
    }

    private function _encodeCastValue($model, $fieldName, $fieldValue) {
        return $this->_catValue($model, $fieldName, $fieldValue, 'encode');
    }

    private function _decodeCastValue($model, $fieldName, $fieldValue) {
        return $this->_catValue($model, $fieldName, $fieldValue, 'decode');
    }
}
