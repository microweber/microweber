<?php
$padding = get_option('padding', $params['id']);
if ($padding === null OR $padding === false OR $padding == '') {
    $padding = '';
}
?>

<div class="padding-select">
    <label class="mw-ui-label">Padding</label>
    <select name="padding" class="mw-ui-field mw_option_field" data-option-group="<?php print $params['id']; ?>">
        <option value="" <?php if ($padding == '') {
            echo 'selected';
        } ?>><?php _lang("No Selected", "templates/dream"); ?>
        </option>

        <option value="space-bottom--sm"<?php if ($padding == 'space-bottom--sm') {
            echo 'selected';
        } ?>><?php _lang("Padding Bottom", "templates/dream"); ?>
        </option>

        <option value="space--sm"<?php if ($padding == 'space--sm') {
            echo 'selected';
        } ?>><?php _lang("Padding Top & Bottom", "templates/dream"); ?>
        </option>

        <option value="space--0"<?php if ($padding == 'space--0') {
            echo 'selected';
        } ?>><?php _lang("No Padding", "templates/dream"); ?>
        </option>
    </select>
</div>