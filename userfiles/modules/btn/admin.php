<? only_admin_access(); ?>


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
  width: 250px;
}


#editor_holder{
  display: none;
}


.mw-iframe-editor{
  width: 100%;
  height: 200px;
}

#btn_url{
   width: 230px;
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

})

</script>

<div style="padding: 0 20px 20px;">


<div class="mw-ui-field-holder">
  <label class="mw-ui-label">Action</label>
  <select class="mw-ui-simple-dropdown mw_option_field" id="action"  name="button_action">
      <option <?php if($action==''){ print 'selected'; } ?> value="">None</option>
      <option <?php if($action=='url'){ print 'selected'; } ?> value="url">Link</option>
      <option <?php if($action=='popup'){ print 'selected'; } ?> value="popup">Popup</option>
  </select>
</div>

<div id="editor_holder">

<textarea  class="mw_option_field"  name="popupcontent"><?php print $popupcontent; ?></textarea>

</div>
<div id="btn_url_holder">
    <input type="text" name="url" id="btn_url" value="<?php print $url; ?>" placeholder="Enter URL"  class="mw_option_field" />
    <div class="vSpace"></div>
    <label class="mw-ui-check"><input type="checkbox" name="url_blank" value="y" class="mw_option_field"><span></span><span>Open in new window</span></label>


</div>

<div class="mw-ui-field-holder">
  <label class="mw-ui-label">Color</label>
  <select  class="mw-ui-simple-dropdown mw_option_field"  name="button_style">
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
  <select  class="mw-ui-simple-dropdown mw_option_field"  name="button_size">
      <option <?php if($size==''){ print 'selected'; } ?> value="">Default</option>
      <option <?php if($size=='large'){ print 'selected'; } ?> value="btn-large">Large</option>
      <option <?php if($size=='small'){ print 'selected'; } ?> value="btn-small">Small</option>
      <option <?php if($size=='mini'){ print 'selected'; } ?> value="btn-mini">Mini</option>
  </select>
</div>
 <label class="mw-ui-label">Text</label>
 <input type="text" name="text" class="mw_option_field" value="<?php print $text; ?>" placeholder="Button" />

</div>