<?php
/*
 * This file is part of the Microweber framework.
 *
 * (c) Microweber CMS LTD
 *
 * For full license information see
 * https://github.com/microweber/microweber/blob/master/LICENSE
 *
 */

namespace MicroweberPackages\Shop;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use MicroweberPackages\Admin\Events\ServingAdmin;
use MicroweberPackages\Admin\Facades\AdminManager;
use MicroweberPackages\Admin\Http\Middleware\Admin;
use MicroweberPackages\Admin\MenuBuilder\Link;
use MicroweberPackages\Admin\MenuBuilder\Menu;
use MicroweberPackages\Shop\Http\Livewire\DashboardSalesComponent;

class ShopServiceProvider extends ServiceProvider
{

    public function registerMenu()
    {
        AdminManager::getMenu('left_menu_top')->submenu(
            Link::to(admin_url(), 'Shop')
                ->order(2)
                ->icon('<svg style="margin-right: 20px;" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 96 960 960" width="24"><path d="M240 976q-33 0-56.5-23.5T160 896V416q0-33 23.5-56.5T240 336h80q0-66 47-113t113-47q66 0 113 47t47 113h80q33 0 56.5 23.5T800 416v480q0 33-23.5 56.5T720 976H240Zm0-80h480V416h-80v80q0 17-11.5 28.5T600 536q-17 0-28.5-11.5T560 496v-80H400v80q0 17-11.5 28.5T360 536q-17 0-28.5-11.5T320 496v-80h-80v480Zm160-560h160q0-33-23.5-56.5T480 256q-33 0-56.5 23.5T400 336ZM240 896V416v480Z"/></svg>')
            ,
            Menu::new()
                ->add(Link::to(admin_url('shop/product'), 'Products'))
                ->add(Link::to(admin_url('order'), 'Orders'))
                ->add(Link::to(admin_url('shop/category'), 'Categories'))
                ->add(Link::to(admin_url('customers'), 'Customers'))
        );
    }


    /**
     * Bootstrap the application services.
     *
     * @return void
     */


    public function register()
    {
        // Allow to overwrite resource files for this module
        $checkForOverwrite = template_dir() . 'modules/shop/src/resources/views';
        $checkForOverwrite = normalize_path($checkForOverwrite);
        $this->loadRoutesFrom((__DIR__) . '/routes/web.php');
        if (is_dir($checkForOverwrite)) {
            View::addNamespace('shop', $checkForOverwrite);
        }

        View::addNamespace('shop', normalize_path((__DIR__) . '/resources/views'));

        Livewire::component('admin-shop-dashboard-sales', DashboardSalesComponent::class);


        Event::listen(ServingAdmin::class, [$this, 'registerMenu']);
    }
}
