<?php

declare(strict_types=1);

namespace MicroweberPackages\LivewireModal;

use Illuminate\Support\Facades\Route;
use Livewire\Livewire;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

/**
 * Registers the Livewire modal stack for standalone Laravel apps and the CMS.
 *
 * Component aliases (for drop-in compatibility):
 * - microweber-livewire-modal (canonical)
 * - livewire-ui-modal (legacy wire-elements / Microweber)
 * - wire-elements-modal (legacy)
 */
class LivewireModalServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('livewire-modal')
            ->hasConfigFile('livewire-modal')
            ->hasViews('livewire-modal');
    }

    public function packageBooted(): void
    {
        Livewire::component('microweber-livewire-modal', Modal::class);
        Livewire::component('livewire-ui-modal', Modal::class);
        Livewire::component('wire-elements-modal', Modal::class);

        $this->registerModalTestPlayground();
    }

    /**
     * In local/testing only, expose the nested-modal Dusk playground
     * (/__mw_livewire_modal_test) and register its fixture components on the
     * SERVER. Dusk's `php artisan serve` process handles the HTTP request, so a
     * route registered inside a test's setUp() (a different PHP process) never
     * reaches it — the browser gets a 404. Registering here, guarded by env +
     * class_exists (the fixtures live in the package's autoload-dev tree), keeps
     * production untouched while making the modal Dusk tests self-sufficient.
     */
    private function registerModalTestPlayground(): void
    {
        if (!app()->environment(['local', 'testing'])) {
            return;
        }

        $fixtures = [
            'demo-modal' => Tests\Fixtures\DemoModal::class,
            'nested-parent-modal' => Tests\Fixtures\NestedParentModal::class,
            'nested-child-modal' => Tests\Fixtures\NestedChildModal::class,
        ];
        foreach ($fixtures as $alias => $class) {
            if (class_exists($class)) {
                Livewire::component($alias, $class);
            }
        }

        // Only publish the route when the playground view + fixtures are present.
        if (!class_exists(Tests\Fixtures\NestedParentModal::class)) {
            return;
        }

        // The fixture components render `livewire-modal-tests::…` views, so the
        // server must resolve that namespace too (dirname(__DIR__) = package root).
        view()->addNamespace(
            'livewire-modal-tests',
            dirname(__DIR__) . '/tests/views'
        );

        Route::get('/__mw_livewire_modal_test', function () {
            return view('livewire-modal::tests.playground');
        })->middleware('web')->name('livewire-modal.test-playground');
    }
}
