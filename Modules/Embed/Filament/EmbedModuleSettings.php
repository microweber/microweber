<?php

namespace Modules\Embed\Filament;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use MicroweberPackages\LiveEdit\Filament\Admin\Pages\Abstract\LiveEditModuleSettings;

class EmbedModuleSettings extends LiveEditModuleSettings
{
    public string $module = 'embed';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Textarea::make('options.source_code')
                    ->label('Embed Code')
                    ->rows(10)
                    ->placeholder('Insert your embed code here')
                    ->live(),

                Toggle::make('options.hide_in_live_edit')
                    ->label('Hide in Live Edit')
                    ->live(),
            ]);
    }
}
