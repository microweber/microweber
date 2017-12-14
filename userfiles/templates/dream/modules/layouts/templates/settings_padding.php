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
        } ?>>No Selected
        </option>

        <option value="space-bottom--sm"<?php if ($padding == 'space-bottom--sm') {
            echo 'selected';
        } ?>>Padding Bottom
        </option>

        <option value="space--sm"<?php if ($padding == 'space--sm') {
            echo 'selected';
        } ?>>Padding Top & Bottom
        </option>

        <option value="space--0"<?php if ($padding == 'space--0') {
            echo 'selected';
        } ?>>No Padding
        </option>
    </select>
</div>