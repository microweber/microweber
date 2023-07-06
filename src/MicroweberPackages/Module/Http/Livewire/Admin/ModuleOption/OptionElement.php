<?php

namespace MicroweberPackages\Module\Http\Livewire\Admin\ModuleOption;

use MicroweberPackages\Admin\Http\Livewire\AdminComponent;
use MicroweberPackages\Multilanguage\MultilanguageHelpers;

class OptionElement extends AdminComponent
{
    public string $view = 'module::admin.module-option.text';
    public string $viewTranslatable = 'module::admin.module-option.text-multilanguage';

    public string $moduleId = '';
    public string $moduleType = '';
    public string $optionName = '';

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

    public function boot()
    {
        $this->model = \MicroweberPackages\Option\Models\ModuleOption::where('option_key', $this->optionName)
            ->where('option_group', $this->moduleId)
            ->where('module', $this->moduleType)
            ->firstOrNew();
    }

    public function mount()
    {
        $this->model = \MicroweberPackages\Option\Models\ModuleOption::where('option_key', $this->optionName)
            ->where('option_group', $this->moduleId)
            ->where('module', $this->moduleType)
            ->first();

        if ($this->optionName) {
            $val = get_module_option($this->optionName, $this->moduleId);
            $this->settings = [
                $this->optionName => $val
            ];
            $this->state['settings'][$this->optionName] = $val;
        }
        if (!empty($this->moduleType)) {
            $translatableModuleOptions = MultilanguageHelpers::getTranslatableModuleOptions();
            if (isset($translatableModuleOptions[$this->moduleType]) && !empty($translatableModuleOptions[$this->moduleType])) {
                $this->translatableOptions = $translatableModuleOptions[$this->moduleType];
            }
        }
        if (in_array($this->optionName, $this->translatableOptions)) {
            $this->translatable = true;
        }

        if ($this->translatable and MultilanguageHelpers::multilanguageIsEnabled()) {
            $this->populateTranslations();
        }

    }

    public function updated($settingKey, $value)
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
                $option['option_group'] = $this->moduleId;
                $option['module'] = $this->moduleType;

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
        $this->saveModuleOptionData($option);
        $this->emit('settingsChanged', ['moduleId' => $this->moduleId, 'state' => $this->state]);
    }

    public function saveModuleOptionData($option)
    {
        $this->model = \MicroweberPackages\Option\Models\ModuleOption::where('option_key', $this->optionName)
            ->where('option_group', $this->moduleId)
            ->where('module', $this->moduleType)
            ->firstOrNew();

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
        return $modelSave;
    }


    public function populateTranslations()
    {

        $this->defaultLanguage = mw()->lang_helper->default_lang();
        $this->currentLanguage = mw()->lang_helper->current_lang();


        $modelTranslations = [];
        if ($this->model && method_exists($this->model, 'getTranslationsFormated')) {
            $modelTranslations = $this->model->getTranslationsFormated();

            if (!empty($modelTranslations)) {
                foreach ($modelTranslations as $locale => $val) {
                    if ($locale != $this->defaultLanguage) {
                        if(isset($val['option_value'])) {
                            $this->translations[$locale][$this->optionName] = $val['option_value'];
                            $this->state['translations'][$locale][$this->optionName] = $val['option_value'];
                        }
                    }
                }

            }
        }

        $supportedLanguages = get_supported_languages(true);

        $this->supportedLanguages = $supportedLanguages;

    }

    public function render()
    {
        if ($this->translatable) {
            return view($this->viewTranslatable);
        }
        return view($this->view);
    }
}
