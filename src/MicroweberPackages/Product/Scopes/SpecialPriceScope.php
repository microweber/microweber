<?php

namespace MicroweberPackages\Product\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class SpecialPriceScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return void
     */
    public function apply(Builder $builder, Model $model)
    {
        $builder->where('rel_type', '=', 'content');
        $builder->where('type', '=', 'price');
        $builder->where('name', '=', 'special_price');
        $builder->where('name_key', '=', 'special_price');
    }
}