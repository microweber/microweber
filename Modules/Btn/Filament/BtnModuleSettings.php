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
                                    ->helperText('Enter the text to display on the button.')
                                    ->live()
                                    ->default('Button'),


                                MwLinkPicker::make('options.url')
                                    ->label('Link')
                                    ->helperText('Select or enter the URL the button should link to.')
                                    ->live()
                                    ->setSimpleMode(true)
                                    ->columnSpanFull(),


                                ToggleButtons::make('options.align')
                                    ->helperText('Choose the alignment of the button.')
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

                                Toggle::make('options.urlBlank')
                                    ->helperText('Enable to open the link in a new window.')
                                    ->live()
                                    ->label('Open link in new window')
                                    ->default(false)
                                    ->columnSpanFull(),

                            ]),
                        Tabs\Tab::make('Design')
                            ->schema([

                                Section::make('Icon Settings')
                                    ->columns(2)
                                    ->schema([
                                        MwIconPicker::make('options.icon')
                                            ->label('Button Icon')
                                            ->helperText('Select an icon to display on the button.')
                                            ->live(),

                                        ToggleButtons::make('options.iconPosition')
                                            ->label('Button Icon Position ')
                                            ->helperText('Choose the position of the icon on the button.')
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
                                    ]),

//                                Select::make('options.style')
//                                    ->label('Button Style')
//                                    ->helperText('Select the style of the button.')
//                                    ->live()
//                                    ->options([
//                                        'normal' => 'Normal',
//                                        'primary' => 'Primary',
//                                        'secondary' => 'Secondary',
//                                        'outline'=> 'Outline',
//                                        'link' => 'Link',
//                                    ])
//                                    ->default('btn-primary'),
//
//                                Select::make('options.size')
//                                    ->label('Button Size')
//                                    ->helperText('Select the size of the button.')
//                                    ->live()
//                                    ->options([
//                                        'default' => 'Default',
//                                        'large' => 'Large',
//                                        'medium' => 'Medium',
//                                        'small' => 'Small',
//                                        'mini' => 'Mini',
//                                    ]),
                                Section::make('Design settings')->schema(
                                    $this->getTemplatesFormSchema()),


                                $this->getCustomSettingsFormSchema(),


                            ])

                    ])


            ]);
    }


    private function getCustomSettingsFormSchema()
    {
        return Section::make('Advanced settings')->schema([
            //button_action
            Select::make('options.action')
                ->label('Button Action')
                ->live()
                ->options([
                    'default' => 'Go to link',
                    'popup' => 'Popup',
//                                            'submit' => 'Submit',
//                                            'reset' => 'Reset',
                ])
                ->default('none'),
            //popupcontent if action is popoup
            Textarea::make('options.popupContent')
                ->label('Popup Content')
                ->live()
                ->visible(function (Get $get) {

                    return $get('options.action') === 'popup';

                })
                ->default(''),


            //backgroundColor
            ColorPicker::make('options.color')
                ->label('Color')
                ->live()
                ->default(''),

            ColorPicker::make('options.backgroundColor')
                ->label('Background Color')
                ->live()
                ->default(''),


            ColorPicker::make('options.borderColor')
                ->label('Border color')
                ->live()
                ->default(''),

            TextInput::make('options.borderWidth')
                ->label('Border width')
                ->live()
                ->numeric()
                ->default(''),

            TextInput::make('options.borderRadius')
                ->label('Border radius')
                ->live()
                ->numeric()
                ->default(''),


            //hoverbackgroundColor
            ColorPicker::make('options.hovercolor')
                ->label('Hover hover color')
                ->live()
                ->default(''),

            ColorPicker::make('options.hoverbackgroundColor')
                ->label('Hover background color')
                ->live()
                ->default(''),


            ColorPicker::make('options.hoverborderColor')
                ->label('Hover border color')
                ->live()
                ->default(''),


            TextInput::make('options.customSize')
                ->label('Custom size')
                ->live()
                ->numeric()
                ->default(''),


        ])->collapsed();
    }


}
