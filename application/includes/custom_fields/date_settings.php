<? include('settings_header.php'); ?>


 <div class="custom-field-col-left">


  <label class="mw-ui-label" for="input_field_label{rand}">
    <?php _e('Define Title'); ?>
  </label>

    <input type="text" class="mw-ui-field" value="<? print ($data['custom_field_name']) ?>" name="custom_field_name" id="input_field_label{rand}">








</div>



   <div class="custom-field-col-right">

      <label class="mw-ui-label" for="custom_field_value{rand}">Value</label>

        <input type="text" class="mw-ui-field" onkeyup="mw.custom_fields.autoSaveOnWriting(this, 'custom_fields_edit{rand}');"  name="custom_field_value"  value="<? print ($data['custom_field_value']) ?>" id="custom_field_value{rand}">

      <?php print $savebtn; ?>
    </div>

    <script>
    $(document).ready(function(){
      mw.$( "#custom_field_value{rand}" ).datepicker();
    });
    </script>
    <? include('settings_footer.php'); ?>
