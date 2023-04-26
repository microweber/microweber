<?php

namespace MicroweberPackages\Admin\MenuBuilder;

use Illuminate\Support\Facades\Route;
use MicroweberPackages\Admin\MenuBuilder\Traits\HasIcon;
use MicroweberPackages\Admin\MenuBuilder\Traits\HasOrder;
use Spatie\Menu\Html\Attributes;
use Spatie\Menu\Item;

class Menu extends \Spatie\Menu\Menu
{
    use HasIcon;
    use HasOrder;

    public string | Item $prepend = '';

    public function items()
    {
        return $this->items;
    }

    protected function __construct(Item ...$items)
    {
        $this->items = $items;

        $this->htmlAttributes = new Attributes();
        $this->parentAttributes = new Attributes();

        $this->setActive(function (Link $link) {
            if ($link->route) {
                if (Route::currentRouteName() == $link->route) {
                    return true;
                }
            }
            if ($link->url() == url()->current()) {
                return true;
            }
        });
    }


}
