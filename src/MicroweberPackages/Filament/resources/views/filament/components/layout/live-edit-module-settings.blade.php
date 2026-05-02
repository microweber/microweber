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
         * CreateAction/EditAction. Two scoped overrides:
         * (1) restore the modal backdrop tint that the project's
         * filament theme globally forces to bg-transparent,
         * (2) make the footer position: sticky so Save/Cancel stay
         * visible on long forms. task-2026-05-02-df09aa.
         */
        .fi-modal:has(> .fi-modal-window-ctn .mw-content-form-modal) > .fi-modal-close-overlay {
            background-color: rgba(0, 0, 0, 0.55) !important;
        }
        .mw-content-form-modal .fi-modal-footer {
            position: sticky;
            bottom: 0;
            background: var(--gray-50, #f9fafb);
            border-top: 1px solid var(--gray-200, #e5e7eb);
            margin-top: 0;
            padding-block: 0.75rem;
            z-index: 1;
        }
        html.dark .mw-content-form-modal .fi-modal-footer,
        .dark .mw-content-form-modal .fi-modal-footer {
            background: var(--gray-900, #111827);
            border-top-color: var(--gray-700, #374151);
        }
    </style>

    {{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::FOOTER, scopes: $livewire->getRenderHookScopes()) }}

</x-filament-panels::layout.base>

