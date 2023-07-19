<?php

namespace MicroweberPackages\LiveEdit\Http\Livewire\ItemsEditor;

use MicroweberPackages\Admin\Http\Livewire\AdminComponent;

abstract class AbstractModuleSettingsEditorComponent extends AdminComponent
{
    public string $view = '';

    public $moduleTitle = 'Module Settings';

    public string $moduleId = '';
    public string $moduleType = '';
    public array $items = [];
    public array $editorSettings = [];


    public function getSettingsKey()
    {
        $editorSettings = $this->getEditorSettings();

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
        save_option(array(
            'option_group' => $this->moduleId,
            'module' => $this->moduleType,
            'option_key' => $this->getSettingsKey(),
            'option_value' => json_encode($allItems)
        ));
        $this->emit('onItemChanged');
    }
}
