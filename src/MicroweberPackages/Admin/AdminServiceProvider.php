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
        Livewire::component('admin-auto-complete', AutoCompleteComponent::class);
    }
}
