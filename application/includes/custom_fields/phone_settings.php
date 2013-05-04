<?php include('settings_header.php'); ?>
  <?php // p ($data['custom_field_value']) ?>
  <?
   if(empty($data['custom_field_values'])){
	//$data['custom_field_values'] = array(0);
   }
   
  // d($data);
   // p ($data['custom_field_values'])

   ?>


 <div class="custom-field-col-left">

  <div class="mw-custom-field-group ">
  <label class="mw-ui-label" for="input_field_label<?php print $rand; ?>">
    <?php _e('Define Title'); ?>
  </label>

    <input type="text" class="mw-ui-field" value="<?php print ($data['custom_field_name']) ?>" name="custom_field_name" id="input_field_label<?php print $rand; ?>">

</div>
</div>



   <div class="custom-field-col-right">

   <label class="mw-ui-label">Default Value</label>



    <input
        class="mw-ui-field" type="text"
        data-default="ex.: 001-8892345678"
        onfocus="mw.form.dstatic(event)"
        onblur="mw.form.dstatic(event)"
        name="custom_field_value"
        value="<?php if($data['custom_field_value']==''): ?>ex.: 001-8892345678<?php else : print $data['custom_field_value'];endif; ?>" />


       <?php print $savebtn; ?>
  </div>
  <?php include('settings_footer.php'); ?>
