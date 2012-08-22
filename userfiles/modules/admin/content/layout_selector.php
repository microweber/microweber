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
	
	
	
	
	
});

</script>
<strong>active_site_template</strong>
<? if($templates != false and !empty($templates)): ?>
<select name="active_site_template" id="active_site_template_<? print $rand; ?>">
  <option value="default"   <? if(('' == trim($data['active_site_template']))): ?>   selected="selected"  <? endif; ?>>Default</option>
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
<? if(!empty($layouts)): ?>
 
<select name="content_layout_file">
  <option value="inherit"   <? if(('' == trim($data['content_layout_file']))): ?>   selected="selected"  <? endif; ?>>None</option>
  <? foreach($layouts as $item): ?>
  <option value="<? print $item['content_layout_file'] ?>"  title="<? print $item['content_layout_file'] ?>"   <? if(($item['content_layout_file'] == $data['content_layout_file']) ): ?>   selected="selected"  <? endif; ?>   > <? print $item['name'] ?> </option>
  <? endforeach; ?>
</select>
<? endif; ?>
<br />
<br />
