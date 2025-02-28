<?php

namespace MicroweberPackages\LiveEdit\Http\Livewire\Presets;

use MicroweberPackages\Admin\Http\Livewire\AdminComponent;

class ModulePresetsManager extends AdminComponent
{
    public $view = "microweber-live-edit::presets.module-presets-manager";

    public $moduleId;
    public $moduleType;

    public $items = [];
    public array $itemState = [];
    public array $selectedPreset = [];
    public $moduleIdFromPreset = '';
    public array $selectedItemsIds = [];
    public $areYouSureDeleteModalOpened = false;
    public $isAlreadySavedAsPreset = false;

    protected $listeners = [
        'onShowConfirmDeleteItemById' => 'showConfirmDeleteItemById',
        'onEditItemById' => 'showItemById',
        'onSaveAsNewPreset' => 'saveAsNewPreset',
        'onSelectPresetForModule' => 'selectPresetForModule',
        'onRemoveSelectedPresetForModule' => 'removeSelectedPresetForModule',
        'switchToMainTab' => 'resetItemState',
    ];

    public function mount()
    {
        $this->resetItemState();
    }

    public function resetItemState()
    {
        $this->itemState = [];
    }

    public function render()
    {
        $this->items = $this->getPresets();
        $this->isAlreadySavedAsPreset = false;

        if (is_array($this->items) && !empty($this->items)) {
            foreach ($this->items as $item) {
                if (isset($item['module_id']) && $item['module_id'] == $this->moduleId) {
                    $this->isAlreadySavedAsPreset = true;
                    break;
                }
            }
        }

        return view($this->view);
    }

    public function showItemById($id)
    {
        // Handle both direct ID and object with ID property
        $itemId = is_array($id) && isset($id['id']) ? $id['id'] : $id;

        $presets = $this->getPresets();
        if (is_array($presets) && !empty($presets)) {
            foreach ($presets as $preset) {
                if (isset($preset['id']) && $preset['id'] == $itemId) {
                    $this->itemState = $preset;
                    break;
                }
            }
        }
    }

    public function submit()
    {
        $this->validate([
            'itemState.name' => 'required|min:2|max:255',
        ]);

        $savePreset = [
            'name' => $this->itemState['name'],
            'module' => $this->itemState['module'],
            'module_id' => $this->itemState['module_id'],
        ];



        if (isset($this->itemState['id'])) {
            $savePreset['id'] = $this->itemState['id'];
        }

        $save = save_module_as_template($savePreset);
        $this->resetItemState();

        $this->dispatch('settingsChanged', [
            'moduleId' => $this->moduleId,
            'moduleType' => $this->moduleType,
         ]);

        return $this->render();
    }

    public function getPresets()
    {
        $presets = get_saved_modules_as_template("module={$this->moduleType}");
        return is_array($presets) ? $presets : [];
    }

    public function showConfirmDeleteItemById($itemId)
    {
        $this->areYouSureDeleteModalOpened = true;
        $this->selectedItemsIds = is_array($itemId) ? [$itemId['itemId']] : [$itemId];
    }

    public function confirmDeleteSelectedItems()
    {
        if (!empty($this->selectedItemsIds)) {
            foreach ($this->selectedItemsIds as $itemId) {
                delete_module_as_template(['id' => $itemId]);
            }
        }

        $this->areYouSureDeleteModalOpened = false;
        $this->selectedItemsIds = [];

        return $this->render();
    }

    public function saveAsNewPreset($module_attrs = [])
    {
        $name = titlelize($this->moduleType);

        $this->itemState = [
            'id' => 0,
            'name' => $name . ' ' . time(),
            'module' => $this->moduleType,
            'module_id' => $this->moduleId,
        ];



        return $this->submit();
    }

    public function removeSelectedPresetForModule($applyToModuleId)
    {
        // Handle both direct ID and object with moduleId property
        $moduleId = is_array($applyToModuleId) && isset($applyToModuleId['moduleId']) ? $applyToModuleId['moduleId'] : $applyToModuleId;

        $this->moduleIdFromPreset = '';
        $this->selectedPreset = [];
        $this->dispatch('removeSelectedPresetForModule', ['moduleId' => $moduleId]);
    }

    public function selectPresetForModule($id)
    {
        // Handle both direct ID and object with ID property
        $presetId = is_array($id) && isset($id['id']) ? $id['id'] : $id;



        $applyToModuleId = $this->moduleId;
        $presets = $this->getPresets();

        if (is_array($presets) && !empty($presets)) {
            foreach ($presets as $preset) {
                if (isset($preset['id']) && $preset['id'] == $presetId) {
                    $this->selectedPreset = $preset;
                    $this->moduleIdFromPreset = $preset['module_id'];



                    $this->dispatch('applyPreset',
                        moduleId :$applyToModuleId,
                        preset: $preset
                    );
                    break;
                }
            }
        }
    }
}
