<?php
if($data['type'] !== 'breakline'):
?>

<div class="custom-field-settings-show-label">
   <div class="d-flex">
       <div class="mw-custom-field-form-controls p-0">
            <label class="mw-ui-check">
                   <input type="hidden" value="0" name="show_label">
                   <input type="checkbox" class="custom-control-input"  name="show_label" id="custom_field_show_label<?php print $rand; ?>" value="1" <?php if ($settings['show_label']): ?> checked="checked"  <?php endif; ?> >
                   <span></span>
                   <span></span>
            </label>
           <span class="align-self-center col-6 pl-0"><?php _e('Show label'); ?></span>
       </div>
   </div>
    <small class="text-muted d-block mb-2"><?php _e('Display the name of title/field name');?></small>
</div>

<?php 
$fields = mw()->ui->custom_fields();
?>
<div class="mw-custom-field-group">
    <label class="control-label" for="custom_field_width_type<?php print $rand; ?>"><?php _e('Field type'); ?></label>
    <small class="text-muted d-block mb-2"><?php _e('Choose type of the fields');?></small>

   <select class="selectpicker w-100" data-width="100%" data-size="5" name="options[field_type]" onChange="$(this).addClass('mw-needs-reload');">
        <?php foreach($fields as $fieldType=>$fieldName): ?>
           <?php if($fieldType=='breakline') { continue; } ?>
           <option <?php if($data['type'] == $fieldType):?>selected="selected"<?php endif; ?> value="<?php echo $fieldType; ?>"><?php echo $fieldName; ?></option>
       <?php endforeach; ?>
   </select>
</div>

<!--    <label class="control-label" for="custom_field_width_size--><?php //print $rand; ?><!--">--><?php //_e('Organize in columns'); ?><!--</label>-->
<!--    <small class="text-muted d-block mb-2">--><?php //_e('Choose columns organization');?><!--</small>-->
<!--    <div class="mw-custom-field-form">-->
<!--    	-->
<!--       <select class="mw-ui-field mw-full-width" name="options[field_size]">-->
<!--       -->
<!--       	--><?php //foreach(template_field_size_options() as $optionKey=>$optionValue): ?><!-- -->
<!--        <option  data-icon="mdi mdi-allergy" --><?php //if($settings['field_size'] == $optionKey):?><!--selected="selected"--><?php //endif; ?><!-- value="--><?php //echo $optionKey; ?><!--">--><?php //echo $optionValue; ?><!--</option>-->
<!--        --><?php //endforeach; ?>
<!--        -->
<!--       </select>-->
<!--    </div>-->


<label class="control-label d-block" for="custom_field_width_size<?php print $rand; ?>"><?php _e('Organize in columns on different resolutions'); ?></label>
<small class="text-muted d-block mb-2"><?php _e('Used for templates based on bootstrap');?></small>

<div class="d-flex">
  <div class="col-4 px-1">
      <label class="control-label d-block" for="custom_field_width_size<?php print $rand; ?>"><?php _e('Desktop'); ?></label>
      <select class="selectpicker"  data-live-search="true" data-width="100%" data-size="5" name="options[field_size]">
          <?php foreach(template_field_size_options() as $optionKey=>$optionValue): ?>
              <option  data-icon="mdi mdi-monitor" <?php if($settings['field_size'] == $optionKey):?>selected="selected"<?php endif; ?> value="<?php echo $optionKey; ?>"><?php echo $optionValue; ?></option>
          <?php endforeach; ?>
      </select>
  </div>


  <div class="col-4 px-1">
      <label class="control-label d-block" for="custom_field_width_size<?php print $rand; ?>"><?php _e('Tablet'); ?></label>
      <select class="selectpicker"  data-live-search="true" data-width="100%" data-size="5" name="options[field_size]">
          <?php foreach(template_field_size_options() as $optionKey=>$optionValue): ?>
              <option  data-icon="mdi mdi-tablet" <?php if($settings['field_size'] == $optionKey):?>selected="selected"<?php endif; ?> value="<?php echo $optionKey; ?>"><?php echo $optionValue; ?></option>
          <?php endforeach; ?>
      </select>
  </div>

  <div class="col-4 px-1">
      <label class="control-label d-block" for="custom_field_width_size<?php print $rand; ?>"><?php _e('Mobile'); ?></label>
      <select class="selectpicker"  data-live-search="true" data-width="100%" data-size="5" name="options[field_size]">
          <?php foreach(template_field_size_options() as $optionKey=>$optionValue): ?>
              <option data-icon="mdi mdi-cellphone" <?php if($settings['field_size'] == $optionKey):?>selected="selected"<?php endif; ?> value="<?php echo $optionKey; ?>"><?php echo $optionValue; ?></option>
          <?php endforeach; ?>
      </select>
  </div>
</div>

<div class="mw-custom-field-group<?php print $hidden_class ?>">
    <label class="control-label mt-3" ><?php _e('Required'); ?></label>
   <div class="d-flex">
       <div class="mw-custom-field-form-controls p-0">
           <label class="mw-ui-check">
               <input type="checkbox" class="mw-ui-field checkbox" name="required" onchange="valueChanged()" id="custom_field_required<?php print $rand; ?>" value="1" <?php if ($data['required']): ?> checked="checked"  <?php endif; ?> >
               <span></span>
           </label>
       </div>
       <span class="align-self-center col-6 pl-0"><?php _e('Is this field required?'); ?></span>
   </div>
</div>

<div class="mw-custom-field-group" id="required_checkbox">
    <label class="control-label" ><?php _e('Error text'); ?></label>
    <small class="text-muted d-block mb-2"><?php _e('This error will be shown when fields are required but not filled');?></small>
    <div class="mw-custom-field-form-controls">
        <input type="text" name="error_text" class="form-control" value="<?php print ($data['error_text']) ?>"  id="custom_field_error_text<?php print $rand; ?>">
    </div>
</div>

<!--<div class="mw-custom-field-group<?php /*print $hidden_class */?>">
    <label class="mw-custom-field-label"><?php /*_e('Active'); */?></label>
    <div class="mw-custom-field-form-controls">
        <label class="radio">
            <input type="radio" class="mw-ui-field mw-full-width" name="is_active"   <?php /*if (trim($data['is_active']) == '1'): */?> checked="checked"  <?php /*endif; */?>  value="1">
            <?php /*_e('Yes'); */?> </label>
        <label class="radio">
            <input type="radio" class="mw-ui-field mw-full-width" name="is_active" <?php /*if (trim($data['is_active']) == '0'): */?> checked="checked"  <?php /*endif; */?>   value="0">
            <?php /*_e('No'); */?> </label>
    </div>
</div>
-->

<!--<div class="mw-custom-field-group<?php /*print $hidden_class */?>">
    <label class="mw-custom-field-label" ><?php /*_e('Help text'); */?></label>
    <div class="mw-custom-field-form-controls">
        <input type="text" name="options[help_text]" class="mw-ui-field mw-full-width" value="<?php /*if (isset($data['options']['help_text'])) { echo $data['options']['help_text']; } */?>"  id="custom_field_help_text<?php /*print $rand; */?>">
    </div>
</div>
-->
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
                $('.selectpicker').selectpicker();
            });
               function valueChanged() {
                 if($('.checkbox').is(":checked"))
                     $("#required_checkbox").show();
                 else
                     $("#required_checkbox").hide();
               }
        </script>
    </div>
<?php endif; ?>
</div>
