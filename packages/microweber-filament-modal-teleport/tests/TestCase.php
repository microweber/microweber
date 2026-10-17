<?php

declare(strict_types=1);

namespace MicroweberPackages\FilamentModalTeleport\Tests;

/**
 * Base test case for the modal-teleport package.
 *
 * Dual-mode: Orchestra Testbench (with the Filament + Livewire stack) for
 * standalone package testing, or the CMS harness (Tests\TestCase) when running
 * inside the Microweber monorepo.
 */
if (class_exists(\Orchestra\Testbench\TestCase::class) && ! trait_exists(\Tests\CreatesApplication::class)) {
    abstract class TestCase extends \Orchestra\Testbench\TestCase
    {
        protected function getPackageProviders($app): array
        {
            return [
                \Livewire\LivewireServiceProvider::class,
                \Filament\Support\SupportServiceProvider::class,
                \Filament\Actions\ActionsServiceProvider::class,
                \Filament\Forms\FormsServiceProvider::class,
                \Filament\Infolists\InfolistsServiceProvider::class,
                \Filament\Notifications\NotificationsServiceProvider::class,
                \Filament\Tables\TablesServiceProvider::class,
                \Filament\Widgets\WidgetsServiceProvider::class,
                \Filament\FilamentServiceProvider::class,
                \BladeUI\Icons\BladeIconsServiceProvider::class,
                \BladeUI\Heroicons\BladeHeroiconsServiceProvider::class,
                \RyanChandler\BladeCaptureDirective\BladeCaptureDirectiveServiceProvider::class,
                \MicroweberPackages\FilamentModalTeleport\ModalTeleportServiceProvider::class,
            ];
        }

        protected function getEnvironmentSetUp($app): void
        {
            $app['config']->set('app.key', 'base64:' . base64_encode(random_bytes(32)));
        }
    }
} else {
    abstract class TestCase extends \Tests\TestCase
    {
        protected function setUp(): void
        {
            parent::setUp();

            // Register the service provider so views resolve
            $this->app->register(\MicroweberPackages\FilamentModalTeleport\ModalTeleportServiceProvider::class);
        }
    }
}
