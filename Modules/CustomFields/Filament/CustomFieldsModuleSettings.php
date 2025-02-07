<?php

namespace Modules\CustomFields\Filament;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use MicroweberPackages\LiveEdit\Filament\Admin\Pages\Abstract\LiveEditModuleSettings;

class CustomFieldsModuleSettings extends LiveEditModuleSettings
{
    public string $module = 'custom_fields';

    protected function getFormSchema(): array
    {
        return [
            //todo
        ];
    }
}
