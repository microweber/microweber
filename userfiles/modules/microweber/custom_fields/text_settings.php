<?php include('settings_header.php'); ?>

<style>
    #mw-custom-fields-text-holder textarea{
        resize:both;
    }
</style>

<div class="mw-ui-field-holder">
    <label class="mw-ui-check">
        <input type="checkbox" class="mw-custom-field-option js-custom-field-as-textarea" name="options[as_text_area]" <?php if($settings["as_text_area"]): ?> checked="checked" <?php endif; ?> value="1" id="mw-custom-fields-text-switch">
        <span></span>
        <span><?php _e("Use as Text Area"); ?></span>
    </label>
</div>

<script>
    $(document).ready(function () {
        $('.js-custom-field-as-textarea').click(function() {
            var val = $('.js-custom-field-value').val();
            if ($(this).is(':checked')) {
                var textareaSettings = '<div class="form-row">'+
                    '<div class="form-group col">'+
                    '<label for="textarea_rows"><?php _e("Textarea Rows"); ?></label>'+
                    '<input id="textarea_rows" type="number" class="form-control" name="options[rows]" value=""/>'+
                    '</div>'+
                    '<div class="form-group col">'+
                    '<label for="textarea_cols"><?php _e("Textarea Cols"); ?></label>'+
                    '<input id="textarea_cols" type="number" class="form-control" name="options[cols]" value=""/>'+
                    '</div>'+
                    '</div>';
                $('.js-custom-field-text-settings').html('<textarea class="form-control js-custom-field-value" name="value">'+val+'</textarea>' + textareaSettings);
            } else {
                $('.js-custom-field-text-settings').html('<input type="text" class="form-control js-custom-field-value" name="value" value="'+val+'" />');
            }
        });
        $('body').on('change', '.js-custom-field-value', function() {
            $('#input_field_label<?php print $rand; ?>').trigger('change');
        });
    });
</script>

<div class="custom-field-settings-values">
    <div class="mw-custom-field-group">
        <label class="control-label" for="value<?php echo $rand; ?>"><?php _e("Value"); ?></label>
        <small class="text-muted d-block mb-2"><?php _e('This attribute specifies the value of description');?></small>
        <div id="mw-custom-fields-text-holder" class="js-custom-field-text-settings">
            <?php if($settings["as_text_area"] == '1'): ?>

                <textarea class="form-control js-custom-field-value" name="value"><?php echo $data['value']; ?></textarea>

                <div class="form-row">
                    <div class="form-group col">
                        <label for="textarea_rows"><?php _e("Textarea Rows"); ?></label>
                        <input id="textarea_rows" type="number" class="form-control" name="options[rows]" value="<?php if (isset($settings['rows'])) { echo $settings['rows']; } ?>"/>
                    </div>
                    <div class="form-group col">
                        <label for="textarea_cols"><?php _e("Textarea Cols"); ?></label>
                        <input id="textarea_cols" type="number" class="form-control" name="options[cols]" value="<?php if (isset($settings['cols'])) {  echo $settings['cols']; } ?>"/>
                    </div>
                </div>

            <?php else: ?>
                <input type="text" class="form-control js-custom-field-value" name="value" value="<?php print ($data['value']) ?>" />
            <?php endif; ?>
        </div>
    </div>
    <?php print $savebtn; ?>
</div>

<?php include('placeholder_settings.php'); ?>
<?php include('settings_footer.php'); ?>
