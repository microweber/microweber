<?php only_admin_access() ?>



<?php

$selected = get_option('admin_theme_name', 'admin');

$templates = $this->app->template->get_admin_supported_themes();


?>

<script>
    $(document).ready(function () {

        mw.options.form('.<?php print $config['module_class'] ?>', function () {
            mw.notification.success("<?php _ejs("All changes are saved"); ?>.");


            reload_admin_css()

        });


        $(".js-select-admin-theme").on("change", function () {


        });

        //$('.js-color').colorpicker();
    });

    function reload_admin_css() {

        $.get("<?php print api_url('mw_admin_colors/get_main_stylesheet_url'); ?>", function (data) {

            $('#admin-main-css-style').attr("href", data + "?rand=" + new Date().getMilliseconds());

        });

    }

    function reset_admin_css() {

        $.get("<?php print api_url('mw_admin_colors/reset_main_stylesheet'); ?>", function (data) {

            reload_admin_css()

        });

    }

</script>


<select class="mw_option_field js-select-admin-theme selectpicker" data-width="100%" name="admin_theme_name" option-group="admin">
    <option value="">None</option>
    <?php if ($templates) { ?>
        <?php foreach ($templates as $template) { ?>
            <option value="<?php print $template ?>" <?php if ($selected == $template): ?>selected="selected"<?php endif; ?>><?php print $template ?></option>
        <?php } ?>
    <?php } ?>
</select>


<button onclick="reset_admin_css()">Reset</button>