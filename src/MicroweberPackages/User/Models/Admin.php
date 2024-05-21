<?php

namespace MicroweberPackages\User\Models;

use MicroweberPackages\User\Scopes\IsAdminScope;

class Admin extends User
{
    protected static function booted()
    {
        static::addGlobalScope(new IsAdminScope());
    }
}
