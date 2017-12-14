<div id="settings-holder">
    <br/><br/>
    <h5 style="font-weight: bold; display: block;">Skin Settings</h5>

    <?php include 'settings_padding.php'; ?>
    <?php include 'settings_height.php'; ?>
    <?php include 'settings_overlay.php'; ?>
    <?php include 'settings_is_color.php'; ?>
    <?php
    $is_parallax = get_option('is_parallax', $params['id']);
    if ($is_parallax == '') {
        $is_parallax = 'no';
    }
    ?>

    <div class="parallax-settings" style="margin-top:15px;">
        <label class="mw-ui-label">Is Parallax ?</label>
        <select name="is_parallax" class="mw-ui-field mw_option_field" data-option-group="<?php print $params['id']; ?>">
            <option value="" <?php if ($is_parallax == '') {
                echo 'selected';
            } ?>>No Selected
            </option>

            <option value="yes"<?php if ($is_parallax == 'yes') {
                echo 'selected';
            } ?>>Yes
            </option>

            <option value="no"<?php if ($is_parallax == 'no') {
                echo 'selected';
            } ?>>No
            </option>
        </select>
    </div>
</div>