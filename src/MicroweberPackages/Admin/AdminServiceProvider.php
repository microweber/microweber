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

namespace MicroweberPackages\Admin;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use MicroweberPackages\Admin\Http\Livewire\AutoCompleteMultipleItemsComponent;
use MicroweberPackages\Admin\Http\Livewire\ProductsAutoComplete;
use MicroweberPackages\Admin\Http\Livewire\TagsAutoComplete;
use MicroweberPackages\Admin\Http\Livewire\UsersAutoComplete;
use MicroweberPackages\Livewire\Http\Livewire\Admin\AutoCompleteComponent;

class AdminServiceProvider extends ServiceProvider
{
    public function register()
    {
        View::addNamespace('admin', __DIR__.'/resources/views');
        $this->loadRoutesFrom(__DIR__ . '/routes/admin.php');
    }

    public function boot()
    {
       // Livewire::component('admin-auto-complete', AutoCompleteComponent::class);
       //  Livewire::component('admin-auto-complete-multiple-items', AutoCompleteMultipleItemsComponent::class);
        Livewire::component('admin-users-autocomplete', UsersAutoComplete::class);
        Livewire::component('admin-tags-autocomplete', TagsAutoComplete::class);
    }
}
