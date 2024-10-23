<?php

namespace Modules\Btn\Filament;

use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Form;
use Filament\Forms\Get;
use MicroweberPackages\Filament\Forms\Components\MwFileUpload;
use MicroweberPackages\Filament\Forms\Components\MwIconPicker;
use MicroweberPackages\Filament\Forms\Components\MwLinkPicker;
use MicroweberPackages\LiveEdit\Filament\Admin\Pages\Abstract\LiveEditModuleSettings;

class BtnModuleSettings extends LiveEditModuleSettings
{
    public string $module = 'btn';

    public function form(Form $form): Form
    {

        return $form
            ->schema([
                Tabs::make('Settings')
                    ->tabs([
                        Tabs\Tab::make('Content')
                            ->schema([

                                TextInput::make('options.text')
                                    ->label('Text')
                                    ->live()
                                    ->default('Button'),


                                MwLinkPicker::make('options.url')
                                    ->label('Link')
                                    ->live()
                                    ->setSimpleMode(true)
                                    ->columnSpanFull(),


                                ToggleButtons::make('options.align')
                                    ->live()
                                    ->options([
                                        'left' => 'Left',
                                        'center' => 'Center',
                                        'right' => 'Right',
                                    ])
                                    ->inline()
                                    ->icons([
                                        'left' => 'heroicon-o-bars-3-bottom-left',
                                        'center' => 'heroicon-o-bars-3',
                                        'right' => 'heroicon-o-bars-3-bottom-right',
                                    ]),

                                Toggle::make('options.url_blank')
                                    ->live()
                                    ->label('Open link in new window')
                                    ->default(false)
                                    ->columnSpanFull(),

                            ]),
                        Tabs\Tab::make('Design')
                            ->schema([

                                MwIconPicker::make('options.icon')
                                    ->label('Button Icon')
                                    ->live(),

                                ToggleButtons::make('options.icon_position')
                                    ->label('Button Icon Position ')
                                    ->live()
                                    ->inline()
                                    ->options([
                                        'left' => 'Left',
                                        'right' => 'Right',
                                    ])
                                    ->icons([
                                        'left' => 'heroicon-o-bars-3-bottom-left',
                                        'right' => 'heroicon-o-bars-3-bottom-right',
                                    ])
                                    ->default('left'),

                                Select::make('options.style')
                                    ->label('Button Style')
                                    ->live()
                                    ->options([
                                        'normal' => 'Normal',
                                        'primary' => 'Primary',
                                        'secondary' => 'Secondary',
                                        'outline'=> 'Outline',
                                        'link' => 'Link',
                                    ])
                                    ->default('btn-primary'),

                                Select::make('options.size')
                                    ->label('Button Size')
                                    ->live()
                                    ->options([
                                        'default' => 'Default',
                                        'large' => 'Large',
                                        'medium' => 'Medium',
                                        'small' => 'Small',
                                        'mini' => 'Mini',
                                    ]),

                            ])
                    ])
            ]);
    }


}
