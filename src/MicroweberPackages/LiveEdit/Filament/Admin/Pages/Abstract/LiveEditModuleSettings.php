<?php

namespace MicroweberPackages\LiveEdit\Filament\Admin\Pages\Abstract;

use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Forms\Components\ColorPicker;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\EmbeddedSchema;
use Filament\Schemas\Components\Form;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Schemas\Schema;
use Filament\Pages\Concerns\InteractsWithFormActions;
use Filament\Pages\Page;
use Filament\Support\Commands\Concerns\CanReadModelSchemas;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use MicroweberPackages\Filament\Forms\Components\MwColorPicker;
use MicroweberPackages\Filament\Forms\Components\MwFileUpload;
use MicroweberPackages\Filament\Forms\Components\MwInputSlider;
use MicroweberPackages\Filament\Forms\Components\MwInputSliderGroup;
use MicroweberPackages\Multilanguage\MultilanguageHelpers;
use MicroweberPackages\Option\Models\ModuleOption;
use MicroweberPackages\Option\Models\Option;


abstract class LiveEditModuleSettings extends Page
{

    use InteractsWithActions;
    use InteractsWithForms;
    use InteractsWithFormActions;


    public string $module;
    public string $optionGroup;
    public array $options = [];
    public array $params = [];
    public array $translatableOptions = [];
    public array $liveEditIframeData = [];
    protected static bool $showTopBar = false;
    protected static bool $shouldRegisterNavigation = false;


    public static function showTopBar(): bool
    {
        return self::$showTopBar;
    }

    public function getLayout(): string
    {
        return static::$layout ?? 'filament-panels::components.layout.live-edit-module-settings';
    }

    protected string $view = 'filament-panels::components.layout.simple-form';

    public function content(Schema $schema): Schema
    {
        return $schema
            ->components([
                Form::make([EmbeddedSchema::make('form')]),
            ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([]);
    }


    public function mount()
    {
        // Populate params from request query when loaded as an iframe page
        if (empty($this->params)) {
            $queryParams = request()->query();
            if (!empty($queryParams)) {
                $this->params = $queryParams;
            }
        }

        $formInstance = $this->form(new Schema($this));
        $formFields = $formInstance->getFlatFields(true);
        if (!empty($formFields)) {
            foreach ($formFields as $field) {
                $fieldStatePath = $field->getStatePath();
                $fieldStatePath = array_undot_str($fieldStatePath);

////  todo
///  this is tricky for the nested options, need to be fixed
//                if(isset($fieldStatePath['options']) and is_array($fieldStatePath['options'])){
//                    foreach ($fieldStatePath['options'] as $optionKey => $optionValue) {
//                        if (isset($this->options[$optionKey]) and $this->options[$optionKey] == '') {
//
//                            if(!$optionValue){
//                                $optionValue = get_option($optionKey, $this->getOptionGroup(), false, $this->module);
//                            }
//                            $this->options[$optionKey] = $optionValue;
//                        }
//                    }
//                } elseif (isset($fieldStatePath['options']) and !empty($fieldStatePath['options'])) {
//                    if (!isset($this->options[$fieldStatePath['options']])) {
//                        $this->options[$fieldStatePath['options']] = '';
//                    }
//                }

                if (isset($fieldStatePath['options'])) {
                    if (!array_key_exists($fieldStatePath['options'], $this->options)) {
                        $this->options[$fieldStatePath['options']] = '';
                    }
                }
            }
        }
        $isMultilanguageEnabled = MultilanguageHelpers::multilanguageIsEnabled();

        // $getOptions = Option::where('option_group', $this->getOptionGroup())->get();
        $getOptions = ModuleOption::where('option_group', $this->getOptionGroup())->get();
        $defaultLocale = app()->lang_helper->default_lang();

        if ($getOptions) {
            foreach ($getOptions as $option) {
                $this->options[$option->option_key] = $option->option_value;

                if ($isMultilanguageEnabled) {


                    $this->translatableOptions[$option->option_key][$defaultLocale] = $option->option_value;

                    if (isset($option->multilanguage) and !empty($option->multilanguage)) {
                        foreach ($option->multilanguage as $tanslations) {
                            if (is_array($tanslations)) {
                                foreach ($tanslations as $locale => $value) {
                                    $this->translatableOptions[$option->option_key][$locale] = $value;
                                }
                            }
                        }
                    }


                }
            }
        }

        //       $getTranslatableOptions = ModuleOption::whereIn('option_group', $this->getOptionGroup())->get();
//
//        if ($getTranslatableOptions) {
//            foreach ($getTranslatableOptions as $option) {
//                if (!empty($option->multilanguage_translations)) {
//                    foreach ($option->multilanguage_translations as $translationLocale => $translationField) {
//                        $this->translatableOptions[$option->option_key][$translationLocale] = $translationField['option_value'];
//                    }
//                }
//            }
//        }

        return [];
    }

    public function updated($propertyName, $value): void
    {

        $option = array_undot_str($propertyName);
        $optionGroup = $this->getOptionGroup();

        /*  "translatableOptions" => array:1 [▼
    "$fielName" => "en_US" = $value
  ]*/


        if (MultilanguageHelpers::multilanguageIsEnabled() and isset($option['translatableOptions'])) {
            $defaultLocale = app()->lang_helper->default_lang();

            $translateOptions = [];
            if (is_array($option['translatableOptions'])) {
                $translateOptions = $option['translatableOptions'];
            }


            foreach ($translateOptions as $optionKey => $locale) {
                $optionKeyForTranslaton = str_replace('translatableOptions.', '', $optionKey);
                $valForOption = $value[$locale] ?? $value;

                if ($defaultLocale == $locale) {
                    // Save the default locale option
                    save_module_option([
                        'option_key' => $optionKey,
                        'option_value' => $valForOption,
                        'option_group' => $optionGroup,
                        'module' => $this->module
                    ]);

                    $this->dispatch('mw-option-saved',
                        optionGroup: $valForOption,
                        optionKey: $optionKey,
                        optionValue: $value,
                        module: $this->module
                    );
                } else {
                    // Save the translatable option
                    save_module_option([
                        'option_key' => $optionKeyForTranslaton,
                        'option_value' => $valForOption,
                        'option_group' => $optionGroup,
                        'lang' => $locale,
                        'module' => $this->module
                    ]);

                    $this->dispatch('mw-option-saved',
                        optionGroup: $optionGroup,
                        optionKey: $optionKeyForTranslaton,
                        optionValue: $value,
                        module: $this->module
                    );
                }


            }


        }


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
                optionValue: $value,
                module: $this->module
            );
        }
    }

    public function getOptionGroup()
    {
        if (!empty($this->params) and isset($this->params['id'])) {
            $this->optionGroup = $this->params['id'];
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
        $engine = \MicroweberPackages\FormBuilder\FormBuilder::fromArray($schemaItemsArray)
            ->withState($this->options);

        if ($appendSettingsKey) {
            $engine->prefix($settingsKey);
        }

        return $engine->build();
    }


    public function templatesForm(Schema $schema)
    {
        return $schema->schema($this->getTemplatesFormSchema());
    }

    public function getTemplatesFormSchema()
    {

        $template_name_from_website = false;
        if (!empty($this->params) and isset($this->params['id'])) {
            if (isset($this->liveEditIframeData) and !empty($this->liveEditIframeData)) {
                $liveEditIframeData = $this->liveEditIframeData;
                if (isset($liveEditIframeData['template_name'])) {
                    $template_name_from_website = $liveEditIframeData['template_name'];
                }
            }
        }

        $template_filter = $this->params['template-filter'] ?? $this->params['template_filter'] ?? null;


        $moduleTemplates = module_templates($this->module);
        $moduleTemplates = $moduleTemplates ?? [];

        if ($template_name_from_website) {
            $moduleTemplatesFromSite = module_templates($this->module, $template_name_from_website);
            if ($moduleTemplatesFromSite) {
                $moduleTemplates = array_merge($moduleTemplates, $moduleTemplatesFromSite);
            }
            if ($moduleTemplates) {
                $moduleTemplates = array_unique($moduleTemplates, SORT_REGULAR);
            }
        }

        $optionGroup = $this->getOptionGroup();

        $selectedSkin = get_module_option('template', $optionGroup);

        $filter = $template_filter ?? null;

        if (!$selectedSkin) {
            $selectedSkin = request()->get('template') ?? $this->params['template'] ?? null;
            // append .php if extension not set

//            if ($selectedSkin and Str::endsWith($selectedSkin, '.php') == false) {
//                $selectedSkin = $selectedSkin . '.php';
//
//            }


        }

        if (!$selectedSkin) {
            $selectedSkin = 'default';
        }

        if ($selectedSkin and $selectedSkin != 'default') {
            $selectedSkin = str_replace('/', '.', $selectedSkin);
            $selectedSkin = str_replace('\\', '.', $selectedSkin);
            $selectedSkin = str_replace('.', '/', $selectedSkin);

        }

        $selectedSkin = str_replace('/', '.', $selectedSkin);
        $selectedSkin = str_replace('\\', '.', $selectedSkin);


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


                $layoutFile = str_replace('/', '.', $moduleTemplate['layout_file']);
                $layoutFile = str_replace('\\', '.', $layoutFile);

                $moduleTemplatesForForm[$layoutFile] = $moduleTemplate['name'];

                if ($selectedSkin == $layoutFile) {
                    if (isset($moduleTemplate['skin_settings_json_file'])
                        and $moduleTemplate['skin_settings_json_file']
                        and is_file($moduleTemplate['skin_settings_json_file'])
                    ) {

                        $curretSkinSettingsFromJson = [];
                        $jsonContent = file_get_contents($moduleTemplate['skin_settings_json_file']);

                        if ($jsonContent) {

                            $moduleTemplateSettingsJson = @json_decode($jsonContent, true);
                            $mergeSchema = [];
                            $mergeSchemasToFind = [];
                            if (is_array($moduleTemplateSettingsJson)
                                // and isset($moduleTemplateSettingsJson['schema'])
                                and !empty($moduleTemplateSettingsJson['useSchemaFrom'])) {


                                if (is_array($moduleTemplateSettingsJson['useSchemaFrom'])) {
                                    $mergeSchemasToFind = $moduleTemplateSettingsJson['useSchemaFrom'];
                                } else {
                                    $mergeSchemasToFind[] = $moduleTemplateSettingsJson['useSchemaFrom'];
                                }
                            }

                            foreach ($mergeSchemasToFind as $mergeSchemaToFind) {
                                $mergeFile = dirname($moduleTemplate['skin_settings_json_file']) . '/' . $mergeSchemaToFind;
                                if ($mergeFile and !Str::endsWith($mergeFile, '.json')) {
                                    $mergeFile = $mergeFile . '.json';

                                    if (is_file($mergeFile)) {
                                        $jsonContent = file_get_contents($mergeFile);
                                        if ($jsonContent) {
                                            $mergeSchemaContent = @json_decode($jsonContent, true);

                                            if (is_array($mergeSchemaContent)
                                                and isset($mergeSchemaContent['schema'])
                                                and !empty($mergeSchemaContent['schema'])) {
                                                $mergeSchema = $mergeSchemaContent['schema'];
                                            }
                                        }
                                    }
                                }
                            }


                            if (is_array($moduleTemplateSettingsJson)
                                and isset($moduleTemplateSettingsJson['schema'])
                                and !empty($moduleTemplateSettingsJson['schema'])) {

                                $curretSkinSettingsFromJson = $moduleTemplateSettingsJson['schema'];

                            }
                        }


                        if (!empty($mergeSchema)) {
                            $curretSkinSettingsFromJson = array_merge($curretSkinSettingsFromJson, $mergeSchema);
                        }

                        if (!empty($curretSkinSettingsFromJson)) {


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

        if (empty($moduleTemplatesForForm)) {
            return [];
        }

        if ($selectedSkin) {
            $this->options['template'] = $selectedSkin;
        }

        //  dump($selectedSkin,$moduleTemplatesForForm);


        $schema = [
            Select::make('options.template')
                ->label('Module template')
                ->default($selectedSkin)
                ->live()
                ->nullable()
                ->options($moduleTemplatesForForm)
        ];

        if ($moduleTemplatesSkinSettingsSchema) {
            $schema = array_merge($schema, $moduleTemplatesSkinSettingsSchema);
        }


        return $schema;
    }


    public function getOption($optionKey, $default = null)
    {
        return get_module_option($optionKey, $this->getOptionGroup(), false, $this->module) ?? $default;
    }

    public function saveOption($optionKey, $optionValue)
    {
        save_option([
            'option_key' => $optionKey,
            'option_value' => $optionValue,
            'option_group' => $this->getOptionGroup(),
            'module' => $this->module
        ]);
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

            Notification::make()
                ->title(__('Saved successfully'))
                ->success()
                ->send();
        }
    }

}
