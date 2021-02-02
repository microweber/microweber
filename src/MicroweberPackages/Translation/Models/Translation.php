<?php

namespace MicroweberPackages\Translation\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;

class Translation extends Model
{
    /** @var array */
    public $translatable = ['text'];

    /** @var array */
    public $guarded = ['id'];


    public static function getGroupedTranslations($page = 15, $namespace = '*')
    {
        $getTranslations = static::where('namespace', $namespace)->groupBy('key')->limit($page)->get();

        $group = [];

        foreach ($getTranslations->toArray() as $translation) {

            $translationLocales = [];
            $getTranslationLocales = static::where('key', $translation['key'])->get()->toArray();
            foreach ($getTranslationLocales as $translationLocale) {
                $translationLocales[$translationLocale['locale']] = $translationLocale['text'];
            }

            $group[$translation['key']] = $translationLocales;
        }

        return $group;

    }
}
