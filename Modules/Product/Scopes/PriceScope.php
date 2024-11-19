<?php

namespace Modules\Product\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class PriceScope implements Scope
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
    //    $builder->where('rel_type', '=', morph_name(\Modules\Content\Models\Content::class));
        $builder->where('type', '=', 'price');
     //   $builder->where('name', '=', 'price');
     //   $builder->where('name_key', '=', 'price');
    }
}
