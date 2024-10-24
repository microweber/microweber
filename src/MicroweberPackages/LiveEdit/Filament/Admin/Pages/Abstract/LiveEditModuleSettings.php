<?php

namespace MicroweberPackages\LiveEdit\Filament\Admin\Pages\Abstract;

use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use MicroweberPackages\Filament\Forms\Components\MwFileUpload;
use MicroweberPackages\Option\Models\Option;

abstract class LiveEditModuleSettings extends Page
{
    public string $module;
    public string $optionGroup;
    public array $options = [];
    public array $params = [];
    public array $translatableOptions = [];
    protected static bool $showTopBar = false;
    protected static bool $shouldRegisterNavigation = false;

    public static function showTopBar(): bool
    {
        return self::$showTopBar;
    }

    public function getLayout(): string
    {
        return static::$layout ?? 'filament-panels::components.layout.live-edit';
    }

    protected static string $view = 'filament-panels::components.layout.simple-form';


    protected function getForms(): array
    {
        return [
            'form',
            'skinsForm',
        ];
    }

    public function setParams($params = [])
    {
        $this->params = $params;
    }

    public function getParams()
    {
        return $this->params;
    }

    public function mount()
    {

        $formInstance = $this->form(new Form($this));
        $formFields = $formInstance->getFlatFields(true);
        if (!empty($formFields)) {
            foreach ($formFields as $field) {
                $fieldStatePath = $field->getStatePath();
                $fieldStatePath = array_undot_str($fieldStatePath);

                $this->options[$fieldStatePath['options']] = '';
            }
        }

        $getOptions = Option::where('option_group', $this->getOptionGroup())->get();

        if ($getOptions) {
            foreach ($getOptions as $option) {
                $this->options[$option->option_key] = $option->option_value;
            }
        }

//        $getTranslatableOptions = ModuleOption::whereIn('option_group', static::getOptionGroups())->get();
//        if ($getTranslatableOptions) {
//            foreach ($getTranslatableOptions as $option) {
//                if (!empty($option->multilanguage_translatons)) {
//                    foreach ($option->multilanguage_translatons as $translationLocale => $translationField) {
//                        $this->translatableOptions[$option->option_key][$translationLocale] = $translationField['option_value'];
//                    }
//                }
//            }
//        }

        return [];
    }

    public function updated($propertyName, $value)
    {

        $option = array_undot_str($propertyName);
        $optionGroup = $this->getOptionGroup();

        if (isset($option['options'])) {
            save_option([
                'option_key' => $option['options'],
                'option_value' => $value,
                'option_group' => $optionGroup,
                'module' => $this->module
            ]);

            $this->dispatch('mw-option-saved',
                optionGroup: $optionGroup,
                optionKey: $option['options'],
                optionValue: $value
            );
        }
    }

    public function getOptionGroup()
    {
        if (!empty($this->params) and isset($this->params['id'])) {
            return $this->params['id'];
        }


        $getOptionGroup = request()->get('id', null);
        if ($getOptionGroup) {
            $this->optionGroup = $getOptionGroup;
        }
        if (!empty($this->optionGroup)) {
            return $this->optionGroup;
        }

        return 'global';
    }

    public function schemaToFormFields($schemaItemsArray, $settingsKey = 'options', $appendSettingsKey = false)
    {
        $formFields = [];

        foreach ($schemaItemsArray as $schema) {
            $name = $schema['name'];

            // $name must  start with options.

            if ($appendSettingsKey and strpos($name, $settingsKey . '.') !== 0) {
                // $name = $settingsKey.'.' . $name;
                $name = $settingsKey . '.' . $name;
            }


            if ($schema['type'] == 'text') {
                $formFields[] = TextInput::make($name)
                    ->label($schema['label'])
                    ->live()
                    ->placeholder($schema['placeholder']);

            }
            if ($schema['type'] == 'textarea') {
                $formFields[] = Textarea::make($name)
                    ->label($schema['label'])
                    ->live()
                    ->placeholder($schema['placeholder']);
            }
            if ($schema['type'] == 'image') {
                $formFields[] = MwFileUpload::make($name)
                    ->label($schema['label'])
                    ->live()
                    ->placeholder($schema['placeholder']);
            }
            if ($schema['type'] == 'color') {
                $formFields[] = ColorPicker::make($name)
                    ->label($schema['label'])
                    ->live()
                    ->placeholder($schema['placeholder']);
            }
            if ($schema['type'] == 'select') {
                $formFields[] = Select::make($name)
                    ->label($schema['label'])
                    ->options($schema['options'])
                    ->live()
                    ->placeholder($schema['placeholder']);
            }
            if ($schema['type'] == 'toggle') {
                $formFields[] = Toggle::make($name)
                    ->live()
                    ->label($schema['label']);
            }

        }
        return $formFields;
    }


    public function skinsForm(Form $form)
    {
        return $form->schema($this->getSkinsFormSchema());
    }

    public function getSkinsFormSchema()
    {


        $moduleTemplates = module_templates($this->module);
        $optionGroup = $this->getOptionGroup();
        $selectedSkin = get_module_option('template', $optionGroup);


        $filter = request()->get('template-filter') ?? null;

        if (!$selectedSkin) {
            $selectedSkin = request()->get('template') ?? null;
            // append .php if extension not set


            if ($selectedSkin and Str::endsWith($selectedSkin, '.php') == false) {
                $selectedSkin = $selectedSkin . '.php';

            }


        }


        $curretSkinSettingsFromJson = [];

        if ($filter) {
            if ($moduleTemplates) {
                foreach ($moduleTemplates as $key => $temp) {
                    if (!str_contains($temp['layout_file'], $filter)) {
                        unset($moduleTemplates[$key]);
                    }
                }
            }
        }


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

                                $settingsKey = 'options';

                                if (isset($moduleTemplateSettingsJson['config'])
                                    and isset($moduleTemplateSettingsJson['config']['settingsKey'])) {
                                    $settingsKey = $moduleTemplateSettingsJson['config']['settingsKey'];
                                }

                                $formFieldsFromSchema = $this->schemaToFormFields($curretSkinSettingsFromJson, $settingsKey, true);

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


    public function save(): void
    {


        $validator = Validator::make(['data' => $this->form->getState()], $this->getRules());
        if (!count($validator->invalid())) {
            $data = ($this->form->getState());

            if (isset($data['options']) and !empty($data['options'])) {
                foreach ($data['options'] as $key => $itemToSave) {
                    $this->updated('options.' . $key, $itemToSave);
                }
            }
        }
    }

}
