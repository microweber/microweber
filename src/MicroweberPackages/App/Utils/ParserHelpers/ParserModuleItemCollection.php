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


}
