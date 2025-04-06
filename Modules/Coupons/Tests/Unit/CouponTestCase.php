<?php

namespace Modules\Coupons\Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Coupons\Providers\CouponsServiceProvider;
use Tests\TestCase;

class CouponTestCase extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->app->register(CouponsServiceProvider::class);
    }

    protected function getPackageProviders($app)
    {
        return [CouponsServiceProvider::class];
    }
}