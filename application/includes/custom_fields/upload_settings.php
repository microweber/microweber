<? include('settings_header.php'); ?>


 <div class="custom-field-col-left">

    <div class="mw-custom-field-group ">
      <label class="mw-ui-label" for="input_field_label<? print $rand ?>">
        <?php _e('Define Title'); ?>
      </label>

        <input type="text" class="mw-ui-field" value="<? print ($data['custom_field_name']) ?>" name="custom_field_name" id="input_field_label<? print $rand ?>">
 
    </div>


    <label class="mw-ui-label"><small>Allowable Format for upload</small></label>


    <div class="mw-custom-fields-upload-filetypes">

      <label class="mw-ui-check">
          <input type="checkbox" />
          <span></span>
          <span>Images Files</span>
      </label>
      <div class="vSpace"></div>
      <label class="mw-ui-check">
          <input type="checkbox" />
          <span></span>
          <span>Document Files</span>
      </label>
       <div class="vSpace"></div>
      <label class="mw-ui-check">
          <input type="checkbox" />
          <span></span>
          <span>Archive Files</span>
      </label>
      <div class="vSpace"></div>

      <div class="vSpace"></div>

      <label class="mw-ui-label">Custom File Types</label>

      <input type="text" class="mw-ui-field" value="psd,html,css" data-default='psd,html,css' onfocus="mw.form.dstatic(event);" onblur="mw.form.dstatic(event);" />

    </div>

</div>

 <div class="custom-field-col-right">


    <label class="mw-ui-label">Click here to upload</label>
    <div style="width: 170px;height: 16px;" class="mw-ui-field relative left">
        <span style="height: 15px;position: absolute; right: -1px; top: -1px;" id="insert_email" class="mw-ui-btn">Browse</span>
    </div>
    
   <?php print $savebtn; ?>

</div>



   <div class="custom-field-col-right">



  </div>
  <? include('settings_footer.php'); ?>
