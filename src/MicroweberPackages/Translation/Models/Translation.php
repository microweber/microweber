<?php

namespace MicroweberPackages\Translation\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\Paginator;
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
        if (!isset($filter['page'])) {
            $filter['page'] = 1;
        }

        $filter['namespace'] = '*';

        Paginator::currentPageResolver(function() use ($filter) {
            return $filter['page'];
        });

        $queryModel = static::query();
        $queryModel->where('namespace', $filter['namespace']);
        $queryModel->groupBy(\DB::raw("BINARY `key`"));

        if (isset($filter['search']) && !empty($filter['search'])) {
            $queryModel->where('key', 'like', '%'.$filter['search'].'%');
            $queryModel->orWhere('text', 'like', '%'.$filter['search'].'%');
        }

        $getTranslations = $queryModel->paginate(100);
        $pagination = $getTranslations->links("pagination::bootstrap-4");

        $group = [];

        foreach ($getTranslations as $translation) {

            $translationLocales = [];
            $getTranslationLocales = static::where('key', $translation->key)->get()->toArray();
            foreach ($getTranslationLocales as $translationLocale) {
                $translationLocales[$translationLocale['locale']] = $translationLocale['text'];
            }

            $group[$translation->key] = $translationLocales;
        }

        return ['results'=>$group,'pagination'=>$pagination];

    }
}
