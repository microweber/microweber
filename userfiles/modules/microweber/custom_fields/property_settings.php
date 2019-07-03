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
  <input type="text" onkeyup="" class="mw-ui-field mw-full-width" value="<?php print ($data['name']) ?>" name="name" id="input_field_label<?php print $rand; ?>">
</div>
<div class="custom-field-settings-values">
  <div class="mw-custom-field-group">
    <label class="mw-ui-label" for="value<?php print $rand; ?>">
      <?php _e("Value"); ?>
    </label>
    <div id="mw-custom-fields-text-holder">
      <textarea class="mw-ui-field mw-full-width" name="value"><?php print ($data['value']) ?></textarea>
    </div>
  </div>
  <?php print $savebtn; ?> </div>
<?php include('settings_footer.php'); ?>
