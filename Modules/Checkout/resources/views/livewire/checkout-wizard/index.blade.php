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

    /*
     * Cycle-161 (2026-05-10): /checkout mobile touch-target floor.
     *
     * UX-audit P2 follow-up findings (agent-test verification of
     * AI-186 cycle-160 fix):
     *   - Filament Wizard "Next" button (.fi-sc-wizard-footer .fi-btn)
     *     measured 83x42 — height below WCAG 2.5.5 / iOS HIG 44x44.
     *   - User-menu trigger (.fi-user-menu-trigger) measured 32x32 —
     *     well below floor.
     *
     * Both rules are scoped to the checkout panel via .fi-panel-checkout
     * so we don't touch other Filament panels' user-menu sizing (admin
     * panel keeps its current density).
     *
     * The Wizard Next button uses min-height (not height) so the
     * button still grows to fit longer translated labels ("Continue",
     * "Place Order", etc.). User menu is fixed at 44x44 — it's an
     * icon-only trigger, no label growth concern.
     */
    @media (max-width: 768px), (pointer: coarse) {
        /* !important here because Filament's bundled `.fi-btn
           { min-height: 36px }` rule loads AFTER this scoped style
           tag in source order — even though our selector specificity
           is higher, a later same-specificity rule with !important
           on the Filament side could still win. Be defensive.
           Cycle-162 (2026-05-10 / AI-203 follow-up): added defensive
           higher-specificity duplicates per PM after agent-test
           reported the cycle-161 rule losing in their environment.
           My direct probe at /checkout?nocache=99 showed cycle-161
           winning (Next 83×44, UserMenu 44×44, computedMinH 44px),
           so the failure was likely a stale CSS bundle on their
           side — but adding extra winning paths costs nothing and
           protects against any future Filament base-CSS bump that
           might actually beat the original (0,3,1) selector. */
        .fi-panel-checkout .fi-sc-wizard-footer .fi-btn,
        .fi-panel-checkout .fi-sc-wizard-footer .fi-btn.fi-color-primary,
        .fi-panel-checkout .checkout-wizard-container .fi-sc-wizard-footer .fi-btn,
        .fi-panel-checkout form .fi-sc-wizard-footer button.fi-btn {
            min-height: 44px !important;
            min-width: 44px !important;
            padding: 8px 16px;
        }
        .fi-panel-checkout .fi-user-menu-trigger,
        .fi-panel-checkout .fi-topbar .fi-user-menu .fi-user-menu-trigger,
        .fi-panel-checkout .fi-topbar [aria-haspopup="menu"].fi-user-menu-trigger,
        body.fi-panel-checkout button.fi-user-menu-trigger {
            min-width: 44px !important;
            min-height: 44px !important;
            padding: 6px;
        }
    }

    /*
     * AI-212 (cycle-166 2026-05-10) — primary CTA color unification.
     *
     * agent-test design audit found 4 different primary-button colors
     * across 4 surfaces:
     *   - /shop "All Categories": #0d6efd Bootstrap blue (radius 0)
     *   - /checkout "Next": #4299e1 Tailwind cyan (radius 4)
     *   - /checkout "Place Order": #2fb344 green (radius 4)
     *   - Admin "Save": #7c3aed purple (radius 4)
     *
     * Scope decision: align the two CHECKOUT buttons to Bootstrap's
     * canonical `--bs-primary: #0d6efd` so the public-facing flow has
     * one consistent color. Admin Save stays purple (Filament admin
     * theme — different surface/persona; PM "audit" brief listed it
     * but didn't ask for cross-application unification).
     *
     * Scoped to `.fi-panel-checkout` so other Filament admin panels
     * keep their existing color tokens. Both `.fi-color-primary`
     * (Next) and `.fi-color-success` (Place Order) bumped to the
     * same blue + 4px radius.
     *
     * Filament v5's button colors are CSS custom properties that
     * Tailwind's `bg-fi-color-600` etc. resolve via the `--fi-color-N`
     * vars. Override the resolved background + border-radius directly
     * with !important to beat both the cycle-N tailwind utility class
     * AND any future Filament theme upgrade.
     */
    /* Bumped specificity from `(0,4,1)` → `(0,5,2)` by prefixing
       with `html body.fi-panel-checkout` so we beat both the cycle-N
       admin theme rule `html.dark .fi-btn.fi-color-primary:not(...)`
       (0,4,1 with 2 :not pseudo-classes) AND the light-mode
       `.fi-btn.fi-color-primary:not(.admin-toolbar-buttons)` (0,3,1).
       Same-specificity ties + later-loaded Filament theme were
       beating my (0,4,1) `.fi-panel-checkout .fi-sc-wizard-footer
       .fi-btn.fi-color-primary` first-pass rule. */
    html body.fi-panel-checkout .fi-sc-wizard-footer .fi-btn.fi-color-primary,
    html body.fi-panel-checkout .fi-sc-wizard-footer .fi-btn.fi-color-success,
    html.dark body.fi-panel-checkout .fi-sc-wizard-footer .fi-btn.fi-color-primary,
    html.dark body.fi-panel-checkout .fi-sc-wizard-footer .fi-btn.fi-color-success,
    html body.fi-panel-checkout form .fi-sc-wizard-footer button.fi-btn.fi-color-primary,
    html body.fi-panel-checkout form .fi-sc-wizard-footer button.fi-btn.fi-color-success {
        background-color: #0d6efd !important;
        border-color: #0d6efd !important;
        color: #ffffff !important;
        border-radius: 4px !important;
    }
    html body.fi-panel-checkout .fi-sc-wizard-footer .fi-btn.fi-color-primary:hover,
    html body.fi-panel-checkout .fi-sc-wizard-footer .fi-btn.fi-color-success:hover,
    html.dark body.fi-panel-checkout .fi-sc-wizard-footer .fi-btn.fi-color-primary:hover,
    html.dark body.fi-panel-checkout .fi-sc-wizard-footer .fi-btn.fi-color-success:hover {
        background-color: #0b5ed7 !important;
        border-color: #0b5ed7 !important;
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
