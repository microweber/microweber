<?php

namespace MicroweberPackages\Filament\Providers;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class FilamentManager extends \Filament\FilamentManager
{
    public function getUserName(Model|Authenticatable $user): string
    {
        return user_name();
    }

}
