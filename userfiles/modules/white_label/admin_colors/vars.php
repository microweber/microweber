<?php

$selected = get_option('admin_theme_name', 'admin');


$selected_vars = get_option('admin_theme_vars', 'admin');

if ($selected_vars) {
    $selected_vars = json_decode($selected_vars, true);
}
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
$vars_theme = app()->template->get_admin_supported_theme_scss_vars($selected);
if ($vars_theme) {


    if ($selected_vars and is_array($selected_vars) and isset($selected_vars) and is_array($selected_vars)) {
        foreach ($selected_vars as $k => $var) {
            if (isset($vars_theme[$k])) {
                $vars_theme[$k] = $var;
            }
        }
    }

    $vars = $vars_theme;

} else {
    if ($selected_vars and is_array($selected_vars) and isset($vars) and is_array($vars)) {
        $vars = array_merge($vars, $selected_vars);
    }
}


?>
<script>
    $(document).ready(function () {
        $('.js-color').each(function () {
            mw.colorPicker({
                element: this,
                position: 'bottom-left',
                onchange: function (color) {
                    stopSaveSelectedColors();
                    saveSelectedColors();

                }
            });
        })
    })
</script>
<?php foreach ($vars as $k => $v) : ?>
    <?php if ($k != 'color_scheme'): ?>
        <label class="control-label"><?php print $k ?></label>

        <div class="input-group">
            <input type="text" class="form-control js-color color-picker-<?php echo $k; ?>" name="<?php print $k ?>"
                   value="<?php print $v ?>"/>
            <span class="input-group-append">
                <span class="input-group-text colorpicker-input-addon"><i
                        style="background: <?php print $v ?>;"></i></span>
            </span>
        </div>
    <?php endif; ?>
<?php endforeach; ?>


