<?php

namespace MicroweberPackages\LiveEdit\Http\Livewire\ItemsEditor;

use MicroweberPackages\Admin\Http\Livewire\AdminComponent;

abstract class AbstractModuleSettingsEditorComponent extends AdminComponent
{
    public string $view = '';

    public $moduleTitle = 'Module Settings';

    public bool $areYouSureDeleteModalOpened = false;
    public string $moduleId = '';
    public string $moduleType = '';
    public array $items = [];
    public array $selectedItemsIds = [];

    public array $editorSettings = [];



    public function getSettingsKey()
    {
        $editorSettings = $this->getEditorSettings();
        if (!$editorSettings) {
            return 'settings';
        }
        if (!isset($editorSettings['config'])) {
            return 'settings';
        }
        if (!isset($editorSettings['config']['settingsKey'])) {
            return 'settings';
        }

        return $editorSettings['config']['settingsKey'];

    }

    public function getEditorSettings()
    {
        return $this->editorSettings;

    }

    public function getItems()
    {
        $settings = get_module_option($this->getSettingsKey(), $this->moduleId);
        $json = @json_decode($settings, true);

        if ($json) {
            $this->items = $json;
        }

        return $this->items;
    }

    public function render()
    {

        return view($this->view);
    }

    public function showConfirmDeleteItemById($itemId)
    {

        $this->areYouSureDeleteModalOpened = true;
        $this->selectedItemsIds = [$itemId];
    }



    public function confirmDeleteSelectedItems()
    {

        if ($this->selectedItemsIds and !empty($this->selectedItemsIds)) {
            $existing = $this->getItems();
            if ($existing) {
                foreach ($existing as $key => $item) {
                    if (isset($item['itemId']) and in_array($item['itemId'], $this->selectedItemsIds)) {
                        unset($existing[$key]);
                    }
                }

                if (empty($existing)) {
                    $this->items = [];
                }
                $this->saveItems($existing);
            }
        }
        $this->areYouSureDeleteModalOpened = false;
        $this->selectedItemsIds = [];


        $this->getItems();

        $this->emit('onItemDeleted');


    }

    public function reorderListItems($order)
    {
        $order = $order['itemIds'];

        $itemsOldSort = $this->getItems();
        $topItems = [];
        if ($itemsOldSort) {
            foreach ($order as $newOrder) {
                foreach ($itemsOldSort as $itemKey => $item) {
                    if (isset($item['itemId'])) {
                        if ($newOrder == $item['itemId']) {
                            $topItems[] = $item;
                            unset($itemsOldSort[$itemKey]);
                        }
                    }
                }

            }
        }
        $allItems = [];
        $allItems = array_merge($topItems, $itemsOldSort);
        $this->emit('onItemChanged');
        $this->saveItems($allItems);
    }

    public function saveItems($allItems)
    {
        $save = array(
            'option_group' => $this->moduleId,
            'module' => $this->moduleType,
            'option_key' => $this->getSettingsKey(),
            'option_value' => json_encode($allItems)
        );
        save_option($save);

        $this->emit('settingsChanged', ['moduleId' => $this->moduleId, 'settings' => $save]);

    }
}
