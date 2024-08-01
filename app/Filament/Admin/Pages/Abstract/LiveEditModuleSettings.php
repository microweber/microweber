<?php

namespace App\Filament\Admin\Pages\Abstract;

use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Illuminate\Support\Arr;
use MicroweberPackages\Filament\Forms\Components\MwFileUpload;
use MicroweberPackages\Option\Models\Option;

abstract class LiveEditModuleSettings extends Page
{
    public string $module;
    public string $optionGroup;
    public array $options = [];
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
        $getOptionGroup = request()->get('id', null);
        if ($getOptionGroup) {
            $this->optionGroup = $getOptionGroup;
        }
        if (!empty($this->optionGroup)) {
            return $this->optionGroup;
        }

        return 'global';
    }

    public function schemaToFormFields($schemaItemsArray)
    {
        $formFields = [];
        foreach ($schemaItemsArray as $schema) {
            if ($schema['type'] == 'text') {
                $formFields[] = TextInput::make($schema['name'])
                    ->label($schema['label'])
                    ->placeholder($schema['placeholder']);

            }
            if ($schema['type'] == 'textarea') {
                $formFields[] = Textarea::make($schema['name'])
                    ->label($schema['label'])
                    ->placeholder($schema['placeholder']);
            }
            if ($schema['type'] == 'image') {
                $formFields[] = MwFileUpload::make($schema['name'])
                    ->label($schema['label'])
                    ->placeholder($schema['placeholder']);
            }
            if ($schema['type'] == 'color') {
                $formFields[] = ColorPicker::make($schema['name'])
                    ->label($schema['label'])
                    ->placeholder($schema['placeholder']);
            }
            if ($schema['type'] == 'select') {
                $formFields[] = Select::make($schema['name'])
                    ->label($schema['label'])
                    ->options($schema['options'])
                    ->placeholder($schema['placeholder']);
            }
            if ($schema['type'] == 'toggle') {
                $formFields[] = Toggle::make($schema['name'])
                    ->label($schema['label']);
            }

        }
        return $formFields;
    }

}
