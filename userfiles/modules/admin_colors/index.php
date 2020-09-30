<?php only_admin_access() ?>

<?php
$selected = get_option('admin_theme_name', 'admin');
$templates = $this->app->template->get_admin_supported_themes();
?>

<style>
    .theme-color-picker {
        width: 300px;
        height: 300px;
        position: fixed;
        right: 0;
        bottom: 0;
        border: 1px solid silver;
        padding: 10px;
    }

/*    .theme-color-picker .bootstrap-select .dropdown-menu .dropdown-item,
    .theme-color-picker .bootstrap-select .dropdown-menu li a {
        color: #000 !important;
        background: #fff !important;
    }*/
</style>

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

<div class="theme-color-picker">
    <div class="form-group">
        <label class="control-label">Select a design</label>
        <select class="mw_option_field js-select-admin-theme selectpicker" data-title="Choose a design" data-width="100%" data-size="5" data-live-search="true" name="admin_theme_name" option-group="admin">
            <option value="" onclick="reset_admin_css()">Microweber</option>
            <?php if ($templates) { ?>
                <?php foreach ($templates as $template) { ?>
                    <option value="<?php print $template ?>" <?php if ($selected == $template): ?>selected="selected"<?php endif; ?>><?php print ucfirst($template) ?></option>
                <?php } ?>
            <?php } ?>
        </select>
    </div>
</div>