<?php

namespace MicroweberPackages\LiveEdit\Models\Scopes;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

/**
 * @deprecated
 */
class HasOptionGroupScope implements Scope
{
    public function apply(Builder $builder, Model $model)
    {

        $builder->where('option_group', $model::$optionGroup);
    }
}

