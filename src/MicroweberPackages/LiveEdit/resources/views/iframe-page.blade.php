<div class="mw-admin-live-edit-page">


    <div
        x-data="{}"
        x-init="() => {
            // Helper: unmount any currently-mounted Filament action
            // before mounting a new one. Without this, clicking a
            // sub-module button while the Layout Settings slideOver
            // is already open stacks the new modal on top — the user
            // sees both panels at once and the click feels broken
            // (see task-2026-04-29-ee7a19). Filament doesn't
            // hot-replace actions, so we explicitly unmount + remount.
            const swapAction = (name, args = {}) => {
                try { $wire.unmountAction(); } catch (_) { /* nothing mounted */ }
                // 60 ms gives Filament time to finish the slideOver-
                // close transition before the new mountAction fires —
                // empirically below this the close + open compose into
                // a single render frame and the user perceives no panel
                // change at all.
                setTimeout(() => {
                    $wire.mountAction(name, args);
                }, 60);
            };

            window.addEventListener('openAddContentAction', () => {
                swapAction('addContentAction', {});
            });

            window.addEventListener('openModuleSettingsAction', (e) => {
                swapAction('openModuleSettingsAction', { data: e.detail });
            });

            // Some live-edit toolbar actions (Template Settings, Style
            // Editor, Quick AI Edit, Setup Wizard, Insert Layout,
            // Layers, Code Editor, Reset Content, Clear Cache, …) open
            // their own non-Filament widgets/dialogs. If a Filament
            // module/layout-settings slideOver is already mounted when
            // the operator clicks one of those, the slideOver stays
            // open behind the new widget — exactly the bug from
            // task-2026-04-29-2315b5 and -70496a. Listen for an
            // explicit close event from the toolbar Vue components and
            // unmount whatever Filament action is mounted.
            window.addEventListener('closeFilamentSlideOver', () => {
                try { $wire.unmountAction(); } catch (_) { /* nothing mounted */ }
            });

            // After AdminLiveEditPage::generateAction creates a new
            // page/post/product/category, navigate the canvas iframe to
            // the new content (or refresh the current page if the event
            // was dispatched without a URL) so the user immediately
            // sees that their save worked.
            //
            // The handler dispatches the Livewire event
            // 'liveEditAddContentSaved' (with `url` payload after
            // task-2026-05-01-3dff3c). Livewire v3 surfaces it as a
            // window-scoped CustomEvent whose `event.detail` is an
            // ARRAY of payloads (one per dispatch arg). The first
            // element is the named-args object — `event.detail[0].url`.
            //
            // Why navigate, not just refresh: most users add a post
            // while editing the homepage (which doesn't list posts),
            // so a plain refresh leaves the iframe looking unchanged
            // and they file 'Add posts is not working' — exactly
            // task-2026-05-01-3dff3c. Loading the new content's URL
            // turns Save into 'I see my post, it worked'. Categories
            // have no public URL — fall back to refresh in that case.
            //
            // Bind once via window.addEventListener only — Livewire v3's
            // dispatch() also emits via Livewire.on(), so registering
            // both would fire the same handler twice (visible flicker).
            // After ContentTableList CreateAction/EditAction/DeleteAction
            // completes inside the post-module-settings iframe, the
            // iframe's layout forwards a `liveEditModuleTableActionSaved`
            // window event up via top.window.dispatchEvent. Reload only
            // the listing modules (posts / content / shop/products) on
            // the canvas — NOT the whole iframe — so the change is
            // visible without a jarring full-page reload that resets
            // scroll position, focus, animations, etc.
            // task-2026-05-02-99f90c (initial wiring) +
            // task-2026-05-02-420d06 (per user request — selective
            // module reload instead of a full canvas refresh).
            window.addEventListener('liveEditModuleTableActionSaved', () => {
                try {
                    var canvasWindow = mw.app && mw.app.canvas
                        && typeof mw.app.canvas.getWindow === 'function'
                        ? mw.app.canvas.getWindow()
                        : null;

                    // Reload only the listing modules whose contents
                    // are managed via ContentTableList. Each call is a
                    // no-op if no module of that type exists on the
                    // current canvas page.
                    var reloadTypes = ['posts', 'content', 'shop/products'];

                    if (canvasWindow && canvasWindow.mw
                        && typeof canvasWindow.mw.reload_module === 'function') {
                        reloadTypes.forEach(function (t) {
                            try { canvasWindow.mw.reload_module(t); } catch (_) {}
                        });
                        return;
                    }

                    // Hard fallback: if the canvas window's mw object
                    // isn't ready (race during initial mount), do the
                    // old full refresh so the user still sees their
                    // change rather than silently no-oping.
                    if (mw.app && mw.app.canvas
                        && typeof mw.app.canvas.refresh === 'function') {
                        mw.app.canvas.refresh();
                    }
                } catch (_) { /* canvas not ready */ }
            });

            window.addEventListener('liveEditAddContentSaved', (event) => {
                try {
                    if (!window.mw || !mw.app || !mw.app.canvas) { return; }
                    let targetUrl = '';
                    const detail = event && event.detail;
                    if (Array.isArray(detail) && detail.length > 0
                        && detail[0] && typeof detail[0].url === 'string') {
                        targetUrl = detail[0].url;
                    } else if (detail && typeof detail.url === 'string') {
                        targetUrl = detail.url;
                    }
                    if (targetUrl && typeof mw.app.canvas.go === 'function') {
                        mw.app.canvas.go(targetUrl);
                    } else if (typeof mw.app.canvas.refresh === 'function') {
                        mw.app.canvas.refresh();
                    }
                } catch (_) { /* canvas not ready */ }
            });

            // The live-edit SAVE button (top-right green pill) wants
            // to behave as a 'save everything that is visible' button.
            // When the user opens a Filament action like 'Add New Post'
            // / 'Add Page' / a module-settings form via $wire.mountAction,
            // they expect SAVE to also submit that form — task-2026-04-29-dc57b7.
            //
            // Don't call $wire.callMountedAction() directly — that
            // bypasses Livewire's form-data sync and the server-side
            // action receives stale (empty) input values, which is
            // why the user saw 'nothing happens' on save in
            // task-2026-04-29-ba63de. Instead, find the mounted
            // action's submit form and call requestSubmit() on it.
            // Livewire intercepts the native submit event, syncs all
            // wire:model bindings, then dispatches callMountedAction
            // with the up-to-date form payload.
            window.addEventListener('liveEditSaveCallMountedAction', () => {
                try {
                    // Don't gate on $wire.mountedActions.length here —
                    // $wire is the AdminLiveEditPage wire, but table
                    // actions (e.g. ContentTableList 'New post' inside
                    // the Edit Posts module settings) are mounted on
                    // the *child* Livewire component, not the parent.
                    // Gating on the parent's mountedActions would cause
                    // table-action submits to be skipped silently —
                    // exactly the bug from task-2026-04-29-a4bd4f. Let
                    // the form-attribute scan below be authoritative:
                    // if no form has the right wire:submit handler,
                    // .forEach is a no-op anyway.

                    // Filament renders the mounted action modal under
                    // a <form wire:submit.prevent='callMountedAction'>
                    // wrapper. CSS-attribute selectors with colons +
                    // dots in the attribute name need escaping that's
                    // awkward to embed inside this Blade-rendered
                    // x-init string, so just iterate and match the
                    // attribute value at runtime.
                    // Filament v5 uses *different* wire:submit handler
                    // names depending on where the action lives:
                    //   - 'callMountedAction'         — page-level/Livewire actions
                    //                                   (AdminLiveEditPage Add Page/Post/etc.)
                    //   - 'callMountedTableAction'    — table header/row actions
                    //                                   (ContentTableList 'New post' inside
                    //                                    the Edit Posts module settings —
                    //                                    task-2026-04-29-005626)
                    //   - 'callMountedTableBulkAction'— table bulk actions
                    //   - 'callMountedFormComponentAction' — form-component
                    //                                   actions (Repeater rows etc.)
                    // Match any of them so the live-edit SAVE button
                    // covers every action surface, not just page-level.
                    const acceptedSubmitNames = [
                        'callMountedAction',
                        'callMountedTableAction',
                        'callMountedTableBulkAction',
                        'callMountedFormComponentAction',
                    ];

                    // Collect every matching form first. When the user
                    // is editing a Posts module's settings (Edit Posts
                    // → New post), TWO forms exist concurrently:
                    //   1. The OUTER form, owned by the parent
                    //      AdminLiveEditPage wire, with submit handler
                    //      'callMountedAction' for the
                    //      openModuleSettingsAction.
                    //   2. The INNER form, owned by the child
                    //      ContentTableList wire, with submit handler
                    //      'callMountedTableAction' for the New-post
                    //      CreateAction.
                    // Submitting both is wrong — the outer form's
                    // submit re-fires openModuleSettingsAction which
                    // re-renders the slideOver and destroys the inner
                    // form's pending state before the row can save
                    // (task-2026-04-29-394cd1). Always prefer the
                    // most-specific form by handler-name precedence:
                    // table/form-component/bulk actions are always
                    // INNER to a generic callMountedAction wrapper, so
                    // pick those first when present.
                    const matched = [];
                    const collectFormsFromDoc = (doc, isIframe) => {
                        if (!doc || typeof doc.querySelectorAll !== 'function') { return; }
                        doc.querySelectorAll('form').forEach((f) => {
                            const submitAttr = f.getAttribute('wire:submit.prevent')
                                || f.getAttribute('wire:submit');
                            if (acceptedSubmitNames.includes(submitAttr)) {
                                matched.push({ form: f, name: submitAttr, isIframe });
                            }
                        });
                    };
                    collectFormsFromDoc(document, false);

                    // Some module-settings slideOvers (Posts, Products,
                    // Pictures…) render the inner Filament action form
                    // INSIDE a same-origin iframe at
                    // /admin/<...>-module-settings, NOT in the parent
                    // document. Without recursing into the iframe, the
                    // SAVE button only finds the OUTER
                    // openModuleSettingsAction wrapper and submitting
                    // that re-fires the wrapper instead of the inner
                    // CreateAction the user actually filled in — bug
                    // from task-2026-05-02-99f90c. Walk every iframe
                    // we can read (same-origin) and collect their
                    // forms too.
                    //
                    // The slideOver iframe's CreateAction also uses
                    // `callMountedAction` (same handler name as the
                    // outer wrapper), so the handler-precedence map
                    // alone can't disambiguate. Iframe forms must ALWAYS
                    // win over parent forms with the same handler name —
                    // anything visible inside an iframe is by definition
                    // INNER to the parent's slideOver wrapper, so the
                    // most-specific submit lives there.
                    document.querySelectorAll('iframe').forEach((ifr) => {
                        try {
                            collectFormsFromDoc(ifr.contentDocument, true);
                        } catch (_) { /* cross-origin iframe — skip */ }
                    });

                    if (matched.length === 0) return;

                    // Precedence (highest = most-specific):
                    //   callMountedTableBulkAction
                    //   callMountedTableAction
                    //   callMountedFormComponentAction
                    //   callMountedAction
                    const precedence = {
                        callMountedTableBulkAction: 4,
                        callMountedTableAction: 3,
                        callMountedFormComponentAction: 2,
                        callMountedAction: 1,
                    };
                    // Sort: iframe forms first (anything inside an
                    // iframe is INNER to a parent slideOver wrapper),
                    // then by handler-name precedence within each
                    // origin tier. This is the disambiguator for the
                    // post-module-settings case where both the outer
                    // wrapper AND the inner CreateAction use
                    // 'callMountedAction' — task-2026-05-02-99f90c.
                    matched.sort((a, b) => {
                        if (a.isIframe !== b.isIframe) {
                            return a.isIframe ? -1 : 1;
                        }
                        return precedence[b.name] - precedence[a.name];
                    });

                    const pick = matched[0].form;
                    if (typeof pick.requestSubmit === 'function') {
                        pick.requestSubmit();
                    } else {
                        pick.dispatchEvent(new Event('submit', { cancelable: true, bubbles: true }));
                    }
                } catch (_) { /* no action mounted, nothing to submit */ }
            });
        }"
    >

    </div>


    <div wire:ignore>


        <div>


            <?php

            $bodyDarkClass = '';

            if (isset($_COOKIE['admin_theme_dark'])) {
                $bodyDarkClass = 'theme-dark';
            }
            ?>

            @include('admin::layouts.partials.loads-user-custom-fonts')

            <?php event_trigger('mw.live_edit.header'); ?>
        </div>

        <script>
            mw.quickSettings = {};
            mw.layoutQuickSettings = [];

            /*
             * Add Page/Post/Product/Category modal — make it
             * draggable by its header so the user can move it out
             * of the way to peek at the live-edit canvas
             * underneath, mirroring v2's mw.dialog behaviour.
             * task-2026-05-04-c124bc.
             *
             * v2's mw.dialog draggability is just a jQuery UI
             * .draggable() call on its own header. We apply the
             * same call directly to Filament's `.fi-modal-window`
             * carrying our `mw-content-form-modal` class, with
             * `.fi-modal-header` as the handle. Doing it this way
             * (instead of porting the whole modal pipeline to
             * mw.dialog) keeps the Livewire form's wire:click /
             * wire:model bindings untouched — the DOM stays where
             * Filament put it, only its position changes.
             *
             * The MutationObserver catches modals that mount AFTER
             * page load (the normal case — Filament inserts the
             * modal node into the document body when an Action is
             * mounted via wire:click).
             */
            (function () {
                function isContentFormModal(el) {
                    return el instanceof HTMLElement
                        && el.classList.contains('fi-modal-window')
                        && el.classList.contains('mw-content-form-modal');
                }

                function resetPin(modal) {
                    modal.style.position = '';
                    modal.style.top = '';
                    modal.style.left = '';
                    modal.style.transform = '';
                    modal.style.margin = '';
                    delete modal.dataset.mwContentModalPinned;
                }

                function watchVisibility(modal) {
                    // Filament reuses the same modal DOM node
                    // when the user closes and reopens an action —
                    // any inline left/top/transform we set during
                    // a previous drag would persist and the modal
                    // would reopen at its dragged position rather
                    // than re-centred. Watch the parent
                    // .fi-modal-window-ctn for the
                    // `data-modal-state="open"` ↔ "closed" toggle
                    // (Filament + Alpine flip this) and reset the
                    // pin every time it transitions to "closed",
                    // so the next open starts at the CSS default.
                    // Falls back to watching the modal's display
                    // style if the data attribute isn't present.
                    // task-2026-05-04-b7eee8.
                    var ctn = modal.parentElement;
                    if (!ctn) return;
                    var obs = new MutationObserver(function () {
                        var open = ctn.getAttribute('data-modal-state') === 'open'
                            || (ctn.style.display !== 'none' && getComputedStyle(ctn).display !== 'none');
                        if (!open) resetPin(modal);
                    });
                    obs.observe(ctn, {attributes: true, attributeFilter: ['data-modal-state', 'style', 'class']});
                }

                function attachDraggable(modal) {
                    resetPin(modal);
                    if (modal.dataset.mwContentModalDraggable === '1') return;
                    modal.dataset.mwContentModalDraggable = '1';
                    watchVisibility(modal);

                    var header = modal.querySelector('.fi-modal-header');
                    if (!header) return;

                    var dragging = false;
                    var startX = 0, startY = 0, startLeft = 0, startTop = 0;

                    function onMouseDown(e) {
                        if (e.button !== 0) return;
                        // Don't start a drag from interactive
                        // elements inside the header (the close X,
                        // links, form controls) — those need their
                        // native click behaviour.
                        if (e.target.closest('button, input, textarea, select, a, [role="button"]')) return;

                        var rect = modal.getBoundingClientRect();
                        // Pin the modal to viewport-fixed coords at
                        // its current rendered position. Filament
                        // centers it via the parent ctn's flex
                        // layout — once we set inline left/top,
                        // those values would fight the flex
                        // centering and park the modal off-screen.
                        // Switching to position: fixed (relative to
                        // viewport) AND zeroing margin removes the
                        // modal from the flex flow so subsequent
                        // left/top updates express viewport coords
                        // directly. Idempotent via the pinned flag.
                        if (modal.dataset.mwContentModalPinned !== '1') {
                            modal.style.position = 'fixed';
                            modal.style.top = Math.round(rect.top) + 'px';
                            modal.style.left = Math.round(rect.left) + 'px';
                            modal.style.margin = '0';
                            // The default CSS layout uses
                            // transform: translateX(-50%) to centre
                            // the modal horizontally. Once we pin
                            // to explicit left/top, that transform
                            // would shift the modal an extra
                            // half-width to the left of the
                            // dragged coords. Zero it out so the
                            // dragged offset is what the user sees.
                            modal.style.transform = 'none';
                            modal.dataset.mwContentModalPinned = '1';
                        }

                        dragging = true;
                        startX = e.clientX;
                        startY = e.clientY;
                        startLeft = rect.left;
                        startTop = rect.top;
                        modal.classList.add('ui-draggable-dragging');
                        e.preventDefault();
                    }

                    function onMouseMove(e) {
                        if (!dragging) return;
                        var dx = e.clientX - startX;
                        var dy = e.clientY - startY;
                        var nx = startLeft + dx;
                        var ny = startTop + dy;

                        // Keep at least a thumb-width of the header
                        // visible so the user can always grab to
                        // drag back. For modals taller than the
                        // viewport (the Add Post form is ~1640px
                        // tall), allow Y to go negative — that's
                        // how the user reaches the bottom of the
                        // form by dragging.
                        var headerH = header.offsetHeight || 56;
                        var w = modal.offsetWidth;
                        var minLeft = 24 - w;
                        var maxLeft = window.innerWidth - 24;
                        var maxTop = window.innerHeight - headerH - 8;
                        nx = Math.max(minLeft, Math.min(maxLeft, nx));
                        // No min-top constraint: tall forms must be
                        // pushable up to expose the bottom.
                        ny = Math.min(maxTop, ny);

                        modal.style.left = nx + 'px';
                        modal.style.top = ny + 'px';
                    }

                    function onMouseUp() {
                        if (!dragging) return;
                        dragging = false;
                        modal.classList.remove('ui-draggable-dragging');
                    }

                    header.addEventListener('mousedown', onMouseDown);
                    document.addEventListener('mousemove', onMouseMove);
                    document.addEventListener('mouseup', onMouseUp);
                }

                function scan(root) {
                    if (!root) return;
                    if (isContentFormModal(root)) {
                        attachDraggable(root);
                        return;
                    }
                    if (root.querySelectorAll) {
                        root.querySelectorAll('.fi-modal-window.mw-content-form-modal').forEach(attachDraggable);
                    }
                }

                function start() {
                    scan(document.body);
                    var observer = new MutationObserver(function (mutations) {
                        mutations.forEach(function (m) {
                            m.addedNodes.forEach(scan);
                        });
                    });
                    observer.observe(document.body, {childList: true, subtree: true});
                }

                if (document.readyState === 'loading') {
                    document.addEventListener('DOMContentLoaded', start);
                } else {
                    start();
                }
            })();

            window.addEventListener('load', function () {
                if (mw.top() && mw.top().app && mw.top().app.liveEdit && mw.top().app.fontManager) {
                    mw.top().app.fontManager.addFonts({!! json_encode(\MicroweberPackages\Utils\Misc\GoogleFonts::getEnabledFonts()) !!});
                }

                const scrollContainer = document.querySelector("#live-edit-frame-holder");
                const frame = scrollContainer.querySelector("iframe");

                scrollContainer.addEventListener("wheel", (e) => {
                    if (e.target === scrollContainer) {
                        e.preventDefault();
                        const win = mw.top().app.canvas.getWindow();
                        win.scrollTo(0, (win.scrollY + e.deltaY) + (e.deltaY < 0 ? -10 : 10));
                    }

                });
                mw.require('{{ asset('vendor/microweber-packages/frontend-assets/build/element-style-editor-app.js') }}')
            });

            <?php


            ?>
        </script>

        <div id="live-edit-app">
            Loading...
        </div>

        <style>
            #mw-element-style-editor-app-container {
                display: none;
                position: fixed;
                top: calc(var(--toolbar-height) + 2px);
                bottom: 0;
                background: white;
                z-index: 100;
                right: 0;
                overflow: auto;
                padding: 0.5rem;
                box-shadow: -2px 2px 2px #b1b1b14a;
                width: calc(var(--sidebar-end-size));
            }

            .dark #mw-element-style-editor-app-container {
                background: rgb(31 41 55);
                box-shadow: -2px 2px 4px rgba(0, 0, 0, 0.3);
                color: white;
            }


            #mw-live-edit-gui-editor-box {
                width: var(--sidebar-end-size);
                min-width: var(--sidebar-end-size-min);
                max-width: var(--sidebar-end-size-max);
            }

            /*
             * mw.notification (used by SaveButton.vue → mw.notification.success
             * "Page saved successfully") renders into top.document.body via
             * the bundled `components/notification.js` template, which uses
             * Bootstrap-5 `text-bg-${type}` classes on the inner div for
             * colour. The admin chrome does NOT load Bootstrap CSS in the
             * top frame, so without an explicit fallback the notification
             * had NO background and the page content (e.g. the Edit Posts
             * iframe table) bled through — task-2026-05-02-003a6b. The
             * fallbacks below match notification.less's palette so the pill
             * stays readable everywhere admin chrome lives.
             */
            .mw-notification {
                background-color: #343a40;
                color: #ffffff;
                border-radius: 5px;
                padding: 8px 14px;
                font-size: 12px;
                box-shadow: 0 3px 8px rgba(0, 0, 0, 0.24);
                z-index: 99999;
            }
            .mw-notification .text-bg-success,
            .mw-notification.mw-success {
                background-color: #28a745;
                color: #ffffff;
            }
            .mw-notification .text-bg-warning,
            .mw-notification.mw-warning {
                background-color: #ffc107;
                color: #212529;
            }
            .mw-notification .text-bg-danger,
            .mw-notification .text-bg-error,
            .mw-notification.mw-error {
                background-color: #dc3545;
                color: #ffffff;
            }
            /* Inner div should inherit the colour modifier set on the outer */
            .mw-notification > div {
                background-color: inherit;
                color: inherit;
                border-radius: inherit;
            }

            /*
             * `.mw-content-form-modal` is applied via
             * extraModalWindowAttributes on the Add Page/Post/Product/
             * Category modal (AdminLiveEditPage::generateAction) and
             * the per-module CreateAction/EditAction. Two scoped
             * overrides: (1) restore the modal backdrop tint that
             * `microweber-filament-theme/.../general-styles.css`
             * globally forces to bg-transparent (fine for slide-overs,
             * catastrophic for centered content forms — the user
             * couldn't tell where the modal ended), (2) make the
             * footer position: sticky so Save/Cancel stay visible on
             * long forms — Filament's `stickyModalFooter()` only adds
             * a marker class, the project's compiled Filament CSS
             * doesn't ship a rule for it. task-2026-05-02-df09aa.
             */
            .fi-modal:has(> .fi-modal-window-ctn .mw-content-form-modal) > .fi-modal-close-overlay {
                background-color: rgba(0, 0, 0, 0.55) !important;
            }

            /*
             * Cap the modal at viewport height and make the body
             * scroll internally — the Add Post form is ~1640px tall
             * (rich-text editor + media picker + parent-page tree +
             * custom fields + SEO + advanced) and used to grow past
             * the viewport, hiding the footer entirely AND letting
             * late sections (Media → Add images) bleed visually
             * behind the sticky footer (task-2026-05-04-b7eee8
             * reproduction). Switch the modal-window to a flex
             * column with header/footer fixed-size and the content
             * as the lone flex-grow scroll region. After this, the
             * footer (Save/Cancel) is always visible at the bottom
             * and `overflow-y: auto` on .fi-modal-content lets the
             * user scroll through the form.
             *
             * Pin the modal-window to viewport-fixed coordinates so
             * the layout is deterministic regardless of Filament's
             * modal-window-ctn flex positioning (which carries
             * `fi-align-start` and pads the modal ~226px from the
             * top of the live-edit toolbar — a position that left
             * a tall form's footer below the fold). 24px from top,
             * 50% horizontal centring via translateX, max-height
             * = full viewport minus the 48px breathing room.
             *
             * The drag handler (task-2026-05-04-c124bc) already
             * sets position: fixed + explicit left/top/margin on
             * mousedown, which overrides these defaults — so users
             * can still drag the modal anywhere they like.
             */
            /*
             * Filament's base rule
             * `.fi-modal:not(.fi-width-screen) .fi-modal-window:not(.fi-modal-slide-over-window)`
             * has specificity (0,4,0). Match it with a (0,4,0)
             * selector of our own — same specificity, later in
             * cascade, wins. This avoids `!important` so the drag
             * handler's inline style.left/top still take effect
             * (task-2026-05-04-c124bc).
             */
            .fi-modal:not(.fi-width-screen) .fi-modal-window.mw-content-form-modal {
                position: fixed;
                top: 1.5rem;
                left: 50%;
                transform: translateX(-50%);
                margin: 0;
                max-height: calc(100vh - 3rem);
                display: flex;
                flex-direction: column;
            }

            /*
             * Mobile-friendly: at narrow viewports the
             * .fi-width-5xl class would set a 1024px max-width
             * that overflows the screen. Below 768px take over
             * width and pin to viewport edges (16px gutter on
             * each side, 12px from top/bottom). The right
             * sidebar columns inside the form already collapse
             * to 1 column at narrow widths via Filament's grid
             * because the compact form uses
             * `.columns(1)->columnSpanFull()` already.
             * task-2026-05-04-76275d.
             */
            @media (max-width: 767px) {
                .fi-modal:not(.fi-width-screen) .fi-modal-window.mw-content-form-modal {
                    top: 0.75rem;
                    left: 0.75rem;
                    right: 0.75rem;
                    transform: none;
                    max-width: none;
                    width: auto;
                    max-height: calc(100vh - 1.5rem);
                }
                .mw-content-form-modal .fi-section {
                    padding: 0.625rem 0.75rem;
                }
                .mw-content-form-modal > .fi-modal-header,
                .mw-content-form-modal > .fi-modal-footer {
                    padding-inline: 1rem;
                }
            }
            .fi-modal-window.mw-content-form-modal > .fi-modal-header,
            .fi-modal-window.mw-content-form-modal > .fi-modal-footer {
                flex: 0 0 auto;
            }
            .fi-modal-window.mw-content-form-modal > .fi-modal-content {
                flex: 1 1 auto;
                min-height: 0;
                overflow-y: auto;
            }
            .mw-content-form-modal .fi-modal-footer {
                background: var(--gray-50, #f9fafb);
                border-top: 1px solid var(--gray-200, #e5e7eb);
                margin-top: 0;
                padding-block: 0.75rem;
            }
            html.dark .mw-content-form-modal .fi-modal-footer,
            .dark .mw-content-form-modal .fi-modal-footer {
                background: var(--gray-900, #111827);
                border-top-color: var(--gray-700, #374151);
            }

            /*
             * Dark-mode polish + further compaction
             * (task-2026-05-04-e0fe54). The user's screenshot
             * showed the dark modal with muddy contrast: section
             * cards blended into the body, the header lost its
             * separator, and overall padding still felt heavy
             * after the schema was already trimmed.
             *
             * - Header gets a clear bottom border + tighter pad.
             * - Section cards in dark mode pick up a slightly
             *   lighter background so they read as cards on the
             *   dark body, plus an accent border for separation.
             * - Section padding tightened so each card takes
             *   less vertical space (lean modal feel).
             * - Remove the heavy outer rounded shadow pop on
             *   the rich-text toolbar in dark mode.
             */
            .mw-content-form-modal > .fi-modal-header {
                padding-block: 0.875rem;
                border-bottom: 1px solid var(--gray-200, #e5e7eb);
            }
            html.dark .mw-content-form-modal > .fi-modal-header,
            .dark .mw-content-form-modal > .fi-modal-header {
                border-bottom-color: var(--gray-700, #374151);
            }
            .mw-content-form-modal .fi-section {
                padding: 0.875rem 1rem;
            }
            .mw-content-form-modal .fi-section .fi-section-header {
                padding-bottom: 0.5rem;
            }
            html.dark .mw-content-form-modal .fi-section,
            .dark .mw-content-form-modal .fi-section {
                background-color: rgba(255, 255, 255, 0.025);
                border: 1px solid var(--gray-700, #374151);
            }
            html.dark .mw-content-form-modal .fi-section-header-heading,
            .dark .mw-content-form-modal .fi-section-header-heading {
                color: var(--gray-100, #f3f4f6);
            }
            html.dark .mw-content-form-modal .fi-modal-heading,
            .dark .mw-content-form-modal .fi-modal-heading {
                color: var(--gray-50, #f9fafb);
            }
            /* Tighter form-field gap inside sections so the lean
               schema actually reads as lean. */
            .mw-content-form-modal .fi-fo-field-wrp {
                gap: 0.375rem;
            }

            /*
             * Make the Add Page/Post/Product/Category modal draggable
             * by its header — same UX Microweber v2's `mw.dialog`
             * shipped (the v2 helper just called jQuery UI
             * `.draggable()` under the hood; we apply the same
             * behaviour directly to Filament's modal so the form's
             * Livewire wiring stays intact instead of porting the
             * whole modal pipeline). The cursor cue tells the user
             * "this header is grabbable"; `user-select: none` stops
             * the drag from selecting the heading text mid-drag;
             * `.ui-draggable-dragging` is the class jQuery UI adds
             * while a drag is in flight. task-2026-05-04-c124bc.
             */
            .mw-content-form-modal .fi-modal-header {
                cursor: move;
                user-select: none;
            }
            .mw-content-form-modal.ui-draggable-dragging,
            .mw-content-form-modal.ui-draggable-dragging .fi-modal-header {
                cursor: grabbing;
            }
            .mw-content-form-modal.ui-draggable-dragging {
                box-shadow: 0 22px 50px -12px rgba(0, 0, 0, 0.45);
            }

            /*
             * `.mw-content-picker-modal` is the +ADD "what do you
             * want to add?" dialog. Cards are stacked 1-per-row by
             * default which feels overwhelming and makes the modal
             * scroll. Grid them 2x2 on viewports wide enough so the
             * four content types are visible at-a-glance — friendlier
             * "Pinterest-style" picker, fewer eye movements before
             * the user picks. task-2026-05-02-4c1606.
             */
            .mw-content-picker-modal .mw-add-content-modal-action-wrapper {
                display: flex;
            }
            @media (min-width: 640px) {
                .mw-content-picker-modal .fi-sc-component .mb-6 {
                    display: grid;
                    grid-template-columns: 1fr 1fr;
                    gap: 0.75rem;
                }
                .mw-content-picker-modal .mw-add-content-modal-action-wrapper {
                    width: 100%;
                    margin: 0;
                }
            }
        </style>


        <div id="mw-element-style-editor-app-container">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <h3 class="fs-2 font-weight-bold">Element Style Editor</h3>
                <span class="x-close-modal-link" style="top: 27px; right: 32px;">
                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px"
                         fill="currentColor">
                        <path
                            d="m256-200-56-56 224-224-224-224 56-56 224 224 224-224 56 56-224 224 224 224-56 56-224-224-224 224Z"></path>
                    </svg>
                </span>
            </div>
            <div id="mw-element-style-editor-app">


            </div>
        </div>


        <div id="live-edit-frame-holder">

        </div>


        <div>
            <?php //print mw_admin_footer_scripts(); ?>
        </div>
        <script>

            mw.settings.adminUrl = '<?php print admin_url(); ?>';
            mw.settings.liveEditModuleSettingsUrls =  <?php print json_encode(\MicroweberPackages\Module\Facades\ModuleAdmin::getLiveEditSettingsUrls()); ?>;
            mw.settings.liveEditModuleSettingsComponents =  <?php print json_encode(\MicroweberPackages\Module\Facades\ModuleAdmin::getSettingsComponents()); ?>;
            mw.settings.liveEditModuleSettingsComponentsFromModuleRepository =  <?php print json_encode(\MicroweberPackages\Microweber\Facades\Microweber::getSettingsComponents()); ?>;

        </script>

        <link rel="stylesheet" href="{{ asset('vendor/microweber-packages/frontend-assets/build/live-edit-app.css') }}">
        <script src="{{ asset('vendor/microweber-packages/frontend-assets/build/live-edit-app.js') }}" type="module"></script>

        <?php print \MicroweberPackages\LiveEdit\Facades\LiveEditManager::headTags(); ?>
        <?php event_trigger('mw.live_edit.footer'); ?>


        @if(\MicroweberPackages\Multilanguage\MultilanguageHelpers::multilanguageIsEnabled())
            <script>

                window.addEventListener('load', function () {

                    mw.lib.require('flag_icons');
                });


            </script>
        @endif


    </div>


    <x-filament-actions::modals/>

    <script x-src="{{ asset('vendor/microweber-packages/frontend-assets/build/element-style-editor-app.js') }}"
            defer></script>


    <script>


        window.addEventListener('load', function () {


            setInterval(function () {
                if(mw.top().app && mw.top().app.canvas) {
                    var targetWindow = mw.top().app.canvas.getWindow();
                    if (targetWindow && targetWindow.mw) {
                        targetWindow.mw.session.check()
                    }
                }

                if (mw.top().win && mw.top().win.mw && mw.top().win.mw.uploadGlobalSettings && mw.top().win.mw.uploadGlobalSettings.on) {
                    //refresh the csrf token
                    mw.top().win.mw.uploadGlobalSettings.on.beforeFileUpload()
                }
            }, 300000); // check session every 5 minutes


        });


    </script>


    @if(request()->get('setup_wizard'))

        @php
            $propmtParams = [];
            $propmtParamsJson = json_encode([]);
            if(request()->get('prompt')){
                $propmtParams['prompt'] = request()->get('prompt');
            }

            if (isset($propmtParams) && is_array($propmtParams) and !empty($propmtParams)) {
                $propmtParamsJson = json_encode($propmtParams);
            }


        @endphp

        <script>


            window.addEventListener('load', function () {


                setTimeout(function () {

                    mw.app.dispatch('showSetupWizard', {!! $propmtParamsJson !!});

                    const topSearch = new URLSearchParams(mw.top().win.location.search);

                    topSearch.delete('prompt');
                    topSearch.delete('setup_wizard');
                    window.top.history.pushState(null, null, `?${topSearch.toString()}`);


                }, 2000);
            });


        </script>

    @endif

</div>
