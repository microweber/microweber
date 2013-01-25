
<div class="mw-custom-field-group<? print $hidden_class ?>">
    <label class="mw-custom-field-label" for="custom_field_required<? print $rand ?>"><?php _e('Required'); ?></label>
    <div class="mw-custom-field-form-controls">
        <label class="mw-ui-check">


              <input type="checkbox" class="mw-ui-field"  name="custom_field_required" id="custom_field_required<? print $rand ?>" value="y" <? if (trim($data['custom_field_required']) == 'y'): ?> checked="checked"  <? endif; ?> >
              <span></span>
            </label>

            <?php _e('Is this field Required?'); ?>
    </div>
</div>
<div class="mw-custom-field-group<? print $hidden_class ?>">
    <label class="mw-custom-field-label"><?php _e('Active'); ?></label>
    <div class="mw-custom-field-form-controls">
        <label class="radio">
            <input type="radio" class="mw-ui-field" name="custom_field_is_active"   <? if (trim($data['custom_field_is_active']) == 'y'): ?> checked="checked"  <? endif; ?>  value="y">
            <?php _e('Yes'); ?> </label>
        <label class="radio">
            <input type="radio" class="mw-ui-field" name="custom_field_is_active" <? if (trim($data['custom_field_is_active']) == 'n'): ?> checked="checked"  <? endif; ?>   value="n">
            <?php _e('No'); ?> </label>
    </div>
</div>
<div class="mw-custom-field-group<? print $hidden_class ?>">
    <label class="mw-custom-field-label" for="custom_field_help_text<? print $rand ?>"><?php _e('Help text'); ?></label>
    <div class="mw-custom-field-form-controls">
        <input type="text"  name="custom_field_help_text" class="mw-ui-field"   value="<? print ($data['custom_field_help_text']) ?>"  id="custom_field_help_text<? print $rand ?>">
    </div>
</div>
<div class="form-actions custom-fields-form-actions">

    <script>

        __save = function(){
             mw.custom_fields.save('#custom_fields_edit<? print $rand ?>', function(){
               if(mw.$("#custom-field-editor").hasClass('mw-custom-field-created')){

                    mw.custom_fields.edit('.mw-admin-custom-field-edit-item', this, function(){
                        __sort_fields();
                    });
                    mw.$("#custom-field-editor").removeClass('mw-custom-field-created')
               }
               else{
                  __sort_fields();
               }
             });
        }

    </script>

</div>

</div>
