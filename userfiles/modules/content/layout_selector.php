<? 
 
$rand = uniqid();
if(!isset($params["data-page-id"])){
	$params["data-page-id"] = PAGE_ID;
}

$data = get_content_by_id($params["data-page-id"]); 

if($data == false or empty($data )){
include('_empty_content_data.php');	
}

 
if(isset($params["data-active-site-template"])){
	$data['active_site_template'] = $params["data-active-site-template"] ;
}



 
 $templates= templates_list();
 
 $layout_options = array();
 $layout_options  ['site_template'] = $data['active_site_template'];
 
  $layouts = layouts_list($layout_options);

 ?>
<script>

$(document).ready(function() {
    // bind your jQuery events here initially
	$('#active_site_template_<? print $rand; ?>').bind("change", function(e) {
        // Do something exciting
		var $pmod = $(this).parent('[data-type="<? print $config['the_module'] ?>"]');
		
		 $pmod.attr('data-active-site-template',$(this).val());
		 
		 
		 
		   mw.reload_module($pmod);
		
	//	alert(1);
    });
	
	
		$('#active_site_layout_<? print $rand; ?>').bind("change", function(e) {
        // Do something exciting
	//	var $pmod = $(this).parent('[data-type="<? print $config['the_module'] ?>"]');
		
		// $pmod.attr('data-active-site-template',$(this).val());
		 
		 generate_preview<? print $rand; ?>();
		 
		//   mw.reload_module($pmod);
		
	 	//alert($(this).val());
    });
	
	function safe_chars_to_str<? print $rand; ?>(str) {
str=str.replace(/\\/g,'____');
str=str.replace(/\'/g,'\\\'');
str=str.replace(/\"/g,'\\"');
str=str.replace(/\0/g,'____');
return str;
}
	
	function generate_preview<? print $rand; ?>(){
		var $template = $('#active_site_template_<? print $rand; ?>').val();
		var $layout = $('#active_site_layout_<? print $rand; ?>').val();
		
		$template = safe_chars_to_str<? print $rand; ?>($template);
		$layout =  safe_chars_to_str<? print $rand; ?>($layout);
		
		$template = $template.replace('/','___');;
		$layout = $layout.replace('/','___');;
		
		var iframe_url = '<? print page_link($data['id']); ?>/no_editmode:true/preview_template:'+$template+'/preview_layout:'+$layout
		
		
		var $html = '<iframe src="'+iframe_url+'" class="preview_frame_small" ></iframe>'
		$('.preview_frame_wrap').html($html);
		$('#preview_frame_wrap').append(iframe_url);
		
		
		
		//alert($template+$layout );
		
	}
	
	
	generate_preview<? print $rand; ?>();
	
	
});

</script>
<strong>active_site_template</strong>
<? if($templates != false and !empty($templates)): ?>
<select name="active_site_template" id="active_site_template_<? print $rand; ?>">
  <option value="default"   <? if(('' == trim($data['active_site_template']))): ?>   selected="selected"  <? endif; ?>>Default</option>
  
    <? if(('' != trim($data['active_site_template']))): ?>
  <option value="<? print $data['active_site_template'] ?>"     selected="selected" ><? print $data['active_site_template'] ?></option>
   <? endif; ?>
  
  
  
  <? foreach($templates as $item): ?>
  <? $attrs = ''; 
 foreach($item as $k=>$v): ?>
  <? $attrs .= "data-$k='{$v}'"; ?>
  <? endforeach ?>
  <option value="<? print $item['dir_name'] ?>"    <? if ($item['dir_name'] == $data['active_site_template']): ?>   selected="selected"  <? endif; ?>   <? print $attrs; ?>  > <? print $item['name'] ?> </option>
  <? endforeach; ?>
</select>
<? endif; ?>
<br />
content_layout_file

 
<select name="content_layout_file" id="active_site_layout_<? print $rand; ?>">
  <option value="inherit"   <? if(('' == trim($data['content_layout_file']))): ?>   selected="selected"  <? endif; ?>>None</option>
   <? if(('' != trim($data['content_layout_file']))): ?>
  <option value="<? print $data['content_layout_file'] ?>"     selected="selected" ><? print $data['content_layout_file'] ?></option>
   <? endif; ?>
  
  <? if(!empty($layouts)): ?>
  <? foreach($layouts as $item): ?>
  <option value="<? print $item['content_layout_file'] ?>"  title="<? print $item['content_layout_file'] ?>"   <? if(($item['content_layout_file'] == $data['content_layout_file']) ): ?>   selected="selected"  <? endif; ?>   > <? print $item['name'] ?> </option>
  <? endforeach; ?>
  <? endif; ?>
</select>

<br />
<br />
Preview<br />
<br />




         <style type="text/css">
.preview_frame_small{
zoom: 0.15;
-moz-transform: scale(0.15);
-moz-transform-origin: 0 0;
-o-transform: scale(0.15);
-o-transform-origin: 0 0;
-webkit-transform: scale(0.15);
-webkit-transform-origin: 0 0;

width:600px;height:800px;border:5px solid black;
}
</style>
<div class="preview_frame_wrap">

  
</div>

