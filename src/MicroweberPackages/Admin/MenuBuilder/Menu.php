<?php

namespace MicroweberPackages\Admin\MenuBuilder;

use Spatie\Menu\Item;

class Menu extends \Spatie\Menu\Menu
{
    public string | Item $prepend = '';

    public function items()
    {
        return $this->items;
    }
}
