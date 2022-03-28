<?php

namespace MicroweberPackages\Install;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class InstallServiceProvider extends ServiceProvider
{
    public function register()
    {

        View::addNamespace('install', __DIR__ . '/resources/views');
    }
}
