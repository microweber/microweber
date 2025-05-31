<div class="mw-admin-live-edit-page">


    <div
        x-data="{}"
        x-init="() => {
            window.addEventListener('openAddContentAction', () => {
                 $wire.mountAction('addContentAction', {})
            });

            window.addEventListener('openModuleSettingsAction', (e) => {

                 $wire.mountAction('openModuleSettingsAction', {data:e.detail})
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


            #mw-live-edit-gui-editor-box {
                width: var(--sidebar-end-size);
                min-width: var(--sidebar-end-size-min);
                max-width: var(--sidebar-end-size-max);
            }
        </style>

        <script>

            addEventListener('load', () => {
                const guiEditor = new (mw.top()).controlBox({
                    content: ``,
                    position: 'right',
                    id: 'mw-live-edit-gui-editor-box',
                    closeButton: true,
                    title: mw.lang('Element Style Editor')
                });


                guiEditor.boxContent.appendChild(document.getElementById('mw-element-style-editor-app'));

                mw.top().app.guiEditorBox = guiEditor


                guiEditor.on('show', () => {
                    document.documentElement.classList['add']('live-edit-gui-editor-opened');
                });
                guiEditor.on('hide', () => {
                    document.documentElement.classList['remove']('live-edit-gui-editor-opened');
                });
            });

        </script>

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

        <script src="{{ asset('vendor/microweber-packages/frontend-assets/build/live-edit-app.js') }}"></script>

        <?php print \MicroweberPackages\LiveEdit\Facades\LiveEditManager::headTags(); ?>
        <?php event_trigger('mw.live_edit.footer'); ?>
    </div>


    <x-filament-actions::modals/>

    <script x-src="{{ asset('vendor/microweber-packages/frontend-assets/build/element-style-editor-app.js') }}"
            defer></script>

</div>
