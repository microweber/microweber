<?

 
//p($params);



?>
<? if(isset($params['page_id'])) :  ?>
<? $page_data = get_page($params['page_id']); 
$custom_fields = $page_data['custom_fields' ];
$the_content_id = $page_data['id'];
$for = 'page';
?>
<? endif; ?>
<? if(isset($params['post_id'])) :  ?>
<? $post_data = get_post($params['post_id']); 
$custom_fields = $post_data['custom_fields' ];
$the_content_id = $post_data['id'];
$for = 'post';
?>
<? endif; ?>
<? if(isset($params['post_id'])) :  ?>
<? $post_data = get_post($params['post_id']); 
$custom_fields = $post_data['custom_fields' ];

$the_content_id = $post_data['id'];

$for = 'post';
?>
<? endif; ?>
<? if(isset($params['category_id'])) :  ?>
<? $cat = get_category($params['category_id']);
 $catetogy_id = $cat['id'];
$custom_fields = $cat['custom_fields' ];
$for = 'category';
?>
<? endif; ?>
<? 

if($params['file'] == ''){
	if($page_data == false){
		if(($params['page_id'] == false) and ($params['post_id'] != false)){
		$page_data = get_page_for_post($params['post_id']);
		$params['page_id'] = $page_data['id'];
		
		}
	
	}
	
	
	$params['file'] =$page_data['content_layout_file'];
}
?>
<? // p($params); ?>
<? if(($params['file']) != '' or ($params['for']== 'global')) :  ?>
<? 

$layouts = CI::model('template')->layoutsList(); 
 
?>
<? if(($params['for']) == 'global') :  
$layouts = array();
$layouts1 = CI::model('template')->layoutsList(); 
$layouts[] = $layouts1[0];
?>
<? endif; ?>
<? if(!empty($layouts)): ?>
<? foreach($layouts as $layout): ?>
<? if($layout['filename'] == $params['file'] or ($params['for']== 'global')): ?>
<? 

// p($custom_fields);
//p($layout['custom_fields'][$for]);


 $custom_fields_from_page_config = array();
 
 $custom_fields_from_page_config['page_id'] =$params['page_id'];
 if($for){
  $custom_fields_from_page_config['content_type'] =$for;
 }
 //var_dump($custom_fields_from_page_config); 
 $custom_fields_from_page_config =  CI::model('core')->getCustomFieldsConfig( $custom_fields_from_page_config);
 //
 if(!empty($custom_fields_from_page_config)){
	foreach($custom_fields_from_page_config as $cfg){
		$k = $cfg['param'];
		
		$found = false;
		foreach($layout['custom_fields'][$for] as $item){
			if($item['param'] ==$k ){
			$found = true;
			}
		}
		if($found == false){
			if($cfg['content_type'] == $for){
			$new_cf = array();
			// p($cfg);
			//p($for);
			$cfg['values'] = $cfg['param_values'];
			$cfg['default'] = $cfg['param_default'];
			
		$new_cf = $cfg;
			$layout['custom_fields'][$for][] = $new_cf;
			}
		}

	}
}
 
if(!empty($custom_fields)){
	foreach($custom_fields as $k=>$v){
		$param_to_find = $k;
		
		$found = false;
		foreach($layout['custom_fields'][$for] as $item){
			if($item['param'] ==$k ){
			$found = true;
			}
		}
		if($found == false){
			$new_cf = array();
			$new_cf['param'] = $k;
			$new_cf['name'] = ucfirst($k);
			$new_cf['not_in_config'] =true;
			$new_cf['default'] = $v;
			$new_cf['content_type'] =$for ;
		 
			
			//$cfg['values'] = $v;
			$layout['custom_fields'][$for][] = $new_cf;
		}

	}
}

 if(!empty($params)){
	
		
		
		
		$param_to_find = $params['param'];
		$for1 = $params['for'];
		$name1 = $params['name'];
		
		$found = false;
		foreach($layout['custom_fields'][$for] as $item){
			if($item['param'] ==$k ){
			$found = true;
			}
		}
		if($found == false){
			
			
			$new_cf = array();
			$new_cf['param'] = $param_to_find;
			$new_cf['name'] =      ucfirst($param_to_find);
			$new_cf['not_in_config'] =true;
			$new_cf['default'] = $param_to_find;
			$new_cf['content_type'] =$for1 ;
		 
			//p($new_cf);
			//$cfg['values'] = $v;
			$layout['custom_fields'][$for][] = $new_cf;
		}
}
 

 
 
 
 
if(!empty($layout['custom_fields'][$for])): ?>
<script type="text/javascript">


function cf_save($form){
	
	data1 = ($('#'+$form).serialize());
	
	
	
 
   $.ajax({
  url: '<? print site_url('api/content/save_field_simple') ?>',
   type: "POST",
      data: data1,

      async:true,

  success: function(resp) {

  // $('#cf_adresp').html(resp);

 
 
  }
    });
	
	
	
}


function cf_delete($form){
	
	data1 = ($('#'+$form).serialize());
	
	
	
 
   $.ajax({
  url: '<? print site_url('api/content/delete_custom_field') ?>',
   type: "POST",
      data: data1,

      async:true,

  success: function(resp) {

  // $('#cf_adresp').html(resp);

 
 
  }
    });
	
	
	
}




function cf_add($tr_id){
	//alert($('#'+$tr_id).serialize());
	//alert($tr_id);
	
	
				 data1 = {}
   data1.module = 'admin/content/custom_fields_creator';
   if(typeof $tr_id  != undefined){
   data1.base64 = $tr_id;
   }
    data1.parent_base64_params = '<? print encode_var($params)  ?>';

 
   $.ajax({
  url: '<? print site_url('api/module') ?>',
   type: "POST",
      data: data1,

      async:true,

  success: function(resp) {

   $('#cf_adresp').html(resp);

 
 
  }
    });
	
	
	
	
	
	
	
}

</script>

<div id="cf_adresp"></div>
<? foreach($layout['custom_fields'][$for] as $item): ?>
<?   if(($item['disable_edit']) == false):  ?>
<form id="cf_edit_<? print $item['param'] ?>">
  <table width="100%" border="0">
    <tr id="cf_<? print $item['param'] ?>">
    
      <td>
      <? // p($item); ?>
      
      <input type="hidden"    name="for" value="<? print $for; ?>" />
        <input type="hidden"    name="content_id" value="<? print $the_content_id; ?>" />
        <? if( $item['not_in_config']) :?>
        <? $item['name'] = str_replace('_', ' ', $item['name']);  ?>
        <input type="hidden" class="newcf"  name="new_cf_<? print $item['param'] ?>" value="<? print $custom_fields[$item['param']]? $custom_fields[$item['param']] : $item['default'] ; ?>" />
        <a href="#" onclick="cf_add('<? print encode_var($item)  ?>')">add</a>
        <h4><? print $item['name'] ?></h4>
        <? else: ?>
        <h4><? print $item['name'] ?></h4>
        <? endif ?>
        <?   if(($item['help'])):  ?>
        <small><? print $item['help'] ?></small>
        <? endif; ?>
        <small style="color:#999"><? print( $item['param_group']) ?></small></td>
      <td><mw module="forms/field" name="custom_field_<? print $item['param'] ?>" value="<? print $custom_fields[$item['param']]? $custom_fields[$item['param']] : $item['default'] ; ?>" type="<? print $item['type']?>" quick_edit="true" /></td>
      <td><? // p($item) ?>
        <input name="save" type="button" onclick="cf_save('cf_edit_<? print $item['param'] ?>')" value="save" />
        
        <input name="delete" type="button" onclick="cf_delete('cf_edit_<? print $item['param'] ?>')" value="save" />
        
        
        </td>
    </tr>
    <? endif; ?>
  </table>
</form>
<? endforeach; ?>
<? endif; ?>
<? endif; ?>
<? endforeach; ?>
<? endif; ?>
<? endif; ?>
<a href="#" onclick="cf_add()">edit custom fields</a> 