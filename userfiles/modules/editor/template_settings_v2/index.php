<div>

    <script src="//unpkg.com/vue@2"></script>
    <script src="<?php echo userfiles_url(); ?>modules/editor/template_settings_v2/dist/App.umd.js"></script>

    <div id="js-template-settings-v2">
        <demo></demo>
    </div>

    <script>

        new Vue({
            components: {
                demo: App
            }
        }).$mount('#js-template-settings-v2')

    </script>

</div>
