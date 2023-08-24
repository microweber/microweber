<?php

namespace MicroweberPackages\LiveEdit\Http\Livewire\Presets;

use MicroweberPackages\Admin\Http\Livewire\AdminComponent;


class ModulePresetsManager extends AdminComponent
{
    public $view = "microweber-live-edit::presets.module-presets-manager";

    public $moduleId;
    public $moduleType;

    public $items = [];
    public $editorSettings = [];
    public array $itemState = [];
    public array $selectedPreset = [];
    public $moduleIdFromPreset = '';
    public array $selectedItemsIds = [];
    public $areYouSureDeleteModalOpened = false;
    public $isAlreadySavedAsPreset = false;

    public $listeners = [
        'onItemChanged' => '$refresh',
        'refreshComponent' => '$refresh',
        'onReorderListItems' => 'reorderListItems',
        'onShowConfirmDeleteItemById' => 'showConfirmDeleteItemById',
        'onEditItemById' => 'showItemById',
        'onSaveAsNewPreset' => 'saveAsNewPreset',
        'onSelectPresetForModule' => 'selectPresetForModule',
        'onRemoveSelectedPresetForModule' => 'removeSelectedPresetForModule',
    ];


    public function render()
    {
       // $this->itemState['module_id'] = $this->moduleId;
     //  $this->itemState['module'] = $this->moduleType;
        // $this->itemState['module_attrs'] = [];

        $this->items = $this->getPresets();
        $this->editorSettings = $this->getEditorSettings();
        $this->isAlreadySavedAsPreset = false;
        if ($this->items) {
            foreach ($this->items as $item) {
                if (isset($item['module_id']) and $item['module_id'] == $this->moduleId) {
                    $this->isAlreadySavedAsPreset = true;
                }
            }
        }

        return view($this->view);
    }

    public function showItemById($id)
    {
        $presets = $this->getPresets();
        if ($presets) {
            foreach ($presets as $preset) {
                if ($preset['id'] == $id) {
                    // $preset['module_attrs'] = json_encode($preset['module_attrs']);
                    $this->itemState = $preset;
                }
            }
        }


    }

    public function submit()
    {
        $rules = [];
        $schema = $this->getEditorSettings()['schema'];

        foreach ($schema as $field) {
            if (isset($field['name']) && isset($field['rules'])) {
                $rules['itemState.' . $field['name']] = $field['rules'];
            }
        }
        $this->validate($rules);

        $savePreset = [];
        $savePreset['name'] = $this->itemState['name'];
        $savePreset['module'] = $this->itemState['module'];
        if (isset($this->itemState['module_attrs'])) {
            $savePreset['module_attrs'] = $this->itemState['module_attrs'];
        }
        $savePreset['module_id'] = $this->itemState['module_id'];
        if (isset($this->itemState['id'])) {
            $savePreset['id'] = $this->itemState['id'];
        }
        $save = save_module_as_template($savePreset);

        $this->emit('switchToMainTab');
        $this->emit('settingsChanged', ['moduleId' => $this->moduleId]);

        return $this->render();

    }

    public function getPresets()
    {
        $presets = get_saved_modules_as_template("module={$this->moduleType}");

        return $presets;
    }

    public function showConfirmDeleteItemById($itemId)
    {

        $this->areYouSureDeleteModalOpened = true;
        $this->selectedItemsIds = [$itemId];


    }

    public function confirmDeleteSelectedItems()
    {

        if ($this->selectedItemsIds and !empty($this->selectedItemsIds)) {
            foreach ($this->selectedItemsIds as $itemId) {
                $delete = delete_module_as_template(['id' => $itemId]);
            }
        }
        $this->areYouSureDeleteModalOpened = false;
        $this->selectedItemsIds = [];
        $this->render();

    }

    public function saveAsNewPreset($module_attrs = [])
    {

        $this->itemState['id'] = 0;
        $this->itemState['name'] = 'New preset ' . time();
        $this->itemState['module'] = $this->moduleType;
        $this->itemState['module_id'] = $this->moduleId;
        if ($module_attrs and is_array($module_attrs) and !empty($module_attrs)) {
            $this->itemState['module_attrs'] = json_encode($module_attrs);
        }

        $this->submit();
    }

    public function removeSelectedPresetForModule($applyToModuleId)
    {
        $this->moduleIdFromPreset = false;
        $this->selectedPreset = [];
        $this->emit('removeSelectedPresetForModule', $applyToModuleId);

    }


    public function selectPresetForModule($id)
    {

        $applyToModuleId = $this->moduleId;
        $presets = $this->getPresets();
        if ($presets) {
            foreach ($presets as $preset) {

                if ($preset['id'] == $id) {
                    $this->selectedPreset = $preset;
                    $this->moduleIdFromPreset = $preset['module_id'];
                    $this->emit('applyPreset', $applyToModuleId,$preset);
                }
            }
        }

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
                'additionalButtonsView' => 'microweber-live-edit::presets.select-preset-button',
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
                    'rules' => 'required|string',
                    'name' => 'module',
                    'label' => 'module',
                ],
                [
                    'type' => 'textarea',
                    // 'rules' => 'required|string',
                    'name' => 'module_attrs',
                    'label' => 'module_attrs',
                ],
                [
                    'type' => 'textarea',
                    'rules' => 'required|string',
                    'name' => 'module_id',
                    'label' => 'module_id',
                ]

            ]
        ];
        return $editorSettings;
    }
}
