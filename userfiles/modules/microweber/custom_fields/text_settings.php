<?php include('settings_header.php'); ?>

<script type="text/javascript">

//if(typeof mw.custom_fields.text === 'undefined'){
//    mw.custom_fields.text = {}
//    mw.custom_fields.text._globalinput = mwd.createElement('input');
//    mw.custom_fields.text._globalinput.type = 'text';
//    mw.custom_fields.text._globalarea = mwd.createElement('textarea');
//}
//
//$(document).ready(function(){
//  mw.$("#mw-custom-fields-text-switch").commuter(function(){
//    var curr = mwd.querySelector('#mw-custom-fields-text-holder input');
//    mw.tools.copyAttributes(curr, mw.custom_fields.text._globalarea, ['type']);
//    curr.parentNode.replaceChild(mw.custom_fields.text._globalarea, curr);
//  }, function(){
//     var curr = mwd.querySelector('#mw-custom-fields-text-holder textarea');
//     mw.tools.copyAttributes(curr, mw.custom_fields.text._globalinput, ['type']);
//     curr.parentNode.replaceChild(mw.custom_fields.text._globalinput, curr);
//  });
//});


</script>
<style>
    #mw-custom-fields-text-holder textarea{
      resize:both;
    }
</style>
 <div class="custom-field-settings-name">
               

  <label class="mw-ui-label" for="input_field_label<?php print $rand; ?>">
    <?php _e('Title'); ?>
  </label>

    <input type="text" onkeyup="" class="mw-ui-field mw-full-width" value="<?php echo $data['name']; ?>" name="name" id="input_field_label<?php print $rand; ?>">

    <br />
    <br />

    <label class="mw-ui-check"><input type="checkbox"  class="mw-custom-field-option mw-full-width" name="options[as_text_area]"  <?php if($settings["as_text_area"]): ?> checked="checked" <?php endif; ?> value="1" id="mw-custom-fields-text-switch"><span></span><span><?php _e("Use as Text Area"); ?></span></label>
    <br />
    <br />
     
    <label class="mw-ui-check"><input type="checkbox"  class="mw-custom-field-option mw-full-width" name="options[required]"  <?php if ($settings["required"]) : ?> checked="checked" <?php endif; ?> value="1"><span></span><span><?php _e("Required"); ?>?</span></label>

</div>



   <div class="custom-field-settings-values">


    <div class="mw-custom-field-group">
      <label class="mw-ui-label" for="value<?php echo $rand; ?>"><?php _e("Value"); ?></label>
        <div id="mw-custom-fields-text-holder">
         <textarea class="mw-ui-field mw-full-width" name="value"><?php echo $data['value']; ?></textarea>
        </div>
    </div>

       <div class="mw-custom-field-group">
           <label class="mw-ui-label" for="value<?php print $rand; ?>"><?php _e("Placeholder"); ?></label>
           <div id="mw-custom-fields-text-holder">
               <input type="text" class="mw-ui-field mw-full-width" name="placeholder" value="<?php echo $data['placeholder']; ?>" />
           </div>
       </div>


       <?php print $savebtn; ?>
    
    </div>

    <?php include('settings_footer.php'); ?>
