<?php

declare(strict_types=1);

namespace MicroweberPackages\LivewireModal\Tests\Browser;

use Laravel\Dusk\Browser;
use MicroweberPackages\LivewireModal\Tests\Fixtures\DemoModal;
use MicroweberPackages\LivewireModal\Tests\Fixtures\NestedChildModal;
use MicroweberPackages\LivewireModal\Tests\Fixtures\NestedParentModal;
use PHPUnit\Framework\Attributes\Test;
use Tests\DuskTestCase;

/**
 * Browser tests for nested / multi-instance Livewire modals.
 *
 * Requires the CMS Dusk stack (Chrome + app server). Validates:
 * - Parent stays open when child opens
 * - Closing child leaves parent visible
 * - Multiple instances of the same modal get distinct DOM nodes
 */
class NestedModalDuskTest extends DuskTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        \Livewire\Livewire::component('demo-modal', DemoModal::class);
        \Livewire\Livewire::component('nested-parent-modal', NestedParentModal::class);
        \Livewire\Livewire::component('nested-child-modal', NestedChildModal::class);

        // Test page route for isolated modal playground
        \Illuminate\Support\Facades\Route::get('/__mw_livewire_modal_test', function () {
            return view('livewire-modal::tests.playground');
        })->middleware('web')->name('mw.livewire-modal.test');
    }

    #[Test]
    public function nested_modal_keeps_parent_open(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/__mw_livewire_modal_test')
                ->waitFor('@open-parent', 10)
                ->click('@open-parent')
                ->waitFor('[data-testid="nested-parent-modal"]', 10)
                ->assertVisible('[data-testid="nested-parent-modal"]')
                ->click('@open-child-modal')
                ->waitFor('[data-testid="nested-child-modal"]', 10)
                ->assertVisible('[data-testid="nested-child-modal"]')
                // Parent must still be in the DOM and active
                ->assertPresent('[data-testid="nested-parent-modal"]')
                ->assertScript(
                    'return document.querySelectorAll(".js-modal-livewire.active").length >= 2'
                )
                ->click('@child-close')
                ->waitUntilMissing('[data-testid="nested-child-modal"]', 10)
                ->assertVisible('[data-testid="nested-parent-modal"]');
        });
    }

    #[Test]
    public function multiple_instances_get_unique_ids(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/__mw_livewire_modal_test')
                ->waitFor('@open-demo', 10)
                ->click('@open-demo')
                ->waitFor('[data-testid="demo-modal"]', 10)
                ->click('@open-demo-again')
                ->pause(500)
                ->assertScript(
                    'return document.querySelectorAll(".js-modal-livewire[data-mw-modal-instance]").length >= 2'
                )
                ->assertScript(
                    'return (function () {'
                    . ' var n = document.querySelectorAll(".js-modal-livewire[data-mw-modal-instance]");'
                    . ' return n.length >= 2'
                    . '   && n[0].getAttribute("data-mw-modal-instance") !== n[1].getAttribute("data-mw-modal-instance");'
                    . ' })();'
                );
        });
    }

    #[Test]
    public function close_button_is_present_on_default_skin(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/__mw_livewire_modal_test')
                ->waitFor('@open-demo', 10)
                ->click('@open-demo')
                ->waitFor('.mw-modal-close-x', 10)
                ->assertVisible('.mw-modal-close-x')
                ->click('.mw-modal-close-x')
                ->waitUntilMissing('[data-testid="demo-modal"]', 10);
        });
    }
}
