<?php

namespace App\Filament\Admin\Pages\Abstract;

use Filament\Notifications\Notification;
use Filament\Pages\Page;
use MicroweberPackages\Option\Models\ModuleOption;
use MicroweberPackages\Option\Models\Option;

abstract class SettingsPageDefault extends Page
{
    protected static bool $shouldRegisterNavigation = false;
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static string $description = '';

    public function getDescription(): string
    {
        return static::$description;
    }

    public array $options = [];
    public $translatableOptions = [];

    public function getOptionGroups(): array
    {
        return [
            'website'
        ];
    }

    public function getOptionModule(): string
    {
        return 'settings/group/website';
    }

    public function updated($propertyName, $value)
    {

        $option = array_undot_str($propertyName);
        if (isset($option['options'])) {
            // dd($option,$propertyName,$value, $this->options);
            foreach ($option['options'] as $optionGroup => $optionKey) {

                $optionData = [
                    'option_key' => $optionKey,
                    'option_value' => $value,
                    'option_group' => $optionGroup,
                    'module' => $this->getOptionModule()
                ];
                save_option($optionData);

                Notification::make()
                    ->title('Settings Updated')
                    ->body('Settings: ' . $optionKey)
                    ->success()
                    ->send();

                $this->dispatch('mw-option-saved',
                    optionGroup: $optionData['option_group'],
                    optionKey: $optionData['option_key'],
                    optionValue: $optionData['option_value']
                );
            }
        }
    }


    public function updatedTranslatableOptions()
    {
        $formState = $this->form->getState();
        if (empty($formState['translatableOptions'])) {
            return;
        }

        foreach ($formState['translatableOptions'] as $optionGroup => $options) {
            if (empty($options)) {
                continue;
            }
            foreach ($options as $optionKey => $optionValueLanguages) {
                if (empty($optionValueLanguages)) {
                    continue;
                }
                foreach ($optionValueLanguages as $optionValueLang => $optionValue) {
                    save_option([
                        'option_key' => $optionKey,
                        'option_value' => $optionValue,
                        'option_group' => $optionGroup,
                        'lang' => $optionValueLang,
                        'module' => $this->getOptionModule()
                    ]);
                }
            }
        }

        Notification::make()
            ->title('Settings Updated')
            ->success()
            ->send();


    }

    public function mount()
    {
        $getOptions = Option::whereIn('option_group', $this->getOptionGroups())->get();

        if ($getOptions) {
            foreach ($getOptions as $option) {
                $this->options[$option->option_group][$option->option_key] = $option->option_value;
            }
        }

        $getTranslatableOptions = ModuleOption::whereIn('option_group', static::getOptionGroups())->get();
        if ($getTranslatableOptions) {
            foreach ($getTranslatableOptions as $option) {
                if (!empty($option->multilanguage_translatons)) {
                    foreach ($option->multilanguage_translatons as $translationLocale => $translationField) {
                        $this->translatableOptions[$option->option_group][$option->option_key][$translationLocale] = $translationField['option_value'];
                    }
                }
            }
        }

        return [];
    }

    public function getFieldName($name, $optionGroup)
    {

        if (empty($this->options[$optionGroup])) {
            $this->options[$optionGroup] = [];
        }
        if (empty($this->options[$optionGroup][$name])) {
            $this->options[$optionGroup][$name] = '';
        }
        return 'options.' . $optionGroup . '.' . $name;

    }

}
