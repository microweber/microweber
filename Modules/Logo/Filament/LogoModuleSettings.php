<?php

namespace Modules\Logo\Filament;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
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
                // Image / Text — the two ways to render a logo. Presented as two
                // top-level tabs (matching the redesign mockup); the previous
                // "Design"/template skin options move under a collapsed Advanced
                // section so the common controls stay front-and-centre.
                Tabs::make('Options')
                    ->contained(false)
                    ->schema([
                        Tabs\Tab::make('Image')
                            ->schema([
                                MwFileUpload::make('options.logoimage')
                                    ->label('Logo image')
                                    ->helperText('Tip: a transparent PNG or SVG works best on coloured headers.')
                                    ->live(),

                                ToggleButtons::make('options.size')
                                    ->label('Size')
                                    ->inline()
                                    ->live()
                                    ->options([
                                        '80' => 'Small',
                                        '120' => 'Medium',
                                        '180' => 'Large',
                                    ])
                                    ->helperText('How large the logo appears.')
                                    ->default(fn () => (string) $this->getOption('size', '120')),
                            ]),

                        Tabs\Tab::make('Text')
                            ->schema([
                                TextInput::make('options.text')
                                    ->label('Logo text')
                                    ->placeholder('Brand name')
                                    ->helperText('Shown when no logo image is set.')
                                    ->live()
                                    ->default(fn () => $this->getOption('text', '')),

                                MwColorPicker::make('options.text_color')
                                    ->label('Colour')
                                    ->hint('Matches theme')
                                    ->live()
                                    ->rgba(),

                                ToggleButtons::make('options.font_size')
                                    ->label('Size')
                                    ->inline()
                                    ->live()
                                    ->options([
                                        '18' => 'Small',
                                        '24' => 'Medium',
                                        '36' => 'Large',
                                    ])
                                    ->helperText('How large the text appears.')
                                    ->default(fn () => (string) $this->getOption('font_size', '24')),
                            ]),
                    ]),

                // Template skin / advanced design options — kept, but tucked away.
                Section::make('Advanced')
                    ->collapsible()
                    ->collapsed()
                    ->schema($this->getTemplatesFormSchema()),
            ]);
    }
}
