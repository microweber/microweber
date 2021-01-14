<?php
/**
 * Created by PhpStorm.
 * User: Bojidar
 * Date: 12/14/2020
 * Time: 12:17 PM
 */

namespace MicroweberPackages\Database\Traits;

use function Clue\StreamFilter\fun;
use MicroweberPackages\Database\Eloquent\Builder\CachedBuilder;

trait CacheableQueryBuilderTrait
{


    public function newEloquentBuilder($query)
    {
        return new CachedBuilder($query);
    }

    /**
     * Get a new query builder instance for the connection.
     *
     * @return \Illuminate\Database\Query\Builder
     */
    protected function newBaseQueryBuilder()
    {
        $conn = $this->getConnection();

        $grammar = $conn->getQueryGrammar();

        $builder = new \MicroweberPackages\Database\Query\CachedBuilder($conn, $grammar, $conn->getPostProcessor());

        return $builder;
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
        $related_tables = [];

        $table = $model->getTable();

        $related_tables [] = $table;


        if (method_exists($model, 'getRelations')) {
            $relations = $model->getRelations();
            if ($relations) {
                foreach ($relations as $key => $relation) {
                    $related_tables [] = $key;
                }
            }
        }
        if (isset($this->cacheTagsToClear) and is_array($this->cacheTagsToClear)) {
            $tags = $this->cacheTagsToClear;
            $related_tables = array_merge($related_tables, $tags);
        }

        \Cache::tags($related_tables)->flush();

    }



}