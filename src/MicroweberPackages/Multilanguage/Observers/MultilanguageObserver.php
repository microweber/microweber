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

        $locale = $this->getLocale();
        $defaultLocale = $this->getDefaultLocale();

        if (!empty($model->translatable)) {

            $findTranslations = app()->multilanguage_repository->getTranslationsByRelTypeAndRelId($model->getTable(), $model->id);

            foreach ($model->translatable as $fieldName) {

                $modelAttributes = $model->getAttributes();

                if (!$modelAttributes) {
                    continue;
                }
                $found = false;
                foreach ($modelAttributes as $attrCheckKey=>$attrCheck){
                    if($attrCheckKey==$fieldName){
                        $found = true;
                    }
                }
                if (!$found) {
                    continue;
                }

                $multilanguage[$defaultLocale][$fieldName] = $model->$fieldName;

                if ($findTranslations !== null) {

                    foreach ($findTranslations as $findedTranslation) {
                        if ($findedTranslation['field_name'] == $fieldName) {

                            $multilanguage[$findedTranslation['locale']][$fieldName] = $this->_decodeCastValue($model, $fieldName, $findedTranslation['field_value']);

                            // Replace model fields if the default lang is different from current lang
                            if ($locale !== $defaultLocale) {
                                if (isset($multilanguage[$locale][$fieldName])) {
                                    $model->$fieldName = $multilanguage[$locale][$fieldName];
                                }
                            }
                        }
                    }
                }
            }
        }

    /*    if (isset($findTranslations)) {
            $model->multilanguage_translations_count = count($findTranslations);
        }*/

        $model->multilanguage = $multilanguage;
        $model->makeHidden(['multilanguage', 'translatable']);
    }

    /**
     * Handle the Page "saving" event.
     *
     * @param  \Illuminate\Database\Eloquent\Model $model
     * @return void
     */
    public function saved(Model $model)
    {
        if (self::$langToSave) {
            $langToSave = self::$langToSave;
        } else {
            $langToSave = $this->getLocale();

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

    }

    /**
     * Handle the "deleted" event.
     *
     * @param  \Illuminate\Database\Eloquent\Model $model
     * @return void
     */
    public function deleted(Model $model)
    {

        $table = $model->getTable();
        $rel_id = $model->id;
        if ($table and $rel_id) {
            MultilanguageTranslations::where('rel_type', $table)
                ->where('rel_id', $rel_id)
                ->delete();
        }

    }
    private function getTranslatableModuleOptions() {
        $modules = get_modules_from_db();
        $translatableModuleOptions = [];
        foreach ($modules as $module) {
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
