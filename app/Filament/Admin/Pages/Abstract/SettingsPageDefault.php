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

    public $options = [];
    public $translatableOptions = [];

    public function getOptionGroups() : array
    {
        return [
            'website'
        ];
    }

    public function getOptionModule() : string
    {
        return 'settings/group/website';
    }

    public function updatedOptions()
    {

        $changedOption = [];
        foreach ($this->options as $optionGroup => $options) {
            if (empty($options)) {
                continue;
            }
            foreach ($options as $optionKey => $optionValue) {
                $oldOptionValue = get_option($optionKey, $optionGroup);
                $isChanged = false;
                if ($oldOptionValue !== $optionValue) {
                    $isChanged = true;
                }
                if ($isChanged) {
                    $changedOption = [
                        'isChanged' => $isChanged,
                        'option_group' => $optionGroup,
                        'option_key' => $optionKey,
                        'option_value' => $optionValue,
                        'old_option_value' => $oldOptionValue
                    ];
                }
            }
        }

        if (empty($changedOption)) {
            return;
        }

        save_option([
            'option_key'=>$changedOption['option_key'],
            'option_value'=>$changedOption['option_value'],
            'option_group'=>$changedOption['option_group'],
        //    'module'=>static::getOptionModule()
        ]);

       // if (filament()->getCurrentPanel()->getId() !== 'admin-live-edit') {
            Notification::make()
                ->title('Settings Updated')
                ->body('Settings: ' . $changedOption['option_key'])
                ->success()
                ->send();
       // }
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
                foreach($optionValueLanguages as $optionValueLang=>$optionValue) {
                    save_option([
                        'option_key'=>$optionKey,
                        'option_value'=>$optionValue,
                        'option_group'=>$optionGroup,
                        'lang'=>$optionValueLang,
                        'module'=>static::$optionModule
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
        $getOptions = Option::whereIn('option_group', static::getOptionGroups())->get();

        if ($getOptions) {
            foreach ($getOptions as $option) {
                $this->options[$option->option_group][$option->option_key] = $option->option_value;
            }
        }

        $getTranslatableOptions = ModuleOption::whereIn('option_group', static::getOptionGroups())->get();
        if ($getTranslatableOptions) {
            foreach ($getTranslatableOptions as $option) {
                if (!empty($option->multilanguage_translatons)) {
                    foreach($option->multilanguage_translatons as $translationLocale=>$translationField) {
                        $this->translatableOptions[$option->option_group][$option->option_key][$translationLocale] = $translationField['option_value'];
                    }
                }
            }
        }

        return [];
    }
}
