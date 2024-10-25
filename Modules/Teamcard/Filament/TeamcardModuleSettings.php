<?php

namespace Modules\Teamcard\Filament;

use Filament\Forms\Form;
use MicroweberPackages\LiveEdit\Filament\Admin\Pages\Abstract\LiveEditModuleSettings;

class TeamcardModuleSettings extends LiveEditModuleSettings
{
    public string $module = 'teamcard';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                
            ]);
    }

}
