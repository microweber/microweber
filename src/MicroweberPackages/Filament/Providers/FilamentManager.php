<?php

namespace MicroweberPackages\Filament\Providers;

use Filament\Models\Contracts\HasName;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class FilamentManager extends \Filament\FilamentManager
{
    public function getUserName(Model | Authenticatable $user): string
    {
        return user_name();
    }

}
