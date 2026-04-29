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
                    if (!$wire.mountedActions || !$wire.mountedActions.length) {
                        return;
                    }

                    // Filament renders the mounted action modal under
                    // a <form wire:submit.prevent='callMountedAction'>
                    // wrapper. CSS-attribute selectors with colons +
                    // dots in the attribute name need escaping that's
                    // awkward to embed inside this Blade-rendered
                    // x-init string, so just iterate and match the
                    // attribute value at runtime.
                    document.querySelectorAll('form').forEach((f) => {
                        // Livewire normalises wire:submit.prevent →
                        // wire:submit at render time in some versions,
                        // so check both attribute names.
                        const submitAttr = f.getAttribute('wire:submit.prevent')
                            || f.getAttribute('wire:submit');
                        if (submitAttr !== 'callMountedAction') {
                            return;
                        }
                        if (typeof f.requestSubmit === 'function') {
                            f.requestSubmit();
                        } else {
                            f.dispatchEvent(new Event('submit', { cancelable: true, bubbles: true }));
                        }
                    });
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
