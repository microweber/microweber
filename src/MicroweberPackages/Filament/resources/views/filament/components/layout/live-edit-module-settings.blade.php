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
    </style>

    {{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::FOOTER, scopes: $livewire->getRenderHookScopes()) }}

</x-filament-panels::layout.base>

