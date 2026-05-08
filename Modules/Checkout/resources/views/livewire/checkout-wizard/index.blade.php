<div class="checkout-wizard-container"
     {{-- AI-60 / TICKET-DD (cycle-75 2026-05-08): keyboard focus
          leak fix. Filament v5's wizard CSS hides inactive steps via
          `.fi-sc-wizard-step:not(.fi-active) { visibility: hidden;
          position: absolute; height: 0; overflow: hidden; }`.
          `visibility: hidden` is supposed to remove focusable
          descendants from the focus tree, but multi-step Filament
          forms re-mount Alpine on step transitions and Firefox in
          particular has been observed letting Tab leak into
          inactive-step inputs while the visibility recompute is
          still in flight.

          Defence-in-depth: an Alpine MutationObserver on this
          wrapper toggles the HTML5 `inert` attribute on each
          `.fi-sc-wizard-step` based on whether `.fi-active` is
          present. `inert` is the modern, browser-native way to
          remove a subtree from BOTH the focus tree AND the AT
          accessibility tree — strictly stronger than visibility:
          hidden, and doesn't depend on visibility recompute timing.
          See WHATWG inert spec / WCAG 2.4.3 Focus Order. --}}
     x-data="checkoutWizardInertShim()"
     x-init="init($el)">
    <div class="max-w-5xl mx-auto">
        {{ $this->form }}
    </div>
</div>

<style>
    .checkout-wizard-container {
        padding: 1.5rem;
    }

    .checkout-wizard-container .fi-fo-wizard-header {
        margin-bottom: 2rem;
    }

    .checkout-wizard-container .fi-fo-wizard-header-step {
        padding: 0.75rem;
    }

    .checkout-wizard-container .fi-fo-wizard-header-step-button {
        font-weight: 500;
    }

    @media (max-width: 640px) {
        .checkout-wizard-container {
            padding: 1rem;
        }
    }
</style>

<script>
    /*
     * AI-60 / TICKET-DD (cycle-75 2026-05-08): defence-in-depth focus
     * shim. Sync the HTML5 `inert` attribute with Filament's
     * `.fi-active` class on each wizard step so Tab can never reach
     * an inactive step's inputs.
     *
     * No-op on browsers without MutationObserver (IE10 and below) —
     * those don't matter for the Filament target set anyway.
     */
    if (typeof window.checkoutWizardInertShim === 'undefined') {
        window.checkoutWizardInertShim = function () {
            return {
                _observer: null,
                init: function (root) {
                    if (!root || typeof MutationObserver === 'undefined') {
                        return;
                    }
                    var self = this;
                    var sync = function () {
                        var steps = root.querySelectorAll('.fi-sc-wizard-step');
                        steps.forEach(function (step) {
                            var active = step.classList.contains('fi-active');
                            if (active) {
                                if (step.hasAttribute('inert')) {
                                    step.removeAttribute('inert');
                                }
                                if (step.getAttribute('aria-hidden') === 'true') {
                                    step.removeAttribute('aria-hidden');
                                }
                            } else {
                                if (!step.hasAttribute('inert')) {
                                    step.setAttribute('inert', '');
                                }
                                if (step.getAttribute('aria-hidden') !== 'true') {
                                    step.setAttribute('aria-hidden', 'true');
                                }
                            }
                        });
                    };
                    // Initial sync on first paint.
                    sync();
                    // Watch for class swaps as the wizard advances.
                    self._observer = new MutationObserver(sync);
                    self._observer.observe(root, {
                        attributes: true,
                        attributeFilter: ['class'],
                        subtree: true,
                    });
                },
                destroy: function () {
                    if (this._observer) {
                        this._observer.disconnect();
                        this._observer = null;
                    }
                }
            };
        };
    }
</script>
