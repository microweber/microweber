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

namespace MicroweberPackages\Helper;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use MicroweberPackages\Security\HtmlClean;
class HelpersServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        // `format` and `xss_security` are fully owned by their standalone
        // packages now (MicroweberPackages\Format\FormatServiceProvider and
        // MicroweberPackages\Security\SecurityServiceProvider). The CMS-only
        // Format methods (render_item_custom_fields_data, text_to_image) were
        // baked into the package class, so no app-side CMS subclass override
        // is needed here anymore.

        $this->app->bind('html_clean', function () {
            return new HtmlClean();
        });


        if (is_cli()) {
            if (app()->runningUnitTests()) {
                Route::get('uri_test_details', function () {
                    return app()->url_manager->current();
                })->name('uri_test_details');
            }

        }
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register(): void
    {
        // url_manager is now registered by MicroweberPackages\Url\Providers\UrlServiceProvider.
        // If it hasn't been registered yet (standalone use without the Url package provider),
        // register the legacy UrlManager as fallback.
        if (!$this->app->bound('url_manager')) {
            $this->app->singleton('url_manager', function () {
                return new UrlManager();
            });
        }
    }
}
