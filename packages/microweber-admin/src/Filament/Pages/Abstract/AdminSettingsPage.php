<?php

namespace MicroweberPackages\Admin\Filament\Pages\Abstract;

use App\Filament\Admin\Pages\Concerns\HasModuleOption;
use App\Filament\Admin\Pages\Concerns\HasOptions;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Cache;
use MicroweberPackages\Option\Models\ModuleOption;
use MicroweberPackages\Option\Models\Option;

abstract class AdminSettingsPage extends Page
{
    protected static bool $shouldRegisterNavigation = false;
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-document-text';
    protected static string $description = '';

    public function getDescription(): string
    {
        return static::$description;
    }

    public array $options;
    public array $translatableOptions;

    public array $optionGroups = [
        'website'
    ];
    public string $moduleNameForOption = 'settings/group/website';

    public function getOptionGroups()
    {
        return $this->optionGroups;
    }

    public function mount()
    {
        $booleanFields = [];
        $formFields = [];

        if (method_exists($this, 'form')) {
            $formInstance = $this->form(new Schema($this));
            $formFields = $formInstance->getFlatFields(true);
        }
        if (!empty($formFields)) {
            foreach ($formFields as $field) {
                $fieldStatePath = $field->getStatePath();
                $fieldStatePath = array_undot_str($fieldStatePath);
                if (isset($fieldStatePath['options'])) {
                    foreach ($fieldStatePath['options'] as $optionGroup => $optionKey) {
                        if (class_basename($field) == 'Toggle' || class_basename($field) == 'Checkbox') {
                            $booleanFields[$optionGroup][] = $optionKey;
                        }
                        $this->options[$optionGroup][$optionKey] = '';
                    }
                }
            }
        }

        if (isset($formState['translatableOptions'])) {
            $this->translatableOptions = $formState['translatableOptions'];
        }
        if (isset($formState['options'])) {
            $this->options = $formState['options'];
        }

        $cacheKey = 'admin_settings_options_' . implode('_', $this->getOptionGroups());
        $getOptions = Cache::remember($cacheKey, 300, function () {
            return Option::whereIn('option_group', $this->getOptionGroups())->get();
        });

        if ($getOptions) {
            foreach ($getOptions as $option) {
                if (!is_object($option)) {
                    continue;
                }
                if (isset($booleanFields[$option->option_group])) {
                    if (in_array($option->option_key, $booleanFields[$option->option_group])) {
                        if ($option->option_value == 'y' || $option->option_value == 1 || $option->option_value === true) {
                            $option->option_value = true;
                        } else {
                            $option->option_value = false;
                        }
                    }
                }
                $this->options[$option->option_group][$option->option_key] = $option->option_value;
            }
        }

        $translatableCacheKey = 'admin_settings_translatable_options_' . implode('_', $this->getOptionGroups());
        $getTranslatableOptions = Cache::remember($translatableCacheKey, 300, function () {
            return ModuleOption::whereIn('option_group', $this->getOptionGroups())->get();
        });
        if ($getTranslatableOptions) {
            foreach ($getTranslatableOptions as $option) {
                if (!is_object($option)) {
                    continue;
                }
                if (!empty($option->multilanguage_translations)) {
                    foreach ($option->multilanguage_translations as $translationLocale => $translationField) {
                        $this->translatableOptions[$option->option_group][$option->option_key][$translationLocale] = $translationField['option_value'];
                    }
                }
            }
        }

        return [];
    }


    public function updated($propertyName, $value)
    {
        $changedField = array_undot_str($propertyName);
        if (isset($changedField['options'])) {

            if (!is_array($changedField['options'])) {
                if (is_array($value)) {
                    //for select fields filanent sometimes send array with one value
                    $valueKeyFirst = array_key_first($value);
                    $valueSel = array_pop($value);
                    $changedField = [];
                    $changedField['options'][$valueKeyFirst] = $valueSel;
                    $value = $valueSel;
                }
            }


            foreach ($changedField['options'] as $optionGroup => $optionKey) {
                $optionToSave = [
                    'option_key' => $optionKey,
                    'option_value' => $value,
                    'option_group' => $optionGroup,
                    //    'module' => 'settings/group/website'
                ];

                if ($this->moduleNameForOption) {
                    $optionToSave['module'] = $this->moduleNameForOption;
                }


            save_option($optionToSave);

            // Clear settings cache when options are updated
            $cacheKey = 'admin_settings_options_' . implode('_', $this->getOptionGroups());
            Cache::forget($cacheKey);

            //$moduleNameForOption

            //get the minute of the hour and add it to the notification id to make it unique
            $notificationId = 'settings_updated' . crc32(date('i') . $optionKey . $optionGroup);

            Notification::make($notificationId)
                ->title('Settings Updated')
                ->success()
                ->send();
            }
        }
        if (isset($changedField['translatableOptions'])) {


            foreach ($changedField['translatableOptions'] as $optionGroup => $optionValueLanguages) {
                foreach ($optionValueLanguages as $optionKey => $optionValueLang) {

                    $optionToSave = [
                        'optionValueLanguages' => $optionValueLanguages,
                        'option_key' => $optionKey,
                        'option_value' => $value,
                        'option_group' => $optionGroup,
                        'lang' => $optionValueLang,
                    ];
                    if ($this->moduleNameForOption) {
                        $optionToSave['module'] = $this->moduleNameForOption;
                    }
                save_option($optionToSave);

                // Clear translatable settings cache when options are updated
                $translatableCacheKey = 'admin_settings_translatable_options_' . implode('_', $this->getOptionGroups());
                Cache::forget($translatableCacheKey);

                // save_option([
                //     'optionValueLanguages' => $optionValueLanguages,
                //     'option_key' => $optionKey,
                //     'option_value' => $value,
                //     'option_group' => $optionGroup,
                //     'lang' => $optionValueLang,
                //     'module' => 'settings/group/website'
                // ]);

                Notification::make()
                    ->title('Settings Updated')
                    ->success()
                    ->send();
                }
            }
        }

    }


}
