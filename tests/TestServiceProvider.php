<?php

namespace Tests;

use Illuminate\Support\ServiceProvider;

class TestServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('white_label', function() {
            return new class {
                public function isEnabled() { return false; }
            };
        });
    }
}