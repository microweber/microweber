<?php

namespace MicroweberPackages\Module\Http\Livewire\Admin\ModuleOption;

class CheckboxOption extends OptionElement
{
    public $checkboxOptions = [];
    public $selectedCheckboxes = [];

    public string $view = 'module::admin.option.checkbox';
    // public string $viewTranslatable = 'module::admin.option.text-multilanguage';

    public function updatedSelectedCheckboxes()
    {
        $this->state['settings'][$this->optionKey] = $this->selectedCheckboxes;
        $this->updated();
    }

    public function mount()
    {
        parent::mount();

        if (isset($this->state['settings'][$this->optionKey])) {
            if (!empty($this->state['settings'][$this->optionKey])) {
                $this->selectedCheckboxes = json_decode($this->state['settings'][$this->optionKey], true);
            }
        }
    }

}
