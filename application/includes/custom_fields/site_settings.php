<? include('settings_header.php'); ?>


 <div class="custom-field-col-left">

  <div class="mw-custom-field-group ">
  <label class="mw-ui-label" for="input_field_label<? print $rand ?>">
    <?php _e('Define Title'); ?>
  </label>

    <input type="text" class="mw-ui-field" value="<? print ($data['custom_field_name']) ?>" name="custom_field_name" id="input_field_label<? print $rand ?>">

</div>
</div>



   <div class="custom-field-col-right">

      <label class="mw-ui-label" for="custom_field_value<? print $rand ?>">http://www.yourwebsite.com</label>

        <input type="text" class="mw-ui-field" onkeyup="mw.custom_fields.autoSaveOnWriting(this, 'custom_fields_edit<? print $rand ?>');"  name="custom_field_value"  value="yourwebsite.com" id="custom_field_value<? print $rand ?>">


    <?php print $savebtn; ?>
    </div>

    <? include('settings_footer.php'); ?>
