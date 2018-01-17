<?php
$is_parallax = get_option('is_parallax', $params['id']);
if ($is_parallax == '') {
    $is_parallax = 'yes';
}
?>

<div class="parallax-settings" style="margin-top:15px;">
    <label class="mw-ui-label">Is Parallax ?</label>
    <select name="is_parallax" class="mw-ui-field mw_option_field" data-option-group="<?php print $params['id']; ?>">
        <option value="" <?php if ($is_parallax == '') {
            echo 'selected';
        } ?>><?php _lang("No Selected", "templates/dream"); ?>
        </option>

        <option value="yes"<?php if ($is_parallax == 'yes') {
            echo 'selected';
        } ?>><?php _lang("Yes", "templates/dream"); ?>
        </option>

        <option value="no"<?php if ($is_parallax == 'no') {
            echo 'selected';
        } ?>><?php _lang("No", "templates/dream"); ?>
        </option>
    </select>
</div>