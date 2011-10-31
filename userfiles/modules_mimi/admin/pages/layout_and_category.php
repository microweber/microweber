<?
 $id = $params['id'];



$form_values = get_page($id);
 
 ?>
<script type="text/javascript">
function set_layout($filename, $layout_name){

	 $('#content_layout_file').val($filename);

  $('#content_layout_name').val($layout_name);


  call_layout_config_module();
}

$(window).load(function(){
  call_layout_config_module();
  ajax_content_subtype_change();
});

function call_layout_config_module(){

 $file = $('#content_layout_file').val();
 $page_id = $('#page_id').val();
$('#auto_create_categories').val('');

 $.ajax({
  url: '<? print site_url('api/module'); ?>',
   type: "POST",
      data: ({module : 'admin/pages/layout_config' ,page_id : $page_id, file: $file }),
     // dataType: "html",
      async:false,
	  success: function(resp) {
	   $('#layout_config_module_placeholder').html(resp);
	   load_layout_config_file()
	   set_layout_icon()
	  }
    });


}

function set_layout_icon($screenshot){
	
	//$laout = load_layout_config_file(true)
	if($screenshot != undefined &&  $screenshot != ''){
			   $('#layout_screenshot').attr('src', $screenshot);
			  //  $('#layout_screenshot').append( $screenshot);
			   $('#layout_screenshot').show();
			   } else {
				   // $('#layout_screenshot').hide();
				   $('#layout_screenshot').attr('src', '<? print $config["url_to_module"]; ?>no_image.gif');
					
			   }
 
  
}

function toggle_change_layout(){
	
	//$laout = load_layout_config_file(true)
	
  
$("#change_layout_settings").toggle(  );
 

  
}


function load_layout_config_file($return){

 $file = $('#content_layout_file').val();
 $page_id = $('#page_id').val();
 $template = $('#active_site_template').val();


 $.ajax({
  url: '<? print site_url('api/content/get_layout_config'); ?>',
   type: "POST",
      data: ({filename :  $file ,page_id : $page_id, file: $file , template: $template}),
      dataType: "json",
      async:true,
	  success: function($resp) {
		  
		  
		  
	 // alert(resp);
	 if($resp != undefined){
		 
		 
		 
		    	if (window.console != undefined) {
						console.log('$resp' +$resp);
					}
			   
			   $screenshot = $resp.screenshot
			   if($screenshot != undefined){
			   $('#layout_screenshot').attr('src', $screenshot);
			  //  $('#layout_screenshot').append( $screenshot);
			   $('#layout_screenshot').show();
			   } else {
				   // $('#layout_screenshot').hide();
				   $('#layout_screenshot').attr('src', '<? print $config["url_to_module"]; ?>no_image.gif');
					
			   }
			   
			   
		 
		 
		 
		 
		 
		 
		 
		 
		 
		 
		 
		 
		 
		 
		 
		 
		 
	  $check_existing_cat = $('#content_subtype_value').val();
	 
	
		 if($resp.type != undefined){
		 $type_from_cfg = $resp.type
		 
		    $('#content_subtype').val( $type_from_cfg);
			
			
			content_subtype_show_info()
		 
		  if($type_from_cfg == 'dynamic'){
			  
			   if($check_existing_cat == ''){
			  $layout_name1 = $('#content_layout_name').val();
			   $('#content_subtype_value_new').val( $layout_name1);
			
			   
			
			   
			   $subcats_from_cfg = $resp.auto_create_categories
			   if($subcats_from_cfg != undefined){
				  // alert($subcats_from_cfg);
				  $('#auto_create_categories').val( $subcats_from_cfg);
				  
			   }
			   
			   } // if($check_existing_cat == ''){
		  }
		 }
	 
	 }
	 
	 
	 
	 
	  }
	  
	  
	  
	  
	  
	  
	  
	  
    });


}
$(document).ready(function () {
  //
 set_layout_icon()   
 });


</script>


   <div class="formitem" >
  <label>Layout</label>
  <span class="formfield">
 <? 

$template_options = array();
//p($params);
if($params['active_site_template'] != ''){
$template_dir = $params['active_site_template'];	
$template_options['site_template'] = $template_dir;
} else {
	$template_dir = false;	
	
}

?>
<? $layouts = CI::model('template')->layoutsList($template_options);  

//p( $layouts);

?>
<? if(!empty($layouts)): ?>
<select name="layoutsList" id="mw_layoutsList">
  <option>Inherit</option>
  <? foreach($layouts as $layout): ?>
  <? if($layout['screenshot']): ?>
  <!-- <a href="<? print $layout['screenshot'] ?>"> <img src="<? print $layout['screenshot'] ?>" height="100" /></a>-->
  <? else: ?>
 <? $screen_class = ''?>
  <? endif; ?>
  
  
 
  
  
  
  <option  onclick="set_layout(this.value, '<? print $layout['layout_name'] ?>')"    onmouseover="set_layout_icon('<? print $layout['screenshot'] ?>')"       <? if($form_values['content_layout_name'] == $layout['layout_name']): ?>   selected="selected"  <? endif; ?>   layout_name="<? print $layout['layout_name'] ?>" value="<? print $layout['filename'] ?>"><? print $layout['name'] ?> (<? print $layout['layout_name'] ?>)</option>
  
  <? endforeach; ?>
</select>
<? endif; ?>
 
  </span> </div>

<a href="javascript:toggle_change_layout()" class="underline"><small>advanced</small></a>
 



  
  
 <div id="change_layout_settings" style="display:none">


<h3>Layout dir</h3>
 <input name="content_layout_name" type="text" id="content_layout_name" value="<? print $form_values['content_layout_name'] ?>" />

<label>Filename</label>
<input name="content_filename" type="text" value="<? print $form_values['content_filename'] ?>" />
<!--<legend>Content sub type</legend>-->
<div class="formitem">
  <label>Page File</label>
  <div class="formfield">
    <input name="content_layout_file" type="text" id="content_layout_file" value="<? print $form_values['content_layout_file'] ?>" />
  </div>

</div>



<div id="layout_config_module_placeholder"></div>

  </div>

  
  

<div class="c">&nbsp;</div>
