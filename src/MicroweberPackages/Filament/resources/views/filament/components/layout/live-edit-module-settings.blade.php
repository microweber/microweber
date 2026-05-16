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

        /* AI-693 (task-2026-05-16-c3d0ed) — Add-Content modal icon palette
           accent contract. The six picker cards now render a monochrome
           icon at rest (.text-gray-600 + bg-gray-500/10 / dark equivalents
           in `add-content-modal.blade.php`); hover/focus replaces both with
           the ESE accent pair (`--ese-accent-soft` bg + `--ese-accent`
           foreground) so the visual coupling matches MwToolButton's accent
           contract from spec §4.5. ESE tokens are defined at `:root` in
           `element-style-editor.css` so they resolve even inside the
           Filament-portaled modal that lives outside `.mw-live-edit-page`.
           Scoped to .mw-content-picker-modal so non-picker modals
           (form-modal, settings, etc.) keep their existing palette. */
        .mw-content-picker-modal .mw-add-content-modal-action-wrapper:hover .mw-add-content-icon,
        .mw-content-picker-modal .mw-add-content-modal-action-wrapper:focus-visible .mw-add-content-icon {
            background-color: var(--ese-accent-soft);
        }
        .mw-content-picker-modal .mw-add-content-modal-action-wrapper:hover .mw-add-content-icon svg,
        .mw-content-picker-modal .mw-add-content-modal-action-wrapper:focus-visible .mw-add-content-icon svg {
            color: var(--ese-accent);
        }

        /* AI-691a (task-2026-05-16-860f75) — §A6 Path B (designer-resolved):
           On desktop ≥769px, hide the picker modal Cancel button. The
           top-right X is reachable with a mouse pointer, so the redundant
           Cancel chrome can come off. On mobile ≤768px Cancel STAYS (no
           rule applies) to preserve NOVICE #3 thumb-reach affordance —
           task-2026-05-13-899d57 documented users reporting the picker
           "stuck open" when only the tiny X was available. When AI-695
           (mobile bottom-sheet) ships, the whole Cancel DOM is deleted
           (PHP `->modalCancelActionLabel` removed) and this rule + AI-691a
           file are removed too. Scoped to .mw-content-picker-modal so the
           global .fi-modal-footer-actions on other Filament modals is
           untouched. modalSubmitAction(false) is set on addContentAction
           so this footer contains only Cancel; hiding the wrapper is
           equivalent to hiding the button. */
        @media (min-width: 769px) {
            .mw-content-picker-modal .fi-modal-footer-actions {
                display: none;
            }
        }
    </style>

    {{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::FOOTER, scopes: $livewire->getRenderHookScopes()) }}

</x-filament-panels::layout.base>

