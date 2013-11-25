<?php only_admin_access(); ?>


<?php
    $style =  get_option('button_style', $params['id']);
    $size =  get_option('button_size', $params['id']);
    $action =  get_option('button_action', $params['id']);
    $url =  get_option('url', $params['id']);
    $popupcontent =  get_option('popupcontent', $params['id']);
    $text =  get_option('text', $params['id']);
	 
?>

<style>
select{
  width: 390px;
}


#editor_holder{
  display: none;
  width: 392px;
}


.mw-iframe-editor{
  width: 100%;
  height: 300px;
}

input[type='text']{
   width: 370px;
}

</style>

<script>



$(document).ready(function(){

var editor = mw.tools.iframe_editor("textarea");


btn_action = function(){
  var el = mw.$("#action");
  if(el.val()=='url'){
     $("#editor_holder").hide();
     mw.$("#btn_url_holder").show();
  }
  else if(el.val()=='popup'){
     $("#editor_holder").show();
     mw.$("#btn_url_holder").hide();
  }
  else{
    $("#editor_holder").hide();
     mw.$("#btn_url_holder").hide();
  }
}




    btn_action();
    mw.$("#action").change(function(){
        btn_action();
    });

});

</script>

<div style="padding: 0 20px 20px;">
<div class="mw-ui-field-holder">
 <label class="mw-ui-label"><?php _e("Text"); ?></label>
 <input type="text" name="text" class="mw_option_field mw-ui-field" value="<?php print $text; ?>" placeholder="<?php _e("Button"); ?>" />
</div>


<div class="mw-ui-field-holder">
  <label class="mw-ui-label"><?php _e("Action"); ?></label>
  <div class="mw-ui-select"><select class="mw_option_field" id="action"  name="button_action">
      <option <?php if($action==''){ print 'selected'; } ?> value=""><?php _e("None"); ?></option>
      <option <?php if($action=='url'){ print 'selected'; } ?> value="url"><?php _e("Go to link"); ?></option>
      <option <?php if($action=='popup'){ print 'selected'; } ?> value="popup"><?php _e("Open a pop-up window"); ?></option>
  </select></div>
</div>

<div id="editor_holder">

<textarea  class="mw_option_field"  name="popupcontent" style="height: 400px;"><?php print $popupcontent; ?></textarea>

</div>
<div id="btn btn-default_url_holder">
    <input type="text" name="url" id="btn btn-default_url" value="<?php print $url; ?>" placeholder="<?php _e("Enter URL"); ?>"  class="mw_option_field mw-ui-field" />
    <div class="vSpace"></div>
    <label class="mw-ui-check"><input type="checkbox" name="url_blank" value="y" class="mw_option_field"><span></span><span><?php _e("Open in new window"); ?></span></label>


</div>
 
<div class="mw-ui-field-holder">
  <label class="mw-ui-label"><?php _e("Color"); ?></label>
  <div class="mw-ui-select"><select  class="mw_option_field"  name="button_style">
      <option <?php if($style==''){ print 'selected'; } ?> value=""><?php _e("Default"); ?></option>
      <option <?php if($style=='btn-primary'){ print 'selected'; } ?> value="btn-primary"><?php _e("Primary"); ?></option>
      <option <?php if($style=='btn-info'){ print 'selected'; } ?> value="btn-info"><?php _e("Info"); ?></option>
      <option <?php if($style=='btn-success'){ print 'selected'; } ?> value="btn-success"><?php _e("Success"); ?></option>
      <option <?php if($style=='btn-warning'){ print 'selected'; } ?> value="btn-warning"><?php _e("Warning"); ?></option>
	  <option <?php if($style=='btn-danger'){ print 'selected'; } ?> value="btn-danger"><?php _e("Danger"); ?></option>

	  
 
	  
      <option <?php if($style=='btn-link'){ print 'selected'; } ?> value="btn-link"><?php _e("Simple"); ?></option>
  </select></div>
</div>
<div class="mw-ui-field-holder">
  <label class="mw-ui-label"><?php _e("Size"); ?></label>
  <div class="mw-ui-select"><select  class="mw_option_field"  name="button_size">
      <option <?php if($size==''){ print 'selected'; } ?> value=""><?php _e("Default"); ?></option>
      <option <?php if($size=='btn-default-large btn-lg'){ print 'selected'; } ?> value="btn-default-large btn-lg"><?php _e("Large"); ?></option>
      <option <?php if($size=='btn-default-small btn-sm'){ print 'selected'; } ?> value="btn-default-small btn-sm"><?php _e("Small"); ?></option>
      <option <?php if($size=='btn-default-mini btn-xs'){ print 'selected'; } ?> value="btn-default-mini btn-xs"><?php _e("Mini"); ?></option>
  </select></div>
</div>

</div>