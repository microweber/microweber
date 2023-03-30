<div>

    <script src="<?php echo userfiles_url(); ?>modules/editor/template_settings_v2/dist/TemplatesSettingsV2.umd.min.js"></script>

    <div id="js-template-settings-v2">
        <run-module></run-module>
    </div>


    <script>
        window.TemplatesSettingsV2 = new Vue({
            components: {
                'run-module': TemplatesSettingsV2
            }
        }).$mount('#js-template-settings-v2')
    </script>

</div>
