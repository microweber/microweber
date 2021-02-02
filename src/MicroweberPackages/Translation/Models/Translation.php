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

    public static function boot() {
        parent::boot();

      /*  static::saving(function($model){
            clearcache();
        });
        static::updating(function($model){
            clearcache();
        });*/
    }

    public static function getGroupedTranslations($filter = [])
    {
        $filter['page'] = 200;
        $filter['namespace'] = '*';

        $queryModel = static::query();
        $queryModel->where('namespace', $filter['namespace']);
        $queryModel->groupBy(\DB::raw("BINARY `key`"));
        $queryModel->limit($filter['page']);

        if (isset($filter['search']) && !empty($filter['search'])) {
            $queryModel->where('key', 'like', '%'.$filter['search'].'%');
            $queryModel->orWhere('text', 'like', '%'.$filter['search'].'%');
        }

        $getTranslations = $queryModel->get();

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
