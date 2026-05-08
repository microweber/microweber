<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Cycle-75 / AI-60 / TICKET-DD — checkout wizard hidden-step focus
 * leak regression coverage.
 *
 * Pins:
 *   - The checkout wizard wrapper carries an Alpine x-data that
 *     wires up the inert shim.
 *   - The shim attaches a MutationObserver that toggles the HTML5
 *     `inert` attribute on each .fi-sc-wizard-step based on
 *     whether .fi-active is present.
 *   - The shim also syncs aria-hidden so AT users don't hear the
 *     inactive step's labels.
 *   - Active steps clear inert + aria-hidden (no false positives
 *     after step transitions).
 *
 * Style after the cycle-52..74 contract tests (file-system reads only,
 * no DB touch). Per project memory `feedback_testing`: contract tests
 * never mount Filament resources or hit MySQL.
 */
class CheckoutWizardInertContractTest extends TestCase
{
    private string $wizardSrc;

    protected function setUp(): void
    {
        parent::setUp();
        $this->wizardSrc = file_get_contents(base_path(
            'Modules/Checkout/resources/views/livewire/checkout-wizard/index.blade.php'
        ));
    }

    #[Test]
    public function wrapper_carries_alpine_data_and_init(): void
    {
        // The shim must be wired up at the WRAPPER element so the
        // observer gets the entire form sub-tree, not just one step.
        $this->assertStringContainsString(
            'x-data="checkoutWizardInertShim()"',
            $this->wizardSrc,
            'wizard wrapper must carry x-data="checkoutWizardInertShim()"'
        );
        $this->assertStringContainsString(
            'x-init="init($el)"',
            $this->wizardSrc,
            'wizard wrapper must carry x-init="init($el)" so the observer attaches on first render'
        );
    }

    #[Test]
    public function shim_uses_mutation_observer_and_filters_class_attribute(): void
    {
        // The observer must watch for class changes (the step
        // transition flips .fi-active on the panel's class list).
        $this->assertStringContainsString(
            'new MutationObserver(',
            $this->wizardSrc,
            'shim must construct a MutationObserver to react to class swaps'
        );
        $this->assertStringContainsString(
            "attributeFilter: ['class']",
            $this->wizardSrc,
            'shim must filter on class attribute changes — anything else is wasted CPU'
        );
        $this->assertStringContainsString(
            'subtree: true',
            $this->wizardSrc,
            'shim must observe the subtree (steps live deep inside the Filament wizard chrome)'
        );
    }

    #[Test]
    public function shim_toggles_inert_based_on_fi_active_class(): void
    {
        // For inactive steps the shim must SET inert; for the active
        // step it must REMOVE inert. Both branches are required —
        // setting once and never clearing would leave the form
        // unfocusable after the first transition.
        $this->assertStringContainsString(
            "step.setAttribute('inert', '')",
            $this->wizardSrc,
            'shim must set the inert attribute on inactive steps'
        );
        $this->assertStringContainsString(
            "step.removeAttribute('inert')",
            $this->wizardSrc,
            'shim must clear the inert attribute on active steps'
        );

        // Pin both branches gate on the active class, not e.g.
        // visibility computed-style or aria-hidden.
        $this->assertStringContainsString(
            "step.classList.contains('fi-active')",
            $this->wizardSrc,
            'shim must read the canonical .fi-active class to decide active vs inactive'
        );
    }

    #[Test]
    public function shim_syncs_aria_hidden_alongside_inert(): void
    {
        // inert removes the subtree from focus + AT trees, but a
        // belt-and-braces aria-hidden=true on inactive steps gives
        // older AT software (NVDA + JAWS pre-2023) the same
        // semantic signal even when their inert support is patchy.
        $this->assertStringContainsString(
            "step.setAttribute('aria-hidden', 'true')",
            $this->wizardSrc,
            'shim must set aria-hidden=true on inactive steps for AT defence-in-depth'
        );
        $this->assertStringContainsString(
            "step.removeAttribute('aria-hidden')",
            $this->wizardSrc,
            'shim must clear aria-hidden when the step becomes active'
        );
    }

    #[Test]
    public function shim_runs_initial_sync_on_first_paint(): void
    {
        // Before the user advances any step, the inactive panels
        // must already be inert. Without an initial `sync()` call,
        // the first Tab press could leak into step-2 before any
        // class swap fires.
        $this->assertMatchesRegularExpression(
            '/sync\\(\\);\\s*\\n[^\\n]*Watch for class swaps/s',
            $this->wizardSrc,
            'shim must call sync() once BEFORE attaching the observer'
        );
    }

    #[Test]
    public function shim_handles_browsers_without_mutation_observer(): void
    {
        // No-op gracefully on ancient browsers; modern Filament
        // targets (Chrome 90+, Firefox 90+, Safari 15+) all support
        // it but we still guard.
        $this->assertStringContainsString(
            "typeof MutationObserver === 'undefined'",
            $this->wizardSrc,
            'shim must guard MutationObserver availability — gracefully no-op on unsupported browsers'
        );
    }

    #[Test]
    public function shim_definition_is_idempotent_across_livewire_remounts(): void
    {
        // Livewire re-renders this view on step transitions; without
        // an idempotency guard, every remount would redefine the
        // global helper AND lose the previous observer reference.
        $this->assertStringContainsString(
            "typeof window.checkoutWizardInertShim === 'undefined'",
            $this->wizardSrc,
            'shim must guard `if (typeof window.checkoutWizardInertShim === undefined)` so Livewire remounts do not redefine it'
        );
    }
}
