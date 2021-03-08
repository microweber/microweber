<?php include('settings_header.php'); ?>
<script type="text/javascript">

//if(typeof mw.custom_fields.text === 'undefined'){
//    mw.custom_fields.text = {}
//    mw.custom_fields.text._globalinput = document.createElement('input');
//    mw.custom_fields.text._globalinput.type = 'text';
//    mw.custom_fields.text._globalarea = document.createElement('textarea');
//}
//
//$(document).ready(function(){
//  mw.$("#mw-custom-fields-text-switch").commuter(function(){
//    var curr = document.querySelector('#mw-custom-fields-text-holder input');
//    mw.tools.copyAttributes(curr, mw.custom_fields.text._globalarea, ['type']);
//    curr.parentNode.replaceChild(mw.custom_fields.text._globalarea, curr);
//  }, function(){
//     var curr = document.querySelector('#mw-custom-fields-text-holder textarea');
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

<div class="custom-field-settings-values mt-3">
  <div class="mw-custom-field-group">
    <label class="control-label" for="value<?php print $rand; ?>">
      <?php _e("Value"); ?>
    </label>
      <small class="text-muted d-block mb-2"><?php _e('This attribute specifies the value of description');?></small>
      <div id="mw-custom-fields-text-holder">
      <textarea class="form-control" name="value"><?php print ($data['value']) ?></textarea>
  </div>
</div>
  <?php print $savebtn; ?> </div>
<?php include('settings_footer.php'); ?>
