<?php

namespace Modules\Background\Filament;

use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\View;
use Filament\Schemas\Schema;
use MicroweberPackages\Filament\Forms\Components\MwColorPicker;
use MicroweberPackages\Filament\Forms\Components\MwFileUpload;
use MicroweberPackages\LiveEdit\Filament\Admin\Pages\Abstract\LiveEditModuleSettings;

class BackgroundModuleSettings extends LiveEditModuleSettings
{
    public string $module = 'background';

    public function form(Schema $schema): Schema
    {


        if (isset($this->params['parent-module-id'])) {
            $optionGroup = $this->params['parent-module-id'];
        } elseif (isset($this->params['data-parent-module-id'])) {
            $optionGroup = $this->params['data-parent-module-id'];
        } elseif (isset($this->params['module-id'])) {
            $optionGroup = $this->params['module-id'];
        } else {
            $optionGroup = $this->getOptionGroup();
        }

        return $schema
            ->schema([

                View::make('modules.layouts::admin.settings')->viewData([
                    'optionGroup' => $optionGroup,
                    'showOnlyBackgroundSettings' => true,
                ]),

//                MwFileUpload::make('options.data-background-image')
//                    ->label('Background Image URL')
//                    ->helperText('Enter the URL of the background image.')
//                    ->live(),
//
//                MwFileUpload::make('options.data-background-video')
//                    ->label('Background Video URL')
//                    ->helperText('Enter the URL of the background video.')
//                    ->live(),
//
//                ColorPicker::make('options.data-background-color')
//                    ->label('Background Color')
//                    ->helperText('Enter the background color in hex format.')
//                    ->live(),
//                TextInput::make('options.data-background-size')
//                    ->label('Background Size')
//                    ->helperText('Enter the background size (e.g., cover, contain).')
//                    ->live(),
            ]);
    }
}
