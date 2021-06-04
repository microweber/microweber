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

namespace MicroweberPackages\Shop;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ShopServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadRoutesFrom((__DIR__) . '/routes/web.php');

        // Allow to overwrite resource files for this module
        $checkForOverwrite = template_dir() . 'modules/shop/src/resources/views';
        $checkForOverwrite = normalize_path($checkForOverwrite);

        if (is_dir($checkForOverwrite)) {
            View::addNamespace('shop', $checkForOverwrite);
        }

        $mainFolder = modules_path() . 'shop/src/resources/views';
        $mainFolder = normalize_path($mainFolder);

        View::addNamespace('shop', $mainFolder);

    }
}
