<?php

namespace MicroweberPackages\LiveEdit\Http\Livewire\ItemsEditor;

class ModuleSettingsItemsEditorEditItemComponent extends AbstractModuleSettingsEditorComponent
{
    public string $view = 'microweber-live-edit::module-items-editor-edit-item';
    public string $itemId = '';

    public array $itemState = [];


    public $listeners = [
        'onItemChanged' => '$refresh',
        'onItemDeleted' => '$refresh',
        'refreshComponent' => '$refresh',
        'onReorderListItems' => 'reorderListItems',
    ];


    public function render()
    {
        $this->getItems();
        if ($this->itemId) {
            $allItems = $this->getItems();
            if ($allItems) {
                foreach ($allItems as $item) {
                    if (isset($item['itemId']) and $item['itemId'] == $this->itemId) {
                        $this->itemState = $item;
                    }
                }
            }
        }

        return view($this->view);
    }


    public function submit()
    {
        $json = $this->getItems();
        $editorSettings = $this->getEditorSettings();

        $defaults = array(
            'itemId' => $this->moduleId . '_' . uniqid(),
        );

        if (isset($editorSettings['schema'])) {
            foreach ($editorSettings['schema'] as $field) {
                $fieldName = $field['name'];
                $defaultValue = isset($field['default']) ? $field['default'] : '';
                $defaults[$fieldName] = $defaultValue;
            }
        }

        if (isset($json) == false or count($json) == 0) {
            $json = [];
        }
        $isNewItem = false;

        $newItem = [];
        $allItems = [];


        $newItemState = $this->itemState;

        if ($newItemState) {
            $newItem = $newItemState;
        }


        if ($this->itemId) {
            $newItem['itemId'] = $this->itemId;
        } else {
            $isNewItem = true;
            $newItem['itemId'] = $this->moduleId . '_' . uniqid();
            foreach ($defaults as $key => $value) {
                if (!isset($newItem[$key])) {
                    $newItem[$key] = $value;
                }
            }
        }
        if ($isNewItem and !empty($newItem)) {

            $allItems[] = $newItem;
        }


        $sortIds = [];
        if (!empty($json)) {
            foreach ($json as $item) {
                if (isset($item['itemId'])) {
                    if (!$isNewItem and !empty($newItem)) {
                        if ($item['itemId'] == $newItem['itemId']) {
                            $item = $newItem;
                        }

                    }
                    foreach ($defaults as $key => $value) {
                        if (!isset($item[$key])) {
                            $item[$key] = $value;
                        }
                    }
                    $allItems[] = $item;
                }
            }
        }

        if ($allItems) {
            foreach ($allItems as $key => $item) {
                if (!isset($item['itemId'])) {
                    unset($allItems[$key]);
                }
            }
        }

        if ($isNewItem) {
            $this->itemState = [];
        }

        $this->saveItems($allItems);

      //  $this->emitTo('microweber-live-edit::module-items-editor-list', 'onItemChanged', ['moduleId' => $this->moduleId]);
        $this->emit( 'onItemChanged', ['moduleId' => $this->moduleId]);

        $this->emit('switchToMainTab');

        $this->emit('onItemChanged');


    }

    public function updatedSettings($settings)
    {
        $this->emit('settingsChanged', ['moduleId' => $this->moduleId, 'settings' => $this->settings]);
    }
}
