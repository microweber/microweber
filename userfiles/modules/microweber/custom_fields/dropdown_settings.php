<?php include('settings_header.php'); ?>


<div class="custom-field-settings-name">

    <div class="mw-custom-field-group ">
       <div class="mb-3">
           <label class="control-label" for="input_field_label<?php print $rand; ?>">
               <?php _e('Title'); ?>
           </label>
           <small class="text-muted d-block mb-2"><?php _e('The name of your field');?></small>
           <input type="text" class="mw-ui-field mw-full-width" value="<?php print ($data['name']) ?>" name="name" id="input_field_label<?php print $rand; ?>">
       </div>

        <div class="mb-3">
            <label class="mw-ui-check left" style="margin-right: 7px;">
                <input type="checkbox"
                       data-option-group="custom_fields"
                       name="options[multiple]"
                       value="1"
                    <?php if ($settings["multiple"]): ?> checked="checked" <?php endif; ?>
                />

                <span></span>
                <span> <?php _e("Multiple Choices"); ?></span>
            </label>
            <small class="text-muted d-block mb-2"><?php _e('Allow multiple choices');?></small>

        </div>

    </div>
</div>

<div class="custom-field-settings-values">

    <label class="mw-ui-label"><?php _e("Values"); ?></label>
    <div class="mw-custom-field-group" style="padding-top: 0;" id="fields<?php print $rand; ?>">
        <?php if (is_array($data['values'])) : ?>
            <?php foreach ($data['values'] as $v): ?>

            <?php if (is_array($v)) {
                $v = implode(',', $v);
            } ?>
            <div class="mw-custom-field-form-controls">
                <i class="mdi mdi-cursor-move custom-fields-handle-field align-self-center mr-2"></i>
                <input type="text" class="mw-ui-field <?php if(empty($add_remove_controls)):?>mw-full-width<?php endif; ?>" name="value[]" value="<?php print $v; ?>">
                <?php print $add_remove_controls; ?>
            </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="mw-custom-field-form-controls">
                <i class="mdi mdi-cursor-move custom-fields-handle-field align-self-center mr-2"></i>
                <input type="text" name="value[]" class="mw-ui-field <?php if(empty($add_remove_controls)):?>mw-full-width<?php endif; ?>" value=""/>
                <?php print $add_remove_controls; ?>
            </div>
        <?php endif; ?>
        <script type="text/javascript">
            mw.custom_fields.sort("fields<?php print $rand; ?>"); 
        </script>
    </div>
    <?php print $savebtn; ?>
</div>

<div class="mw-custom-field-group">
    <label class="control-label" for="value<?php print $rand; ?>"><?php _e("Placeholder"); ?></label>
    <small class="text-muted d-block mb-2"><?php _e('Specifies a short hint that describes the expected value of an input field');?></small>
    <div id="mw-custom-fields-text-holder">
        <input type="text" class="mw-ui-field mw-full-width" name="placeholder" value="<?php echo $data['placeholder']; ?>" />
    </div>
</div>

<?php include('settings_footer.php'); ?>
