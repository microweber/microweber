<?php

namespace MicroweberPackages\Template\Traits;

use MicroweberPackages\Admin\MenuBuilder\Link;
use MicroweberPackages\Admin\MenuBuilder\Menu;

trait HasMenus
{
    public $menus;

    public function initMenus()
    {
        $this->menus['left_menu_top'] = Menu::new();
        $this->menus['left_menu_bottom'] = Menu::new();
        $this->menus['top_menu_left'] = Menu::new();
        $this->menus['top_menu_right'] = Menu::new();
        $this->menus['footer_links'] = Menu::new();


        $this->menus['left_menu_top']->add(Link::to(admin_url(), 'Dashboard'));
        $this->menus['left_menu_top']->submenu(
            Link::to(admin_url(), 'Website'),
            Menu::new()
            ->add(Link::to(admin_url('page'), 'Pages'))
            ->add(Link::to(admin_url('category'), 'Category'))
            ->add(Link::to(admin_url('post'), 'Post'))
        );


        $this->menus['left_menu_top']->submenu(
            Link::to(admin_url(), 'Shop'),
            Menu::new()
                ->add(Link::to(admin_url('shop/product'), 'Products'))
                ->add(Link::to(admin_url('order'), 'Orders'))
                ->add(Link::to(admin_url('shop/category'), 'Categories'))
                ->add(Link::to(admin_url('customers'), 'Customers'))
        );

        $this->menus['left_menu_top']->add(Link::to(admin_url('module/view?type=admin/modules'), 'Modules'));
        $this->menus['left_menu_top']->add(Link::to(admin_url('marketplace'), 'Marketplace'));
        $this->menus['left_menu_top']->add(Link::to(admin_url('settings'), 'Settings'));
        $this->menus['left_menu_top']->add(Link::to(admin_url('users'), 'Users'));

    }

    public function addMenuItem($menu, $item)
    {
        $this->menus[$menu]->add($item);
    }

    public function getMenu($menu)
    {
        return $this->menus[$menu];
    }
}
