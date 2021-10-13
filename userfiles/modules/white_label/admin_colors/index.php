<?php

if (!is_admin()) {
    return;
}
?>

<?php
$selected = get_option('admin_theme_name', 'admin');
$selected_vars = get_option('admin_theme_vars', 'admin');

if ($selected_vars) {
    $selected_vars = json_decode($selected_vars, true);
}

$templates = app()->template->get_admin_supported_themes();
?>

<?php
$vars = [
    'white' => 'whitesmoke',
    'black' => '#000',
    'silver' => '#bcbfc2',

    'primary' => '#4592ff',
    'secondary' => '#eeefef',
    'success' => '#3dc47e',
    'info' => '#e1f1fd',
    'warning' => '#ffc107',
    'danger' => '#ff4f52',
    'light' => '#f8f9fa',
    'dark' => '#2b2b2b',

    'body-bg' => '#fff',
    'body-color' => '#212529',

    'textDark' => '#2B2B2B',
    'textGray' => '#7e7e7e',
    'textLight' => '#d1d1d1'
];

if ($selected_vars and is_array($selected_vars) and isset($vars) and is_array($vars)) {
    $vars = array_merge($vars, $selected_vars);
}
?>

<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-colorpicker/3.2.0/css/bootstrap-colorpicker.min.css" rel="stylesheet"/>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-colorpicker/3.2.0/js/bootstrap-colorpicker.min.js"></script>

<style>
    .theme-color-picker {
        width: 300px;
        height: 300px;
        position: fixed;
        right: 0;
        bottom: 0;
        border: 1px solid silver;
        padding: 10px;
        overflow: scroll;
        background: #fff;
    }

    .theme-color-picker label.control-label {
        color: #000;
    }

    .theme-color-picker input {
        color: #000;
        background: #fff;
    }
</style>

<?php

include(__DIR__.'/ui_app.php');
?>

<script>
    $(document).ready(function () {
        mw.options.form('.<?php print $config['module_class'] ?>', function () {
            mw.notification.success("<?php _ejs("All changes are saved"); ?>.");
            reload_admin_css()
        });

        $(".js-select-admin-theme").on("change", function () {
            $('#selected_colors_vars').val('').trigger('change');
        });
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

    $(document).ready(function () {
        $('.js-color').colorpicker();

        $('.js-color').on('change', function () {
            stopSaveSelectedColors();
            saveSelectedColors();
        });
    })


    var setColorTemeout;

    function saveSelectedColors() {
        setColorTemeout = setTimeout(function () {
            var json_text = {};

            $.each($('.js-color').serializeArray(), function () {
                json_text[this.name] = this.value;
            });

            var array = JSON.stringify(json_text, null, 2);
            $('#selected_colors_vars').val(array).trigger('change');
        }, 500);
    }

    function stopSaveSelectedColors() {
        clearTimeout(setColorTemeout);
    }
</script>

<div class="theme-color-picker">
    <div class="form-group">
        <label class="control-label">Select a design</label>
        <select class="mw_option_field js-select-admin-theme selectpicker" data-title="Choose a design" data-width="100%" data-size="5" data-live-search="true" name="admin_theme_name" option-group="admin">
            <option value="" onclick="reset_admin_css()">Default</option>
            <?php if ($templates) { ?>
                <?php foreach ($templates as $template) { ?>
                    <?php
                    $template_name = str_replace('-', ' ', $template);
                    $template_name = ucfirst($template_name);
                    ?>
                    <option value="<?php print $template ?>" <?php if ($selected == $template): ?>selected="selected"<?php endif; ?>><?php print $template_name ?></option>
                <?php } ?>
            <?php } ?>
        </select>
    </div>

    <?php foreach ($vars as $k => $v) : ?>
        <?php if ($k != 'color_scheme'): ?>
            <label class="control-label">$<?php print $k ?></label>

            <div class="input-group">
                <input type="text" class="form-control js-color color-picker-<?php echo $k; ?>" name="<?php print $k ?>" value="<?php print $v ?>"/>
                <span class="input-group-append">
                <span class="input-group-text colorpicker-input-addon"><i style="background: <?php print $v ?>;"></i></span>
            </span>
            </div>
        <?php endif; ?>
    <?php endforeach; ?>

    <textarea class="d-none mw_option_field" id="selected_colors_vars" name="admin_theme_vars" option-group="admin"></textarea>
</div>



