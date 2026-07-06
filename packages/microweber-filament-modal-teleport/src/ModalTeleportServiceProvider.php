<?php

declare(strict_types=1);

namespace MicroweberPackages\FilamentModalTeleport;

use Illuminate\Support\ServiceProvider;

class ModalTeleportServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'mw-modal-teleport');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../resources/css' => public_path('vendor/mw-modal-teleport/css'),
            ], 'mw-modal-teleport-assets');
        }
    }
}
