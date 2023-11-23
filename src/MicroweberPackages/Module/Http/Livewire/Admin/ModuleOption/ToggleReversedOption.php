<?php

namespace MicroweberPackages\Module\Http\Livewire\Admin\ModuleOption;

class ToggleReversedOption extends OptionElement
{
    public string $view = 'module::admin.option.toggle-reversed';
  //  public string $viewTranslatable = 'module::admin.option.toggle-multilanguage';

    public $optionValueReversed = 1;

    public function updatedOptionValueReversed($optionValue)
    {
        if ($optionValue) {
            $this->state['settings'][$this->optionKey] = 0;
        } else {
            $this->state['settings'][$this->optionKey] = 1;
        }
        $this->updated();
    }

    public function mount()
    {
        parent::mount();

        if ($this->state['settings'][$this->optionKey] == 1) {
            $this->optionValueReversed = 0;
        } else {
            $this->optionValueReversed = 1;
        }
    }
}
