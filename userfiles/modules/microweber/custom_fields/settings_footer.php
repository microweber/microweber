<?php
$instanceField = mw()->fields_manager->instanceField($data['type']);
?>

<?php
if ($instanceField->hasShowLabelOptions):
?>
<div class="custom-field-settings-show-label">
   <div class="d-flex">
       <div class="mw-custom-field-form-controls p-0">
            <label class="mw-ui-check">
                   <input type="hidden" value="1" name="show_label">
                   <input type="checkbox" class="custom-control-input" name="show_label" id="custom_field_show_label<?php print $rand; ?>" value="0" <?php if ($settings['show_label'] == '0'): ?> checked="checked"  <?php endif; ?> >
                   <span></span>
                   <span></span>
            </label>
           <span class="align-self-center col-6 pl-0"><?php _e('Hide label'); ?></span>
       </div>
   </div>
    <small class="text-muted d-block mb-2"><?php _e('Display the name of title/field name');?></small>
</div>
<?php
endif;
?>

<?php
$fields = mw()->ui->custom_fields();
?>

<div class="mw-custom-field-group">
    <label class="control-label" for="custom_field_width_type<?php print $rand; ?>"><?php _e('Field type'); ?></label>
    <small class="text-muted d-block mb-2"><?php _e('Choose type of the fields');?></small>

   <select class="selectpicker w-100" data-width="100%" data-size="5" name="type" onChange="$(this).addClass('mw-needs-reload');">
        <?php foreach($fields as $fieldType=>$fieldName): ?>
           <?php if($fieldType=='breakline') { continue; } ?>
           <option <?php if($data['type'] == $fieldType):?>selected="selected"<?php endif; ?> value="<?php echo $fieldType; ?>"><?php echo $fieldName; ?></option>
       <?php endforeach; ?>
   </select>
</div>

<?php
if ($instanceField->hasResponsiveOptions):
?>
<label class="control-label d-block" for="custom_field_width_size<?php print $rand; ?>"><?php _e('Organize in columns on different resolutions'); ?></label>
<small class="text-muted d-block mb-2"><?php _e('Used for templates based on bootstrap');?></small>

<div class="d-flex">
  <div class="col-4 px-1">
      <label class="control-label d-block" for="custom_field_width_size<?php print $rand; ?>"><?php _e('Desktop'); ?></label>
      <select class="selectpicker"  data-live-search="true" data-width="100%" data-size="5" name="options[field_size_desktop]">
          <?php foreach(template_field_size_options() as $optionKey=>$optionValue): ?>
              <option  data-icon="mdi mdi-monitor" <?php if(isset($settings['field_size_desktop']) && $settings['field_size_desktop'] == $optionKey):?>selected="selected"<?php endif; ?> value="<?php echo $optionKey; ?>"><?php echo $optionValue; ?></option>
          <?php endforeach; ?>
      </select>
  </div>


  <div class="col-4 px-1">
      <label class="control-label d-block" for="custom_field_width_size<?php print $rand; ?>"><?php _e('Tablet'); ?></label>
      <select class="selectpicker"  data-live-search="true" data-width="100%" data-size="5" name="options[field_size_tablet]">
          <?php foreach(template_field_size_options() as $optionKey=>$optionValue): ?>
              <option  data-icon="mdi mdi-tablet" <?php if(isset($settings['field_size_tablet']) && $settings['field_size_tablet'] == $optionKey):?>selected="selected"<?php endif; ?> value="<?php echo $optionKey; ?>"><?php echo $optionValue; ?></option>
          <?php endforeach; ?>
      </select>
  </div>

  <div class="col-4 px-1">
      <label class="control-label d-block" for="custom_field_width_size<?php print $rand; ?>"><?php _e('Mobile'); ?></label>
      <select class="selectpicker"  data-live-search="true" data-width="100%" data-size="5" name="options[field_size_mobile]">
          <?php foreach(template_field_size_options() as $optionKey=>$optionValue): ?>
              <option data-icon="mdi mdi-cellphone" <?php if(isset($settings['field_size_mobile']) && $settings['field_size_mobile'] == $optionKey):?>selected="selected"<?php endif; ?> value="<?php echo $optionKey; ?>"><?php echo $optionValue; ?></option>
          <?php endforeach; ?>
      </select>
  </div>
</div>
<?php
endif;
?>

<?php
if ($instanceField->hasRequiredOptions):
?>
<div class="mw-custom-field-group">
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
<?php
endif;
?>

<?php
if ($data['required']) {
    ?>
    <script>
        $(document).ready(function() {
            $("#required_checkbox").show();
        });
    </script>
<?php
}
?>

<?php
if ($instanceField->hasErrorTextOptions):
?>
<div class="mw-custom-field-group" id="required_checkbox" style="display: none;">
    <label class="control-label" ><?php _e('Error text'); ?></label>
    <small class="text-muted d-block mb-2"><?php _e('This error will be shown when fields are required but not filled');?></small>
    <div class="mw-custom-field-form-controls">
        <input type="text" name="error_text" class="form-control" value="<?php print ($data['error_text']) ?>"  id="custom_field_error_text<?php print $rand; ?>">
    </div>
</div>
<?php
endif;
?>

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
                $('.custom-fields-settings-cancel-btn').on('click', function (){
                    thismodal.remove()
                });
            });
               function valueChanged() {
                   if ($('.checkbox').is(":checked")) {
                       $("#required_checkbox").show();
                   } else {
                       $("#required_checkbox").hide();
                   }
               }
        </script>
    </div>
</div>

<style>
    #save-menu-container{
        height: 60px;
    }
    #save-menu-wrapper{

        position: fixed;
        bottom: 0;
        right: 0;
        width: 100%;
        padding: 10px 20px;
        background-color: #FAFAFA;
        border-top: 1px solid #cfcfcf;
    }
    #save-menu{
        display: flex;
        width: 100%;
        justify-content: space-between;
    }
</style>

<div id="save-menu-container">
    <div id="save-menu-wrapper">
        <nav id="save-menu">
            <span class="btn btn-outline-secondary custom-fields-settings-cancel-btn"><?php _e('Cancel'); ?></span>
            <button class="btn btn-primary custom-fields-settings-save-btn"><?php _e('Save'); ?></button>
        </nav>
    </div>
</div>
