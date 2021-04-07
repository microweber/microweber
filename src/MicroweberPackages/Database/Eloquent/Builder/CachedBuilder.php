<?php
/**
 * Created by PhpStorm.
 * User: Bojidar
 * Date: 12/14/2020
 * Time: 12:15 PM
 */

namespace MicroweberPackages\Database\Eloquent\Builder;

use function Opis\Closure\serialize as serializeClosure;
use function Opis\Closure\unserialize as unserializeClosure;

class CachedBuilder extends \Illuminate\Database\Eloquent\Builder
{
    /**
     * A cache prefix.
     *
     * @var string
     */
    protected $cachePrefix = 'cached_model';

    /**
     * The tags for the query cache.
     *
     * @var array
     */
    protected $cacheTags;


    /**
     * The number of seconds to cache the query.
     *
     * @var int
     */
    protected $cacheSeconds = 3600; // 1 hour

    /**
     * Is cache disabled
     *
     * @var boolean
     */
    protected $cacheIsDisabled = false;


    /**
     * Execute the query as a "select" statement.
     *
     * @param  array|string $columns
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function get($columns = ['*'])
    {
        if (!defined('MW_INSTALL_CONTROLLER')) {
            $is_disabled = \Config::get('microweber.disable_model_cache');

            if (!$is_disabled) {
                $is_disabled = $this->cacheIsDisabled;
            }
        } else {
            $is_disabled = true;
        }



        if (!$is_disabled) {
            $cacheKey = $this->getCacheKey($columns);
            $cacheTags = $this->generateCacheTags();

            $cacheFind = \Cache::tags($cacheTags)->get($cacheKey);
            if ($cacheFind) {
                return $cacheFind;
            }
        }
        $builder = $this->applyScopes();

        // If we actually found models we will also eager load any relationships that
        // have been specified as needing to be eager loaded, which will solve the
        // n+1 query issue for the developers to avoid running a lot of queries.
        if (count($models = $builder->getModels($columns)) > 0) {
            $models = $builder->eagerLoadRelations($models);
        }

        $collection = $builder->getModel()->newCollection($models);
        if (!$is_disabled) {
            \Cache::tags($cacheTags)->put($cacheKey, $collection, $this->cacheSeconds);
        }
        return $collection;
    }


//
//    /**
//     * Get a base query builder instance.
//     *
//     * @return \Illuminate\Database\Query\Builder
//     */
//    public function toBase()
//    {
//
//        $is_disabled = \Config::get('microweber.disable_model_cache');
//
//        if (!$is_disabled) {
//            $cacheKey = $this->getCacheKey('toBase');
//            $cacheTags = $this->generateCacheTags();
//
//            $cacheFind = \Cache::tags($cacheTags)->get($cacheKey);
//            if ($cacheFind) {
//                return $cacheFind;
//            }
//        }
//        $query  = $this->applyScopes()->getQuery();
//
//        if (!$is_disabled) {
//            \Cache::tags($cacheTags)->put($cacheKey, $query, $this->cacheSeconds);
//        }
//
//
//
//        return $query;
//    }




    public function disableCache($isDisabled=true)
    {
        return $this->cacheIsDisabled  = $isDisabled;
    }

    /**
     * Get a unique cache key for the complete query.
     *
     * @return string
     */
    public function getCacheKey($appends)
    {
        return $this->cachePrefix . '_' . $this->generateCacheKey($appends);
    }

    /**
     * Generate the unique cache key for the query.
     *
     * @return string
     */
    public function generateCacheKey($appends = [])
    {
        $name = $this->getConnection()->getDatabaseName();
        $key = md5($name . $this->toSql() . implode('_', $this->generateCacheTags()) . serialize($this->getBindings()) . implode('_', $appends) . app()->getLocale());

        // dump($this->toSql(),$this->getBindings());

        return $key;
    }

    public function generateCacheTags()
    {
        $tags = [];
        $tags[] = $this->getModel()->getTable();

        if ($this->eagerLoad) {
            foreach ($this->eagerLoad as $name => $constraints) {
                $relation = $this->getRelation($name);
                $tags[] = $relation->getQuery()->getModel()->getTable();
            }
        }

        return $tags;
    }

    public function insert(array $values)
    {
        $this->_clearModelTaggedCache();
        return parent::insert($values);
    }

    public function delete()
    {
        $this->_clearModelTaggedCache();
        return parent::delete();
    }

    public function update(array $values)
    {
        $this->_clearModelTaggedCache();
        return parent::update($values);
    }

    public function findOrNew($id, $columns = ['*'])
    {
        $this->_clearModelTaggedCache();
        return parent::findOrNew($id, $columns);
    }

    public function firstOrCreate(array $attributes = [], array $values = [])
    {
        $this->_clearModelTaggedCache();
        return parent::firstOrCreate($attributes, $values);
    }

    public function updateOrCreate(array $attributes, array $values = [])
    {

        $this->_clearModelTaggedCache();
        return parent::updateOrCreate($attributes, $values);
    }

    public function updateOrInsert(array $attributes, array $values = [])
    {
        $this->_clearModelTaggedCache();
        return parent::updateOrInsert($attributes, $values);
    }

    private function _clearModelTaggedCache()
    {
        $tags = $this->generateCacheTags();

        \Cache::tags($tags)->flush();
    }


}