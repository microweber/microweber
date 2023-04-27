<?php

namespace MicroweberPackages\Admin\MenuBuilder;

use Illuminate\Support\Facades\Route;
use MicroweberPackages\Admin\MenuBuilder\Traits\HasIcon;
use MicroweberPackages\Admin\MenuBuilder\Traits\HasId;
use MicroweberPackages\Admin\MenuBuilder\Traits\HasOrder;
use Spatie\Menu\Html\Attributes;
use Spatie\Menu\Item;

class Menu extends \Spatie\Menu\Menu
{
    use HasIcon;
    use HasOrder;

    public ?string $id = null;

    public string | Item $prepend = '';


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

    public function reordered()
    {
        $items = $this->items();

//        $menu = new Menu();
//        foreach ($items as $item) {
//            if (method_exists($item, 'items')) {
//
//            }
//        }

        usort($items, function($item,$secondItem){
            return $item->order > $secondItem->order;
        });

        $this->items = $items;

        return $this;
    }

    public function modify($id)
    {
        $items = $this->items();

        foreach ($items as $item) {
            if (method_exists($item, 'items')) {
                foreach ($item->items() as $subItem) {
                //  dump($item->id());
                }
            }
        }

        $newSubmenu = Menu::new();
        $newSubmenu->id = $id;
        $this->submenu(
            Link::to('', $id),
            $newSubmenu
        );

        return $newSubmenu;
    }

    public function items()
    {
        return $this->items;
    }

}
