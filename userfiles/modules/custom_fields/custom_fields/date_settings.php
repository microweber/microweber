<? include('settings_header.php'); ?>


 <div class="custom-field-col-left">


  <label class="mw-ui-label" for="input_field_label<? print $rand ?>">
    <?php _e('Define Title'); ?>
  </label>

    <input type="text" class="mw-ui-field" value="<? print ($data['custom_field_name']) ?>" name="custom_field_name" id="input_field_label<? print $rand ?>">








</div>



   <div class="custom-field-col-right">

      <label class="mw-ui-label" for="custom_field_value<? print $rand ?>">Value</label>

        <input type="text" class="mw-ui-field" onkeyup="mw.custom_fields.autoSaveOnWriting(this, 'custom_fields_edit<? print $rand ?>');"  name="custom_field_value"  value="<? print ($data['custom_field_value']) ?>" id="custom_field_value<? print $rand ?>">

      <button type="button" class="mw-ui-btn mw-ui-btn-blue mw-custom-fields-save" onclick="__save();"><?php _e('Save changes'); ?></button>
    </div>

    <script>
    $(document).ready(function(){
      mw.$( "#custom_field_value<? print $rand ?>" ).datepicker();
    });
    </script>
    <? include('settings_footer.php'); ?>
