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
     * The key that should be used when caching the query.
     *
     * @var string
     */
    protected $cacheKey;

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
    protected $cacheSeconds;

    /**
     * Execute the query as a "select" statement.
     *
     * @param  array|string $columns
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function get($columns = ['*'])
    {

        $builder = $this->applyScopes();

        // If we actually found models we will also eager load any relationships that
        // have been specified as needing to be eager loaded, which will solve the
        // n+1 query issue for the developers to avoid running a lot of queries.
        if (count($models = $builder->getModels($columns)) > 0) {
            $models = $builder->eagerLoadRelations($models);
        }

        $collection = $builder->getModel()->newCollection($models);

        dump($this->getCacheKey());

        return $collection;
    }


    /**
     * Get a unique cache key for the complete query.
     *
     * @return string
     */
    public function getCacheKey()
    {
        return $this->cachePrefix . '_' . ($this->cacheKey ?: $this->generateCacheKey());
    }

    /**
     * Generate the unique cache key for the query.
     *
     * @return string
     */
    public function generateCacheKey($appends = [])
    {
        $name = $this->getConnection()->getDatabaseName();

        foreach ($this->eagerLoad as $name => $constraints) {
            $relation = $this->getRelation($name);
            $appends[] = $relation->getQuery()->getModel()->getTable();
        }

        return hash('sha256', $name . $this->toSql() . implode('_', $appends) . serialize($this->getBindings()));
    }

}