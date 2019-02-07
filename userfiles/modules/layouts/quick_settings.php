<?php only_admin_access() ?>

<?php
$padding_top = get_option('padding-top', $params['id']);
if ($padding_top === null OR $padding_top === false OR $padding_top == '') {
    $padding_top = false;
}

$padding_bottom = get_option('padding-bottom', $params['id']);
if ($padding_bottom === null OR $padding_bottom === false OR $padding_bottom == '') {
    $padding_bottom = false;
}
?>

    <div class="padding-select">
        <label class="mw-ui-label">Padding Top</label>
        <select name="padding-top" class="mw-ui-field mw_option_field" data-option-group="<?php print $params['id']; ?>">
            <option value="" <?php if (!$padding_top) {echo 'selected';} ?>><?php _e("No Selected"); ?></option>
            <option value="1"<?php if ($padding_top == '1') {echo 'selected';} ?>><?php _e("Padding Top"); ?> 1</option>
            <option value="2"<?php if ($padding_top == '2') {echo 'selected';} ?>><?php _e("Padding Top"); ?> 2</option>
            <option value="3"<?php if ($padding_top == '3') {echo 'selected';} ?>><?php _e("Padding Top"); ?> 3</option>
            <option value="4"<?php if ($padding_top == '4') {echo 'selected';} ?>><?php _e("Padding Top"); ?> 4</option>
            <option value="5"<?php if ($padding_top == '5') {echo 'selected';} ?>><?php _e("Padding Top"); ?> 5</option>
            <option value="6"<?php if ($padding_top == '6') {echo 'selected';} ?>><?php _e("Padding Top"); ?> 6</option>
            <option value="7"<?php if ($padding_top == '7') {echo 'selected';} ?>><?php _e("Padding Top"); ?> 7</option>
            <option value="8"<?php if ($padding_top == '8') {echo 'selected';} ?>><?php _e("Padding Top"); ?> 8</option>
            <option value="9"<?php if ($padding_top == '9') {echo 'selected';} ?>><?php _e("Padding Top"); ?> 9</option>
            <option value="10"<?php if ($padding_top == '10') {echo 'selected';} ?>><?php _e("Padding Top"); ?> 10</option>
        </select>
    </div>

    <div class="padding-select">
        <label class="mw-ui-label">Padding Bottom</label>
        <select name="padding-bottom" class="mw-ui-field mw_option_field" data-option-group="<?php print $params['id']; ?>">
            <option value="" <?php if (!$padding_bottom) {echo 'selected';} ?>><?php _e("No Selected"); ?></option>
            <option value="1"<?php if ($padding_bottom == '1') {echo 'selected';} ?>><?php _e("Padding Bottom"); ?> 1</option>
            <option value="2"<?php if ($padding_bottom == '2') {echo 'selected';} ?>><?php _e("Padding Bottom"); ?> 2</option>
            <option value="3"<?php if ($padding_bottom == '3') {echo 'selected';} ?>><?php _e("Padding Bottom"); ?> 3</option>
            <option value="4"<?php if ($padding_bottom == '4') {echo 'selected';} ?>><?php _e("Padding Bottom"); ?> 4</option>
            <option value="5"<?php if ($padding_bottom == '5') {echo 'selected';} ?>><?php _e("Padding Bottom"); ?> 5</option>
            <option value="6"<?php if ($padding_bottom == '6') {echo 'selected';} ?>><?php _e("Padding Bottom"); ?> 6</option>
            <option value="7"<?php if ($padding_bottom == '7') {echo 'selected';} ?>><?php _e("Padding Bottom"); ?> 7</option>
            <option value="8"<?php if ($padding_bottom == '8') {echo 'selected';} ?>><?php _e("Padding Bottom"); ?> 8</option>
            <option value="9"<?php if ($padding_bottom == '9') {echo 'selected';} ?>><?php _e("Padding Bottom"); ?> 9</option>
            <option value="10"<?php if ($padding_bottom == '10') {echo 'selected';} ?>><?php _e("Padding Bottom"); ?> 10</option>
        </select>
    </div>


