<?php

namespace MicroweberPackages\Content;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use MicroweberPackages\Content\Http\Livewire\Admin\ContentFormBuilder;


class ContentFormBuilderServiceProvider extends ServiceProvider
{
    public function register()
    {


        Livewire::component('admin-content-form-builder', ContentFormBuilder::class);

    }

}
