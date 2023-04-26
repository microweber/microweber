<?php

namespace MicroweberPackages\Template\Traits;

use Spatie\Menu\Link;
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

        $this->menus['footer_links']->add(Link::to('/', 'Home'));


        $this->menus['footer_links']->add(Link::to(admin_url(), 'Dashboard'));
        $this->menus['footer_links']->add(
            Menu::new()
            ->add(Link::to(admin_url(), 'Website'))
            ->add(Link::to(admin_url('page'), 'Pages'))
            ->add(Link::to(admin_url('category'), 'Category'))
            ->add(Link::to(admin_url('post'), 'Post'))
        );

    }

    public function addMenuItem($menu, $item)
    {
        $this->menus[$menu]->add($item);
    }

    public function renderMenu($menu)
    {
        return $this->menus[$menu]->render();
    }
}
