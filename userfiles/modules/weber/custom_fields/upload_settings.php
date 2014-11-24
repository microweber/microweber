<?php include('settings_header.php'); ?>




 <div class="custom-field-settings-name">

    <div class="mw-custom-field-group ">
      <label class="mw-ui-label" for="input_field_label<?php print $rand; ?>">
        <?php _e('Title'); ?>
      </label>

        <input type="text" class="mw-ui-field" value="<?php print ($data['custom_field_name']) ?>" name="custom_field_name" id="input_field_label<?php print $rand; ?>">
        <input type="hidden" class="mw-ui-field" value="<?php print ($data['custom_field_value']) ?>" name="custom_field_value">

    </div>

   
   



    <div class="mw-custom-fields-upload-filetypes">

    <label class="mw-ui-label"><small><?php _e("Allowable Format for upload"); ?></small></label>

      <div class="mw-ui-field-holder">
        <label class="mw-ui-check">
        <input type="checkbox"  name="options[file_types]" <?php if(isset($data['options']) and isset($data['options']['file_types']) and in_array('images',$data['options']['file_types'])) : ?> checked <?php endif; ?> value="images"  />
            <span></span>
            <span><?php _e("Images Files"); ?></span>
        </label>
      </div>

      <div class="mw-ui-field-holder">
        <label class="mw-ui-check">
            <input type="checkbox"  name="options[file_types]" <?php if(isset($data['options']) and isset($data['options']['file_types']) and in_array('documents',$data['options']['file_types'])) : ?> checked <?php endif; ?>  value="documents" />
            <span></span>
            <span><?php _e("Document Files"); ?></span>
        </label>
      </div>
       
      <div class="mw-ui-field-holder">
        <label class="mw-ui-check">
            <input type="checkbox"  name="options[file_types]" <?php if(isset($data['options']) and isset($data['options']['file_types']) and in_array('archives',$data['options']['file_types'])) : ?> checked <?php endif; ?>  value="archives" />
            <span></span>
            <span><?php _e("Archive Files"); ?></span>
        </label>
      </div>
      


        <div class="mw-ui-field-holder">
      <label class="mw-ui-label"><?php _e("Custom File Types"); ?></label>

      <input type="text" class="mw-ui-field"  name="options[file_types]" value="<?php if(isset($data['options']) and isset($data['options']['file_types']) and is_array($data['options']['file_types'])) : ?><?php

      $array2 = array("images", "documents", "archives");

      $oresult = array_diff( $data['options']['file_types'], $array2 );

      print implode(',', $oresult); ?><?php endif; ?>" placeholder='psd,html,css' />


      </div>

    </div>
    
    <label class="mw-ui-check">
    <input type="checkbox"  class="mw-custom-field-option" name="options[required]"  <?php if(isset($data['options']) == true and isset($data['options']["required"]) == true): ?> checked="checked" <?php endif; ?> value="1"><span></span><span><?php _e("Required"); ?>?</span></label>

</div>

 <div class="custom-field-settings-values">




   <?php print $savebtn; ?>

</div>



   <div class="custom-field-settings-values">



  </div>
  <?php include('settings_footer.php'); ?>
