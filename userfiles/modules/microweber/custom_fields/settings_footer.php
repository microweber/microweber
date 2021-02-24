<?php
if($data['type'] !== 'breakline'):
?>

<div class="custom-field-settings-show-label">
    <div class="mw-custom-field-form-controls">
        <label class="mw-ui-check">
            <input type="hidden" value="0" name="show_label">
            <input type="checkbox" class="custom-control-input"  name="show_label" id="custom_field_show_label<?php print $rand; ?>" value="1" <?php if ($settings['show_label']): ?> checked="checked"  <?php endif; ?> >
            <span></span><span><?php _e('Show Label'); ?></span>
            <small class="text-muted d-block mb-3"><?php _e('Label');?></small>
            <span></span>
        </label>
    </div>
</div>

<div class="mw-custom-field-group">
    <label class="control-label" ><?php _e('Error text'); ?></label>
    <small class="text-muted d-block mb-3"><?php _e('This error will be shown when fields are required but not filled');?></small>
    <div class="mw-custom-field-form-controls">
        <input type="text"  name="error_text" class="form-control" value="<?php print ($data['error_text']) ?>"  id="custom_field_error_text<?php print $rand; ?>">
    </div>
</div>

<?php 
$fields = mw()->ui->custom_fields();
?>
<div class="mw-custom-field-group">
    <label class="control-label" for="custom_field_width_type<?php print $rand; ?>"><?php _e('Field Type'); ?></label>
    <small class="text-muted d-block mb-3"><?php _e('Choose type of the fields');?></small>
    <div class="mw-custom-field-form">
    	
       <select class="mw-ui-field mw-full-width" name="options[field_type]" onChange="$(this).addClass('mw-needs-reload');">
       
       	<?php foreach($fields as $fieldType=>$fieldName): ?> 
       	<?php if($fieldType=='breakline') { continue; } ?>
        <option <?php if($data['type'] == $fieldType):?>selected="selected"<?php endif; ?> value="<?php echo $fieldType; ?>"><?php echo $fieldName; ?></option> 
        <?php endforeach; ?>
        
       </select>
    </div>
</div>

<div class="mw-custom-field-group">
    <label class="control-label" for="custom_field_width_size<?php print $rand; ?>"><?php _e('Organaize in columns'); ?></label>
    <small class="text-muted d-block mb-3"><?php _e('Choose columns organization');?></small>
    <div class="mw-custom-field-form">
    	
       <select class="mw-ui-field mw-full-width" name="options[field_size]">
       
       	<?php foreach(template_field_size_options() as $optionKey=>$optionValue): ?> 
        <option <?php if($settings['field_size'] == $optionKey):?>selected="selected"<?php endif; ?> value="<?php echo $optionKey; ?>"><?php echo $optionValue; ?></option> 
        <?php endforeach; ?>
        
       </select>
    </div>
</div>

<div class="mw-custom-field-group<?php print $hidden_class ?>">
    <label class="mw-custom-field-label" for="custom_field_required<?php print $rand; ?>"><?php _e('Required'); ?></label>
    <div class="mw-custom-field-form-controls mw-full-width">
        <label class="mw-ui-check">
              <input type="checkbox" class="mw-ui-field mw-full-width"  name="required" id="custom_field_required<?php print $rand; ?>" value="1" <?php if ($data['required']): ?> checked="checked"  <?php endif; ?> >
              <span></span>
        </label>
            <?php _e('Is this field Required?'); ?>
    </div>
</div>

<div class="mw-custom-field-group<?php print $hidden_class ?>">
    <label class="mw-custom-field-label"><?php _e('Active'); ?></label>
    <div class="mw-custom-field-form-controls">
        <label class="radio">
            <input type="radio" class="mw-ui-field mw-full-width" name="is_active"   <?php if (trim($data['is_active']) == '1'): ?> checked="checked"  <?php endif; ?>  value="1">
            <?php _e('Yes'); ?> </label>
        <label class="radio">
            <input type="radio" class="mw-ui-field mw-full-width" name="is_active" <?php if (trim($data['is_active']) == '0'): ?> checked="checked"  <?php endif; ?>   value="0">
            <?php _e('No'); ?> </label>
    </div>
</div>
<div class="mw-custom-field-group<?php print $hidden_class ?>">
    <label class="mw-custom-field-label" ><?php _e('Help text'); ?></label>
    <div class="mw-custom-field-form-controls">
        <input type="text" name="options[help_text]" class="mw-ui-field mw-full-width" value="<?php if (isset($data['options']['help_text'])) { echo $data['options']['help_text']; } ?>"  id="custom_field_help_text<?php print $rand; ?>">
    </div>
</div>
<div class="form-actions custom-fields-form-actions">
    <script>
         __save__global_id = '#custom_fields_edit<?php print $rand; ?>';
         $(document).ready(function(){
           if(typeof __custom_fields_editor_binded == 'undefined'){
                __custom_fields_editor_binded = true;
                mw.$("#custom-field-editor").keyup(function(e){
                  if(e.target.name == 'name'){
                      $(this).find('.custom-field-edit-title strong').html(e.target.value);
                  }
                });
           }

        });
    </script>
</div>
<?php endif; ?> 

</div>
