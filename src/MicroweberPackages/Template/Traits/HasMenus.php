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


        $this->menus['left_menu_top']->add(Link::to(admin_url(), 'Dashboard')->icon('<svg style="margin-right: 20px;" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 96 960 960" width="24"><path d="M520 456V216h320v240H520ZM120 616V216h320v400H120Zm400 320V536h320v400H520Zm-400 0V696h320v240H120Zm80-400h160V296H200v240Zm400 320h160V616H600v240Zm0-480h160v-80H600v80ZM200 856h160v-80H200v80Zm160-320Zm240-160Zm0 240ZM360 776Z"/></svg>'));
        $this->menus['left_menu_top']->submenu(
            Link::to(admin_url(), 'Website')
                ->icon('<svg style="margin-right: 20px;" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 96 960 960" width="24"><path d="M480 976q-83 0-156-31.5T197 859q-54-54-85.5-127T80 576q0-83 31.5-156T197 293q54-54 127-85.5T480 176q83 0 156 31.5T763 293q54 54 85.5 127T880 576q0 83-31.5 156T763 859q-54 54-127 85.5T480 976Zm-40-82v-78q-33 0-56.5-23.5T360 736v-40L168 504q-3 18-5.5 36t-2.5 36q0 121 79.5 212T440 894Zm276-102q20-22 36-47.5t26.5-53q10.5-27.5 16-56.5t5.5-59q0-98-54.5-179T600 280v16q0 33-23.5 56.5T520 376h-80v80q0 17-11.5 28.5T400 496h-80v80h240q17 0 28.5 11.5T600 616v120h40q26 0 47 15.5t29 40.5Z"/></svg>')
            ,
            Menu::new()
            ->add(Link::to(admin_url('page'), 'Pages'))
            ->add(Link::to(admin_url('category'), 'Category'))
            ->add(Link::to(admin_url('post'), 'Post'))
        );


        $this->menus['left_menu_top']->submenu(
            Link::to(admin_url(), 'Shop')
                ->icon('<svg style="margin-right: 20px;" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 96 960 960" width="24"><path d="M240 976q-33 0-56.5-23.5T160 896V416q0-33 23.5-56.5T240 336h80q0-66 47-113t113-47q66 0 113 47t47 113h80q33 0 56.5 23.5T800 416v480q0 33-23.5 56.5T720 976H240Zm0-80h480V416h-80v80q0 17-11.5 28.5T600 536q-17 0-28.5-11.5T560 496v-80H400v80q0 17-11.5 28.5T360 536q-17 0-28.5-11.5T320 496v-80h-80v480Zm160-560h160q0-33-23.5-56.5T480 256q-33 0-56.5 23.5T400 336ZM240 896V416v480Z"/></svg>')
            ,
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

        $this->menus['left_menu_top']->setActive(function (Link $link) {
            if ($link->url() == url()->current()) {
                return true;
            }
        });

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
