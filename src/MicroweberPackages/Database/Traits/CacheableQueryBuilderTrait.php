<?php
/**
 * Created by PhpStorm.
 * User: Bojidar
 * Date: 12/14/2020
 * Time: 12:17 PM
 */

namespace MicroweberPackages\Database\Traits;

use MicroweberPackages\Database\Eloquent\Builder\MultilanguageCachedBuilder;
use MicroweberPackages\Multilanguage\MultilanguageHelpers;
use function Clue\StreamFilter\fun;
use MicroweberPackages\Database\Eloquent\Builder\CachedBuilder;

trait CacheableQueryBuilderTrait
{


    public function newEloquentBuilder($query)
    {
        if (MultilanguageHelpers::multilanguageIsEnabled()) {
            return new MultilanguageCachedBuilder($query);
        } else {
            return new CachedBuilder($query);
        }
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
        $relatedTables = [];

        $table = $model->getTable();

        $relatedTables[] = $table;


        if (method_exists($model, 'getRelations')) {
            $relations = $model->getRelations();
            if ($relations) {
                foreach ($relations as $key => $relation) {
                    $relatedTables[] = $key;
                }
            }
        }
        if (isset($this->cacheTagsToClear) and is_array($this->cacheTagsToClear)) {
            $tags = $this->cacheTagsToClear;
            $relatedTables = array_merge($relatedTables, $tags);
        }

        \Cache::tags($relatedTables)->flush();

    }


}
