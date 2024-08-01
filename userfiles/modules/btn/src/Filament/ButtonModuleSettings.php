<?php

namespace MicroweberPackages\Modules\Btn\Filament;

use App\Filament\Admin\Pages\Abstract\LiveEditModuleSettings;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use MicroweberPackages\Filament\Forms\Components\MwFileUpload;

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


        //   dd($moduleTemplates);

        return $form
            ->schema([
                Tabs::make('Options')
                    ->tabs([
                        Tabs\Tab::make('Text')
                            ->schema([

                                TextInput::make('options.text')
                                    ->label('Button Text')
                                    ->live()
                                    ->default('Button'),

                                TextInput::make('options.icon')
                                    ->label('Icon')
                                    ->live()
                                    ->default(''),
                                TextInput::make('options.url')
                                    ->label('url')
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


                        Tabs\Tab::make('Design')
                            ->schema($this->getSkinsFormSchema()),

                    ]),
            ]);
    }


}
