<?php
/**
 * Created by PhpStorm.
 * Page: Bojidar
 * Date: 8/19/2020
 * Time: 2:53 PM
 */

namespace MicroweberPackages\Multilanguage\Observers;


use Illuminate\Database\Eloquent\Model;
use MicroweberPackages\Multilanguage\MultilanguageTranslations;

class MultilanguageObserver
{
    /**
     * Handle the Page "saving" event.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return void
     */
    public function saving(Model $model)
    {
        if (isset($model->translatable) && is_array($model->translatable)) {
            foreach ($model->translatable as $fieldName) {

                $findTranslate = MultilanguageTranslations::where('field_name', $fieldName)
                    ->where('rel_type', $model->getTable())
                    ->where('rel_id', $model->id)
                    ->where('locale', $this->getLocale())
                    ->first();

                if ($findTranslate) {
                    $findTranslate->field_value = $model->$fieldName;
                    $findTranslate->save();

                } else {
                    MultilanguageTranslations::create([
                        'field_name'=>$fieldName,
                        'field_value'=>$model->$fieldName,
                        'rel_type'=>$model->getTable(),
                        'rel_id'=>$model->id,
                        'locale'=>$this->getLocale()
                    ]);
                }
            }
        }
    }

    protected function getLocale()
    {
        return mw()->lang_helper->current_lang();
    }
}