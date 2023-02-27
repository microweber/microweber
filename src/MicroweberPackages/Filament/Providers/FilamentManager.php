<?php

namespace MicroweberPackages\Filament\Providers;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use MicroweberPackages\Filament\Providers\Concerns\RegisterComponentsFromDirectory;

class FilamentManager extends \Filament\FilamentManager
{
    use RegisterComponentsFromDirectory;

    public function getUserName(Model|Authenticatable $user): string
    {
        return user_name();
    }

}
