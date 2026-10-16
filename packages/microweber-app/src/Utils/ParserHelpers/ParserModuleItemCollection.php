<?php

namespace MicroweberPackages\App\Utils\ParserHelpers;

class ParserModuleItemCollection
{

    public $items = array();

    public function add($key, ParserModuleItem $item)
    {
        $this->items[$key] = $item;
    }

    public function getItems()
    {
        return $this->items;
    }

    public function get($key)
    {
        return $this->items[$key];
    }
    public function has($key)
    {
        return isset($this->items[$key]);
    }

    public function getItemsForProcessing(): array
    {
        $to_return = array();
        if($this->items){
            foreach ($this->items as $key => $item) {
                if($item and !$item->isProcessed() and !$item->isProcessing()){
                    $to_return[$key] = $item;
                }

            }
        }
        return $to_return;
    }


}
