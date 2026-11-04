<?php

namespace MicroweberPackages\Filament\Models\Contracts;

use Filament\Panel;
use Illuminate\Foundation\Auth\User as Authenticatable;

class FilamentUser extends Authenticatable implements \Filament\Models\Contracts\FilamentUser
{
    public function canAccessPanel(Panel $panel): bool
    {
        return is_admin();
    }

    public function canAccessFilament(): bool
    {
        return is_admin();
    }
}
