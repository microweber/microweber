<?php
if($data['type'] !== 'breakline'):
?>

<div class="custom-field-settings-show-label">
    <div class="mw-custom-field-form-controls">
        <label class="mw-ui-check">
            <input type="hidden" value="n" name="custom_field_show_label">
            <input type="checkbox" class="mw-custom-field-option"  name="custom_field_show_label" id="custom_field_show_label<?php print $rand; ?>" value="y" <?php if ($settings['show_label']): ?> checked="checked"  <?php endif; ?> >
            <span></span><span><?php _e('Show Label'); ?></span>
        </label>
    </div>
</div>

<?php 
$fields = mw()->ui->custom_fields();
?>
<div class="mw-custom-field-group">
    <label class="mw-custom-field-label" for="custom_field_width_type<?php print $rand; ?>"><b><?php _e('Field Type'); ?></b></label>
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
    <label class="mw-custom-field-label" for="custom_field_width_size<?php print $rand; ?>"><b><?php _e('Organaize in columns'); ?></b></label>
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


              <input type="checkbox" class="mw-ui-field mw-full-width"  name="custom_field_required" id="custom_field_required<?php print $rand; ?>" value="y" <?php if ($settings['required']): ?> checked="checked"  <?php endif; ?> >
              <span></span>
            </label>

            <?php _e('Is this field Required?'); ?>
    </div>
</div>

<div class="mw-custom-field-group<?php print $hidden_class ?>">
    <label class="mw-custom-field-label"><?php _e('Active'); ?></label>
    <div class="mw-custom-field-form-controls">
        <label class="radio">
            <input type="radio" class="mw-ui-field mw-full-width" name="custom_field_is_active"   <?php if (trim($data['custom_field_is_active']) == 'y'): ?> checked="checked"  <?php endif; ?>  value="y">
            <?php _e('Yes'); ?> </label>
        <label class="radio">
            <input type="radio" class="mw-ui-field mw-full-width" name="custom_field_is_active" <?php if (trim($data['custom_field_is_active']) == 'n'): ?> checked="checked"  <?php endif; ?>   value="n">
            <?php _e('No'); ?> </label>
    </div>
</div>
<div class="mw-custom-field-group<?php print $hidden_class ?>">
    <label class="mw-custom-field-label" ><?php _e('Help text'); ?></label>
    <div class="mw-custom-field-form-controls">
        <input type="text"  name="custom_field_help_text" class="mw-ui-field mw-full-width"   value="<?php print ($data['custom_field_help_text']) ?>"  id="custom_field_help_text<?php print $rand; ?>">
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
