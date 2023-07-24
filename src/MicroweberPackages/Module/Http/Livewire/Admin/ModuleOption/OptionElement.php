<?php

namespace MicroweberPackages\Module\Http\Livewire\Admin\ModuleOption;

use MicroweberPackages\Admin\Http\Livewire\AdminComponent;
use MicroweberPackages\Multilanguage\MultilanguageHelpers;

class OptionElement extends AdminComponent
{
    public string $view = 'module::admin.option.text';
   // public string $viewTranslatable = 'module::admin.option.text-multilanguage';

    public string $optionGroup = '';
    public string $module = '';
    public string $optionKey = '';

    public array $state = [

    ];
    public array $translatableOptions = [

    ];

    public array $translations = [

    ];


    public $model;

    public $translatable = false;

    public string $defaultLanguage = '';
    public string $currentLanguage = '';
    public array $supportedLanguages = [];
    public array $currentLanguageData = [];
    public string $fieldName = '';

    public function boot()
    {
        $this->newModelInstance();
    }

    private function newModelInstance()
    {
        if (!empty($this->module)) {
            $this->model = \MicroweberPackages\Option\Models\ModuleOption::where('option_key', $this->optionKey)
                ->where('option_group', $this->optionGroup)
                ->where('module', $this->module)
                ->firstOrNew();
        } else {
            $this->model = \MicroweberPackages\Option\Models\Option::where('option_key', $this->optionKey)
                ->where('option_group', $this->optionGroup)
                ->firstOrNew();
        }
    }

    public function mount()
    {
        $this->newModelInstance();

        if ($this->optionKey) {
            $val = get_module_option($this->optionKey, $this->optionGroup);
            $this->settings = [
                $this->optionKey => $val
            ];
            $this->state['settings'][$this->optionKey] = $val;
        }

        if (!empty($this->module)) {
            $translatableModuleOptions = MultilanguageHelpers::getTranslatableModuleOptions();
            if (isset($translatableModuleOptions[$this->module]) && !empty($translatableModuleOptions[$this->module])) {
                $this->translatableOptions = $translatableModuleOptions[$this->module];
            }
        }

        $multilanguageIsEnabled = MultilanguageHelpers::multilanguageIsEnabled();
        if ($multilanguageIsEnabled) {
            if (in_array($this->optionKey, $this->translatableOptions)) {
                $this->translatable = true;
            }
        }

        if ($this->translatable && $multilanguageIsEnabled) {
            $this->supportedLanguages = get_supported_languages(true);
            if (!empty($this->supportedLanguages)) {
                $this->populateTranslations();
            } else {
                $this->translatable = false;
            }
        }

    }

    public function updated()
    {

        $option = array();
        $settings = [];
        if (isset($this->state['settings']) and !empty($this->state['settings'])) {
            $settings = $this->state['settings'];
        }

        if (isset($settings) and !empty($settings)) {
            foreach ($settings as $key => $value) {
                $option['option_value'] = $value;
                $option['option_key'] = $key;
                $option['option_group'] = $this->optionGroup;

                if (!empty($this->module)) {
                    $option['module'] = $this->module;
                }

            }
        }

        $translations = [];
        if (isset($this->state['translations']) and !empty($this->state['translations'])) {
            $translations = $this->state['translations'];
        }

        if (isset($translations) and !empty($translations)) {
            $option['multilanguage'] = [];
            foreach ($translations as $lang => $items) {
                foreach ($items as $key => $val) {
                    $option['multilanguage'] ['option_value'][$lang] = $val;
                }
            }
        }
        $this->saveOptionData($option);

        $this->emit('settingsChanged', ['optionGroup' => $this->optionGroup,'moduleId' => $this->optionGroup, 'state' => $this->state]);
    }

    public function saveOptionData($option)
    {
        $this->newModelInstance();

        if (isset($option['option_value'])) {
            $this->model->option_value = $option['option_value'];
        }
        if (isset($option['option_group'])) {
            $this->model->option_group = $option['option_group'];
        }
        if (isset($option['option_key'])) {
            $this->model->option_key = $option['option_key'];
        }
        if (isset($option['module'])) {
            $this->model->module = $option['module'];
        }

        if (isset($option['multilanguage']) and !empty($option['multilanguage'])) {
            $this->model['multilanguage'] = $option['multilanguage'];
        }

        $modelSave = $this->model->save();

        $this->dispatchBrowserEvent('mw-option-saved', [
            'optionGroup' => $this->optionGroup,
            'optionKey' => $this->optionKey,
            'optionValue' => $this->model->option_value
        ]);

        return $modelSave;
    }


    public function populateTranslations()
    {

        $this->defaultLanguage = mw()->lang_helper->default_lang();
        $this->currentLanguage = mw()->lang_helper->current_lang();

        if ($this->model && method_exists($this->model, 'getTranslationsFormated')) {
            $modelTranslations = $this->model->getTranslationsFormated();
            if (!empty($modelTranslations)) {
                foreach ($modelTranslations as $locale => $val) {
                    if ($locale != $this->defaultLanguage) {
                        if(isset($val['option_value'])) {
                            $this->translations[$locale][$this->optionKey] = $val['option_value'];
                            $this->state['translations'][$locale][$this->optionKey] = $val['option_value'];
                        }
                    }
                }

            }
        }

        $this->fieldName = $this->optionKey;
        foreach($this->supportedLanguages as $language) {
            if ($language['locale'] == $this->defaultLanguage) {
                $this->currentLanguageData = $language;
            }
        }

    }

    public function render()
    {
        if ($this->translatable) {
            return view($this->viewTranslatable);
        }
        return view($this->view);
    }
}
