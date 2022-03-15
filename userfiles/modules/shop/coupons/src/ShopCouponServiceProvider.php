<?php
namespace MicroweberPackages\Modules\Shop\Coupons;


use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ShopCouponServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->loadRoutesFrom(__DIR__ . '/routes/api.php');
    }
}
