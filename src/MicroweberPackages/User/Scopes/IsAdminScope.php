<?php

namespace MicroweberPackages\User\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class IsAdminScope implements Scope
{
    public function apply(Builder $builder, Model $model)
    {
        $builder->where('is_admin', 1);
    }
}
