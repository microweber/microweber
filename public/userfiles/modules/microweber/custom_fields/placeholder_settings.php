<div class="mw-custom-field-group">
    <label class="form-label font-weight-bold" for="value<?php print $rand; ?>"><?php _e("Placeholder"); ?></label>
    <small class="text-muted d-block mb-2"><?php _e('Specifies a short hint that describes the expected value of an input field');?></small>

    <div id="mw-custom-fields-text-holder">
        <input type="text" class="form-control" name="placeholder" value="<?php echo $data['placeholder']; ?>" />
    </div>
</div>


<?php if (isset($settings['show_placeholder'])): ?>
<div class="custom-field-settings-show-placeholder">
    <div class="d-flex">
        <div class="mw-custom-field-form-controls p-0">
            <label class="form-label font-weight-bold"><?php _e('Display the placeholder');?></label>

            <label class="form-check">
                <input type="hidden" value="false" name="options[show_placeholder]">

                <input type="checkbox" class="form-check-input me-2" name="options[show_placeholder]" id="custom_field_show_placeholder<?php print $rand; ?>" value="true" <?php if ($settings['show_placeholder'] == 'true'): ?> checked="checked"  <?php endif; ?> >

                <span class="form-check-label"><?php _e("Show placeholder"); ?></span>
            </label>

        </div>
    </div>

</div>
<br />
<?php endif; ?>
