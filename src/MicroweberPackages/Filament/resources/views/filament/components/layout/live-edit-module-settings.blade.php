@php
    use Filament\Support\Enums\Width as MaxWidth;

    $navigation = filament()->getNavigation();
@endphp

<x-filament-panels::layout.base :livewire="$livewire">

    @if (method_exists($livewire, 'showTopBar') && $livewire->showTopBar())



    @endif

    @php
        $iframeClass = '';
        $isIframe = false;

        if (request()->header('Sec-Fetch-Dest') === 'iframe'
            || request()->boolean('iframe')
            || request()->boolean('live_edit')
        ) {
            $iframeClass = 'mw-live-edit-module-settings-iframe';
            $isIframe = true;
        }
    @endphp


    <main class="mw-live-edit-page-wrapper {{ $iframeClass }}" id="mw-live-edit-page-wrapper" role="main" aria-label="Module settings">
        {{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::CONTENT_START, scopes: $livewire->getRenderHookScopes()) }}

        {{ $slot }}

        {{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::CONTENT_END, scopes: $livewire->getRenderHookScopes()) }}


        @if ($isIframe)

            <script>
                if (self !== top) {

                    document.addEventListener("DOMContentLoaded", () => {
                        if (typeof Livewire === 'undefined') {
                            return;
                        }

                        Livewire.hook('message.sent', (message, component) => {
                            $('body').addClass('mw-livewire-loading')
                        })

                        Livewire.hook('message.processed', (message, component) => {
                            $('body').removeClass('mw-livewire-loading')
                        })
                        Livewire.hook('message.failed', (message, component) => {
                            $('body').removeClass('mw-livewire-loading')
                        })
                        Livewire.hook('element.updated', (message, component) => {
                            $('body').removeClass('mw-livewire-loading')
                        })

                        // remove the class after 5 seconds, just in case
                        // if there is an error and the class is not removed
                        setTimeout(function () {
                            $('body').removeClass('mw-livewire-loading')
                        }, 5000);

                        // Catch mw-option-saved dispatched by Livewire components
                        // in this iframe (e.g. MenusList) and reload the matching
                        // module in the parent canvas. The parent-frame listeners
                        // (admin-filament.js / livewire-hooks-reload-module.js)
                        // cannot hear events from a child iframe's Livewire instance.
                        // ContentTableList CreateAction/EditAction/DeleteAction
                        // dispatch this Livewire event after successful
                        // table-row save. Forward it to the parent
                        // window so the live-edit canvas refreshes the
                        // host page (which renders the posts/products
                        // listing being edited) — without this, the
                        // user added/edited a post via "Edit Posts →
                        // New post", the row landed in the DB, the
                        // slideOver iframe re-rendered the table, but
                        // the host page behind the slideOver kept
                        // showing the OLD list — task-2026-05-02-99f90c.
                        Livewire.on('liveEditModuleTableActionSaved', function () {
                            try {
                                if (top && top.window && typeof top.window.dispatchEvent === 'function') {
                                    top.window.dispatchEvent(new Event('liveEditModuleTableActionSaved'));
                                }
                            } catch (e) { /* cross-origin or top missing */ }
                        });

                        Livewire.on('mw-option-saved', function (params) {
                            var optionGroup = params.optionGroup || (Array.isArray(params) && params[0] && params[0].optionGroup);
                            if (!optionGroup) return;

                            var topWin = top;
                            if (!topWin || !topWin.mw) return;

                            try {
                                var topMw = topWin.mw.top ? topWin.mw.top() : topWin.mw;
                                if (!topMw || !topMw.app || !topMw.app.canvas) {
                                    // no canvas — plain admin page
                                    topWin.mw.reload_module && topWin.mw.reload_module('#' + optionGroup);
                                    return;
                                }
                                var canvasWindow = topMw.app.canvas.getWindow();
                                if (!canvasWindow || !canvasWindow.mw) {
                                    topMw.reload_module_everywhere && topMw.reload_module_everywhere('#' + optionGroup);
                                    return;
                                }
                                // First try: if the canvas module is itself a Livewire
                                // component, refresh it without a full page reload.
                                var canvasDocument = topMw.app.canvas.getDocument();
                                var refreshed = false;
                                if (canvasWindow.Livewire) {
                                    var el = canvasDocument.querySelector('#' + optionGroup + '> [wire\\:id]');
                                    if (el) {
                                        var wireId = el.getAttribute('wire:id');
                                        var component = canvasWindow.Livewire.find(wireId);
                                        if (component) {
                                            component.$refresh();
                                            refreshed = true;
                                        }
                                    }
                                }
                                if (!refreshed) {
                                    canvasWindow.mw.reload_module('#' + optionGroup);
                                }
                            } catch (e) {
                                // guard against cross-origin or missing context
                            }
                        });

                        // fi-modal hit-test escape — task-2026-06-05-mnumodal-teleport.
                        // Inside the live-edit slide-over this settings page renders as
                        // a NESTED iframe, and a module's Filament action modal (e.g. the
                        // Menu "Add menu item" / "Edit" create-edit dialog) mounts DEEP
                        // inside the settings form schema. In that nested position the
                        // modal's fixed .fi-modal-window-ctn is composited away from its
                        // layout box: elementFromPoint at the modal inputs returns the
                        // bare body and REAL CLICKS never reach the fields — the dialog
                        // renders but is completely un-usable (verified in-browser; a real
                        // mouse click at an input's exact coordinates left activeElement on
                        // body). This is the same hit-test trap fixed for the canvas in
                        // iframe-page.blade.php, where every CSS-only approach
                        // (pointer-events / isolation / overflow) was proven to fail. The
                        // proven fix is to hoist .fi-modal up to be a direct child of
                        // <body> (Filament's canonical, un-nested modal location), escaping
                        // the form ancestors.
                        //
                        // Robust against MULTIPLE / NESTED / STACKED modals and the
                        // two-Livewire-component layout (the settings Page + the embedded
                        // table/list each commit independently):
                        //   1. Hoist EVERY modal still nested in the settings wrapper out to
                        //      <body>, in document order, so a modal that itself opens a
                        //      second modal (link picker, media browser, confirm) keeps its
                        //      stack order — querySelectorAll, not querySelector.
                        //   2. Prune the hoisted copies ONLY when NO Filament action is
                        //      mounted anywhere in this iframe (i.e. every dialog is closed).
                        //      Mounted-action state is the authoritative "is a modal open"
                        //      signal — NOT inline display, which is transiently `none` while
                        //      Alpine's x-show animates a freshly-opened modal in (pruning on
                        //      display removed dialogs the instant they opened). Because we
                        //      never blanket-remove while something is mounted, a commit from
                        //      the OTHER component cannot yank an open, already-hoisted dialog
                        //      out from under the user; and the unmount commit that closes the
                        //      last dialog flips this to "nothing mounted" so the orphaned
                        //      <body> copy is cleaned up instead of lingering.
                        var mwAnyActionMounted = function () {
                            var keys = ['mountedActions', 'mountedTableActions',
                                'mountedFormComponentActions', 'mountedInfolistActions',
                                'mountedTableBulkActions'];
                            try {
                                var comps = (window.Livewire && window.Livewire.all) ? window.Livewire.all() : [];
                                return comps.some(function (c) {
                                    var w = c.$wire || c;
                                    return keys.some(function (k) {
                                        var v;
                                        try { v = w.get ? w.get(k) : null; } catch (e) { v = null; }
                                        return Array.isArray(v) ? v.length > 0 : (v !== null && v !== undefined && v !== false);
                                    });
                                });
                            } catch (e) {
                                return true; // fail-safe: assume a modal is open, never prune blindly
                            }
                        };
                        // A modal is only safe to prune when it is BOTH not backed by a
                        // mounted action AND has no visibly-rendered window. The second
                        // signal protects any future non-action Filament modal component
                        // (the x-filament modal blade tag, driven by Alpine x-show and NOT
                        // tracked in mountedActions) from being yanked while it is open, and
                        // double-guards against an exotic mounted-action key that
                        // mwAnyActionMounted() might not enumerate.
                        // NOTE: never write a literal "less-than x-..." blade component tag
                        // anywhere in this file (even inside a JS comment) — Blade parses it
                        // as a real component and breaks compilation (task-2026-06-05).
                        var mwModalHasVisibleWindow = function (m) {
                            var w = m.querySelector('.fi-modal-window');
                            return !!(w && (w.offsetParent !== null || w.getClientRects().length > 0));
                        };

                        var mwHoistSettingsModal = function () {
                            var wrapper = document.querySelector('.mw-live-edit-page-wrapper');
                            if (wrapper) {
                                wrapper.querySelectorAll('.fi-modal').forEach(function (m) {
                                    document.body.appendChild(m);
                                });
                            }
                            if (!mwAnyActionMounted()) {
                                document.querySelectorAll('body > .fi-modal').forEach(function (m) {
                                    if (!mwModalHasVisibleWindow(m)) { m.remove(); }
                                });
                            }
                        };
                        if (typeof Livewire !== 'undefined' && typeof Livewire.hook === 'function') {
                            Livewire.hook('commit', function (ref) {
                                ref.succeed(function () { requestAnimationFrame(mwHoistSettingsModal); });
                            });
                        }

                        // task-2026-06-05-modsettings-save: save the create/edit
                        // form from a teleported module-settings modal. The hoist
                        // above moves the modal to <body>, OUTSIDE the embedded
                        // Livewire component's wire:id subtree, so the modal's
                        // form wire:submit="callMountedAction" has no component to
                        // bind to -- the footer Save button (type=submit) fired a
                        // native submit event Livewire never handled and the row
                        // could not be saved. Catch the orphaned submit here, find
                        // the Livewire component that actually has the matching
                        // mounted action, and invoke it directly (its $wire is
                        // intact; wire:model already synced the field values).
                        // Forms still inside a wire:id ancestor are left untouched.
                        var mwCallKeyByName = {
                            callMountedAction: 'mountedActions',
                            callMountedTableAction: 'mountedTableActions',
                            callMountedTableBulkAction: 'mountedTableBulkActions',
                            callMountedFormComponentAction: 'mountedFormComponentActions'
                        };
                        document.addEventListener('submit', function (e) {
                            var f = e.target;
                            if (!f || f.tagName !== 'FORM') { return; }
                            var s = f.getAttribute('wire:submit.prevent') || f.getAttribute('wire:submit') || '';
                            if (s.indexOf('callMounted') !== 0) { return; }
                            if (f.closest('[wire\\:id]')) { return; }
                            var key = mwCallKeyByName[s];
                            if (!key) { return; }
                            var comps = (window.Livewire && window.Livewire.all) ? window.Livewire.all() : [];
                            for (var i = 0; i < comps.length; i++) {
                                var w = comps[i].$wire || comps[i];
                                var v;
                                try { v = w.get ? w.get(key) : null; } catch (err) { v = null; }
                                if (Array.isArray(v) && v.length > 0) {
                                    e.preventDefault();
                                    try { w[s](); } catch (err2) {}
                                    return;
                                }
                            }
                        }, true);
                    });
                    if(self.frameElement && mw.tools && mw.tools.iframeAutoHeight){
                        mw.tools.iframeAutoHeight(self.frameElement);
                    }

                }
            </script>

        @endif

    </main>

    <style>
        /*
         * `.mw-content-form-modal` is applied via
         * extraModalWindowAttributes on ContentTableList's
         * CreateAction/EditAction. Three scoped overrides:
         * (1) restore the modal backdrop tint that the project's
         * filament theme globally forces to bg-transparent,
         * (2) cap the modal at viewport height with an internal
         * scroll region so long forms don't bleed past the footer
         * (task-2026-05-04-b7eee8 — same fix mirrored from
         * iframe-page.blade.php),
         * (3) keep the footer styled (background, border) so it
         * reads as a footer rather than blending into the form.
         */
        .fi-modal:has(> .fi-modal-window-ctn .mw-content-form-modal) > .fi-modal-close-overlay {
            background-color: rgba(0, 0, 0, 0.55) !important;
        }
        /*
         * task-2026-06-05-mnu-modal — Generic module-settings create/edit modal
         * chrome. Module panels OTHER than Content (the Menu Add-menu-item / Edit
         * dialog, and any LiveEditModuleSettings page that renders its own
         * filament-actions modals create/edit action) carry NO marker class, so
         * the per-class backdrop and sizing rules above never reached them. On
         * this surface the project filament theme forces the modal close overlay
         * to a transparent background and leaves the window container top-aligned,
         * so those dialogs opened with no backdrop dim and uncentred — a bare
         * inline box over the settings list (the add-menu-item-looks-broken
         * report). Restore the two defaults for every non-slide-over modal on the
         * module-settings surface. Slide-over modals are excluded so the settings
         * panel itself keeps its right-edge alignment. This style block only loads
         * inside the module-settings layout, so it cannot reach full-page admin
         * modals (which already render correct chrome from the panel CSS).
         */
        body.fi-panel-admin .fi-modal:not(.fi-modal-slide-over) .fi-modal-close-overlay {
            background-color: rgba(0, 0, 0, 0.5) !important;
        }
        body.fi-panel-admin .fi-modal:not(.fi-modal-slide-over) .fi-modal-window-ctn {
            align-items: center !important;
        }
        /*
         * Live-edit submodal full-screen slide sheet — task-2026-06-05-mnumodal-slide.
         * Inside the live-edit slide-over the module-settings page is a nested iframe,
         * and its create/edit "submodal" (Add post, Custom field, Accordion item,
         * New tab, ...) is teleported to be a direct child of body (see the hoist hook
         * above). A small CENTRED dialog — or a narrow right-docked slide-over — inside
         * the already-narrow settings panel is cramped and reads as a stray popup, so
         * EVERY hoisted submodal (centred dialog AND native slide-over alike) instead
         * slides in from the inline-end edge and fills the full panel viewport, giving
         * the form maximum room. Scoped with the child combinator to `body > .fi-modal`
         * so it ONLY affects the teleported (in-iframe) copy — the standalone
         * /admin/<module>-module-settings page keeps its centred dialog. Inside the
         * iframe the only `body > .fi-modal` nodes are these inner action modals, so
         * the rule never touches the outer settings panel (that lives in the parent
         * frame, beyond this stylesheet's reach).
         */
        body.fi-panel-admin > .fi-modal .fi-modal-window {
            position: fixed !important;
            inset: 0 !important;
            width: 100vw !important;
            max-width: 100vw !important;
            height: 100vh !important;
            max-height: 100vh !important;
            margin: 0 !important;
            border-radius: 0 !important;
            display: flex !important;
            flex-direction: column !important;
            animation: mw-submodal-slide-in 0.28s cubic-bezier(0.32, 0.72, 0, 1) both;
        }
        body.fi-panel-admin > .fi-modal .fi-modal-content {
            flex: 1 1 auto !important;
            overflow-y: auto !important;
            max-height: none !important;
        }
        @keyframes mw-submodal-slide-in {
            from { transform: translateX(100%); }
            to { transform: translateX(0); }
        }
        @media (prefers-reduced-motion: reduce) {
            body.fi-panel-admin > .fi-modal .fi-modal-window {
                animation: none !important;
            }
        }
        /*
         * Match the (0,4,0) specificity of Filament's base
         * `.fi-modal:not(.fi-width-screen) .fi-modal-window:not(...)`
         * rule so this overrides without !important — the drag
         * handler in iframe-page.blade.php still wins via inline
         * style at runtime.
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

        /* Mobile-friendly (mirrored from iframe-page.blade.php).
           task-2026-05-04-76275d. */
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

            /* Mobile-designer audit fixes (mirrored from
               iframe-page.blade.php). task-2026-05-04-0c2964. */
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
            .mw-content-form-modal input:not([type="checkbox"]):not([type="radio"]),
            .mw-content-form-modal textarea,
            .mw-content-form-modal select,
            .mw-content-form-modal .fi-input {
                font-size: 16px;
            }
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
         * (task-2026-05-04-e0fe54). Mirrored from
         * iframe-page.blade.php so per-module Items-list
         * Create/Edit modals (rendered in a separate iframe)
         * pick up the same chrome.
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
        .mw-content-form-modal .fi-fo-field-wrp {
            gap: 0.375rem;
        }

        /*
         * Facebook-style writing surface
         * (task-2026-05-04-bfe418). Mirrored from
         * iframe-page.blade.php so per-module Items-list
         * Create/Edit modals get the same skin.
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
        .mw-content-form-modal .mw-fb-title-section .fi-input-wrp {
            background: transparent;
            box-shadow: none;
            outline: none;
        }
        /*
         * task-2026-05-17-04f015 / AI-817 — WCAG 3.3.2 Level A.
         * The Facebook-style writing surface intentionally hides
         * the field label visually (big-type placeholder carries
         * the affordance). BEFORE AI-817 we used Filament's
         * `->hiddenLabel()` which removed the label from the DOM
         * entirely — AT users heard "edit text, blank" with no
         * field name. AFTER: `->label('Title')` is restored and
         * this sr-only rule visually-hides the rendered label
         * while keeping it programmatically associated with the
         * input for screen readers / Dragon NaturallySpeaking /
         * voice-control software.
         *
         * Shared by ContentResource compactTitleOnlySection (Post +
         * Page + Product via CreateContent inheritance) AND
         * CategoryResource Slice B sibling — both surfaces tag
         * the field wrapper with .mw-fb-title-wrap.
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

        /* "More options" accordion polish + upfront stack
           tightening (mirrored from iframe-page.blade.php).
           task-2026-05-04-1f20d2 + task-2026-05-04-2cd250. */
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
        .mw-content-form-modal > .fi-modal-content {
            padding: 0.875rem 1rem 0.5rem;
        }
        .mw-content-form-modal .fi-fo-component-ctn,
        .mw-content-form-modal .fi-fo-component-ctn > .fi-fo-component {
            gap: 0.625rem;
        }
        .mw-content-form-modal .mw-fb-media-section {
            padding: 0.5rem 0.75rem;
        }
        .mw-content-form-modal .mw-fb-media-section .filepond--root,
        .mw-content-form-modal .mw-fb-media-section [class*="filepond--root"] {
            min-height: 2.5rem;
        }

        /* mw.dialog Select-image polish (mirrored from
           iframe-page.blade.php). task-2026-05-04-6bd361. */
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
        .mw-dialog.mw-dialog-skin-default:has(.mw-filepicker-footer) .mw-dialog-header {
            border-bottom: 1px solid #e5e7eb;
        }
        html.dark .mw-dialog.mw-dialog-skin-default:has(.mw-filepicker-footer) .mw-dialog-header,
        .dark .mw-dialog.mw-dialog-skin-default:has(.mw-filepicker-footer) .mw-dialog-header {
            border-bottom-color: #374151;
        }
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
        html.dark .mw-dialog.mw-dialog-skin-default:has(.mw-filepicker-footer) .mw-dialog-holder,
        html.dark .mw-dialog.mw-dialog-skin-default:has(.mw-filepicker-footer) .mw-dialog-header,
        .dark .mw-dialog.mw-dialog-skin-default:has(.mw-filepicker-footer) .mw-dialog-holder,
        .dark .mw-dialog.mw-dialog-skin-default:has(.mw-filepicker-footer) .mw-dialog-header {
            background-color: #1f2937;
            color: #f3f4f6;
        }
        html.dark .mw-dialog.mw-dialog-skin-default:has(.mw-filepicker-footer) .mw-dialog-close::after,
        .dark .mw-dialog.mw-dialog-skin-default:has(.mw-filepicker-footer) .mw-dialog-close::after {
            filter: invert(1) brightness(2);
        }
        .mw-filepicker-footer-wrapper .form-control-live-edit-label-wrapper a:not(.active) {
            box-shadow: none !important;
        }

        /* Drunk-designer audit fixes (mirrored from
           iframe-page.blade.php). task-2026-05-04-a8d5bb. */
        :root {
            --mw-radius-md: 8px;
            --mw-radius-lg: 12px;
            --mw-shadow-modal: 0 25px 50px -12px rgba(0, 0, 0, 0.18),
                               0 0 0 1px rgba(0, 0, 0, 0.04);
            --mw-accent-ring: rgb(59, 130, 246);
        }
        .mw-content-form-modal .fi-modal-heading,
        .mw-content-picker-modal .fi-modal-heading {
            font-size: 1.125rem !important;
            line-height: 1.5rem !important;
            font-weight: 600 !important;
            letter-spacing: -0.005em;
        }
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
        .fi-modal:not(.fi-width-screen) .fi-modal-window.mw-content-form-modal,
        .fi-modal:not(.fi-width-screen) .fi-modal-window.mw-content-picker-modal {
            border-radius: var(--mw-radius-lg);
            box-shadow: var(--mw-shadow-modal);
        }
        .mw-content-form-modal .fi-section,
        .mw-content-picker-modal .mw-add-content-modal-action-wrapper {
            border-radius: var(--mw-radius-md);
            box-shadow: none;
        }
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
        .mw-content-form-modal .mw-fb-title-section {
            border: none !important;
            background: transparent !important;
            padding: 0 !important;
            box-shadow: none !important;
        }

        /* UX-engineer audit (task-2026-05-04-39767a) — drop
           blanket-uppercase on green Save + section headings
           inside live-edit modals. Mirrored from
           iframe-page.blade.php. */
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

        /* AI-694 (task-2026-05-16-de4ce4) — `visibility: hidden` filtered-card
           state. Replaces the prior `x-show` (display: none) path so cards
           filtered out by the search query keep their grid cell — the
           remaining visible cards do not reflow as the user types. Pair
           with the Alpine `visibleCards()` filter which now skips
           visibility: hidden alongside display: none. pointer-events: none
           keeps the hidden card from intercepting clicks/keyboard while
           still occupying its grid cell. Scope: any
           .mw-add-content-modal-action-wrapper anywhere on the page —
           the class is unique to the add-content picker cards. */
        .mw-add-content-modal-action-wrapper.mw-add-content-card--hidden {
            visibility: hidden;
            pointer-events: none;
        }

        /* AI-693 (task-2026-05-16-c3d0ed) — hover-accent rules MOVED
           OUT of this Blade per task-2026-05-16-651cc2 CHANGE.
           Designer verified live: same defect class as AI-691a /
           AI-697 — this Blade is the LiveEditModuleSettings sub-form
           layout, NOT the live-edit canvas, so the `<style>` block
           never renders when the +ADD picker modal opens. Hover-
           accent rules now live in `packages/microweber-filament-
           theme/resources/assets/css/microweber/live-edit-classes.
           css` (Webpack-bundled, loaded on /admin/live-edit) — see
           the AI-693 / task-651cc2 block in that file. */

        /* task-2026-05-16-bc28fd CHANGE — the desktop-Cancel-hide
           rule [originally AI-691a (task-2026-05-16-860f75)] and the
           anchored Add-Content picker rule [originally AI-697
           (task-2026-05-16-e3da1a)] MOVED OUT of this Blade. Reason: designer verified live (per the SOUL #108
           verify-before-accept contract) that this `<style>` block ONLY
           renders when the LiveEditModuleSettings sub-form page loads
           (per LiveEditModuleSettings::layout = 'live-edit-module-
           settings'), NOT when the +ADD picker modal opens on the
           live-edit canvas. The two rules now live in
           `packages/microweber-filament-theme/resources/assets/css/
           microweber/live-edit-classes.css` — the Webpack-bundled
           always-on Live Edit stylesheet that fires on
           `/admin/live-edit`. See the AI-691a / AI-697 / task-bc28fd
           blocks in that file. */
    </style>

    {{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::FOOTER, scopes: $livewire->getRenderHookScopes()) }}

    @livewire('microweber-livewire-modal')
    @include('microweber-live-edit::partials.filament-mw-dialog-scripts')

</x-filament-panels::layout.base>

