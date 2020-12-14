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
}