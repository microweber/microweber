<?php

namespace MicroweberPackages\Marketplace;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use MicroweberPackages\Admin\Events\ServingAdmin;
use MicroweberPackages\Admin\Facades\AdminManager;
use MicroweberPackages\Admin\MenuBuilder\Link;
use MicroweberPackages\Marketplace\Http\Livewire\Admin\Marketplace;
use MicroweberPackages\Marketplace\Http\Livewire\Admin\MarketplaceItemModal;

class MarketplaceServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Livewire::component('admin-marketplace', Marketplace::class);
        Livewire::component('admin-marketplace-item-modal', MarketplaceItemModal::class);

        View::addNamespace('marketplace', __DIR__ . '/resources/views');

    }

    public function register()
    {
        //  $this->loadRoutesFrom(__DIR__ . '/routes/api.php');
        $this->loadRoutesFrom(__DIR__ . '/routes/web.php');

        Event::listen(ServingAdmin::class, [$this, 'registerMenu']);
    }


    public function registerMenu()
    {
        AdminManager::getMenuInstance('left_menu_top')->addChild('Marketplace', [
            'uri' => route('admin.marketplace.index'),
            'attributes'=>[
                'icon'=>' <svg fill="currentColor"class="me-3" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 96 960 960" width="24"><path d="M841 538v318q0 33-23.5 56.5T761 936H201q-33 0-56.5-23.5T121 856V538q-23-21-35.5-54t-.5-72l42-136q8-26 28.5-43t47.5-17h556q27 0 47 16.5t29 43.5l42 136q12 39-.5 71T841 538Zm-272-42q27 0 41-18.5t11-41.5l-22-140h-78v148q0 21 14 36.5t34 15.5Zm-180 0q23 0 37.5-15.5T441 444V296h-78l-22 140q-4 24 10.5 42t37.5 18Zm-178 0q18 0 31.5-13t16.5-33l22-154h-78l-40 134q-6 20 6.5 43t41.5 23Zm540 0q29 0 42-23t6-43l-42-134h-76l22 154q3 20 16.5 33t31.5 13ZM201 856h560V574q-5 2-6.5 2H751q-27 0-47.5-9T663 538q-18 18-41 28t-49 10q-27 0-50.5-10T481 538q-17 18-39.5 28T393 576q-29 0-52.5-10T299 538q-21 21-41.5 29.5T211 576h-4.5q-2.5 0-5.5-2v282Zm560 0H201h560Z"/></svg>'
            ]
        ]);

        AdminManager::getMenuInstance('left_menu_top')
            ->menuItems
            ->getChild('Marketplace')
            ->setExtra('orderNumber', 3);

    }
}
