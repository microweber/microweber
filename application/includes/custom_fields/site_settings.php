<?php include('settings_header.php'); ?>


 <div class="custom-field-col-left">

  <div class="mw-custom-field-group ">
  <label class="mw-ui-label" for="input_field_label<?php print $rand; ?>">
    <?php _e('Define Title'); ?>
  </label>

    <input type="text" class="mw-ui-field" value="<?php print ($data['custom_field_name']) ?>" name="custom_field_name" id="input_field_label<?php print $rand; ?>">

</div>
</div>



   <div class="custom-field-col-right">

      <label class="mw-ui-label" for="custom_field_value<?php print $rand; ?>"><?php _e("Default Value"); ?></label>

        <input type="text" class="mw-ui-field"  name="custom_field_value"  value="<?php print ($data['custom_field_value']) ?>" id="custom_field_value<?php print $rand; ?>">


    <?php print $savebtn; ?>
    </div>

    <?php include('settings_footer.php'); ?>
