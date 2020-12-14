<?php
/**
 * Created by PhpStorm.
 * User: Bojidar
 * Date: 12/14/2020
 * Time: 12:17 PM
 */

namespace MicroweberPackages\Database\Traits;

use MicroweberPackages\Database\Eloquent\Builder\CachedBuilder;

trait CacheableQueryBuilderTrait
{
/*
    public function newEloquentBuilder($query)
    {
        return new CachedBuilder($query);
    }


    protected static function bootCacheableQueryBuilderTrait()
    {

        static::saving(function ($model) {
            $model->_clearModelCache($model);
        });

        static::creating(function ($model) {
            $model->_clearModelCache($model);
        });

        static::updating(function ($model) {
            $model->_clearModelCache($model);
        });

        static::deleting(function ($model) {
            $model->_clearModelCache($model);
        });

    }


    private function _clearModelCache($model)
    {
        $table = $model->getTable();
        \Cache::tags($table)->flush();
    }*/

}