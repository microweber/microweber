<?php


namespace MicroweberPackages\Translation\Repositories;


use MicroweberPackages\Repository\Repositories\AbstractRepository;
use MicroweberPackages\Translation\Models\TranslationKey;
use MicroweberPackages\Translation\Models\TranslationKeyCached;

class TranslationKeyRepository extends AbstractRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public $model = TranslationKey::class;


    public function getTranslatedStrings($locale, $group, $namespace)
    {

        return $this->cacheCallback(__FUNCTION__, func_get_args(), function () use ($locale, $group, $namespace) {

            $result = [];
            $query = $this->getModel();

            $get = $query->select(['translation_text','translation_key'])->where('translation_group', $group)
                ->join('translation_texts', 'translation_keys.id', '=', 'translation_texts.translation_key_id')
                ->where('translation_texts.translation_locale', $locale)
                ->where('translation_namespace', $namespace)  ->get()         ;

            if ($get and $get->isNotEmpty()) {
                $result = $get->toArray();
            }
            return $result;

        });



    }





}
