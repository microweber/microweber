<?php

namespace MicroweberPackages\Admin\MenuBuilder;

use MicroweberPackages\Admin\MenuBuilder\Traits\HasIcon;
use Spatie\Menu\Item;

class Menu extends \Spatie\Menu\Menu
{
    use HasIcon;

    public string | Item $prepend = '';

    public function items()
    {
        return $this->items;
    }
}
