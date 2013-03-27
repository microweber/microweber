<? only_admin_access(); ?>


<?php
    $style =  get_option('button_style', $params['id']);
    $size =  get_option('button_size', $params['id']);
    $action =  get_option('button_action', $params['id']);
    $url =  get_option('url', $params['id']);
    $popupcontent =  get_option('popupcontent', $params['id']);
?>

<style>
select{
  width: 250px;
}

.mw-iframe-editor{
  width: 100%;
  height: 200px;
  display: none;
}

#btn_url{
  display: none;
}

</style>

<script>



$(document).ready(function(){

var editor = mw.tools.iframe_editor("textarea");


btn_action = function(){
  var el = mw.$("#action");
  if(el.val()=='url'){
     $(editor).hide();
     mw.$("#btn_url").show();
  }
  else if(el.val()=='popup'){
     $(editor).show();
     mw.$("#btn_url").hide();
  }
  else{
    $(editor).hide();
     mw.$("#btn_url").hide();
  }
}




    btn_action();
mw.$("#action").change(function(){
    btn_action();
});

})

</script>

<div style="padding: 0 20px 20px;">

<div class="mw-ui-field-holder">
  <label class="mw-ui-label">Color</label>
  <select type="text"  class="mw-ui-simple-dropdown mw_option_field"  name="button_style">
      <option <?php if($style==''){ print 'selected'; } ?> value="">Default</option>
      <option <?php if($style=='btn-primary'){ print 'selected'; } ?> value="btn-primary">Dark Blue</option>
      <option <?php if($style=='btn-info'){ print 'selected'; } ?> value="btn-info">Light Blue</option>
      <option <?php if($style=='btn-success'){ print 'selected'; } ?> value="btn-success">Green</option>
      <option <?php if($style=='btn-warning'){ print 'selected'; } ?> value="btn-warning">Orange</option>
      <option <?php if($style=='btn-link'){ print 'selected'; } ?> value="btn-link">Simple</option>
  </select>
</div>
<div class="mw-ui-field-holder">
  <label class="mw-ui-label">Size</label>
  <select type="text"  class="mw-ui-simple-dropdown mw_option_field"  name="button_size">
      <option <?php if($size==''){ print 'selected'; } ?> value="">Default</option>
      <option <?php if($size=='large'){ print 'selected'; } ?> value="btn-large">Large</option>
      <option <?php if($size=='small'){ print 'selected'; } ?> value="btn-small">Small</option>
      <option <?php if($size=='mini'){ print 'selected'; } ?> value="btn-mini">Mini</option>
  </select>
</div>

<div class="mw-ui-field-holder">
  <label class="mw-ui-label">Action</label>
  <select type="text"  class="mw-ui-simple-dropdown mw_option_field" id="action"  name="button_action">
      <option <?php if($action==''){ print 'selected'; } ?> value="">None</option>
      <option <?php if($action=='url'){ print 'selected'; } ?> value="url">Link</option>
      <option <?php if($action=='popup'){ print 'selected'; } ?> value="popup">Popup</option>
  </select>
</div>

<textarea  class="mw_option_field"  name="popupcontent"><?php print $popupcontent; ?></textarea>

<input type="text" name="url" id="btn_url" value="<?php print $url; ?>" placeholder="Enter URL"  class="mw_option_field" />

</div>