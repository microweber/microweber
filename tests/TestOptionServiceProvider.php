<?php

namespace Tests;

use Illuminate\Support\ServiceProvider;
use Mockery;

class TestOptionServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(CouponService::class, function() {
            return new \Tests\TestCouponService();
        });
    }
}