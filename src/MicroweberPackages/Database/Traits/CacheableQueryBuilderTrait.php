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

    public function newEloquentBuilder($query)
    {
        return new CachedBuilder($query);
    }

    /**
     * Get a new query builder instance for the connection.
     *
     * @return \Illuminate\Database\Query\Builder
     */
    /*protected function newBaseQueryBuilder()
    {
        $conn = $this->getConnection();

        $grammar = $conn->getQueryGrammar();

        $builder = new \MicroweberPackages\Database\Query\Builder\CachedBuilder($conn, $grammar, $conn->getPostProcessor());

        return $builder;
    }*/
}