<?php

namespace MicroweberPackages\Install;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class InstallServiceProvider extends ServiceProvider
{
    public function boot()
    {

        View::addNamespace('install', __DIR__ . '/resources/views');
    }
}
