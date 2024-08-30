<?php
if(!user_can_access('site_stats.settings')){
    return;
}
?>

<div class="form-group">
    <div class="custom-control custom-switch ml-4">
        <input type="checkbox" name="google-tag-manager-enable-events" id="google-tag-manager-enable-events" class="mw_option_field form-check-input" data-option-group="website" data-value-checked="1" data-value-unchecked="0" <?php if (get_option('google-tag-manager-enable-events', 'website') == 1): ?>checked<?php endif; ?>>
        <label class="custom-control-label" for="google-tag-manager-enable-events"><?php _e("Send events to Google Analytics"); ?></label>
    </div>
</div>

