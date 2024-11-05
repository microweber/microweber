<?php

namespace Modules\Logo\Filament;

use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Components\Select;
use MicroweberPackages\Filament\Forms\Components\MwFileUpload;
use MicroweberPackages\LiveEdit\Filament\Admin\Pages\Abstract\LiveEditModuleSettings;

class LogoModuleSettings extends LiveEditModuleSettings
{
    public string $module = 'logo';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Tabs::make('Options')
                    ->tabs([
                        Tabs\Tab::make('Image')
                            ->schema([
                                MwFileUpload::make('options.logoimage')
                                    ->label('Logo Image')
                                    ->live(),
                                TextInput::make('options.size')
                                    ->label('Logo Size')
                                    ->numeric()
                                    ->live()
                                    ->default(200), // Default size
                            ]),
                        Tabs\Tab::make('Text')
                            ->schema([
                                TextInput::make('options.text')
                                    ->label('Logo Text')
                                    ->helperText('This logo text will appear when image not applied')
                                    ->live(),
                                ColorPicker::make('options.text_color')
                                    ->label('Text Color')
                                    ->live()
                                    ->rgba(),
                                TextInput::make('options.font_size')
                                    ->label('Font Size')
                                    ->numeric()
                                    ->live()
                                    ->default(30), // Default text size
                            ]),
                        Tabs\Tab::make('Template')
                            ->schema(
                                $this->getTemplatesFormSchema()

                            ),
                    ]),
            ]);
    }
}
