<?php

namespace MicroweberPackages\Template\Http\Livewire\Admin;

use MicroweberPackages\Marketplace\Http\Livewire\Admin\MarketplaceItemModal;

class AdminTemplateUpdateModal extends MarketplaceItemModal
{
    public function mount()
    {
        $templateComposerFile = template_dir() . 'composer.json';
        if (is_file($templateComposerFile)) {
            $composer = json_decode(file_get_contents($templateComposerFile), true);
            if (isset($composer['name'])) {
                $this->name = $composer['name'];
            }
        }

        parent::mount();
    }

    public function render()
    {
        return view('template::livewire.admin.template-update-modal');
    }
}
