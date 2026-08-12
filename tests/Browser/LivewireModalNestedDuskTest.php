<?php

declare(strict_types=1);

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Livewire\Livewire;
use MicroweberPackages\LivewireModal\Tests\Fixtures\DemoModal;
use MicroweberPackages\LivewireModal\Tests\Fixtures\NestedChildModal;
use MicroweberPackages\LivewireModal\Tests\Fixtures\NestedParentModal;
use PHPUnit\Framework\Attributes\Test;
use Tests\DuskTestCase;

/**
 * CMS-level Dusk coverage for nested Livewire modals from the
 * microweber-livewire-modal package.
 */
class LivewireModalNestedDuskTest extends DuskTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Livewire::component('demo-modal', DemoModal::class);
        Livewire::component('nested-parent-modal', NestedParentModal::class);
        Livewire::component('nested-child-modal', NestedChildModal::class);

        view()->addNamespace(
            'livewire-modal-tests',
            base_path('packages/microweber-livewire-modal/tests/views')
        );

        \Illuminate\Support\Facades\Route::get('/__mw_livewire_modal_test', function () {
            return view('livewire-modal::tests.playground');
        })->middleware('web');
    }

    #[Test]
    public function nested_modal_does_not_close_parent(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/__mw_livewire_modal_test')
                ->waitFor('@open-parent', 15)
                ->click('@open-parent')
                ->waitFor('[data-testid="nested-parent-modal"]', 15)
                ->assertVisible('[data-testid="nested-parent-modal"]')
                ->waitFor('@open-child-modal', 10)
                ->click('@open-child-modal')
                ->waitFor('[data-testid="nested-child-modal"]', 15)
                ->assertVisible('[data-testid="nested-child-modal"]')
                ->assertPresent('[data-testid="nested-parent-modal"]')
                ->assertScript(
                    'return document.querySelectorAll(".js-modal-livewire.active").length >= 2;'
                )
                ->click('@child-close')
                ->waitUntilMissing('[data-testid="nested-child-modal"]', 15)
                ->assertVisible('[data-testid="nested-parent-modal"]');
        });
    }

    #[Test]
    public function multi_instance_modals_have_distinct_instance_ids(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/__mw_livewire_modal_test')
                ->waitFor('@open-demo', 15)
                ->click('@open-demo')
                ->waitFor('[data-testid="demo-modal"]', 15)
                ->click('@open-demo-again')
                ->pause(800)
                ->assertScript(
                    'return (function () {'
                    . ' var n = document.querySelectorAll(".js-modal-livewire[data-mw-modal-instance]");'
                    . ' return n.length >= 2'
                    . '   && n[0].getAttribute("data-mw-modal-instance") !== n[1].getAttribute("data-mw-modal-instance");'
                    . ' })();'
                );
        });
    }
}
