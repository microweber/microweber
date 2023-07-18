<?php

namespace MicroweberPackages\Modules\Teamcard\Http\Livewire;

use MicroweberPackages\LiveEdit\Http\Livewire\ModuleSettingsComponent;

class TeamcardListItemsComponent extends ModuleSettingsComponent
{

    public $items = [];

    public $listeners = [
        'onItemChanged' => '$refresh',
        'onReorderListItems' => 'reorderListItems',
    ];

    public function getItems()
    {
        $settings = get_module_option('settings', $this->moduleId);
        $json = @json_decode($settings, true);

        if ($json) {
            $this->items = $json;
        }

        return $this->items;
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
            'option_key' => 'settings',
            'option_value' => json_encode($allItems)
        ));
        $this->emit('onItemChanged');
    }

    public function render()
    {
        $this->getItems();

        return view('microweber-module-teamcard::livewire.list-teamcard-items');
    }


}
