<?php

namespace Modules\CustomFields\Filament;

use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use MicroweberPackages\LiveEdit\Filament\Admin\Pages\Abstract\LiveEditModuleSettings;

class CustomFieldsModuleSettings extends LiveEditModuleSettings
{
    public string $module = 'custom_fields';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                // Module settings will be added here
            ]);
    }
}
