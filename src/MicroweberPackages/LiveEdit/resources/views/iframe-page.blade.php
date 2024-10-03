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

            <script>

                // mw.lib.require('nouislider');
                // mw.require('components.css')
                //
                //

                // mw.lib.require('flag_icons');
                /*mw.iconLoader()

                    .addIconSet('iconsMindLine')
                    .addIconSet('iconsMindSolid')
                    .addIconSet('fontAwesome')
                    .addIconSet('materialDesignIcons')*/

            </script>

            <?php

            $bodyDarkClass = '';

            if(isset($_COOKIE['admin_theme_dark'])){
                $bodyDarkClass = 'theme-dark';
            }
            ?>


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
            });

            <?php


            /*

             @php
                     $templateColors = [];
                     $getTemplateConfig = app()->template_manager->get_config();
                     if($getTemplateConfig){
                     $templateColors = get_template_colors_settings();
                     }
                     if(empty($templateColors)){
                     $templateColors =[['value' => '#000000']];
                     }

             @endphp
             @if(!empty($templateColors))
                 mw.tools.colorPickerColors = mw.tools.colorPickerColors || [];
                 mw.tools.colorPickerColors = [
                     @foreach($templateColors as $color)
                     '{{ $color['value'] }}',
                     @endforeach
                 ];
             @endif

             * */


            ?>
        </script>

        <div id="live-edit-app">
            Loading...
        </div>

        <div id="live-edit-frame-holder">

        </div>

        <?php print \MicroweberPackages\LiveEdit\Facades\LiveEditManager::headTags(); ?>
        <?php event_trigger('mw.live_edit.footer'); ?>

<div>
        <?php print mw_admin_footer_scripts(); ?>
</div>
        <script>

            mw.settings.adminUrl = '<?php print admin_url(); ?>';
            mw.settings.liveEditModuleSettingsUrls =  <?php print json_encode(\MicroweberPackages\Module\Facades\ModuleAdmin::getLiveEditSettingsUrls()); ?>;
            mw.settings.liveEditModuleSettingsComponents=  <?php print json_encode(\MicroweberPackages\Module\Facades\ModuleAdmin::getSettingsComponents()); ?>;

        </script>

        @vite('resources/assets/ui/live-edit-app.js',  'vendor/microweber-packages/frontend-assets/build')

    </div>


    <x-filament-actions::modals />

</div>
