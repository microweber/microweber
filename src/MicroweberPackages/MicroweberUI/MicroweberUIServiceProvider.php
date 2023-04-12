<?php

namespace MicroweberPackages\MicroweberUI;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class MicroweberUIServiceProvider extends ServiceProvider
{

    public function boot()
    {
        View::prependNamespace('mw-ui', __DIR__.'/resources/views');

        Blade::componentNamespace('MicroweberPackages\\View\\Views\\Components', 'mw-ui');

    }
}
