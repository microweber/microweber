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
                <div class="js-modal-livewire {{$activeComponent ? 'active' : ''}}" id="js-modal-livewire-id-{{ $id }}"
                     wire:key="{{ $id }}"
                     role="dialog"
                     aria-modal="true"
                     tabindex="-1">
                    <div class="js-modal-livewire-content">
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
                        if (tabbables.length > 0) {
                            tabbables[0].focus();
                        } else {
                            topModal.focus();
                        }
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
