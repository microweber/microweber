<?php

namespace Modules\Logo\Filament;

use Filament\Schemas\Components\Tabs;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use MicroweberPackages\Filament\Forms\Components\MwColorPicker;
use MicroweberPackages\Filament\Forms\Components\MwFileUpload;
use MicroweberPackages\LiveEdit\Filament\Admin\Pages\Abstract\LiveEditModuleSettings;

class LogoModuleSettings extends LiveEditModuleSettings
{
    public string $module = 'logo';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Tabs::make('Options')
                    ->schema([
                        Tabs\Tab::make('Image')
                            ->schema([
                                MwFileUpload::make('options.logoimage')
                                    ->label('Logo Image')
                                    ->live(),
                                TextInput::make('options.size')
                                    ->label('Logo Size')
                                    ->numeric()
                                    ->helperText('Logo width in pixels')
                                    ->live()
                                    ->default(fn () => $this->getOption('size', '100')),
                            ]),
                        Tabs\Tab::make('Text')
                            ->schema([
                                TextInput::make('options.text')
                                    ->label('Logo Text')
                                    ->helperText('This logo text will appear when image not applied')
                                    ->live()
                                    ->default(fn () => $this->getOption('text', '')),
                                MwColorPicker::make('options.text_color')
                                    ->label('Text Color')
                                    ->live()
                                    ->rgba(),
                                TextInput::make('options.font_size')
                                    ->label('Font Size')
                                    ->numeric()
                                    ->helperText('Logo text size in pixels')
                                    ->live()
                                    ->default(fn () => $this->getOption('font_size', '')),
                            ]),
                        Tabs\Tab::make('Design')
                            ->schema(
                                $this->getTemplatesFormSchema()

                            ),
                    ]),
            ]);
    }
}
