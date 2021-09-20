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


    public function getTranslatedNamespacesThatHaveTexts()
    {



            try {
                return $this->cacheCallback(__FUNCTION__, func_get_args(), function ()   {

                $result = [];

                $translation_namespaces = \DB::table('translation_keys')
                    ->select('translation_namespace')
                    ->join('translation_texts', 'translation_keys.id', '=', 'translation_texts.translation_key_id')
                    ->groupBy('translation_namespace')
                    ->get();

                if ($translation_namespaces) {
                    $result = $translation_namespaces->toArray();
                    if ($result and is_array($result)) {
                        $result = array_map(function ($value) {
                            return (array)$value;
                        }, $result);
                        $result = array_values($result);
                        $result = array_flatten($result);
                        $result = array_flip($result);
                        $result = array_keys($result);
                    }

                }

                return $result;
                });

            } catch (\Illuminate\Database\QueryException $e) {
                if (!\Schema::hasTable('translation_keys')) {
                    $system_refresh = new \MicroweberPackages\Install\DbInstaller();
                    $system_refresh->createSchema();
                    return false;
                } else {
                    echo 'Caught exception: ', $e->getMessage(), "\n";
                    exit();
                }

            }







    }

    public function getTranslatedStrings($locale, $group, $namespace)
    {

        $namespaces = $this->getTranslatedNamespacesThatHaveTexts();
        if (!is_array($namespaces) or (!in_array($namespace, $namespaces))) {
            return [];
        }


        return $this->cacheCallback(__FUNCTION__, func_get_args(), function () use ($locale, $group, $namespace) {

            $result = [];
            $query = $this->getModel();

            $get = $query->select(['translation_text', 'translation_key'])->where('translation_group', $group)
                ->join('translation_texts', 'translation_keys.id', '=', 'translation_texts.translation_key_id')
                ->where('translation_texts.translation_locale', $locale)
                ->where('translation_namespace', $namespace)->get();

            if ($get and $get->isNotEmpty()) {
                $result = $get->toArray();
            }
            return $result;

        });


    }


}
