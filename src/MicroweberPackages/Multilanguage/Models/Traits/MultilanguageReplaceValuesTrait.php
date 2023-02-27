<?php

namespace MicroweberPackages\Multilanguage\Models\Traits;


trait MultilanguageReplaceValuesTrait
{
    protected function getDefaultLocale()
    {
        return mw()->lang_helper->default_lang();
    }

    protected function getLocale()
    {
        return mw()->lang_helper->current_lang();
    }

    public function replaceMultilanguageValues($model,array $findTranslations = [], string $locale, string $defaultLocale)
    {

        if (!empty($model->translatable) and !empty($findTranslations)) {

        //  $findTranslations = app()->multilanguage_repository->getTranslationsByRelTypeAndRelId($model->getTable(), $model->id);

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
        return $model;
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
