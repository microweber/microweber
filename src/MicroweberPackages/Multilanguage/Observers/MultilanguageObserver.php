<?php
/**
 * Created by PhpStorm.
 * Page: Bojidar
 * Date: 8/19/2020
 * Time: 2:53 PM
 */

namespace MicroweberPackages\Multilanguage\Observers;


use Illuminate\Database\Eloquent\Model;
use MicroweberPackages\Database\Eloquent\Builder\MultilanguageCachedBuilder;
use MicroweberPackages\Multilanguage\Models\MultilanguageTranslations;
use MicroweberPackages\Multilanguage\Models\Traits\MultilanguageReplaceValuesTrait;
use MicroweberPackages\Multilanguage\Repositories\MultilanguageRepository;

class MultilanguageObserver
{
    use MultilanguageReplaceValuesTrait;
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
             // replace values in model with multilanguage values
            $findTranslations = app()->multilanguage_repository->getTranslationsByRelTypeAndRelId($model->getMorphClass(), $model->id);
            if(!empty($findTranslations)){
                $model = $this->replaceMultilanguageValues($model, $findTranslations, $locale, $defaultLocale);
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





}
