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

            const resolveFirstInvalidField = (doc = document) => {
                if (!doc || typeof doc.querySelector !== 'function') {
                    return null;
                }

                const directInvalidField = doc.querySelector(
                    `.mw-content-form-modal .fi-input-wrp.fi-invalid input,
                     .mw-content-form-modal .fi-input-wrp.fi-invalid textarea,
                     .mw-content-form-modal .fi-input-wrp.fi-invalid select,
                     .mw-content-form-modal [aria-invalid='true']`
                );

                if (directInvalidField) {
                    return directInvalidField;
                }

                const firstErrorMessage = doc.querySelector(
                    '.mw-content-form-modal .fi-fo-field-error-message, .mw-content-form-modal .fi-fo-field-wrp-error-message'
                );

                if (!firstErrorMessage) {
                    return null;
                }

                const fieldWrapper = firstErrorMessage.closest(
                    '.fi-fo-field-wrp, .fi-fo-field, .fi-fo-component-ctn, .fi-section'
                );

                if (!fieldWrapper) {
                    return firstErrorMessage;
                }

                return fieldWrapper.querySelector(`input, textarea, select, [tabindex]:not([tabindex='-1'])`)
                    || fieldWrapper;
            };

            const clearInlineTitleValidation = (doc = document) => {
                const titleInput = doc.querySelector('.mw-content-form-modal .mw-fb-title-input');

                if (!titleInput) {
                    return;
                }

                if (titleInput.dataset.mwInlineInvalid === '1') {
                    titleInput.removeAttribute('aria-invalid');
                    delete titleInput.dataset.mwInlineInvalid;
                }

                const inputWrapper = titleInput.closest('.fi-input-wrp');

                if (inputWrapper && inputWrapper.dataset.mwInlineInvalid === '1') {
                    inputWrapper.classList.remove('fi-invalid');
                    delete inputWrapper.dataset.mwInlineInvalid;
                }

                doc.querySelectorAll('[data-mw-inline-title-required]').forEach((message) => {
                    message.remove();
                });
            };

            const showInlineTitleValidation = (doc = document) => {
                const titleInput = doc.querySelector('.mw-content-form-modal .mw-fb-title-input');

                if (!titleInput) {
                    return false;
                }

                const titleValue = typeof titleInput.value === 'string'
                    ? titleInput.value.trim()
                    : '';

                if (titleValue !== '') {
                    clearInlineTitleValidation(doc);
                    return false;
                }

                const inputWrapper = titleInput.closest('.fi-input-wrp');
                const fieldWrapper = titleInput.closest('.fi-fo-field');
                const errorHost = fieldWrapper
                    ? (fieldWrapper.querySelector('.fi-fo-field-content-col') || fieldWrapper)
                    : (titleInput.parentElement || titleInput);

                titleInput.dataset.mwInlineInvalid = '1';
                titleInput.setAttribute('aria-invalid', 'true');

                if (inputWrapper) {
                    inputWrapper.classList.add('fi-invalid');
                    inputWrapper.dataset.mwInlineInvalid = '1';
                }

                if (!doc.querySelector('[data-mw-inline-title-required]') && errorHost) {
                    const errorMessage = doc.createElement('p');
                    errorMessage.className = 'fi-fo-field-wrp-error-message';
                    errorMessage.setAttribute('data-mw-inline-title-required', '1');
                    errorMessage.setAttribute('data-validation-error', '');
                    errorMessage.textContent = 'The title field is required.';
                    errorHost.appendChild(errorMessage);
                }

                if (titleInput.dataset.mwInlineListenerBound !== '1') {
                    titleInput.addEventListener('input', () => {
                        const currentValue = typeof titleInput.value === 'string'
                            ? titleInput.value.trim()
                            : '';

                        if (currentValue !== '') {
                            clearInlineTitleValidation(doc);
                        }
                    });
                    titleInput.dataset.mwInlineListenerBound = '1';
                }

                const scrollTarget = inputWrapper || fieldWrapper || titleInput;
                const prefersReducedMotion = window.matchMedia
                    && window.matchMedia('(prefers-reduced-motion: reduce)').matches;

                try {
                    scrollTarget.scrollIntoView({
                        behavior: prefersReducedMotion ? 'auto' : 'smooth',
                        block: 'center',
                        inline: 'nearest',
                    });
                } catch (_) { /* ignore scroll failures */ }

                setTimeout(() => {
                    try {
                        titleInput.focus({ preventScroll: true });
                    } catch (_) { /* ignore focus failures */ }
                }, prefersReducedMotion ? 0 : 120);

                try {
                    window.dispatchEvent(new CustomEvent('liveEditMountedActionValidationFailed'));
                } catch (_) { /* no-op */ }

                return true;
            };

            const revealValidationFailure = (doc = document) => {
                const firstInvalidField = resolveFirstInvalidField(doc);

                if (!firstInvalidField) {
                    return false;
                }

                const prefersReducedMotion = window.matchMedia
                    && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
                const scrollTarget = firstInvalidField.closest(
                    '.fi-fo-field-wrp, .fi-fo-field, .fi-modal-content, .fi-modal-window, .fi-section'
                ) || firstInvalidField;

                try {
                    scrollTarget.scrollIntoView({
                        behavior: prefersReducedMotion ? 'auto' : 'smooth',
                        block: 'center',
                        inline: 'nearest',
                    });
                } catch (_) { /* ignore scroll failures */ }

                setTimeout(() => {
                    try {
                        if (typeof firstInvalidField.focus === 'function') {
                            firstInvalidField.focus({ preventScroll: true });
                        }
                    } catch (_) { /* ignore focus failures */ }
                }, prefersReducedMotion ? 0 : 120);

                try {
                    window.dispatchEvent(new CustomEvent('liveEditMountedActionValidationFailed'));
                } catch (_) { /* no-op */ }

                return true;
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

            // NOVICE #4 (task-2026-05-13-899d57) — when the user picks
            // 'Add a block to this page' from the +ADD picker, the
            // card's click handler fires this window event. The
            // listener forwards to the canvas's editor dispatcher,
            // which opens the Insert Layout dialog (the same flow the
            // SettingsCustomize 'Insert layout' toolbar button opens).
            //
            // Why a window event rather than a Filament server-side
            // action: the Insert Layout flow lives entirely in
            // canvas-iframe JS (mw.app.editor.dispatch). There is no
            // server route to redirect to, and embedding the layout
            // picker inside a Filament modal would conflict with the
            // canvas iframe root (the same constraint that pushed
            // upload-image into a redirect originally — see
            // AdminLiveEditPage::addImageAction comment block). The
            // picker card-click handler unmounts the picker and
            // dispatches this event in one tick; the canvas takes
            // over from here without any extra user interaction.
            window.addEventListener('liveEditInsertLayoutRequest', () => {
                try {
                    if (window.mw && window.mw.app && window.mw.app.editor
                        && typeof window.mw.app.editor.dispatch === 'function') {
                        // ListLayouts.vue only listens for insertLayoutRequestOnBottom
                        // and insertLayoutRequestOnTop — never the bare insertLayoutRequest
                        // event. Dispatching the named OnBottom variant opens the picker
                        // dialog (task-2026-05-28).
                        window.mw.app.editor.dispatch('insertLayoutRequestOnBottom',
                            window.mw.app.liveEdit?.layoutHandle?.getTarget?.() ?? null);
                    }
                } catch (_) { /* canvas not ready */ }
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
                    const ownerDoc = pick.ownerDocument || document;

                    if (showInlineTitleValidation(ownerDoc)) {
                        return;
                    }

                    if (typeof pick.requestSubmit === 'function') {
                        pick.requestSubmit();
                    } else {
                        pick.dispatchEvent(new Event('submit', { cancelable: true, bubbles: true }));
                    }

                    setTimeout(() => {
                        revealValidationFailure(ownerDoc);
                    }, 450);
                } catch (_) { /* no action mounted, nothing to submit */ }
            });

            // fi-modal stacking-context escape — moves fi-modal out of
            // fi-main-ctn to be a direct sibling of fi-main-ctn (child of
            // fi-layout). fi-main-ctn creates a stacking context during
            // Filament panel transitions (opacity, transform changes) that
            // traps position:fixed descendants in its compositor layer,
            // causing Chrome's elementFromPoint to return fi-main-ctn for
            // ALL modal coordinates and making modal inputs un-clickable.
            // Teleporting fi-modal out is the proven fix; all CSS-only
            // approaches (pointer-events, isolation, overflow) failed.
            // Livewire morphdom re-inserts fi-modal into fi-main-ctn on
            // each commit cycle, so the hook cleans up the prior teleported
            // copy (removes :scope > .fi-modal from fi-layout) before
            // appending the freshly-rendered one.
            (function () {
                var doTeleport = function () {
                    var layout = document.querySelector('.fi-layout');
                    if (!layout) { return; }
                    // Remove stale teleported copies from prior Livewire rounds.
                    layout.querySelectorAll(':scope > .fi-modal').forEach(
                        function (el) { el.remove(); }
                    );
                    var modal = document.querySelector('.fi-main-ctn .fi-modal');
                    if (modal) { layout.appendChild(modal); }
                };
                var registerHook = function () {
                    if (!window.Livewire || typeof window.Livewire.hook !== 'function') { return; }
                    window.Livewire.hook('commit', function (ref) {
                        ref.succeed(function () { requestAnimationFrame(doTeleport); });
                    });
                };
                if (window.Livewire && typeof window.Livewire.hook === 'function') {
                    registerHook();
                } else {
                    document.addEventListener('livewire:initialized', registerHook);
                }
            }());
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
                    /*
                     * task-2026-05-05-978bdf — extend the drag
                     * support to the Module Settings modal too. Same
                     * Filament .fi-modal-window node, just carrying a
                     * different class for the live-edit Module
                     * Settings center-modal flow. The drag pipeline
                     * is structure-only (header drag handle, viewport
                     * pin, escape-on-close reset) — both modals
                     * benefit from it without any per-modal logic.
                     */
                    return el instanceof HTMLElement
                        && el.classList.contains('fi-modal-window')
                        && (el.classList.contains('mw-content-form-modal')
                            || el.classList.contains('mw-module-settings-live-edit-modal'));
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

                        // Keep enough of the header on-screen that
                        // the user can always grab it to drag back
                        // (P0-2 from task-2026-05-04-1e4af3 —
                        // previous bounds let the modal go almost
                        // entirely off-screen left and there was no
                        // recovery path). Reserve a minimum
                        // grab-strip of 120px of header pixels on
                        // each axis. closeModalByEscaping(false) +
                        // closeModalByClickingAway(false) on the
                        // create modal mean the only way to close
                        // it is via the X / SAVE / Cancel — those
                        // MUST stay reachable.
                        var headerH = header.offsetHeight || 56;
                        var w = modal.offsetWidth;
                        var grabStrip = 120;
                        var minLeft = grabStrip - w;
                        var maxLeft = window.innerWidth - grabStrip;
                        var minTop = 0;
                        var maxTop = window.innerHeight - headerH - 8;
                        nx = Math.max(minLeft, Math.min(maxLeft, nx));
                        // Tall-form scenario: still allow Y near 0
                        // so the user can push the modal up to
                        // reach SAVE on a tall form, but never
                        // negative (which would put the header
                        // above the toolbar / off-screen).
                        ny = Math.max(minTop, Math.min(maxTop, ny));

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
                        if (root.classList.contains('mw-content-form-modal')) {
                            attachOpenInAdminTitleSync(root);
                            attachCancelGuard(root);
                        }
                        return;
                    }
                    if (root.querySelectorAll) {
                        root.querySelectorAll('.fi-modal-window.mw-content-form-modal, .fi-modal-window.mw-module-settings-live-edit-modal').forEach(function (m) {
                            attachDraggable(m);
                            // The title-sync + cancel-guard helpers
                            // are content-modal specific (they rely
                            // on the title input and unsaved-work
                            // probes that only exist in the Add
                            // Page/Post/Product/Category form). Skip
                            // them on the Module Settings modal —
                            // task-2026-05-05-978bdf only needs the
                            // drag behaviour.
                            if (m.classList.contains('mw-content-form-modal')) {
                                attachOpenInAdminTitleSync(m);
                                attachCancelGuard(m);
                            }
                        });
                    }
                }

                /*
                 * task-2026-05-04-novice — FORGIVENESS guard for the
                 * Cancel + X buttons in the live-edit Add Content
                 * modal. Brenda the novice user was observed typing a
                 * title + body, then clicking Cancel and silently
                 * losing 5 minutes of work — Filament's default modal
                 * Cancel just closes the dialog. Intercept the click
                 * once: if the title input or rich-text body has any
                 * content, ask "Discard this draft?" before letting
                 * the close go through. Pure DOM-level guard, no
                 * Filament API change required.
                 */
                function attachCancelGuard(modal) {
                    if (modal.dataset.mwCancelGuardWired === '1') return;
                    modal.dataset.mwCancelGuardWired = '1';

                    function hasUnsavedWork() {
                        var title = modal.querySelector('input.mw-fb-title-input, input[id$=".title"]');
                        var titleVal = title && (title.value || '').trim();
                        if (titleVal) return true;
                        // Rich-text body — Filament's Tiptap editor
                        // exposes the visible text via .ProseMirror
                        // contenteditable. Empty editor renders an
                        // empty <p><br></p>; treat <2 chars trimmed
                        // as empty to ignore that.
                        var body = modal.querySelector('.ProseMirror');
                        if (body) {
                            var txt = (body.textContent || '').replace(/\s+/g, '').trim();
                            if (txt.length > 0) return true;
                        }
                        // Excerpt / Short summary textarea.
                        var ta = modal.querySelector('textarea[id$=".description"]');
                        if (ta && (ta.value || '').trim()) return true;
                        return false;
                    }

                    function shouldDiscard() {
                        if (!hasUnsavedWork()) return true;
                        return window.confirm(
                            "Discard this draft?\n\n" +
                            "You've typed something but haven't saved. " +
                            "Click OK to throw it away, or Cancel to keep editing."
                        );
                    }

                    function intercept(e) {
                        if (shouldDiscard()) return; // let it proceed
                        e.preventDefault();
                        e.stopPropagation();
                        e.stopImmediatePropagation();
                    }

                    // Capture-phase listeners on the close-X and the
                    // Cancel footer button. Use capture so we run
                    // before Filament's wire:click handler.
                    var closeBtn = modal.querySelector('.fi-modal-close-btn, button[aria-label="Close"]');
                    if (closeBtn && !closeBtn.dataset.mwGuarded) {
                        closeBtn.dataset.mwGuarded = '1';
                        closeBtn.addEventListener('click', intercept, true);
                    }
                    // Cancel button is a Filament action with no
                    // stable id — pick by visible label inside the
                    // modal footer. Re-scan on every observer tick
                    // because Livewire re-renders the footer.
                    var footer = modal.querySelector('.fi-modal-footer');
                    if (footer) {
                        Array.prototype.forEach.call(footer.querySelectorAll('button'), function (b) {
                            if (b.dataset.mwGuarded === '1') return;
                            var label = (b.textContent || '').trim().toLowerCase();
                            if (label === 'cancel') {
                                b.dataset.mwGuarded = '1';
                                b.addEventListener('click', intercept, true);
                            }
                        });
                    }
                }

                /*
                 * task-2026-05-04-1e4af3 (P1-3): keep the
                 * Open-in-admin button's href in sync with the
                 * title that's been typed in the live-edit
                 * compact modal. The button is a static <a> with
                 * the URL set server-side at modal render —
                 * before any title is typed. So when the user
                 * clicks "Open in admin" mid-typing, the admin
                 * Create page used to load with no title. Append
                 * `&title=<typed>` on every input event so the
                 * admin Create page can read it from the query
                 * string (the existing /admin/contents/create
                 * route accepts standard query-string defaults
                 * via Filament's default-fill behaviour for
                 * fields with a matching name).
                 */
                function attachOpenInAdminTitleSync(modal) {
                    if (modal.dataset.mwOpenInAdminWired === '1') return;
                    modal.dataset.mwOpenInAdminWired = '1';

                    function findBtn() {
                        return modal.querySelector('.mw-open-in-admin-btn[href], a.mw-open-in-admin-btn, .fi-modal-footer a[href*="contents/create"], .fi-modal-footer a[href*="categories/create"]');
                    }
                    function findTitle() {
                        return modal.querySelector('input.mw-fb-title-input, input[id$=".title"]');
                    }
                    /*
                     * NOVICE #12 (task-2026-05-13-899d57) — extended the
                     * Open-in-admin URL sync to ALSO carry the user's
                     * typed body and excerpt, not just the title. The
                     * persona reported: "I clicked 'Show all options'
                     * because I wanted to add tags. A new tab opened,
                     * the original modal closed, and the title I'd typed
                     * was gone — I had to start over." Title was already
                     * carried (task-2026-05-04-1e4af3); body + excerpt
                     * were not. Now ALL three fields persist.
                     *
                     * Caps: title 256 chars, body 6 KB, excerpt 2 KB.
                     * URL practical limits start at ~8 KB across major
                     * browsers + servers, so we cap each field below
                     * its share. If the user has a longer body than
                     * the cap, we still carry the truncated prefix —
                     * the rest of the body is lost but the user lands
                     * on the admin form with a meaningful head-start,
                     * which is strictly better than losing everything.
                     */
                    function findBody() {
                        return modal.querySelector('.fi-fo-rich-editor [contenteditable="true"], .ProseMirror[contenteditable="true"], .tiptap[contenteditable="true"]');
                    }
                    function findExcerpt() {
                        return modal.querySelector('textarea[id$=".description"], textarea[id$=".content_body_excerpt"]');
                    }
                    function clip(s, limit) {
                        s = (s || '').toString();
                        if (s.length <= limit) return s;
                        return s.slice(0, limit);
                    }
                    function sync() {
                        var btn = findBtn();
                        var title = findTitle();
                        if (!btn) return;
                        if (!btn.dataset.mwOriginalHref) {
                            btn.dataset.mwOriginalHref = btn.getAttribute('href') || '';
                        }
                        var base = btn.dataset.mwOriginalHref;
                        var typedTitle = clip((title && title.value || '').trim(), 256);
                        var body = findBody();
                        var typedBody = body
                            ? clip((body.innerHTML || '').trim(), 6144)
                            : '';
                        var excerpt = findExcerpt();
                        var typedExcerpt = excerpt
                            ? clip((excerpt.value || '').trim(), 2048)
                            : '';

                        var params = [];
                        if (typedTitle)   params.push('title=' + encodeURIComponent(typedTitle));
                        if (typedBody)    params.push('content_body=' + encodeURIComponent(typedBody));
                        if (typedExcerpt) params.push('description=' + encodeURIComponent(typedExcerpt));

                        if (!params.length) {
                            btn.setAttribute('href', base);
                            return;
                        }
                        var sep = base.indexOf('?') === -1 ? '?' : '&';
                        btn.setAttribute('href', base + sep + params.join('&'));
                    }
                    var title = findTitle();
                    if (title) {
                        title.addEventListener('input', sync);
                        title.addEventListener('change', sync);
                    }
                    var excerpt = findExcerpt();
                    if (excerpt) {
                        excerpt.addEventListener('input', sync);
                        excerpt.addEventListener('change', sync);
                    }
                    // RichEditor doesn't bubble plain 'input' from the
                    // hidden state field; listen for events on its
                    // contenteditable surface. Use a MutationObserver
                    // fallback so we catch programmatic changes too.
                    var body = findBody();
                    if (body) {
                        body.addEventListener('input', sync);
                        body.addEventListener('keyup', sync);
                        body.addEventListener('blur', sync);
                    }
                    // Also sync once on mount so any default
                    // title (when editing) is reflected.
                    sync();
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
            /*
             * The live-edit toolbar sits at the top of the
             * viewport at `position: relative; z-index: 999`
             * (frontend-assets `ui/css/index.css`). The modal
             * pinned to `top: 1.5rem` rendered its heading
             * directly behind the 60px toolbar visually
             * (heading center at y=52, toolbar bottom = 60),
             * which not only hid the title but also intercepted
             * mousedown on the drag handle. Push the modal
             * below the toolbar height instead so the heading
             * + drag handle are always reachable.
             * task-2026-05-04-f575c7.
             */
            .fi-modal:not(.fi-width-screen) .fi-modal-window.mw-content-form-modal {
                position: fixed;
                top: calc(var(--toolbar-height, 60px) + 0.75rem);
                left: 50%;
                transform: translateX(-50%);
                margin: 0;
                max-height: calc(100vh - var(--toolbar-height, 60px) - 1.5rem);
                display: flex;
                flex-direction: column;
            }

            /*
             * UX-engineer audit (task-2026-05-05-02f93f):
             * validation failure on the compact "what's the title?"
             * surface was too easy to miss, which made failed save feel
             * like "nothing happened". When the title input becomes
             * invalid, give the whole field wrapper a tinted callout and
             * restore a real error border/ring on the giant title input.
             */
            .mw-content-form-modal .fi-fo-field:has(.fi-input-wrp.fi-invalid .mw-fb-title-input) {
                background: rgba(220, 38, 38, 0.06);
                border: 1px solid rgba(220, 38, 38, 0.26);
                border-radius: 0.875rem;
                padding: 0.75rem 0.875rem;
            }
            .dark .mw-content-form-modal .fi-fo-field:has(.fi-input-wrp.fi-invalid .mw-fb-title-input) {
                background: rgba(248, 113, 113, 0.12);
                border-color: rgba(248, 113, 113, 0.38);
            }
            .mw-content-form-modal .fi-input-wrp.fi-invalid .mw-fb-title-input {
                border: 2px solid #dc2626 !important;
                border-radius: 0.875rem !important;
                background: rgba(220, 38, 38, 0.04) !important;
                box-shadow: 0 0 0 4px rgba(220, 38, 38, 0.12) !important;
                padding-inline: 0.875rem !important;
            }
            .dark .mw-content-form-modal .fi-input-wrp.fi-invalid .mw-fb-title-input {
                border-color: #f87171 !important;
                background: rgba(248, 113, 113, 0.08) !important;
                box-shadow: 0 0 0 4px rgba(248, 113, 113, 0.16) !important;
            }
            .mw-content-form-modal .fi-fo-field:has(.fi-input-wrp.fi-invalid .mw-fb-title-input) .fi-fo-field-error-message,
            .mw-content-form-modal .fi-fo-field:has(.fi-input-wrp.fi-invalid .mw-fb-title-input) .fi-fo-field-wrp-error-message {
                font-size: 0.875rem;
                font-weight: 600;
                margin-top: 0.625rem;
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
                    /* Mobile-designer audit P1 — at 390px the
                       live-edit toolbar wraps to 2 rows
                       (~93-104px), so the original 60px offset
                       puts the modal under the wrapped SAVE
                       chip. Bump to 104px below 480px viewports
                       to clear both single-row (60px) and
                       wrapped (~93px) toolbar states.
                       task-2026-05-04-0c2964. */
                    top: calc(var(--toolbar-height, 104px) + 0.5rem);
                    left: 0.75rem;
                    right: 0.75rem;
                    transform: none;
                    max-width: none;
                    width: auto;
                    max-height: calc(100vh - var(--toolbar-height, 104px) - 1rem);
                }
                .mw-content-form-modal .fi-section {
                    padding: 0.625rem 0.75rem;
                }
                .mw-content-form-modal > .fi-modal-header,
                .mw-content-form-modal > .fi-modal-footer {
                    padding-inline: 1rem;
                }

                /*
                 * Mobile-designer audit fixes
                 * (task-2026-05-04-0c2964).
                 *
                 * P0-1: Half-width inputs. Filament's nested
                 * field wrappers (.fi-section > .fi-section-content
                 * > .fi-fo-field > .fi-fo-field-content-col >
                 * .fi-input-wrp) each stack their own grid +
                 * padding which collapsed Title/body/Excerpt
                 * to ~189px on a 366px-wide modal. Force every
                 * level to `min-width: 0` and a single-column
                 * grid so the input fills the section.
                 */
                .mw-content-form-modal .fi-section,
                .mw-content-form-modal .fi-section-content,
                .mw-content-form-modal .fi-fo-field,
                .mw-content-form-modal .fi-fo-field-content-col,
                .mw-content-form-modal .fi-input-wrp,
                .mw-content-form-modal .fi-fo-component-ctn,
                .mw-content-form-modal .fi-fo-component {
                    min-width: 0;
                }
                .mw-content-form-modal .fi-fo-field,
                .mw-content-form-modal .fi-fo-field-content-col,
                .mw-content-form-modal .fi-fo-component-ctn,
                .mw-content-form-modal .fi-sc.fi-grid,
                .mw-content-form-modal .fi-grid {
                    grid-template-columns: minmax(0, 1fr) !important;
                }
                /* Strip the compounding inline padding leak —
                   .fi-modal-content (1rem) + .fi-section (16px)
                   + .fi-section-content (26px from theme
                   `@layer`) compounded to ~80px lost out of the
                   366px inner width before Title even saw it.
                   Override with !important since Filament's
                   theme rule lives in @layer components and was
                   winning regardless of specificity. */
                .mw-content-form-modal > .fi-modal-content {
                    padding-inline: 0.5rem !important;
                }
                .mw-content-form-modal .fi-section {
                    padding-inline: 0.5rem !important;
                }
                .mw-content-form-modal .fi-section-content,
                .mw-content-form-modal .fi-sc.fi-grid {
                    padding: 0 !important;
                }

                /*
                 * P0-2: iOS zoom-on-focus. Safari zooms any
                 * input under 16px on focus and never unzooms.
                 * Force every editable form control inside the
                 * compact modal to 16px so typing doesn't kick
                 * the page sideways.
                 */
                .mw-content-form-modal input:not([type="checkbox"]):not([type="radio"]),
                .mw-content-form-modal textarea,
                .mw-content-form-modal select,
                .mw-content-form-modal .fi-input {
                    font-size: 16px;
                }

                /*
                 * P0-3: Rich-text toolbar wraps to 4-5 rows of
                 * 32px buttons inside a 189px column. Switch
                 * to horizontal scroll + 44pt min-height/width
                 * on each button so the toolbar takes ONE row
                 * of comfortable taps and the user scrolls
                 * horizontally for the rest.
                 */
                .mw-content-form-modal .fi-fo-rich-editor-toolbar,
                .mw-content-form-modal .fi-fo-rich-editor-toolbar [role="toolbar"] {
                    flex-wrap: nowrap;
                    overflow-x: auto;
                    -webkit-overflow-scrolling: touch;
                    scrollbar-width: thin;
                }
                .mw-content-form-modal .fi-fo-rich-editor-toolbar > *,
                .mw-content-form-modal .fi-fo-rich-editor-toolbar [role="toolbar"] > * {
                    flex: 0 0 auto;
                    min-height: 44px;
                    min-width: 44px;
                }

                /*
                 * P1-5: Tap targets. Bump the modal close X +
                 * filepicker close X + filepicker footer
                 * buttons to a 44×44 minimum hit area so
                 * thumbs aren't fighting the geometry.
                 */
                .mw-content-form-modal .fi-modal-close-btn,
                .mw-content-picker-modal .fi-modal-close-btn,
                .mw-dialog.mw-dialog-skin-default:has(.mw-filepicker-footer) .mw-dialog-close {
                    min-width: 44px;
                    min-height: 44px;
                }
                .mw-filepicker-footer .btn,
                .mw-filepicker-footer .btn-primary {
                    min-height: 44px;
                }

                /*
                 * P1-6: Sticky footer + safe-area-inset.
                 * Footer sat as `position: static` inside the
                 * flex column, so on iOS the home-indicator
                 * (34px) clipped the bottom button + a
                 * keyboard appearance could push SAVE off the
                 * fold. Pin it sticky inside the scrolling
                 * column AND respect the safe-area inset for
                 * notched devices.
                 */
                .mw-content-form-modal .fi-modal-footer {
                    position: sticky;
                    bottom: 0;
                    z-index: 2;
                    padding-bottom: max(0.75rem, env(safe-area-inset-bottom));
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
             * Facebook-style writing surface
             * (task-2026-05-04-bfe418). The Title section drops
             * its card border so the input reads as a writing
             * area rather than a form input. The Title <input>
             * itself uses a larger typography and a borderless
             * focus state — the modal heading "Create post" +
             * the placeholder text "What's on your mind?" do
             * the labelling work that a field label used to do.
             */
            .mw-content-form-modal .mw-fb-title-section {
                border: none;
                background: transparent;
                box-shadow: none;
                padding: 0.5rem 0.25rem 0;
            }
            .mw-content-form-modal .mw-fb-title-input {
                font-size: 1.5rem;
                line-height: 2rem;
                font-weight: 500;
                border: none;
                box-shadow: none;
                padding-inline: 0.25rem;
                background: transparent;
            }
            .mw-content-form-modal .mw-fb-title-input::placeholder {
                color: var(--gray-400, #9ca3af);
                font-weight: 400;
            }
            .mw-content-form-modal .mw-fb-title-input:focus {
                outline: none;
                box-shadow: none;
                background: transparent;
            }
            html.dark .mw-content-form-modal .mw-fb-title-input,
            .dark .mw-content-form-modal .mw-fb-title-input {
                color: var(--gray-50, #f9fafb);
            }
            html.dark .mw-content-form-modal .mw-fb-title-input::placeholder,
            .dark .mw-content-form-modal .mw-fb-title-input::placeholder {
                color: var(--gray-500, #6b7280);
            }
            /*
             * Hide the wrapper around the title input so the
             * borderless input is flush with the modal body —
             * removes the focus ring + background variant that
             * Filament applies to .fi-input-wrp.
             */
            .mw-content-form-modal .mw-fb-title-section .fi-input-wrp {
                background: transparent;
                box-shadow: none;
                outline: none;
                ring-width: 0;
            }
            /*
             * The "Add to your post" media row — strip section
             * card chrome so the upload affordance reads as a
             * tool button instead of a labelled section.
             */
            .mw-content-form-modal .mw-fb-media-section {
                border: 1px dashed var(--gray-200, #e5e7eb);
                background: transparent;
                box-shadow: none;
                padding: 0.5rem;
            }
            html.dark .mw-content-form-modal .mw-fb-media-section,
            .dark .mw-content-form-modal .mw-fb-media-section {
                border-color: var(--gray-700, #374151);
            }
            .mw-content-form-modal .mw-fb-media-section .fi-section-content-ctn,
            .mw-content-form-modal .mw-fb-media-section .fi-section-content {
                padding: 0;
            }
            .mw-content-form-modal .mw-fb-media-section .fi-fo-field-label-ctn,
            .mw-content-form-modal .mw-fb-media-section .fi-fo-field-label {
                display: none;
            }

            /*
             * "More options" accordion polish — when expanded,
             * the inner sections used to ship their own card
             * borders + heavy padding, stacking 3 levels of
             * chrome. Strip the inner section borders and
             * tighten paddings so the expanded accordion looks
             * intentional, not nested. task-2026-05-04-1f20d2.
             */
            .mw-content-form-modal .mw-fb-more-options {
                padding: 0.625rem 0.875rem;
            }
            .mw-content-form-modal .mw-fb-more-options > .fi-section-content-ctn,
            .mw-content-form-modal .mw-fb-more-options > .fi-section-content {
                padding-top: 0.5rem;
            }
            .mw-content-form-modal .mw-fb-more-options .fi-section {
                border: none;
                background: transparent;
                box-shadow: none;
                padding: 0;
            }
            .mw-content-form-modal .mw-fb-more-options .fi-section .fi-section-header {
                padding-block: 0.375rem;
                padding-inline: 0;
            }
            .mw-content-form-modal .mw-fb-more-options .fi-section .fi-section-header-heading {
                font-size: 0.8125rem;
                font-weight: 500;
                color: var(--gray-500, #6b7280);
                text-transform: uppercase;
                letter-spacing: 0.025em;
            }
            html.dark .mw-content-form-modal .mw-fb-more-options .fi-section .fi-section-header-heading,
            .dark .mw-content-form-modal .mw-fb-more-options .fi-section .fi-section-header-heading {
                color: var(--gray-400, #9ca3af);
            }
            .mw-content-form-modal .mw-fb-more-options .fi-section-content-ctn,
            .mw-content-form-modal .mw-fb-more-options .fi-section-content {
                padding: 0;
            }
            /* Tighter parent-page tree inside More options:
               smaller search box, smaller tree-item rows. */
            .mw-content-form-modal .mw-fb-more-options .mw-tree-wrapper {
                padding: 0;
            }
            .mw-content-form-modal .mw-fb-more-options .mw-tree-wrapper input[type="search"],
            .mw-content-form-modal .mw-fb-more-options .mw-tree-wrapper input[type="text"] {
                font-size: 0.8125rem;
                padding-block: 0.375rem;
            }
            .mw-content-form-modal .mw-fb-more-options .mw-tree-item {
                padding-block: 0.25rem !important;
                font-size: 0.8125rem;
            }
            /* RichEditor inside More options — minimize the
               toolbar height + content padding so the rich-text
               box doesn't dominate the accordion. */
            .mw-content-form-modal .mw-fb-more-options .fi-fo-rich-editor {
                font-size: 0.875rem;
            }
            .mw-content-form-modal .mw-fb-more-options .fi-fo-rich-editor .ProseMirror {
                min-height: 6rem;
                padding: 0.5rem;
            }
            .mw-content-form-modal .mw-fb-more-options .fi-fo-field-wrp-label {
                font-size: 0.8125rem;
                color: var(--gray-600, #4b5563);
                font-weight: 500;
            }
            html.dark .mw-content-form-modal .mw-fb-more-options .fi-fo-field-wrp-label,
            .dark .mw-content-form-modal .mw-fb-more-options .fi-fo-field-wrp-label {
                color: var(--gray-300, #d1d5db);
            }

            /*
             * Tighten the upfront stack so the modal stays under
             * ~480px tall without scrolling — keeps the SAVE
             * button reachable on smaller viewports.
             * task-2026-05-04-2cd250.
             */
            .mw-content-form-modal > .fi-modal-content {
                padding: 0.875rem 1rem 0.5rem;
            }
            .mw-content-form-modal .fi-fo-component-ctn,
            .mw-content-form-modal .fi-fo-component-ctn > .fi-fo-component {
                gap: 0.625rem;
            }
            /* Smaller media drop-zone tile. */
            .mw-content-form-modal .mw-fb-media-section {
                padding: 0.5rem 0.75rem;
            }
            .mw-content-form-modal .mw-fb-media-section .filepond--root,
            .mw-content-form-modal .mw-fb-media-section [class*="filepond--root"] {
                min-height: 2.5rem;
            }

            /*
             * task-2026-05-05-899bf8: cap the UPFRONT RichEditor body
             * (post + product upfront body, page upfront page-content
             * editor) at ~7rem ProseMirror so Title + Media + Body +
             * Excerpt fits under ~480px tall on a 1280×800 viewport
             * without internal scrolling. The `.mw-fb-more-options`
             * version above already caps to 6rem — this is the same
             * idea applied to the upfront slot. Users who need more
             * room click "Show all options" to reach the full admin
             * form. The `:not(.mw-fb-more-options ...)` guard keeps
             * the More-options block at 6rem (it has its own rule).
             */
            .mw-content-form-modal .fi-fo-rich-editor:not(.mw-fb-more-options *) .ProseMirror {
                min-height: 5rem;
                max-height: 9rem;
                overflow-y: auto;
            }
            /* Tighten section padding so the upfront stack doesn't
               accumulate ~24px of empty padding per section. */
            .mw-content-form-modal .fi-section {
                padding: 0.625rem 0.875rem;
            }
            .mw-content-form-modal .fi-section-content {
                padding: 0;
            }
            /* Drop the Title section's framing chrome so the giant
               input sits flush with the modal padding instead of
               inside a nested rounded box (the Title field used to
               look like an inset card-within-a-card). !important
               needed because Filament's `.fi-section` rules ship
               background/border tokens at higher specificity. */
            .mw-content-form-modal .fi-section:has(.mw-fb-title-input) {
                background: transparent !important;
                border: none !important;
                box-shadow: none !important;
                padding: 0 !important;
            }
            /* Excerpt textarea in the upfront body section was 80px+
               by default — shrink to 2 rows so Title + Media + Body
               + Excerpt fits without a scroll on 1280×800. */
            .mw-content-form-modal textarea[name*="description"],
            .mw-content-form-modal textarea[wire\\:model$="description"] {
                min-height: 3rem;
                max-height: 5rem;
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
            .mw-content-form-modal .fi-modal-header,
            .mw-module-settings-live-edit-modal .fi-modal-header {
                cursor: move;
                user-select: none;
            }
            .mw-content-form-modal.ui-draggable-dragging,
            .mw-content-form-modal.ui-draggable-dragging .fi-modal-header,
            .mw-module-settings-live-edit-modal.ui-draggable-dragging,
            .mw-module-settings-live-edit-modal.ui-draggable-dragging .fi-modal-header {
                cursor: grabbing;
            }
            .mw-content-form-modal.ui-draggable-dragging,
            .mw-module-settings-live-edit-modal.ui-draggable-dragging {
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

            /*
             * `.mw-dialog` Select-image polish
             * (task-2026-05-04-6bd361). Customer-persona audit
             * showed the modal's X close was nearly invisible
             * (opacity .3 over white), the OK button looked
             * broken when disabled, the Cancel was a naked text
             * link, the modal had no header divider, and dark
             * mode wasn't handled at all. Scoped via
             * `:has(.mw-filepicker-footer)` so non-media-browser
             * dialogs (confirm modals, video skin, module
             * settings) are untouched.
             */
            .mw-dialog.mw-dialog-skin-default:has(.mw-filepicker-footer) {
                box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.45),
                            0 0 0 1px rgba(0, 0, 0, 0.08);
                border-radius: 8px;
            }
            .mw-dialog.mw-dialog-skin-default:has(.mw-filepicker-footer) .mw-dialog-close {
                width: 36px;
                height: 36px;
                border-radius: 999px;
                cursor: pointer;
            }
            .mw-dialog.mw-dialog-skin-default:has(.mw-filepicker-footer) .mw-dialog-close::after {
                opacity: 1;
                background-size: 14px auto;
            }
            .mw-dialog.mw-dialog-skin-default:has(.mw-filepicker-footer) .mw-dialog-close:hover {
                background-color: rgba(0, 0, 0, 0.08);
            }
            html.dark .mw-dialog.mw-dialog-skin-default:has(.mw-filepicker-footer) .mw-dialog-close:hover,
            .dark .mw-dialog.mw-dialog-skin-default:has(.mw-filepicker-footer) .mw-dialog-close:hover {
                background-color: rgba(255, 255, 255, 0.08);
            }
            /* Header divider so the user perceives the modal as a
               container, not a free-floating title. */
            .mw-dialog.mw-dialog-skin-default:has(.mw-filepicker-footer) .mw-dialog-header {
                border-bottom: 1px solid #e5e7eb;
            }
            html.dark .mw-dialog.mw-dialog-skin-default:has(.mw-filepicker-footer) .mw-dialog-header,
            .dark .mw-dialog.mw-dialog-skin-default:has(.mw-filepicker-footer) .mw-dialog-header {
                border-bottom-color: #374151;
            }
            /* Cancel button — give it real button chrome so it
               reads as a peer of OK, not a link. */
            .mw-filepicker-footer .btn:not(.btn-primary) {
                border: 1px solid #d1d5db;
                padding: 0.5rem 1rem;
                border-radius: 6px;
                color: #374151;
                font-weight: 500;
                background: #ffffff;
            }
            .mw-filepicker-footer .btn:not(.btn-primary):hover {
                background: #f9fafb;
            }
            html.dark .mw-filepicker-footer .btn:not(.btn-primary),
            .dark .mw-filepicker-footer .btn:not(.btn-primary) {
                border-color: #4b5563;
                color: #f3f4f6;
                background: #1f2937;
            }
            html.dark .mw-filepicker-footer .btn:not(.btn-primary):hover,
            .dark .mw-filepicker-footer .btn:not(.btn-primary):hover {
                background: #374151;
            }
            /* OK button — readable disabled state and bigger hit
               target. The default rgba(120,169,255,.3) made it
               look broken. */
            .mw-filepicker-footer .btn-primary {
                min-width: 5rem;
                padding: 0.5rem 1rem;
                border-radius: 6px;
            }
            .mw-filepicker-footer .btn-primary:disabled,
            .mw-filepicker-footer .btn-primary[disabled] {
                background-color: #e5e7eb !important;
                color: #6b7280 !important;
                opacity: 1 !important;
                cursor: not-allowed;
            }
            html.dark .mw-filepicker-footer .btn-primary:disabled,
            html.dark .mw-filepicker-footer .btn-primary[disabled],
            .dark .mw-filepicker-footer .btn-primary:disabled,
            .dark .mw-filepicker-footer .btn-primary[disabled] {
                background-color: #374151 !important;
                color: #9ca3af !important;
            }
            /* Dark-mode body fill so the modal stops being a
               white slab on a dark canvas. */
            html.dark .mw-dialog.mw-dialog-skin-default:has(.mw-filepicker-footer) .mw-dialog-holder,
            html.dark .mw-dialog.mw-dialog-skin-default:has(.mw-filepicker-footer) .mw-dialog-header,
            .dark .mw-dialog.mw-dialog-skin-default:has(.mw-filepicker-footer) .mw-dialog-holder,
            .dark .mw-dialog.mw-dialog-skin-default:has(.mw-filepicker-footer) .mw-dialog-header {
                background-color: #1f2937;
                color: #f3f4f6;
            }
            html.dark .mw-dialog.mw-dialog-skin-default:has(.mw-filepicker-footer) .mw-dialog-close::after,
            .dark .mw-dialog.mw-dialog-skin-default:has(.mw-filepicker-footer) .mw-dialog-close::after {
                /* Invert the black SVG to white so it shows on
                   the dark header. */
                filter: invert(1) brightness(2);
            }
            /* Tab strip — kill the lingering box-shadow on the
               previously-active tab when user switches. */
            .mw-filepicker-footer-wrapper .form-control-live-edit-label-wrapper a:not(.active) {
                box-shadow: none !important;
            }

            /*
             * Drunk-designer audit fixes (task-2026-05-04-a8d5bb).
             * Unified design tokens scoped to the live-edit Add
             * Content modal + picker — consistent radius/shadow/
             * heading/focus across the surface.
             */
            :root {
                --mw-radius-md: 8px;
                --mw-radius-lg: 12px;
                --mw-shadow-modal: 0 25px 50px -12px rgba(0, 0, 0, 0.18),
                                   0 0 0 1px rgba(0, 0, 0, 0.04);
                --mw-accent-ring: rgb(59, 130, 246);
            }

            /* #2 Modal heading collapse — promote the title to a
               clear top of the type ladder. Filament's base
               `.fi-modal-heading` rule is inside an
               `@layer components` block in the theme css, which
               makes its tokens win unless we either match the
               cascade layer OR explicitly outrank with
               `!important` — the latter is the smaller surface
               here. */
            .mw-content-form-modal .fi-modal-heading,
            .mw-content-picker-modal .fi-modal-heading {
                font-size: 1.125rem !important;
                line-height: 1.5rem !important;
                font-weight: 600 !important;
                letter-spacing: -0.005em;
            }
            /* Section subheadings sit one rung below — small,
               weighted, all-caps tracking. The "MORE OPTIONS"
               heading already reads this way; align all section
               headings inside the compact form to match. */
            .mw-content-form-modal .fi-section-header-heading {
                font-size: 0.75rem;
                font-weight: 600;
                letter-spacing: 0.05em;
                text-transform: uppercase;
                color: var(--gray-500, #6b7280);
            }
            html.dark .mw-content-form-modal .fi-section-header-heading,
            .dark .mw-content-form-modal .fi-section-header-heading {
                color: var(--gray-400, #9ca3af);
            }

            /* #5 Radius war — unify the radii inside the modal
               surface. The modal-window itself stays at its
               existing radius (Filament tier); inner sections,
               cards, and the picker tiles get the shared token. */
            .fi-modal:not(.fi-width-screen) .fi-modal-window.mw-content-form-modal,
            .fi-modal:not(.fi-width-screen) .fi-modal-window.mw-content-picker-modal {
                border-radius: var(--mw-radius-lg);
            }
            .mw-content-form-modal .fi-section,
            .mw-content-picker-modal .mw-add-content-modal-action-wrapper {
                border-radius: var(--mw-radius-md);
            }

            /* #6 Shadow soup — single elevation token for the
               outer modal; inner sections and picker tiles get
               NO shadow (they're already inside an elevated
               container). */
            .fi-modal:not(.fi-width-screen) .fi-modal-window.mw-content-form-modal,
            .fi-modal:not(.fi-width-screen) .fi-modal-window.mw-content-picker-modal {
                box-shadow: var(--mw-shadow-modal);
            }
            .mw-content-form-modal .fi-section,
            .mw-content-picker-modal .mw-add-content-modal-action-wrapper {
                box-shadow: none;
            }

            /* #8 Restore focus rings — Filament's default ring
               was being suppressed by the theme. Bring it back
               on the title input, picker cards, and close X. */
            .mw-content-form-modal .fi-input:focus-visible,
            .mw-content-form-modal .mw-fb-title-input:focus-visible,
            .mw-content-form-modal input:focus-visible,
            .mw-content-form-modal textarea:focus-visible,
            .mw-content-form-modal select:focus-visible,
            .mw-content-picker-modal .mw-add-content-modal-action-wrapper:focus-visible,
            .mw-content-form-modal .fi-modal-close-btn:focus-visible,
            .mw-content-picker-modal .fi-modal-close-btn:focus-visible {
                outline: 2px solid var(--mw-accent-ring);
                outline-offset: 2px;
                box-shadow: none;
            }

            /* #10 (CSS half) — strip the Title section's
               border + padding so the input doesn't sit in dead
               chrome. The PHP swap to a borderless Group lives
               in ContentResource::compactTitleOnlySection. */
            .mw-content-form-modal .mw-fb-title-section {
                border: none !important;
                background: transparent !important;
                padding: 0 !important;
                box-shadow: none !important;
            }

            /* task-2026-05-04-a11y — respect prefers-reduced-motion.
               Picker cards + Filament modal animations + the section
               transition-duration:150ms class can trigger vestibular
               disorders. WCAG 2.3.3 Animation from Interactions.
               We disable transitions/animations inside the live-edit
               modal stack; users who opted out of motion will still
               see hover state changes (instant) but no slide/fade. */
            @media (prefers-reduced-motion: reduce) {
                .mw-content-picker-modal .mw-add-content-modal-action-wrapper,
                .mw-content-picker-modal .mw-add-content-modal-action-wrapper *,
                .mw-content-form-modal .fi-modal-window,
                .mw-content-form-modal .fi-section,
                .mw-content-form-modal .fi-tabs-tab,
                .mw-content-form-modal .fi-input,
                .mw-content-form-modal .fi-btn {
                    transition-duration: 0.001ms !important;
                    animation-duration: 0.001ms !important;
                    animation-iteration-count: 1 !important;
                }
            }

            /*
             * UX-engineer audit (task-2026-05-04-39767a) —
             * polish quick wins.
             *
             * P1-4: drop blanket `text-transform: uppercase
             * !important` from `.fi-btn.fi-color-success`. The
             * theme's `microweber-theme-v3.scss:799` shouts
             * "SAVE" / "NEW POST" / "SAVE CHANGES" / "SELECT
             * ALL" / "DELETE SELECTED" everywhere — visual
             * rhythm in every footer is jarring. Override
             * scoped to the live-edit modal stack so other
             * Filament areas keep their existing uppercase
             * convention until the SCSS source is rebuilt.
             *
             * P2-9: drop `text-transform: uppercase` from the
             * compact-form section headings (`.fi-section-
             * header-heading`). Friendly placeholder "What's
             * the post about?" sat next to "WHERE TO PUT IT"
             * — two voices on one screen. Keep the small
             * weight + tracking but in title case.
             */
            .mw-content-form-modal .fi-btn.fi-color-success,
            .mw-content-picker-modal .fi-btn.fi-color-success,
            .mw-module-settings-live-edit-modal .fi-btn.fi-color-success {
                text-transform: none !important;
            }
            .mw-content-form-modal .fi-section-header-heading,
            .mw-content-picker-modal .fi-section-header-heading {
                text-transform: none !important;
                letter-spacing: -0.005em !important;
            }

            /*
             * task-2026-05-31 / AI-817 follow-up: sr-only label visibility.
             * The .mw-fb-title-wrap class hides the label visually while
             * keeping it readable by screen readers (WCAG 3.3.2 Level A).
             * Mirrors the canonical rule in
             * src/MicroweberPackages/Filament/resources/views/filament/components/layout/live-edit-module-settings.blade.php
             * but scoped to the live-edit iframe page where the
             * addImageAction modal renders. ->hiddenLabel() removes the
             * element from the accessibility tree entirely.
             */
            .mw-fb-title-wrap > .fi-fo-field-lbl,
            .mw-fb-title-wrap > .fi-fo-field-lbl-ctn,
            .mw-fb-title-wrap label.fi-fo-field-lbl,
            .mw-fb-title-wrap > label {
                position: absolute !important;
                width: 1px !important;
                height: 1px !important;
                padding: 0 !important;
                margin: -1px !important;
                overflow: hidden !important;
                clip: rect(0, 0, 0, 0) !important;
                white-space: nowrap !important;
                border: 0 !important;
            }
        </style>


        {{-- task-2026-05-17-49266a / AI-710 CHANGE — designer
             verified the right-rail Vue button rename ("Element
             styles") shipped clean but the rendered panel h3
             STILL served "Element Style Editor" at runtime.
             Root cause: this STATIC <h3> in iframe-page.blade.php
             is not replaced by any Vue mount — the Vue
             StyleEditor.vue's own <h3> renders inside its own
             card component, not inside this container. The
             previous AI-710 ship updated the Vue h3 source but
             missed this Blade-side static h3. Renaming here so
             both code paths read "Element styles". --}}
        <div id="mw-element-style-editor-app-container">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <h3 class="fs-2 font-weight-bold">Element styles</h3>
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
