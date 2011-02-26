

<?

$temp = $params['base64'];
if($temp){
$base64_val_for_insert = decode_var($temp)	;
} else {
	$base64_val_for_insert = false;
}

$temp = $params['parent_base64_params'];
if($temp){
$parent_params = decode_var($temp)	;

if(!empty($parent_params)){
if($params['page_id'] == false){
	$params['page_id'] = $parent_params['page_id'];
}

if($params['post_id'] == false){
	$params['post_id'] = $parent_params['post_id'];
}
}



} else {
	$parent_params = false;
}
//p($parent_params);



if(($params['page_id'] == false) and ($params['post_id'] != false)){
		$page_data = get_page_for_post($params['post_id']);
		$params['page_id'] = $page_data['id'];
		
		}


if(($params['page_id'])) :  ?>
<script type="text/javascript">
function save_cf($form){
	
$f = '#'+$form;
	 data1 = ($($f).serialize());
	 
	 data1=data1+'&module=admin/content/custom_fields_creator';
	  data1=data1+'&page_id=<? print $params['page_id'] ?>';
	   data1=data1+'&save=<? print $params['page_id'] ?>';
	 
	//alert(data1);
	
	 $.ajax({
  url: '<? print site_url('api/module'); ?>',
   type: "POST",
      data: (data1),
      dataType: "html",
      async:true,
	  success: function(resp) {
		  $("#cf_save_resp").html(resp);
		
	  }
    });
	
 

}


function delete_cf($form){
	
$f = '#'+$form;
	 data1 = ($($f).serialize());
	 
	 data1=data1+'&module=admin/content/custom_fields_creator';
	  data1=data1+'&page_id=<? print $params['page_id'] ?>';
	   data1=data1+'&delete=true';
	 
	//alert(data1);
	
	 $.ajax({
  url: '<? print site_url('api/module'); ?>',
   type: "POST",
      data: (data1),
      dataType: "html",
      async:true,
	  success: function(resp) {
		  $("#cf_save_resp").html(resp);
		    $($f).fadeOut();
	  }
    });
	
  

}
</script>
<?

//
/*$cfdir=dirname(__FILE__).'/custom_fields_config/';
$cfdir = normalize_path($cfdir);
if(is_dir($cfdir) == false){
	mkdir($cfdir);	
}
$cf_file = $cfdir.'page_'.$params['page_id'].'.php';
if(is_file($cf_file) == false){
	touch($cf_file);
}*/

//p($params);

if($params['save']){
	$src = CI::model('core')->saveCustomFieldConfig($params);
	
	print('Saved id, please reload the page:' . $src);
	return;
}

if($params['delete']){
	
	
	$src = CI::model('core')->deleteDataById('table_custom_fields_config', $params['id'], $delete_cache_group = false) ;
	
	print('Deleted id:' . $params['id']);
	return;
}




?>
<? $page_data = get_page($params['page_id']);  ?>
<? 



/*if($found == false){
			$new_cf = array();
			$new_cf['param'] = $k;
			$new_cf['name'] = ucfirst($k);
			$new_cf['default'] = $v;
			$layout['custom_fields'][$for][] = $new_cf;
		}*/




$data =  CI::model('core')->getCustomFieldsConfig();
//p($data);
if($base64_val_for_insert != false){
	$data[] = $base64_val_for_insert;
} else {
	$data[] = array();
	
}



?>
<div id="cf_save_resp"></div>
<? foreach($data as $item): ?> 
<? /// p($item); ?>
<form action="" method="post" id="cf_form_<? print $item['id'] ?>">
  <input name="id" type="hidden" value="<? print $item['id'] ?>" />
<input name="page_id" type="hidden" value="<? print $params['page_id'] ?>" />
  <br />
  <label>name:
    <input name="name" type="text" value="<? print $item['name'] ?>"  />
  </label>
   <br />
  <label>group:
    <input name="param_group" type="text" value="<? print $item['param_group'] ?>"  />
  </label>
  <br />
  <label>help:
    <input name="help" type="text" value="<? print $item['help'] ?>"  />
  </label>
  <label>content_type: 
    <select name="content_type">
      <option <? if(($item['content_type']) == 'page') :  ?>  selected="selected" <? endif; ?> value="page">page</option>
      <option  <? if(($item['content_type']) == 'post') :  ?>  selected="selected" <? endif; ?> value="post">post</option>
      <option  <? if(($item['content_type']) == 'category') :  ?>  selected="selected" <? endif; ?> value="category">category</option>
      <option  <? if(($item['content_type']) == 'media') :  ?>  selected="selected" <? endif; ?> value="media">media</option>
    </select>
  </label>
  <br />
  <label>type:
    <input name="type" type="text" value="<? print $item['type'] ?>"  />
  </label>
  
   <label>type: 
    <select name="type">
      <option <? if(($item['type']) == 'text') :  ?>  selected="selected" <? endif; ?> value="text">text</option>
      <option <? if(($item['type']) == 'textarea') :  ?>  selected="selected" <? endif; ?> value="textarea">textarea</option>
      <option  <? if(($item['type']) == 'richtext') :  ?>  selected="selected" <? endif; ?> value="richtext">richtext</option>
      <option  <? if(($item['type']) == 'dropdown') :  ?>  selected="selected" <? endif; ?> value="dropdown">dropdown</option>
      
      <option  <? if(($item['type']) == 'date') :  ?>  selected="selected" <? endif; ?> value="date">date</option>
      
       <option  <? if(($item['type']) == 'category_selector') :  ?>  selected="selected" <? endif; ?> value="category_selector">category_selector</option>
       <option  <? if(($item['type']) == 'image') :  ?>  selected="selected" <? endif; ?> value="image">image</option>
      
      
      
    </select>
  </label>
  
  
  <br />
  <label>param:
    <input name="param" type="text" value="<? print $item['param'] ?>"  />
  </label>
  <br />
  <label>param_values:
    <input name="param_values" type="text" value="<? print $item['param_values'] ?>"  />
  </label>
  <br />
  <label>param_default:
    <input name="param_default" type="text" value="<? print $item['param_default'] ?>"  />
  </label>
  <br />
  <input name="save" value="save <? print $item['id'] ?>" type="button" onClick="save_cf('cf_form_<? print $item['id'] ?>')" />
  <? if(($item['id']) != false) :  ?>
  <input name="delete" value="delete <? print $item['id'] ?>" type="button" onClick="delete_cf('cf_form_<? print $item['id'] ?>')" />
  <? endif; ?>
</form>
<hr />
<? endforeach; ?>
<? else :?>
You must save the page before you can edit the custom fields data.
<? endif; ?>
