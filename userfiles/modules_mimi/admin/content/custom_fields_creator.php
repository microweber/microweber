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




if(($params['page_id'] == false) and ($params['post_id'] != false)){
		$page_data = get_page_for_post($params['post_id']);
		$params['page_id'] = $page_data['id'];
		
		}


if(($params['page_id'])) :  ?>
<script type="text/javascript">
function save_cf($form_id){
	  data1 = ($('.'+$form_id).serialize());
	  $.ajax({
	  type: 'POST',
	  url: '<? print site_url('api/content/save_cf') ?>',
	  data: data1,
	 
	  dataType: 'html'
	});
	  

	
//	 $.ajax({
//  url: '<? print site_url('api/content/save_cf'); ?>',
//   type: "POST",
//      data: (data1),
//      
//      async:true,
//	  success: function(resp) {
//		  $("#cf_save_resp").html(resp);
//		  mw.reload_module('admin/content/custom_fields_editor');
//		
//	  }
//    });
	

}


function delete_cf($form){
	
$f = '#'+$form;
	 data1 = ($($f).serialize());
	 
//	 data1=data1+'&module=admin/content/custom_fields_creator';
//	  data1=data1+'&page_id=<? print $params['page_id'] ?>';
//	   data1=data1+'&delete=true';
//	 
//	//alert(data1);
//	
//	 $.ajax({
//  url: '<? print site_url('api/module'); ?>',
//   type: "POST",
//      data: (data1),
//      dataType: "html",
//      async:true,
//	  success: function(resp) {
//		  $("#cf_save_resp").html(resp);
//		    $($f).fadeOut();
//	  }
//    });
//	

	 
	 
	//alert(data1);
	
	 $.ajax({
  url: '<? print site_url('api/content/delete_cf'); ?>',
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


$(document).ready(function(){
$(".cf_form_holder h3").click(function(){
  $(this).parent().find(".cf_form").slideToggle();
});
});
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

 

 




?>
<? $page_data = get_page($params['page_id']);  ?>
<? 


$cf_cfg = array ();
 if($page_data != false){
		
$cf_cfg ['page_id'] = $page_data['id'];
								 
		} 




$data =  CI::model('core')->getCustomFieldsConfig($cf_cfg);
//p($data);
if($base64_val_for_insert != false){
	$data[] = $base64_val_for_insert;
} else {
	$data[] = array();
	
}



?>



<table width="100%" border="0" class="custom_fields_table">
 
<? foreach($data as $cf): ?>
<tr>
    <td><strong><? print $cf['name'] ?></strong>
 
<? print $cf['help'] ?>
    </td>
    <td>
	
	
	 <? print $cf['type'] ?>
    
    
    </td>
    <td><? print $cf['values'] ?></td>
    <td><? print $cf['param_group'] ?></td>
    <td><? print $cf['content_type'] ?></td>
    <td>edit</td>
    <td>delete</td>
</tr>
<? endforeach; ?>
</table>






<div id="cf_save_resp"></div>
<? /*
<input name="reload module" type="button"  />
*/ ?>
<? foreach($data as $item): ?>
<form class="cf_form" action="" method="post" id="cf_form_<? print $item['id'] ?>">
  <input name="id" type="hidden" class="cf_form_<? print $item['id'] ?>" value="<? print $item['id'] ?>" />
  <input name="page_id" type="hidden" class="cf_form_<? print $item['id'] ?>"  value="<? print $params['page_id'] ?>" />
  <div class="formitem">
    <label>Name:</label>
    <span class="formfield">
    <input name="name" type="text" class="cf_form_<? print $item['id'] ?>" value="<? print $item['name'] ?>"  />
    </span> </div>
  <div class="formitem">
    <label>Group:</label>
    <span class="formfield">
    <input name="param_group" type="text" class="cf_form_<? print $item['id'] ?>" value="<? print $item['param_group'] ?>"  />
    </span> </div>
  <div class="formitem">
    <label>Help:</label>
    <span class="formfield">
    <input name="help" type="text" class="cf_form_<? print $item['id'] ?>" value="<? print $item['help'] ?>"  />
    </span> </div>
  <div class="formitem">
    <label>Content Type:</label>
    <span class="formfield">
    <select name="content_type" class="cf_form_<? print $item['id'] ?>">
      <option <? if(($item['content_type']) == 'page') :  ?>  selected="selected" <? endif; ?> value="page">page</option>
      <option  <? if(($item['content_type']) == 'post') :  ?>  selected="selected" <? endif; ?> value="post">post</option>
      <option  <? if(($item['content_type']) == 'category') :  ?>  selected="selected" <? endif; ?> value="category">category</option>
      <option  <? if(($item['content_type']) == 'media') :  ?>  selected="selected" <? endif; ?> value="media">media</option>
    </select>
    </span> </div>
  <div class="formitem">
    <label>Type: </label>
    <span class="formfield">
    <input name="type" type="text" class="cf_form_<? print $item['id'] ?>" value="<? print $item['type'] ?>"  />
    </span> </div>
  <div class="formitem">
    <label>Type:</label>
    <select name="type" class="cf_form_<? print $item['id'] ?>">
      <option <? if(($item['type']) == 'text') :  ?>  selected="selected" <? endif; ?> value="text">text</option>
      <option <? if(($item['type']) == 'textarea') :  ?>  selected="selected" <? endif; ?> value="textarea">textarea</option>
      <option  <? if(($item['type']) == 'richtext') :  ?>  selected="selected" <? endif; ?> value="richtext">richtext</option>
      <option  <? if(($item['type']) == 'dropdown') :  ?>  selected="selected" <? endif; ?> value="dropdown">dropdown</option>
      <option  <? if(($item['type']) == 'radio') :  ?>  selected="selected" <? endif; ?> value="radio">radio</option>
      <option  <? if(($item['type']) == 'checkbox') :  ?>  selected="selected" <? endif; ?> value="checkbox">checkbox</option>
      <option  <? if(($item['type']) == 'date') :  ?>  selected="selected" <? endif; ?> value="date">date</option>
      <option  <? if(($item['type']) == 'category_selector') :  ?>  selected="selected" <? endif; ?> value="category_selector">category_selector</option>
      <option  <? if(($item['type']) == 'image') :  ?>  selected="selected" <? endif; ?> value="image">image</option>
    </select>
  </div>
  <div class="formitem">
    <label>Param:</label>
    <span class="formfield">
    <input name="param" type="text" class="cf_form_<? print $item['id'] ?>" value="<? print $item['param'] ?>"  />
    </span> </div>
  <div class="formitem">
    <label>Param values:</label>
    <span class="formfield">
    <input name="param_values" class="cf_form_<? print $item['id'] ?>" type="text" value="<? print $item['param_values'] ?>"  />
    </span> </div>
  <div class="formitem">
    <label>Param default: </label>
    <span class="formfield">
    <input name="param_default" class="cf_form_<? print $item['id'] ?>" type="text" value="<? print $item['param_default'] ?>"  />
    </span> </div>
  <input class="btn" name="save"  value="save <? print $item['id'] ?>" type="button" onClick="save_cf('cf_form_<? print $item['id'] ?>')" />
  <? if(($item['id']) != false) :  ?>
  <input class="btn" name="delete" value="delete <? print $item['id'] ?>" type="button" onClick="delete_cf('cf_form_<? print $item['id'] ?>')" />
  <? endif; ?>
</form>
<? endforeach; ?>
<? else :?>
You must save the page before you can edit the custom fields data.
<? endif; ?>
