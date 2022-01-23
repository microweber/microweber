<?php

namespace MicroweberPackages\Multilanguage\Listeners;

use Illuminate\Support\Facades\DB;
use MicroweberPackages\Multilanguage\Models\MultilanguageTranslations;

class MultilanguageEventListener
{
    public function handle($event)
    {
        $defaultLocale = $this->getDefaultLocale();
        $model = $event->getModel();
        $data = $event->getData();

        if (isset($data['multilanguage']) && is_array($data['multilanguage']) && !empty($data['multilanguage'])) {
            DB::transaction(function () use ($data, $model, $defaultLocale) {
                foreach ($data['multilanguage'] as $fieldName => $field) {
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

        clearcache();
    }

    protected function getDefaultLocale()
    {
        return mw()->lang_helper->default_lang();
    }

    protected function getLocale()
    {
        return mw()->lang_helper->current_lang();
    }
}
