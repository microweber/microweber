<?php

namespace MicroweberPackages\User\Models;

use Filament\Panel;
use MicroweberPackages\User\Scopes\IsAdminScope;

class Admin extends User
{
    protected $table = 'users';

    public function canAccessPanel(Panel $panel): bool
    {
        return $this->isAdmin();
    }
    protected static function booted()
    {
        static::addGlobalScope(new IsAdminScope());
    }
}
