<?php
$style = get_option('button_style', $params['id']);
$size = get_option('button_size', $params['id']);
$action = get_option('button_action', $params['id']);
$url = get_option('url', $params['id']);
$popupcontent = get_option('popupcontent', $params['id']);
$text = get_option('text', $params['id']);
$description = get_option('description', $params['id']);

$url_blank = get_option('url_blank', $params['id']);
?>

<div class="row">
    <div class="col-6">
        <div class="form-group">
            <label class="control-label d-block"><?php _e("Color"); ?></label>
            <select class="mw_option_field selectpicker" data-width="100%" name="button_style">
                <option <?php if ($style == ''): ?>selected<?php endif; ?> value=""><?php _e("Default"); ?></option>
                <option <?php if ($style == 'btn-primary'): ?>selected<?php endif; ?> value="btn-primary"><?php _e("Primary"); ?></option>
                <option <?php if ($style == 'btn-info'): ?>selected<?php endif; ?> value="btn-info"><?php _e("Info"); ?></option>
                <option <?php if ($style == 'btn-success'): ?>selected<?php endif; ?> value="btn-success"><?php _e("Success"); ?></option>
                <option <?php if ($style == 'btn-warning'): ?>selected<?php endif; ?> value="btn-warning"><?php _e("Warning"); ?></option>
                <option <?php if ($style == 'btn-danger'): ?>selected<?php endif; ?> value="btn-danger"><?php _e("Danger"); ?></option>
                <option <?php if ($style == 'btn-link'): ?>selected<?php endif; ?> value="btn-link"><?php _e("Simple"); ?></option>
            </select>
        </div>
    </div>

    <div class="col-6">
        <div class="form-group">
            <label class="control-label d-block"><?php _e("Size"); ?></label>
            <select class="mw_option_field selectpicker" data-width="100%" name="button_size">
                <option <?php if ($size == ''): ?>selected<?php endif; ?> value=""><?php _e("Default"); ?></option>
                <option <?php if ($size == 'btn-default-large btn-lg'): ?>selected<?php endif; ?> value="btn-default-large btn-lg"><?php _e("Large"); ?></option>
                <option <?php if ($size == 'btn-default-medium btn-md'): ?>selected<?php endif; ?> value="btn-default-medium btn-md"><?php _e("Medium"); ?></option>
                <option <?php if ($size == 'btn-default-small btn-sm'): ?>selected<?php endif; ?> value="btn-default-small btn-sm"><?php _e("Small"); ?></option>
                <option <?php if ($size == 'btn-default-mini btn-xs'): ?>selected<?php endif; ?> value="btn-default-mini btn-xs"><?php _e("Mini"); ?></option>
            </select>
        </div>
    </div>
</div>