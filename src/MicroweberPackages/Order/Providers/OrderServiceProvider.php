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

namespace MicroweberPackages\Order\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use MicroweberPackages\Order\Http\Controllers\OrdersController;
use MicroweberPackages\Order\Http\Livewire\Admin\Modals\OrdersBulkDelete;
use MicroweberPackages\Order\Http\Livewire\Admin\Modals\OrdersBulkPaymentStatus;
use MicroweberPackages\Order\Http\Livewire\Admin\Modals\OrdersBulkOrderStatus;
use MicroweberPackages\Order\Http\Livewire\Admin\OrdersCustomersAutoComplete;
use MicroweberPackages\Order\Http\Livewire\Admin\OrdersFiltersComponent;
use MicroweberPackages\Order\Http\Livewire\Admin\FilterItemOrdersShippingAddress;
use MicroweberPackages\Order\Http\Livewire\Admin\OrdersShippingCityAutoComplete;
use MicroweberPackages\Order\Http\Livewire\Admin\OrdersShippingCountryAutoComplete;
use MicroweberPackages\Order\Http\Livewire\Admin\OrdersShippingStateAutoComplete;
use MicroweberPackages\Order\Http\Livewire\Admin\OrdersTableComponent;
use MicroweberPackages\Order\Http\Livewire\Admin\FilterItemOrderCustomer;
use MicroweberPackages\Order\OrderManager;
use MicroweberPackages\Order\Repositories\OrderRepository;

class OrderServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //Livewire::component('content-bulk-options', ContentBulkOptions::class);
        Livewire::component('admin-orders-filters', OrdersFiltersComponent::class);
        Livewire::component('admin-orders-table', OrdersTableComponent::class);
        Livewire::component('admin-orders-shipping-country-autocomplete', OrdersShippingCountryAutoComplete::class);
        Livewire::component('admin-orders-shipping-state-autocomplete', OrdersShippingStateAutoComplete::class);
        Livewire::component('admin-orders-shipping-city-autocomplete', OrdersShippingCityAutoComplete::class);
        Livewire::component('admin-orders-shipping-address-autocomplete', FilterItemOrdersShippingAddress::class);
        Livewire::component('admin-orders-customers-autocomplete', OrdersCustomersAutoComplete::class);
        Livewire::component('admin-orders-bulk-delete', OrdersBulkDelete::class);
        Livewire::component('admin-orders-bulk-order-status', OrdersBulkOrderStatus::class);
        Livewire::component('admin-orders-bulk-payment-status', OrdersBulkPaymentStatus::class);

        Livewire::component('admin-orders-filter-item-customer', FilterItemOrderCustomer::class);

        /**
         * @property \MicroweberPackages\Order\OrderManager    $order_manager
         */
        $this->app->singleton('order_manager', function ($app) {
            return new OrderManager();
        });

        /**
         * @property \MicroweberPackages\Order\Repositories\OrderRepository    $order_repository
         */
        $this->app->singleton('order_repository', function ($app) {
            return new OrderRepository();
        });



        View::addNamespace('order', dirname(__DIR__) . '/resources/views');

        $this->loadRoutesFrom(dirname(__DIR__) . '/routes/web.php');
        $this->loadRoutesFrom(dirname(__DIR__) . '/routes/api.php');


    }
}
