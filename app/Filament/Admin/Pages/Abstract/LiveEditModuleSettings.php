<?php

namespace App\Filament\Admin\Pages\Abstract;

use Filament\Forms\Form;
use Filament\Pages\Page;
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
        $formState = $formInstance->getState();

        if (isset($formState['translatableOptions'])) {
            $this->translatableOptions = $formState['translatableOptions'];
        }
        if (isset($formState['options'])) {
            $this->options = $formState['options'];
        }

        $getOptions = Option::where('option_group', $this->getOptionGroup())->get();

        if ($getOptions) {
            foreach ($getOptions as $option) {
                $this->options[$option->option_group][$option->option_key] = $option->option_value;
            }
        }

//        $getTranslatableOptions = ModuleOption::whereIn('option_group', static::getOptionGroups())->get();
//        if ($getTranslatableOptions) {
//            foreach ($getTranslatableOptions as $option) {
//                if (!empty($option->multilanguage_translatons)) {
//                    foreach ($option->multilanguage_translatons as $translationLocale => $translationField) {
//                        $this->translatableOptions[$option->option_group][$option->option_key][$translationLocale] = $translationField['option_value'];
//                    }
//                }
//            }
//        }

        return [];
    }
    public function updated($propertyName, $value)
    {
        $option = array_undot_str($propertyName);

        if (isset($option['options'])) {
            foreach ($option['options'] as $optionGroup => $optionKey) {
                save_option([
                    'option_key' => $optionKey,
                    'option_value' => $value,
                    'option_group' => $optionGroup,
                    'module' => $this->module
                ]);

                $this->dispatch('mw-option-saved',
                    optionGroup: $optionGroup,
                    optionKey: $optionKey,
                    optionValue: $value
                );
            }
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


}
