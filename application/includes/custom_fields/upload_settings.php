<?php include('settings_header.php'); ?>




 <div class="custom-field-col-left">

    <div class="mw-custom-field-group ">
      <label class="mw-ui-label" for="input_field_label<?php print $rand; ?>">
        <?php _e('Define Title'); ?>
      </label>

        <input type="text" class="mw-ui-field" value="<?php print ($data['custom_field_name']) ?>" name="custom_field_name" id="input_field_label<?php print $rand; ?>">

    </div>


    <label class="mw-ui-label"><small>Allowable Format for upload</small></label>


    <div class="mw-custom-fields-upload-filetypes">

      <label class="mw-ui-check">




      <input type="checkbox"  name="options[file_types]" <?php if(isset($data['options']) and isset($data['options']['file_types']) and in_array('images',$data['options']['file_types'])) : ?> checked <?php endif; ?> value="images"  />
          <span></span>
          <span>Images Files</span>
      </label>
      <div class="vSpace"></div>
      <label class="mw-ui-check">
          <input type="checkbox"  name="options[file_types]" <?php if(isset($data['options']) and isset($data['options']['file_types']) and in_array('documents',$data['options']['file_types'])) : ?> checked <?php endif; ?>  value="documents" />
          <span></span>
          <span>Document Files</span>
      </label>
       <div class="vSpace"></div>
      <label class="mw-ui-check">
          <input type="checkbox"  name="options[file_types]" <?php if(isset($data['options']) and isset($data['options']['file_types']) and in_array('archives',$data['options']['file_types'])) : ?> checked <?php endif; ?>  value="archives" />
          <span></span>
          <span>Archive Files</span>
      </label>
      <div class="vSpace"></div>

      <div class="vSpace"></div>

      <label class="mw-ui-label">Custom File Types</label>

      <input type="text" class="mw-ui-field"  name="options[file_types]" value="<?php if(isset($data['options']) and isset($data['options']['file_types']) and is_array($data['options']['file_types'])) : ?><?

      $array2 = array("images", "documents", "archives");
$oresult = array_diff($data['options']['file_types'], $array2);
       print implode(',',$oresult); ?><?php endif; ?>" placeholder='psd,html,css' />

    </div>

</div>

 <div class="custom-field-col-right">




   <?php print $savebtn; ?>

</div>



   <div class="custom-field-col-right">



  </div>
  <?php include('settings_footer.php'); ?>
