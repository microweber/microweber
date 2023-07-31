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
use MicroweberPackages\Shop\Http\Livewire\Admin\DashboardSalesComponent;

class ShopServiceProvider extends ServiceProvider
{

    public function registerMenu()
    {
        $shop_disabled = get_option('shop_disabled', 'website') == 'y';

        if ($shop_disabled) {
            return;
        }

        if (user_can_view_module(['module' => 'shop.products'])) {
            AdminManager::getMenuInstance('left_menu_top')->addChild('Shop', [
                'attributes' => [
                    'route' => 'admin.product.index',
                    'icon' => ' <svg fill="currentColor"class="me-3" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 96 960 960" width="24"><path d="M240 976q-33 0-56.5-23.5T160 896V416q0-33 23.5-56.5T240 336h80q0-66 47-113t113-47q66 0 113 47t47 113h80q33 0 56.5 23.5T800 416v480q0 33-23.5 56.5T720 976H240Zm0-80h480V416h-80v80q0 17-11.5 28.5T600 536q-17 0-28.5-11.5T560 496v-80H400v80q0 17-11.5 28.5T360 536q-17 0-28.5-11.5T320 496v-80h-80v480Zm160-560h160q0-33-23.5-56.5T480 256q-33 0-56.5 23.5T400 336ZM240 896V416v480Z"/></svg>'
                ]
            ]);

            AdminManager::getMenuInstance('left_menu_top')
                ->menuItems
                ->getChild('Shop')
                ->setExtra('orderNumber', 3);

            AdminManager::getMenuInstance('left_menu_top')->getChild('Shop')
                ->addChild('Products', [
                    'attributes' => ['route' => 'admin.product.index']
                ]);

            AdminManager::getMenuInstance('left_menu_top')
                ->menuItems
                ->getChild('Shop')
                ->getChild('Products')
                ->setExtra('routes', [
                    'admin.product.index',
                    'admin.product.create',
                    'admin.product.edit',
                    'admin.product.show',
                ]);

            AdminManager::getMenuInstance('left_menu_top')
                ->getChild('Shop')->getChild('Products')
                ->addChild('Add Product', [
                    'attributes' => ['route' => 'admin.product.create']
                ]);

            AdminManager::getMenuInstance('left_menu_top')
                ->getChild('Shop')->getChild('Products')
                ->addChild('List Products', [
                    'attributes' => ['route' => 'admin.product.index']
                ]);

            if (user_can_view_module(['module' => 'order.index'])) {
                AdminManager::getMenuInstance('left_menu_top')->getChild('Shop')
                    ->addChild('Orders', [
                        'attributes' => ['route' => 'admin.order.index']
                    ]);
                AdminManager::getMenuInstance('left_menu_top')
                    ->menuItems
                    ->getChild('Shop')
                    ->getChild('Orders')
                    ->setExtra('routes', [
                        'admin.order.index',
                        'admin.order.create',
                        'admin.order.edit',
                        'admin.order.show',
                    ]);

                AdminManager::getMenuInstance('left_menu_top')
                    ->getChild('Shop')->getChild('Orders')
                    ->addChild('Add Order', [
                        'attributes' => ['route' => 'admin.order.create']
                    ]);

                AdminManager::getMenuInstance('left_menu_top')
                    ->getChild('Shop')->getChild('Orders')
                    ->addChild('List Orders', [
                        'attributes' => ['route' => 'admin.order.index']
                    ]);
            }
            if (user_can_view_module(['module' => 'shop.category'])) {


                AdminManager::getMenuInstance('left_menu_top')->getChild('Shop')
                    ->addChild('Categories', [
                        'attributes' => ['route' => 'admin.shop.category.index']
                    ]);
                AdminManager::getMenuInstance('left_menu_top')
                    ->menuItems
                    ->getChild('Shop')
                    ->getChild('Categories')
                    ->setExtra('routes', [
                        'admin.shop.category.index',
                        'admin.shop.category.create',
                        'admin.shop.category.edit',
                        'admin.shop.category.show',
                    ]);


                AdminManager::getMenuInstance('left_menu_top')
                    ->getChild('Shop')->getChild('Categories')
                    ->addChild('Add Category', [
                        'attributes' => ['route' => 'admin.shop.category.create']
                    ]);

                AdminManager::getMenuInstance('left_menu_top')
                    ->getChild('Shop')->getChild('Categories')
                    ->addChild('List Categories', [
                        'attributes' => ['route' => 'admin.shop.category.index']
                    ]);
            }
            if (user_can_view_module(['module' => 'shop.customers'])) {
                AdminManager::getMenuInstance('left_menu_top')->getChild('Shop')
                    ->addChild('Customers', [
                        'attributes' => ['route' => 'admin.customers.index']
                    ]);
                AdminManager::getMenuInstance('left_menu_top')
                    ->menuItems
                    ->getChild('Shop')
                    ->getChild('Customers')
                    ->setExtra('routes', [
                        'admin.customers.index',
                        'admin.customers.create',
                        'admin.customers.edit',
                        'admin.customers.show',
                    ]);

                AdminManager::getMenuInstance('left_menu_top')
                    ->getChild('Shop')->getChild('Customers')
                    ->addChild('Add Customer', [
                        'attributes' => ['route' => 'admin.customers.create']
                    ]);

                AdminManager::getMenuInstance('left_menu_top')
                    ->getChild('Shop')->getChild('Customers')
                    ->addChild('List Customers', [
                        'attributes' => ['route' => 'admin.customers.index']
                    ]);
            }
        }


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

        if (get_option('shop_disabled', 'website') !== 'y') {
            Event::listen(ServingAdmin::class, [$this, 'registerMenu']);
        }

    }
}
