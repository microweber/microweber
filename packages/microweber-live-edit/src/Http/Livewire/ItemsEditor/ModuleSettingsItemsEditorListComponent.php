<?php

/**
 * DEPRECATED
 */



namespace MicroweberPackages\LiveEdit\Http\Livewire\ItemsEditor;
use Livewire\Attributes\Isolate;
/**
 * @deprecated
 */
#[Isolate]
class ModuleSettingsItemsEditorListComponent extends AbstractModuleSettingsEditorComponent
{
    public string $view = 'microweber-live-edit::module-items-editor-list';



    public $listeners = [
     //  'onItemChanged' => '$refresh',
     'onItemChanged' => 'handleOnItemChanged',
     'onItemDeleted' => '$refresh',
        'refreshComponent' => '$refresh',
        'onReorderListItems' => 'reorderListItems',
        'onShowConfirmDeleteItemById' => 'showConfirmDeleteItemById',
        'onConfirmDeleteSelectedItems' => 'confirmDeleteSelectedItems',
    ];
    public function handleOnItemChanged($moduleId, $item, $isNew, $itemId)
    {

      //   dd($moduleId, $item, $isNew, $itemId);

        //  $this->dispatch('$refresh')->to('microweber-live-edit::module-items-editor-list');
//        if($isNew){
//            return $this->render();
//        }
       // return $this->render();
        $this->dispatch('refreshComponent')->self();
        //. $this->dispatch('onItemChanged', moduleId: $moduleId, item: $item, isNew: $isNew, itemId: $itemId);
    }


//    public function reorderListItems($order)
//    {
//        $order = $order['itemIds'];
//
//        $itemsOldSort = $this->getItems();
//        $topItems = [];
//        if ($itemsOldSort) {
//            foreach ($order as $newOrder) {
//                foreach ($itemsOldSort as $itemKey => $item) {
//                    if (isset($item['itemId'])) {
//                        if ($newOrder == $item['itemId']) {
//                            $topItems[] = $item;
//                            unset($itemsOldSort[$itemKey]);
//                        }
//                    }
//                }
//
//            }
//        }
//        $allItems = [];
//        $allItems = array_merge($topItems, $itemsOldSort);
//
//        $this->saveItems($allItems);
//   //   $this->dispatch('onItemChanged');
//     }


    public function render()
    {
        $this->getItems();

        return view($this->view);
    }
}
