<?php
$height = get_option('height', $params['id']);
if ($height === null OR $height === false OR $height == '') {
    $height = '';
}
?>

<div class="height-select" style="padding-top: 15px;">
    <label class="mw-ui-label"><?php _lang("Height", "templates/dream"); ?></label>
    <select name="height" class="mw-ui-field mw_option_field" data-option-group="<?php print $params['id']; ?>">
        <option value="" <?php if ($height == '') {
            echo 'selected';
        } ?>><?php _lang("Not Selected", "templates/dream"); ?>
        </option>

        <option value="10"<?php if ($height == '10') {
            echo 'selected';
        } ?>>10
        </option>

        <option value="20"<?php if ($height == '20') {
            echo 'selected';
        } ?>>20
        </option>

        <option value="30"<?php if ($height == '30') {
            echo 'selected';
        } ?>>30
        </option>

        <option value="40"<?php if ($height == '40') {
            echo 'selected';
        } ?>>40
        </option>

        <option value="50"<?php if ($height == '50') {
            echo 'selected';
        } ?>>50
        </option>

        <option value="60"<?php if ($height == '60') {
            echo 'selected';
        } ?>>60
        </option>

        <option value="70"<?php if ($height == '70') {
            echo 'selected';
        } ?>>70
        </option>

        <option value="80"<?php if ($height == '80') {
            echo 'selected';
        } ?>>80
        </option>

        <option value="90"<?php if ($height == '90') {
            echo 'selected';
        } ?>>90
        </option>

        <option value="100"<?php if ($height == '100') {
            echo 'selected';
        } ?>>100
        </option>
    </select>
</div>