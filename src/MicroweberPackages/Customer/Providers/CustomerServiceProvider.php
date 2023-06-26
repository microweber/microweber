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

namespace MicroweberPackages\Customer\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use MicroweberPackages\Content\Http\Livewire\Admin\ContentList;
use MicroweberPackages\Customer\Http\Livewire\CustomersListComponent;

class CustomerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        View::addNamespace('customer', dirname(__DIR__) . '/resources/views');

        $this->loadRoutesFrom(dirname(__DIR__) . '/routes/admin.php');
        $this->loadMigrationsFrom(dirname(__DIR__) . '/database/');
    }

    public function register()
    {
        Livewire::component('admin-customers-list', CustomersListComponent::class);
    }
}
