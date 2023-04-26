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

        $this->menus['left_menu_top']->add(Link::to(admin_url('module/view?type=admin/modules'), 'Modules')->icon('<svg style="margin-right: 20px;" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 96 960 960" width="24"><path d="m390 976-68-120H190l-90-160 68-120-68-120 90-160h132l68-120h180l68 120h132l90 160-68 120 68 120-90 160H638l-68 120H390Zm248-440h86l44-80-44-80h-86l-45 80 45 80ZM438 656h84l45-80-45-80h-84l-45 80 45 80Zm0-240h84l46-81-45-79h-86l-45 79 46 81ZM237 536h85l45-80-45-80h-85l-45 80 45 80Zm0 240h85l45-80-45-80h-86l-44 80 45 80Zm200 120h86l45-79-46-81h-84l-46 81 45 79Zm201-120h85l45-80-45-80h-85l-45 80 45 80Z"/></svg>'));
        $this->menus['left_menu_top']->add(Link::to(admin_url('marketplace'), 'Marketplace')->icon('<svg style="margin-right: 20px;" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 96 960 960" width="24"><path d="M841 538v318q0 33-23.5 56.5T761 936H201q-33 0-56.5-23.5T121 856V538q-23-21-35.5-54t-.5-72l42-136q8-26 28.5-43t47.5-17h556q27 0 47 16.5t29 43.5l42 136q12 39-.5 71T841 538Zm-272-42q27 0 41-18.5t11-41.5l-22-140h-78v148q0 21 14 36.5t34 15.5Zm-180 0q23 0 37.5-15.5T441 444V296h-78l-22 140q-4 24 10.5 42t37.5 18Zm-178 0q18 0 31.5-13t16.5-33l22-154h-78l-40 134q-6 20 6.5 43t41.5 23Zm540 0q29 0 42-23t6-43l-42-134h-76l22 154q3 20 16.5 33t31.5 13ZM201 856h560V574q-5 2-6.5 2H751q-27 0-47.5-9T663 538q-18 18-41 28t-49 10q-27 0-50.5-10T481 538q-17 18-39.5 28T393 576q-29 0-52.5-10T299 538q-21 21-41.5 29.5T211 576h-4.5q-2.5 0-5.5-2v282Zm560 0H201h560Z"/></svg>'));
        $this->menus['left_menu_top']->add(Link::to(admin_url('settings'), 'Settings')->icon('<svg style="margin-right: 20px;" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 96 960 960" width="24"><path d="m370 976-16-128q-13-5-24.5-12T307 821l-119 50L78 681l103-78q-1-7-1-13.5v-27q0-6.5 1-13.5L78 471l110-190 119 50q11-8 23-15t24-12l16-128h220l16 128q13 5 24.5 12t22.5 15l119-50 110 190-103 78q1 7 1 13.5v27q0 6.5-2 13.5l103 78-110 190-118-50q-11 8-23 15t-24 12l-16 128H370Zm112-260q58 0 99-41t41-99q0-58-41-99t-99-41q-59 0-99.5 41T342 576q0 58 40.5 99t99.5 41Zm0-80q-25 0-42.5-17.5T422 576q0-25 17.5-42.5T482 516q25 0 42.5 17.5T542 576q0 25-17.5 42.5T482 636Zm-2-60Zm-40 320h79l14-106q31-8 57.5-23.5T639 729l99 41 39-68-86-65q5-14 7-29.5t2-31.5q0-16-2-31.5t-7-29.5l86-65-39-68-99 42q-22-23-48.5-38.5T533 362l-13-106h-79l-14 106q-31 8-57.5 23.5T321 423l-99-41-39 68 86 64q-5 15-7 30t-2 32q0 16 2 31t7 30l-86 65 39 68 99-42q22 23 48.5 38.5T427 790l13 106Z"/></svg>'));
        $this->menus['left_menu_top']->add(Link::to(admin_url('users'), 'Users')->icon('<svg style="margin-right: 20px;" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 96 960 960" width="24"><path d="M400 576q-66 0-113-47t-47-113q0-66 47-113t113-47q66 0 113 47t47 113q0 66-47 113t-113 47ZM80 896V784q0-33 17-62t47-44q51-26 115-44t141-18h14q6 0 12 2-8 18-13.5 37.5T404 696h-4q-71 0-127.5 18T180 750q-9 5-14.5 14t-5.5 20v32h252q6 21 16 41.5t22 38.5H80Zm560 40-12-60q-12-5-22.5-10.5T584 852l-58 18-40-68 46-40q-2-14-2-26t2-26l-46-40 40-68 58 18q11-8 21.5-13.5T628 596l12-60h80l12 60q12 5 22.5 11t21.5 15l58-20 40 70-46 40q2 12 2 25t-2 25l46 40-40 68-58-18q-11 8-21.5 13.5T732 876l-12 60h-80Zm40-120q33 0 56.5-23.5T760 736q0-33-23.5-56.5T680 656q-33 0-56.5 23.5T600 736q0 33 23.5 56.5T680 816ZM400 496q33 0 56.5-23.5T480 416q0-33-23.5-56.5T400 336q-33 0-56.5 23.5T320 416q0 33 23.5 56.5T400 496Zm0-80Zm12 400Z"/></svg>'));

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
