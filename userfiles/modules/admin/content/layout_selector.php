<? 

if(!isset($params["data-page-id"])){
	$params["data-page-id"] = PAGE_ID;
}

$data = get_content_by_id($params["data-page-id"]); 

if($data == false or empty($data )){
include('_empty_content_data.php');	
}
 
 $templates= templates_list();
  $layouts = layouts_list();

 ?>

<strong>active_site_template</strong>
<input name="active_site_template"  type="text" value="<? print ($data['active_site_template'])?>" />
<? if($templates != false and !empty($templates)): ?>
<ul>
  <? foreach($templates as $template): ?>
  <? $attrs = ''; 
 foreach($template as $k=>$v): ?>
  <? $attrs .= "data-$k='{$v}'"; ?>
  <? endforeach ?>
  <li <? print $attrs; ?>><? print  $template["name"] ?></li>
  <? endforeach ?>
</ul>
<? endif; ?>
<br>
---------


<strong>content_layout_name</strong>
<input name="content_layout_name"  type="text" value="<? print ($data['content_layout_name'])?>" />
<? d($layouts);  ?>
<? if($layouts == 'asdasdasdsa' and !empty($layouts)): ?>
<ul>
  <? foreach($layouts as $template): ?>
  <? $attrs = ''; 
 foreach($template as $k=>$v): ?>
  <? $attrs .= "data-$k='{$v}'"; ?>
  <? endforeach ?>
  <li <? print $attrs; ?>><? print  $template["name"] ?></li>
  <? endforeach ?>
</ul>
<? endif; ?>





 
content_layout_file
<input name="content_layout_file"  type="text" value="<? print ($data['content_layout_file'])?>" />
<br />
<br />
