<?php

namespace Modules\Logo\Filament;

use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use MicroweberPackages\Filament\Forms\Components\MwColorPicker;
use MicroweberPackages\Filament\Forms\Components\MwFileUpload;
use MicroweberPackages\Filament\Forms\Components\MwInputSlider;
use MicroweberPackages\Filament\Forms\Components\MwInputSliderGroup;
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
                                    ->live()
                                  ,



                                MwInputSliderGroup::make()
                                    ->live()
                                    ->sliders([
                                        MwInputSlider::make('options.size')
                                            ->label('Logo Size')

                                        ,
                                    ])
                                    ->enableTooltips()


                                    ->range([
                                        "min" => 0,
                                        "max" => 600
                                    ])
                                    ->label('Set Size'),
                            ]),
                        Tabs\Tab::make('Text')
                            ->schema([
                                TextInput::make('options.text')
                                    ->label('Logo Text')
                                    ->helperText('This logo text will appear when image not applied')
                                    ->live(),
                                MwColorPicker::make('options.text_color')
                                    ->label('Text Color')
                                    ->live()
                                    ->rgba(),
                                TextInput::make('options.font_size')
                                    ->label('Font Size')
                                    ->numeric()
                                    ->live(),
                                MwInputSliderGroup::make()
                                    ->live()
                                    ->sliders([
                                        MwInputSlider::make('options.font_size')
                                            ->label('Font Size')

                                        ,
                                    ])
                                    ->enableTooltips()


                                    ->range([
                                        "min" => 0,
                                        "max" => 120
                                    ])
                                    ->label('Set Font Size'),



                            ]),
                        Tabs\Tab::make('Template')
                            ->schema(
                                $this->getTemplatesFormSchema()

                            ),
                    ]),
            ]);
    }
}
