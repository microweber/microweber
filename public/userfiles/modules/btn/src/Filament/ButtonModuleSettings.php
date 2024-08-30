<?php

namespace MicroweberPackages\Modules\Btn\Filament;

use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use MicroweberPackages\Filament\Forms\Components\MwColorPicker;
use MicroweberPackages\Filament\Forms\Components\MwLinkPicker;
use MicroweberPackages\LiveEdit\Filament\Admin\Pages\Abstract\LiveEditModuleSettings;

class ButtonModuleSettings extends LiveEditModuleSettings
{
    public string $module = 'btn';

    public function form(Form $form): Form
    {
        /*$btn_options = [];
$btn_options['button_style'] = '';
$btn_options['button_size'] = '';
$btn_options['button_action'] = '';
$btn_options['popupcontent'] = '';
$btn_options['url'] = '';
$btn_options['url_blank'] = '';
$btn_options['text'] = '';
$btn_options['icon'] = '';
$btn_options['icon_position'] = '';
$btn_options['button_id'] = '';


$align = get_module_option('align', $params['id']);

$backgroundColor = get_module_option('backgroundColor', $params['id']);
$color = get_module_option('color', $params['id']);
$borderColor = get_module_option('borderColor', $params['id']);
$borderWidth = get_module_option('borderWidth', $params['id']);
$borderRadius = get_module_option('borderRadius', $params['id']);
$customSize = get_module_option('customSize', $params['id']);
$shadow = get_module_option('shadow', $params['id']);


$hoverbackgroundColor = get_module_option('hoverbackgroundColor', $params['id']);
$hovercolor = get_module_option('hovercolor', $params['id']);
$hoverborderColor = get_module_option('hoverborderColor', $params['id']);


        */


        return $form
            ->schema([
                Tabs::make('Options')
                    ->tabs([
                        Tabs\Tab::make('Text')
                            ->schema([
                                Section::make('Button settings')->schema([
                                    TextInput::make('options.text')
                                        ->label('Button Text')
                                        ->live()
                                        ->default('Button'),

//                                    TextInput::make('options.url')
//                                        ->label('Link')
//                                        ->live()
//                                        ->default(''),


                                    MwLinkPicker::make('options.url')
                                        ->live()
                                        ->setSimpleMode(true)
                                        ->afterStateUpdated(function ($state) {

                                        })->columnSpanFull(),


                                    TextInput::make('options.icon')
                                        ->label('Icon')
                                        ->live()
                                        ->default(''),

                                    Select::make('options.icon_position')
                                        ->label('Icon Position')
                                        ->live()
                                        ->options([
                                            'left' => 'Left',
                                            'right' => 'Right',
                                            'center' => 'Center',
                                        ])
                                        ->default('left'),

                                ]),

                                Section::make('Advanced settings')->schema([
                                    //button_action
                                    Select::make('options.button_action')
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
                                    Textarea::make('options.popupcontent')
                                        ->label('Popup Content')
                                        ->live()
                                        ->visible(function (Get $get) {

                                            return $get('options.button_action') === 'popup';

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


                                ])->collapsed(),
                            ]),


                        Tabs\Tab::make('Design')
                            ->schema([
                                    Section::make('Design settings')->schema(
                                        $this->getSkinsFormSchema())
                                ]
                            ),

                    ]),
            ]);
    }


}
