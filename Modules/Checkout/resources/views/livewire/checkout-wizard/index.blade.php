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
     * AI-267 (cycle-179 2026-05-11) — checkout breadcrumb / progress
     * indicator.
     *
     * PM filed AI-267 as Phase 1 UX after the design audit: customers
     * need a clear "where am I in checkout" signal. The Filament
     * Wizard ALREADY renders a header with step icons + labels, but
     * the default styling looks like a navigation menu, not a
     * progress indicator — there's no visual distinction between
     * "completed", "current", and "future" steps, and on a 390px
     * viewport the 5-step header overflows or wraps awkwardly.
     *
     * This block enhances the existing `.fi-fo-wizard-header` into
     * a true breadcrumb-style progress indicator using design tokens
     * from cycle-178:
     *   - Current step: filled `--color-primary` background + white
     *     text, slight elevation via `--shadow-1`
     *   - Past steps: hollow with `--color-primary` text + thin
     *     `--color-primary` border (visited markers)
     *   - Future steps: dimmed `--color-text-muted`
     *   - Connecting line between steps anchored at 50% Y of the
     *     icon row, colored `--color-primary` for past->current
     *     and `--color-border` for current->future
     *   - On <768px, the header uses `overflow-x: auto` with
     *     `scroll-snap-type: x mandatory` so all 5 steps stay
     *     reachable without breaking the row layout.
     *
     * Scope: `.checkout-wizard-container` so other Filament wizards
     * (admin install, etc.) keep their existing visual. The rule
     * sits BEFORE the cycle-161 floor block so the floor's
     * `!important` rules can still win where they overlap.
     */
    .checkout-wizard-container .fi-fo-wizard-header {
        display: flex;
        flex-wrap: nowrap;
        align-items: center;
        gap: 0;
        padding: 1rem 0.5rem 1.5rem;
        position: relative;
    }
    .checkout-wizard-container .fi-fo-wizard-header-step {
        flex: 1 0 auto;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem;
        scroll-snap-align: start;
        position: relative;
    }
    /* Step indicator circle (Filament renders an icon button — we
       size the icon button as the indicator + add ::after as the
       connecting line). */
    .checkout-wizard-container .fi-fo-wizard-header-step-button {
        width: var(--touch-target-min, 44px);
        height: var(--touch-target-min, 44px);
        min-width: var(--touch-target-min, 44px);
        min-height: var(--touch-target-min, 44px);
        border-radius: 9999px;
        background-color: var(--color-surface, #ffffff);
        border: 2px solid var(--color-border, #d1d5db);
        color: var(--color-text-muted, #9ca3af);
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: background-color var(--duration-fast, 150ms) var(--ease-default, cubic-bezier(0.4, 0, 0.2, 1)),
                    border-color var(--duration-fast, 150ms) var(--ease-default, cubic-bezier(0.4, 0, 0.2, 1)),
                    color var(--duration-fast, 150ms) var(--ease-default, cubic-bezier(0.4, 0, 0.2, 1));
        position: relative;
        z-index: 2;
    }
    /* Visited / past steps — Filament marks completed steps with
       `.fi-completed` (or absence of `.fi-active` on a step that
       precedes the current one in source order). The most reliable
       marker is `.fi-completed`. */
    .checkout-wizard-container .fi-fo-wizard-header-step.fi-completed .fi-fo-wizard-header-step-button {
        background-color: var(--color-primary, #0d6efd);
        border-color: var(--color-primary, #0d6efd);
        color: #ffffff;
    }
    /* Active / current step — filled primary, slightly larger
       focus ring via box-shadow. */
    .checkout-wizard-container .fi-fo-wizard-header-step.fi-active .fi-fo-wizard-header-step-button {
        background-color: var(--color-primary, #0d6efd);
        border-color: var(--color-primary, #0d6efd);
        color: #ffffff;
        box-shadow: 0 0 0 4px rgba(13, 110, 253, 0.15), var(--shadow-1, 0 1px 3px rgba(0, 0, 0, 0.1));
    }
    /* Connecting horizontal line — sits behind the indicator
       circles, runs through their centers. */
    .checkout-wizard-container .fi-fo-wizard-header-step:not(:first-child)::before {
        content: "";
        position: absolute;
        left: -50%;
        right: 50%;
        top: calc(0.5rem + var(--touch-target-min, 44px) / 2);
        height: 2px;
        background-color: var(--color-border, #d1d5db);
        z-index: 1;
    }
    .checkout-wizard-container .fi-fo-wizard-header-step.fi-completed::before,
    .checkout-wizard-container .fi-fo-wizard-header-step.fi-active::before {
        background-color: var(--color-primary, #0d6efd);
    }
    /* Step label — small text under each indicator. */
    .checkout-wizard-container .fi-fo-wizard-header-step-label {
        font-size: var(--text-small-size, 12px);
        line-height: var(--text-small-line, 1.4);
        color: var(--color-text-secondary, #6b7280);
        text-align: center;
        max-width: 90px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    .checkout-wizard-container .fi-fo-wizard-header-step.fi-active .fi-fo-wizard-header-step-label,
    .checkout-wizard-container .fi-fo-wizard-header-step.fi-completed .fi-fo-wizard-header-step-label {
        color: var(--color-text-primary, #111827);
        font-weight: 600;
    }

    /* Mobile (<768px) — let the header scroll horizontally with
       snap so the user can swipe between step indicators while
       always seeing the current step on entry. */
    @media (max-width: 768px), (pointer: coarse) {
        .checkout-wizard-container .fi-fo-wizard-header {
            overflow-x: auto;
            scroll-snap-type: x mandatory;
            -webkit-overflow-scrolling: touch;
            padding: 0.5rem 0.25rem 1rem;
        }
        .checkout-wizard-container .fi-fo-wizard-header-step {
            min-width: 100px;
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
