<?php

namespace MicroweberPackages\LiveEdit\Http\Livewire\ItemsEditor;

class ModuleSettingsItemsEditorListComponent extends AbstractModuleSettingsEditorComponent
{
    public string $view = 'microweber-live-edit::module-items-editor-list';



    public $listeners = [
        'onItemChanged' => '$refresh',
        'refreshComponent' => '$refresh',
        'onReorderListItems' => 'reorderListItems',
    ];



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

        $this->saveItems($allItems);
        $this->emit('onItemChanged');
    }


    public function render()
    {
        $this->getItems();

        return view($this->view);
    }
}
