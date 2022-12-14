<?php

namespace MicroweberPackages\Database\Eloquent\Builder;


use MicroweberPackages\Multilanguage\Models\Traits\MultilanguageReplaceValuesTrait;

class MultilanguageCachedBuilder extends CachedBuilder
{
    use MultilanguageReplaceValuesTrait;

    public function get($columns = ['*'])
    {
        $collection = parent::get($columns);

        //todo add multilanguage
        // $collection = $this->getMultilanguageFields($collection);
        return $collection;
    }

    public function first($columns = ['*'])
    {
        $collectionItem = parent::first($columns);
//
//        if ($collectionItem) {
//            $newCollection = new \Illuminate\Database\Eloquent\Collection();
//            $newCollection->add($collectionItem);
//            $newCollection = $this->getMultilanguageFields($newCollection);
//            $collectionItem = $newCollection->first();
//
//        }

        return $collectionItem;
    }

    public function getMultilanguageFields($collection)
    {

        $newCollection = $collection;
        if ($collection instanceof \Illuminate\Database\Eloquent\Collection) {
            //  $newCollection = new \Illuminate\Database\Eloquent\Collection();
            $newCollectionForTranslate = new \Illuminate\Database\Eloquent\Collection();
            $newCollectionForTranslatedAll = new \Illuminate\Database\Eloquent\Collection();

            foreach ($newCollection as $item) {
                if (is_object($item) and isset($item->isMultiLanguage) and $item->isMultiLanguage) {
                    //   $item->title='test';
                    $newCollectionForTranslate->add($item);

                }
                $newCollection->add($item);

            }


            if ($newCollectionForTranslate->count() > 0) {
                $locale = $this->getLocale();
                $defaultLocale = $this->getDefaultLocale();

                //get translated fields
                $newCollectionForTranslate = $this->getTranslatedFields($newCollectionForTranslate);
                foreach ($newCollectionForTranslate as $model) {
                    $model = $this->translateModelItemValues($model, $locale, $defaultLocale);

                    $newCollectionForTranslatedAll->add($model);
                }

                //  $newCollection->merge ($newCollectionForTranslatedAll);

                //$newCollection->merge($newCollectionForTranslatedAll);
            }

            if ($newCollectionForTranslate->count() > 0) {
                if ($newCollectionForTranslatedAll->count() > 0) {
                    foreach ($newCollection as &$item) {
                        foreach ($newCollectionForTranslatedAll as $itemForTranslate) {
                            if ($item->id == $itemForTranslate->id) {
                                $item = $itemForTranslate;
                            }
                        }
                    }
                }
            }
        }

        return $newCollection;

    }

    public function getTranslatedFields($collection)
    {
        $collection->load('translations');
        return $collection;

    }

    public function translateModelItemValues($model, $locale, $defaultLocale)
    {

        /** @var MultilanguageCachedBuilder $model */

        $arrayItem = $model->toArray();
        if (isset($arrayItem['translations'])) {
            $findTranslations = $arrayItem['translations'];
            $model->replaceMultilanguageValues($model, $findTranslations, $locale, $defaultLocale);
        }
        return $model;
    }
}
