<div class="checkout-wizard-container"
     {{-- Defence-in-depth keyboard-focus fix: Filament v5 hides inactive wizard steps with
          `visibility: hidden`, but Firefox has been observed letting Tab leak into them while
          the visibility recompute is in flight. An Alpine MutationObserver toggles the HTML5
          `inert` attribute in sync with `.fi-active` so an inactive step is removed from both
          the focus tree and the accessibility tree (WHATWG inert / WCAG 2.4.3 Focus Order). --}}
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
     * Checkout breadcrumb / progress indicator.
     *
     * Enhances the Filament `.fi-fo-wizard-header` into a breadcrumb-style
     * progress indicator using shared design tokens:
     *   - Current step: filled `--color-primary` background + white text
     *   - Past steps: filled `--color-primary` (completed markers)
     *   - Future steps: hollow with muted color and border
     *   - Connecting line between steps anchored at the icon row Y,
     *     colored `--color-primary` for past/current and `--color-border` otherwise
     *   - On <=768px the header uses overflow-x: auto + scroll-snap so all
     *     steps stay reachable without breaking the row layout
     *
     * Scoped to `.checkout-wizard-container` so other Filament wizards keep their visual.
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
    /* Visited / past steps — Filament marks completed steps with `.fi-completed`. */
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
     * /checkout mobile touch-target floor — enforce WCAG 2.5.5 / iOS HIG 44x44 on
     * the Wizard "Next" button and the user-menu trigger inside the checkout panel.
     *
     * Scoped to `.fi-panel-checkout` so other Filament panels keep their density.
     * `min-height` (not `height`) so the button still grows for longer translated labels.
     */
    @media (max-width: 768px), (pointer: coarse) {
        /* `!important` and defensive higher-specificity duplicates: Filament's bundled
           `.fi-btn { min-height: 36px }` rule loads AFTER this scoped style tag, so a later
           same-specificity rule could otherwise win. */
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
     * Primary CTA color unification — align the two checkout buttons (`fi-color-primary`
     * "Next" and `fi-color-success` "Place Order") to Bootstrap's `--bs-primary` #0d6efd
     * with a 4px radius so the public-facing flow has one consistent CTA color.
     *
     * Scoped to `.fi-panel-checkout` so other Filament panels keep their tokens. The
     * `html body.fi-panel-checkout` prefix raises specificity so we beat both the
     * light-mode and `html.dark` Filament theme defaults regardless of source order.
     */
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
     * Defence-in-depth focus shim: sync the HTML5 `inert` attribute with Filament's
     * `.fi-active` class on each wizard step so Tab can never reach an inactive step's
     * inputs. No-op on browsers without MutationObserver.
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
