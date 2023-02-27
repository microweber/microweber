<?php

namespace MicroweberPackages\Filament\Models\Contracts;

class FilamentUser extends Authenticatable implements \Filament\Models\Contracts\FilamentUser
{
    public function canAccessFilament(): bool
    {
        return is_admin();
    }
}
