<?php

namespace MicroweberPackages\Multilanguage\Repositories;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use MicroweberPackages\Multilanguage\Models\MultilanguageTranslations;
use MicroweberPackages\Repository\Repositories\AbstractRepository;

class MultilanguageRepository extends AbstractRepository
{
    public $model = MultilanguageTranslations::class;


    public function getAllTranslationsByFieldNameAndRelType($fieldName, $relType)
    {
        return $this->cacheCallback(__FUNCTION__, func_get_args(), function () use ($fieldName, $relType) {

            $getMultilangTranslatesQuery = DB::table('multilanguage_translations');
            $getMultilangTranslatesQuery->select(['field_value', 'field_name', 'rel_type', 'rel_id']);
            $getMultilangTranslatesQuery->where('field_name', $fieldName);
            $getMultilangTranslatesQuery->where('rel_type', $relType);
            $getMultilangTranslatesQuery->whereNotNull('field_value');

            $translations = false;
            $executeQuery = $getMultilangTranslatesQuery->get();
            if ($executeQuery !== null) {
                $translations = collect($executeQuery)->map(function ($item) {
                    return (array)$item;
                })->toArray();
            }
            return $translations;
        });
    }

    public function getTranslationByFieldNameFieldValueAndRelType($fieldName, $fieldValue, $relType)
    {
        return $this->cacheCallback(__FUNCTION__, func_get_args(), function () use ($fieldName, $fieldValue, $relType) {

            $getMultilangTranslatesQuery = DB::table('multilanguage_translations');
            $getMultilangTranslatesQuery->select(['locale','field_name', 'field_value','rel_type','rel_id']);

            $getMultilangTranslatesQuery->where('field_name', $fieldName);
            $getMultilangTranslatesQuery->where('field_value', $fieldValue);
            //  $getMultilangTranslatesQuery->whereNotNull('field_value');

            if ($relType) {
                $getMultilangTranslatesQuery->where('rel_type', $relType);
            }

            $get = false;
            $executeQuery = $getMultilangTranslatesQuery->first();
            if ($executeQuery !== null) {
                $get = (array)$executeQuery;
            }
            return $get;
        });
    }

    public function getSupportedLocales($onlyActive = false)
    {
        try {

            return $this->cacheCallback(__FUNCTION__, func_get_args(), function () use ($onlyActive) {
                $getSupportedLocalesQuery = DB::table('multilanguage_supported_locales');
                if ($onlyActive) {
                    $getSupportedLocalesQuery->where('is_active', 'y');
                }
                $getSupportedLocalesQuery->orderBy('position', 'asc');

                $executeQuery = $getSupportedLocalesQuery->get();

                $languages = [];
                if ($executeQuery !== null) {
                    $languages = collect($executeQuery)->map(function ($item) {
                        return (array)$item;
                    })->toArray();
                }
                return $languages;
            });

        } catch (\Illuminate\Database\QueryException $e) {
            if (!Schema::hasTable('multilanguage_supported_locales')) {
                $system_refresh = new \MicroweberPackages\Install\DbInstaller();
                $system_refresh->createSchema();
                return false;
            } else {
                echo 'Caught exception: ', $e->getMessage(), "\n";
                exit();
            }

        }


    }

    public function getSupportedLocaleByLocale($locale)
    {
        return $this->cacheCallback(__FUNCTION__, func_get_args(), function () use ($locale) {
            $locale = DB::table('multilanguage_supported_locales')->where('locale', $locale)->first();
            $locale = (array)$locale;

            return $locale;
        });
    }

    public function getSupportedLocaleByDisplayLocale($display_locale)
    {
        return $this->cacheCallback(__FUNCTION__, func_get_args(), function () use ($display_locale) {
            $display_locale = DB::table('multilanguage_supported_locales')->where('display_locale', $display_locale)->first();
            $display_locale = (array)$display_locale;

            return $display_locale;
        });
    }

    public function getTranslationByLocale($locale)
    {
        return $this->cacheCallback(__FUNCTION__, func_get_args(), function () use ($locale) {
            $locale = DB::table('multilanguage_translations')->where('locale', $locale)->first();
            $locale = (array)$locale;

            return $locale;
        });
    }

    public function hasTranslationsByRelTypeAndRelId($relType, $relId)
    {
        $all_by_type = $this->cacheCallback(__FUNCTION__, [$relType], function () use ($relType) {
            $getMultilangTranslatesQuery = DB::table('multilanguage_translations');
            $getMultilangTranslatesQuery->select('rel_id');
            $getMultilangTranslatesQuery->where('rel_type', $relType);
            //  $getMultilangTranslatesQuery->whereNotNull('field_value');
            $rel_ids = $getMultilangTranslatesQuery->groupBy('rel_id')->pluck('rel_id')->toArray();

            if ($rel_ids) {
                $rel_ids = array_values($rel_ids);
                return $rel_ids;
            }


            return [];
        });


        if (is_array($all_by_type) and in_array($relId, $all_by_type)) {
            return true;
        }

    }

    public function getTranslationsByRelTypeAndRelId($relType, $relId)
    {
        if (!$this->hasTranslationsByRelTypeAndRelId($relType, $relId)) {
            return [];
        }

        return $this->cacheCallback(__FUNCTION__, func_get_args(), function () use ($relType, $relId) {
            $getMultilangTranslatesQuery = DB::table('multilanguage_translations');
            $getMultilangTranslatesQuery->select(['locale','field_name', 'field_value','rel_type','rel_id']);

            $getMultilangTranslatesQuery->where('rel_id', $relId);
            $getMultilangTranslatesQuery->where('rel_type', $relType);
            //  $getMultilangTranslatesQuery->whereNotNull('field_value');

            $executeQuery = $getMultilangTranslatesQuery->get();

            if ($executeQuery !== null) {
                $executeQuery = collect($executeQuery)->map(function ($item) {
                    return (array)$item;
                })->toArray();

                return $executeQuery;
            }

            return [];
        });
    }

    public static $_getTranslationsByRelTypeAndLocale = [];

    public function getTranslationsByRelTypeAndLocale($relType, $locale)
    {
        if (isset(self::$_getTranslationsByRelTypeAndLocale[$relType][$locale])) {
            return self::$_getTranslationsByRelTypeAndLocale[$relType][$locale];
        }

        return $this->cacheCallback(__FUNCTION__, func_get_args(), function () use ($relType, $locale) {

            $getMultilangTranslatesQuery = DB::table('multilanguage_translations');

            $getMultilangTranslatesQuery->select(['locale','field_name', 'field_value','rel_type','rel_id']);
            $getMultilangTranslatesQuery->where('locale', $locale);
            $getMultilangTranslatesQuery->where('rel_type', $relType);
            // $getMultilangTranslatesQuery->whereNotNull('field_value');

            $executeQuery = $getMultilangTranslatesQuery->get();
            if ($executeQuery !== null) {

                $executeQuery = collect($executeQuery)->map(function ($item) {
                    return (array)$item;
                })->toArray();

                self::$_getTranslationsByRelTypeAndLocale[$relType][$locale] = $executeQuery;

                return $executeQuery;
            }

            return [];
        });
    }

}
