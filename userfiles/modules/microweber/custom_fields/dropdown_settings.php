<?php include('settings_header.php'); ?>

<div class="mw-custom-field-group ">
    <div class="mb-3">
        <label class="mw-ui-check left" style="margin-inline-end: 7px;">
            <input type="checkbox" data-option-group="custom_fields" name="options[multiple]" value="1" <?php if ($settings["multiple"]): ?> checked="checked" <?php endif; ?>/>
            <span></span>
            <span> <?php _e("Multiple Choices"); ?></span>
        </label>
        <small class="text-muted d-block mb-2"><?php _e('Allow multiple choices');?></small>
    </div>
</div>
<div class="custom-field-settings-values">
    <label class="control-label"><?php _e("Values"); ?></label>
    <div class="mw-custom-field-group" style="padding-top: 0;" id="fields<?php print $rand; ?>">
        <?php if (is_array($data['values']) and !empty($data['values'])) : ?>
            <?php foreach ($data['values'] as $v): ?>

            <?php if (is_array($v)) {
                $v = implode(',', $v);
            } ?>
            <div class="mw-custom-field-form-controls d-flex">
                <i class="mdi mdi-cursor-move custom-fields-handle-field align-self-center mr-2"></i>
                <input type="text" class="form-control col-5 <?php if(empty($add_remove_controls)):?>mw-full-width<?php endif; ?>" name="value[]" value="<?php print $v; ?>">
                <?php print $add_remove_controls; ?>
            </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="mw-custom-field-form-controls d-flex">
                <i class="mdi mdi-cursor-move custom-fields-handle-field align-self-center mr-2"></i>
                <input type="text" name="value[]" class="form-control col-5 <?php if(empty($add_remove_controls)):?>mw-full-width<?php endif; ?>" value=""/>
                <?php print $add_remove_controls; ?>
            </div>
        <?php endif; ?>
        <script type="text/javascript">
            mw.custom_fields.sort("fields<?php print $rand; ?>");
        </script>
    </div>
    <?php print $savebtn; ?>
</div>

<?php include('placeholder_settings.php'); ?>

<?php include('settings_footer.php'); ?>
