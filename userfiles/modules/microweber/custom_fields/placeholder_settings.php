<div class="mw-custom-field-group">
    <label class="control-label" for="value<?php print $rand; ?>"><?php _e("Placeholder"); ?></label>
    <small class="text-muted d-block mb-2"><?php _e('Specifies a short hint that describes the expected value of an input field');?></small>

    <div id="mw-custom-fields-text-holder">
        <input type="text" class="form-control" name="placeholder" value="<?php echo $data['placeholder']; ?>" />
    </div>
</div>


<?php if (isset($settings['show_placeholder'])): ?>
<div class="custom-field-settings-show-placeholder">
    <div class="d-flex">
        <div class="mw-custom-field-form-controls p-0">
            <label class="mw-ui-check">
                <input type="hidden" value="false" name="options[show_placeholder]">
                <input type="checkbox" class="custom-control-input" name="options[show_placeholder]" id="custom_field_show_placeholder<?php print $rand; ?>" value="true" <?php if ($settings['show_placeholder'] == 'true'): ?> checked="checked"  <?php endif; ?> >
                <span></span>
                <span></span>
            </label>
            <span class="align-self-center col-6 pl-0"><?php _e('Show placeholder'); ?></span>
        </div>
    </div>
    <small class="text-muted d-block mb-2"><?php _e('Display the placeholder inside in field');?></small>
</div>
<br />
<?php endif; ?>
