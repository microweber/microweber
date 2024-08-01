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

    public function getSkinsFormSchema()
    {
        $moduleTemplates = module_templates($this->module);
        $optionGroup = $this->getOptionGroup();
        $selectedSkin = get_module_option('template', $optionGroup);

        $curretSkinSettingsFromJson = [];


        $moduleTemplatesForForm = [];
        $moduleTemplatesSkinSettingsSchema = [];
        if ($moduleTemplates) {
            foreach ($moduleTemplates as $moduleTemplate) {
                $moduleTemplatesForForm[$moduleTemplate['layout_file']] = $moduleTemplate['name'];

                if ($selectedSkin == $moduleTemplate['layout_file']) {
                    if (isset($moduleTemplate['skin_settings_json_file'])
                        and $moduleTemplate['skin_settings_json_file']
                        and is_file($moduleTemplate['skin_settings_json_file'])
                    ) {
                        $jsonContent = file_get_contents($moduleTemplate['skin_settings_json_file']);
                        if ($jsonContent) {
                            $moduleTemplateSettingsJson = @json_decode($jsonContent, true);
                            if (is_array($moduleTemplateSettingsJson)
                                and isset($moduleTemplateSettingsJson['schema'])
                                and !empty($moduleTemplateSettingsJson['schema'])) {
                                $curretSkinSettingsFromJson = $moduleTemplateSettingsJson['schema'];
                                $formFieldsFromSchema = $this->schemaToFormFields($curretSkinSettingsFromJson);

                                if ($formFieldsFromSchema) {
                                    $moduleTemplatesSkinSettingsSchema = array_merge($moduleTemplatesSkinSettingsSchema, $formFieldsFromSchema);
                                }

                            }
                        }

                    }
                }
            }
        }


        $schema = [
            Select::make('options.template')
                ->label('Module skin')
                ->default($selectedSkin)
                ->live()
                ->options($moduleTemplatesForForm)
        ];

        if ($moduleTemplatesSkinSettingsSchema) {
            $schema = array_merge($schema, $moduleTemplatesSkinSettingsSchema);
        }

         return $schema;
    }
}
