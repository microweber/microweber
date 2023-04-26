<?php

namespace MicroweberPackages\Template\Traits;

use Spatie\Menu\Menu;

trait HasMenus
{
    public $menus;

    public function initMenus()
    {
        $this->menus['left_side_bar_top'] = Menu::new();
        $this->menus['left_side_bar_bottom'] = Menu::new();
        $this->menus['top_menu_left'] = Menu::new();
        $this->menus['top_menu_right'] = Menu::new();
        $this->menus['footer_links'] = Menu::new();
    }

    public function addMenuItem($menu, $item)
    {
        $this->menus[$menu]->add($item); 
    }

    public function render($menu)
    {
        return $this->menus[$menu]->render();
    }
}
