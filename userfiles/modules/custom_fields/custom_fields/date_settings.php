<? include('settings_header.php'); ?>


 <div class="custom-field-col left">

  <div class="mw-custom-field-group ">
  <label class="mw-ui-label" for="input_field_label<? print $rand ?>">
    <?php _e('Define Title'); ?>
  </label>
  <div class="mw-custom-field-form-controls">
    <input type="text" class="mw-ui-field" value="<? print ($data['custom_field_name']) ?>" name="custom_field_name" id="input_field_label<? print $rand ?>">
  </div>
</div>
</div>



   <div class="custom-field-col right">
    <div class="mw-custom-field-group">
      <label class="mw-ui-label" for="custom_field_value<? print $rand ?>">Value</label>
      <div class="mw-custom-field-form-controls">
        <input type="text" class="mw-ui-field" onkeyup="mw.custom_fields.autoSaveOnWriting(this, 'custom_fields_edit<? print $rand ?>');"  name="custom_field_value"  value="<? print ($data['custom_field_value']) ?>" id="custom_field_value<? print $rand ?>">
      </div>
    </div>
    </div>
    <script>
    $(document).ready(function(){
      mw.$( "#custom_field_value<? print $rand ?>" ).datepicker();
    });
    </script>
    <? include('settings_footer.php'); ?>
