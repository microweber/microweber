<?php
/**
 * Created by PhpStorm.
 * User: Bojidar
 * Date: 12/14/2020
 * Time: 12:15 PM
 */

namespace MicroweberPackages\Database\Query;

use Illuminate\Database\Query\Builder;
use Leafo\ScssPhp\Cache;

class CachedBuilder extends Builder
{
    /**
     * Remove all of the expressions from a list of bindings.
     *
     * @param  array  $bindings
     * @return array
     */
    public function cleanBindings(array $bindings)
    {
//      cache_delete($this->from);
        return parent::cleanBindings($bindings);
    }
}