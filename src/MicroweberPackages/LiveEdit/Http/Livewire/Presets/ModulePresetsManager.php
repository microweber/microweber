<?php

namespace MicroweberPackages\LiveEdit\Http\Livewire\Presets;

use MicroweberPackages\Admin\Http\Livewire\AdminComponent;

//class ModulePresetsManager extends AdminComponent
class ModulePresetsManager extends AdminComponent
{
    public $view = "microweber-live-edit::presets.module-presets-manager";

    public $moduleId;
    public $moduleType;

    public $items = [];
    public $editorSettings = [];
    public array $itemState = [];

    public function render()
    {
        $this->itemState['module_id'] = $this->moduleId;
        $this->itemState['module'] = $this->moduleType;
        $this->itemState['module_attrs'] = [];

        $this->items = $this->getPresets();
        $this->editorSettings = $this->getEditorSettings();

        return view($this->view);
    }

    public function submit()
    {
        $this->validate([
            'itemState.name' => 'required|string',
            'itemState.module' => 'required|string',
            'itemState.module_attrs' => 'required',
            'itemState.module_id' => 'required',
        ]);



        $savePreset = [];
        $savePreset['name'] = $this->itemState['name'];
        $savePreset['module'] = $this->itemState['module'];
        $savePreset['module_attrs'] = $this->itemState['module_attrs'];
        $savePreset['module_id'] = $this->itemState['module_id'];
       $save =  save_module_as_template($savePreset);

        $this->emit('settingsChanged', ['moduleId' => $this->moduleId]);

    }

    public function getEditorSettings()
    {

        $editorSettings = [
            'config' => [
                'title' => '',
                'addButtonText' => 'Add Item',
                'editButtonText' => 'Edit',
                'deleteButtonText' => 'Delete',
                'sortItems' => true,
                'settingsKey' => 'settings',
                'listColumns' => [
                    'name' => 'name',
                ],
            ],
            'schema' => [
                [
                    'type' => 'text',
                    'rules' => 'required|min:2|max:255',
                    'label' => 'Preset name',
                    'name' => 'name',
                    'placeholder' => 'Preset name',
                    'help' => 'Preset name is required'
                ],
                [
                    'type' => 'textarea',
                    'name' => 'module',
                    'label' => 'module',
                ],
                [
                    'type' => 'textarea',
                    'name' => 'module_attrs',
                    'label' => 'module_attrs',
                ],
                [
                    'type' => 'textarea',
                    'name' => 'module_id',
                    'label' => 'module_id',
                ]

            ]
        ];
        return $editorSettings;
    }

    public function getPresets()
    {
        $presets = get_saved_modules_as_template("module={$this->moduleType}");

        return $presets;
    }
}
