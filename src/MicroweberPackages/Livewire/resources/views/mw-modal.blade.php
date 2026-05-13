<div>

    <div wire:ignore>
        <style>
            .js-modal-livewire.active {
                display: block;
            }

            .js-modal-livewire {
                display: none;
                position: fixed;
                z-index: 1100;
                left: 0;
                top: 0;
                width: 100%;
                height: 100%;
                overflow: auto;
                background-color: rgb(0, 0, 0);
                background-color: rgba(0, 0, 0, 0.4);
            }

            .js-modal-livewire-content {
                margin: auto;
                background-color: #fff;
                width: 100%;
                overflow: auto;
                position: relative;
            }

            @media only screen and (min-width: 600px) {
                .js-modal-livewire {
                    padding-top: 100px;
                }

                .js-modal-livewire-content {
                    max-width: 480px;
                    max-height: calc(100vh - 100px);
                }
            }

            @media only screen and (min-width: 768px) {
                .js-modal-livewire {
                    padding-top: 8%;
                }

                .js-modal-livewire-content {
                    max-height: calc(100vh - 100px);
                    overflow: auto;
                }
            }

            /*
             * NOVICE #13 (task-2026-05-13-899d57) — wrapper close X.
             *
             * The mw-modal wrapper had no top-right close affordance.
             * Whether a close button existed depended entirely on the
             * embedded child component. Novice persona reported: "I'm
             * stuck staring at a modal I can't dismiss until I find
             * the right inner button." Even when the X DID exist, it
             * was visually inconsistent across components.
             *
             * Adding a wrapper-level close X guarantees every Livewire
             * modal has the same top-right dismiss control. 44×44 touch
             * target (WCAG 2.5.5), high-contrast on the modal's white
             * background, gets a focus ring for keyboard users.
             */
            .mw-modal-close-x {
                position: absolute;
                top: 8px;
                right: 8px;
                width: 44px;
                height: 44px;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                border: 0;
                background: transparent;
                color: #475569;
                font-size: 24px;
                line-height: 1;
                cursor: pointer;
                border-radius: 6px;
                z-index: 2;
                padding: 0;
            }
            .mw-modal-close-x:hover {
                background: rgba(0, 0, 0, 0.06);
                color: #0f172a;
            }
            .mw-modal-close-x:focus-visible {
                outline: 2px solid #6366f1;
                outline-offset: 2px;
                background: rgba(99, 102, 241, 0.08);
            }
        </style>
    </div>


    <div id="modal-holder-livewire">


        @if($components)
            @foreach($components as $id => $component)
                {{-- AI-240 (task-2026-05-13-0fb704): a11y attributes on the
                     modal root. role="dialog" + aria-modal="true" tell
                     assistive tech this overlay is a modal so background
                     content is announced as inert. tabindex="-1" makes
                     the wrapper programmatically focusable so the focus-
                     trap script can land focus inside the modal if no
                     focusable child exists (rare but possible). --}}
                {{-- NOVICE #13 (task-2026-05-13-899d57) — the
                     `data-mw-modal-backdrop="1"` flag identifies the
                     backdrop element for the JS click handler below.
                     Clicking the dim area (event target IS the
                     backdrop) dispatches `closeModal`; clicks on the
                     inner white card bubble up but the target check
                     filters them out. --}}
                <div class="js-modal-livewire {{$activeComponent ? 'active' : ''}}" id="js-modal-livewire-id-{{ $id }}"
                     wire:key="{{ $id }}"
                     role="dialog"
                     aria-modal="true"
                     tabindex="-1"
                     data-mw-modal-backdrop="1">
                    <div class="js-modal-livewire-content">
                        {{-- Wrapper-level close X — every Livewire
                             modal now gets the same dismiss affordance
                             regardless of which child component is
                             loaded inside. `data-mw-modal-close="1"`
                             marks it for the focus-trap so initial
                             focus prefers a form field over the X. --}}
                        <button type="button"
                                class="mw-modal-close-x"
                                aria-label="Close"
                                title="Close"
                                data-mw-modal-close="1"
                                onclick="try { window.Livewire && window.Livewire.dispatch('closeModal'); } catch (e) {}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <line x1="18" y1="6" x2="6" y2="18"></line>
                                <line x1="6" y1="6" x2="18" y2="18"></line>
                            </svg>
                        </button>
                        @livewire($component['name'], $component['attributes'], key($id))
                    </div>
                </div>

            @endforeach
        @endif
    </div>


    <div wire:ignore>
        <script>

            document.addEventListener('livewire:init', function () {

                /*
                 * AI-239 body-scroll lock for public-side Livewire modals.
                 *
                 * The admin Filament theme bundle already locks `body` scroll on
                 * `x-modal-opened` / `modal-closed` events, but the
                 * livewire-ui-modal (MwModal) component used on public pages and
                 * in some admin partials runs outside that event bus. When a
                 * modal opens via `activeModalComponentChanged`, the overlay sits
                 * at `position: fixed` with `background: rgba(0,0,0,0.4)` — but
                 * `body` is still scrollable, so the page behind the dim scrolls
                 * away from under the modal on touch.
                 *
                 * Lock policy:
                 *   - save current scrollY into `body.dataset.mwModalScrollY`
                 *   - set `body.style.overflow = 'hidden'`
                 *   - reverse on close + restore scrollY
                 *
                 * Idempotent — guarded by a stack counter so nested opens don't
                 * double-restore.
                 */
                let mwModalOpenStack = 0;
                function mwLockBodyScrollForModal() {
                    if (mwModalOpenStack++ > 0) return;
                    document.body.dataset.mwModalScrollY = String(window.scrollY || window.pageYOffset || 0);
                    document.body.style.overflow = 'hidden';
                }
                function mwUnlockBodyScrollForModal() {
                    if (mwModalOpenStack > 0) mwModalOpenStack--;
                    if (mwModalOpenStack > 0) return;
                    document.body.style.overflow = '';
                    /*
                     * NOVICE #15 (task-2026-05-13-899d57) — when
                     * `dataset.mwModalScrollY` is undefined (e.g. a
                     * nested-modal race opened modal B before modal A's
                     * lock fired, then both close in quick succession),
                     * the previous code parsed the missing string as
                     * `0` and called `window.scrollTo(0, 0)` — snapping
                     * the user back to the top of the page. Novice
                     * persona reported "I scrolled down to edit the
                     * footer, opened a modal, closed it, and the page
                     * jumped back to the top — I had to scroll all the
                     * way down again every time." Early-return when
                     * the stored scrollY is missing so the current
                     * position is preserved.
                     */
                    const stored = document.body.dataset.mwModalScrollY;
                    if (typeof stored === 'undefined' || stored === null || stored === '') {
                        return;
                    }
                    const y = parseInt(stored, 10);
                    if (!isNaN(y)) window.scrollTo(0, y);
                    delete document.body.dataset.mwModalScrollY;
                }

                /*
                 * AI-240 focus management for the Livewire `mw-modal` overlay
                 * (task-2026-05-13-0fb704). Filament's `.fi-modal` already
                 * provides focus-trap + Escape + focus-return via Alpine's
                 * x-trap + x-on:keydown.escape (vendor/filament/support/...
                 * /components/modal/index.blade.php lines 113 + 143). The
                 * Livewire `mw-modal` runs outside that Alpine pipeline and
                 * had none of those affordances — keyboard users could tab
                 * out into the page beneath, Escape was a no-op, and on
                 * close focus fell back to <body> instead of the trigger.
                 *
                 * Three vanilla-JS pieces, no new npm dep:
                 *   1. On open: remember `document.activeElement` as the
                 *      trigger, push it onto a stack so nested modals
                 *      stack-restore correctly, then move focus to the
                 *      first tabbable element inside the modal (or the
                 *      modal wrapper itself if no tabbable child exists —
                 *      tabindex="-1" on the wrapper makes that fallback
                 *      programmatically focusable).
                 *   2. While open: a window-level keydown handler traps
                 *      Tab + Shift+Tab inside the topmost modal AND closes
                 *      it on Escape (matching the Filament behaviour).
                 *   3. On close: pop the focus stack and restore focus to
                 *      the saved trigger if it's still in the DOM.
                 *
                 * Tabbable-element selector matches the standard set used
                 * by every focus-trap library on npm — links, buttons,
                 * inputs/selects/textareas/checkboxes that are not
                 * disabled, plus anything with an explicit non-negative
                 * tabindex. We exclude `[type="hidden"]` and elements
                 * inside `[hidden]` parents.
                 */
                const MW_MODAL_TABBABLE_SELECTOR = [
                    'a[href]',
                    'button:not([disabled])',
                    'input:not([disabled]):not([type="hidden"])',
                    'select:not([disabled])',
                    'textarea:not([disabled])',
                    '[tabindex]:not([tabindex="-1"])',
                ].join(', ');

                let mwModalFocusStack = [];
                let mwModalKeydownBound = false;

                function mwGetTopmostOpenModal() {
                    const all = document.querySelectorAll('.js-modal-livewire');
                    for (let i = all.length - 1; i >= 0; i--) {
                        const el = all[i];
                        const cs = window.getComputedStyle(el);
                        if (cs.display !== 'none' && cs.visibility !== 'hidden') {
                            return el;
                        }
                    }
                    return null;
                }

                function mwGetTabbablesInModal(modalEl) {
                    if (!modalEl) return [];
                    return Array.from(modalEl.querySelectorAll(MW_MODAL_TABBABLE_SELECTOR))
                        .filter(function (n) {
                            if (n.offsetParent === null && n !== document.activeElement) return false;
                            if (n.hasAttribute('disabled')) return false;
                            return true;
                        });
                }

                function mwModalKeydownHandler(event) {
                    const topModal = mwGetTopmostOpenModal();
                    if (!topModal) return;

                    if (event.key === 'Escape' || event.keyCode === 27) {
                        event.preventDefault();
                        if (window.Livewire && typeof window.Livewire.dispatch === 'function') {
                            window.Livewire.dispatch('closeModal');
                        }
                        return;
                    }

                    if (event.key !== 'Tab' && event.keyCode !== 9) return;

                    const tabbables = mwGetTabbablesInModal(topModal);
                    if (tabbables.length === 0) {
                        event.preventDefault();
                        topModal.focus();
                        return;
                    }

                    const first = tabbables[0];
                    const last = tabbables[tabbables.length - 1];
                    const active = document.activeElement;

                    if (event.shiftKey) {
                        if (active === first || !topModal.contains(active)) {
                            event.preventDefault();
                            last.focus();
                        }
                    } else {
                        if (active === last || !topModal.contains(active)) {
                            event.preventDefault();
                            first.focus();
                        }
                    }
                }

                function mwBindModalKeydown() {
                    if (mwModalKeydownBound) return;
                    document.addEventListener('keydown', mwModalKeydownHandler, true);
                    mwModalKeydownBound = true;
                }

                function mwUnbindModalKeydown() {
                    if (!mwModalKeydownBound) return;
                    if (mwModalFocusStack.length > 0) return;
                    document.removeEventListener('keydown', mwModalKeydownHandler, true);
                    mwModalKeydownBound = false;
                }

                /*
                 * NOVICE #13 (task-2026-05-13-899d57) — prefer a non-
                 * close tabbable for INITIAL focus. The new wrapper-
                 * level close X is the first tabbable in DOM order so
                 * focus-trap's default would land focus on it on every
                 * modal open — the user opens a form and their cursor
                 * sits on a Close button instead of the first input.
                 * Skip elements marked `data-mw-modal-close="1"` when
                 * picking the initial focus target, but keep them in
                 * the tab cycle (Tab/Shift+Tab still reach them).
                 */
                function mwPickInitialFocus(tabbables, modalEl) {
                    if (tabbables.length === 0) return modalEl;
                    const nonClose = tabbables.filter(function (n) {
                        return !n.hasAttribute('data-mw-modal-close');
                    });
                    return nonClose.length > 0 ? nonClose[0] : tabbables[0];
                }

                function mwTrapFocusForModal() {
                    mwModalFocusStack.push(document.activeElement || null);
                    mwBindModalKeydown();

                    // Defer focus so the modal DOM is laid out + visible
                    // before we try to focus into it (Livewire mounts the
                    // component asynchronously).
                    setTimeout(function () {
                        const topModal = mwGetTopmostOpenModal();
                        if (!topModal) return;
                        const tabbables = mwGetTabbablesInModal(topModal);
                        mwPickInitialFocus(tabbables, topModal).focus();
                    }, 30);
                }

                function mwReleaseFocusForModal() {
                    const trigger = mwModalFocusStack.pop();
                    mwUnbindModalKeydown();
                    if (trigger && typeof trigger.focus === 'function' && document.body.contains(trigger)) {
                        try {
                            trigger.focus();
                        } catch (e) { /* no-op — trigger may have been removed */ }
                    }
                }

                Livewire.on('activeModalComponentChanged', () => {
                    mwLockBodyScrollForModal();
                    mwTrapFocusForModal();
                });

                /*
                 * NOVICE #13 (task-2026-05-13-899d57) — backdrop-click
                 * dismiss. Click on the dim area (the `.js-modal-
                 * livewire` element itself, NOT its inner white card)
                 * dispatches `closeModal`. `e.target === e.currentTarget`
                 * is not used because we listen on document (delegation)
                 * — instead we check `e.target.classList.contains(
                 * 'js-modal-livewire')` which is only true when the
                 * pointer landed on the backdrop itself. Clicks on the
                 * inner card or any descendant resolve `e.target` to
                 * the card/descendant, NOT the backdrop, so they fall
                 * through.
                 *
                 * Listening on document via capture so we run BEFORE
                 * any inner-component click handlers can stop
                 * propagation by accident. Modal components that
                 * INTENTIONALLY want to opt out of click-to-dismiss
                 * can mark themselves with `data-mw-modal-no-backdrop-
                 * close="1"` on the `.js-modal-livewire` element (no
                 * existing components do — but the opt-out is here
                 * for the future).
                 */
                document.addEventListener('click', function (e) {
                    const t = e.target;
                    if (!t || !t.classList || !t.classList.contains('js-modal-livewire')) return;
                    if (!t.hasAttribute('data-mw-modal-backdrop')) return;
                    if (t.hasAttribute('data-mw-modal-no-backdrop-close')) return;
                    if (window.Livewire && typeof window.Livewire.dispatch === 'function') {
                        window.Livewire.dispatch('closeModal');
                    }
                }, true);

                Livewire.on('closeMwTopDialogIframe', () => {
                    let dialog = mw.top().dialog.get('#mw-livewire-component-iframe');
                    if (dialog) {
                        dialog.remove();
                    }
                });

                Livewire.on('openMwTopDialogIframe', (componentName, jsonParams) => {

                    let params = [];
                    params.componentName = componentName;
                    if (jsonParams) {
                        jsonParams.componentName = componentName;
                        params = new URLSearchParams(jsonParams).toString();
                    }

                    let mwNativeModal = mw.top().dialogIframe({
                        url: "{{ route('admin.livewire.components.render-component') }}?" + params,
                        width: 900,
                        height: 900,
                        id: 'mw-livewire-component-iframe',
                        skin: 'square_clean',
                        center: false,
                        resize: true,
                        overlayClose: true,
                        draggable: true
                    });
                    mwNativeModal.dialogHeader.style.display = 'none';
                });

                // simple modal
                Livewire.on('closeModal', (force = false, skipPreviousModals = 0, destroySkipped = false) => {
                    let openedModals = document.querySelectorAll('.js-modal-livewire');
                    for (let i = 0; i < openedModals.length; i++) {
                        let openedModalId = openedModals[i].getAttribute('wire:key');
                        let modal = document.getElementById("js-modal-livewire-id-" + openedModalId);
                        if (modal) {
                            modal.style.display = "none";
                            //Livewire.dispatch('destroyComponent', ['id', openedModalId]);
                        }
                    }
                    // AI-239: release the body scroll lock symmetrically with open.
                    mwUnlockBodyScrollForModal();
                    // AI-240: release the focus trap + restore focus to the
                    // element that triggered the modal so keyboard users
                    // resume from their last tabstop instead of <body>.
                    mwReleaseFocusForModal();
                });


                /*  Livewire.on('activeModalComponentChanged', (data) => {




                      let modal = document.getElementById("js-modal-livewire-id-" + data.id);

                      if(!modal) {
                         console.log('Modal not found', data);
                      }

                      if (modal) {
                          modal.style.display = "block";
                          if (data.modalSettings) {
                              modal.querySelector('.js-modal-livewire-content').style.width = data.modalSettings.width;
                          }
                      }
                  });*/

            });

        </script>

    </div>
</div>
