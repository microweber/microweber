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
 
$post_custom_fields = $custom_fields = $post_data['custom_fields' ];

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

 if(empty($page_data) and !empty($post_custom_fields )){
	$no_page_data_only_post = true;
	
 }
?>

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

 
<? if(($params['file']) != '' or ($params['for']== 'global') or ($no_page_data_only_post== true) ) :  ?>
<? 

$layouts = CI::model('template')->layoutsList(); 
 
?>



<? if(($params['for']) == 'global' or $no_page_data_only_post  == true) :  
$layouts = array();
$layouts1 = CI::model('template')->layoutsList(); 
$layouts[] = $layouts1[0];
?>
<? endif; ?>



<? //p($post_custom_fields); ?>


<? if(!empty($layouts)): ?>
<? foreach($layouts as $layout): ?>
<? if($layout['filename'] == $params['file'] or ($params['for']== 'global') or  $no_page_data_only_post  == true): ?>
<? 

 



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



if(!empty($post_custom_fields)){
	foreach($post_custom_fields as $k=>$v){
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
		$param_id = $params['id'];
		
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
			$new_cf['param_id'] =$param_id ;
		 
	 
			//$cfg['values'] = $v;
			$layout['custom_fields'][$for][] = $new_cf;
		}
}
 


 
 
 
if(!empty($layout['custom_fields'][$for])): ?>
<script>


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
  url: '<? print site_url('api/content/delete_custom_field_by_name') ?>',
   type: "POST",
      data: data1,

      async:true,

  success: function(resp) {
($('#'+$form).fadeOut());
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

  // $('#cf_adresp').html(resp);
  mw.modal.init({html:resp});

 
 
  }
    });
	
	
	
	
	
	
	
}

</script>

 
 
<div id="cf_adresp"></div>
<? foreach($layout['custom_fields'][$for] as $item): ?>
<?   if(($item['disable_edit']) == false):  ?>


<? if($params['no_form'] == false): ?>
<form id="cf_edit_<? print $item['param'] ?>">
<? endif; ?>

    <div id="cf_<? print $item['param'] ?>">
     <table class="formtable" width="100%">




      <? // p($item); ?>
      
      
      <? if($params['no_form'] == false): ?>
      
       <input type="hidden"    name="field_id" value="<? print $item['id'] ?>" />
      <input type="hidden"    name="for" value="<? print $for; ?>" />
        <input type="hidden"    name="content_id" value="<? print $the_content_id; ?>" />
        
        
        
         <? endif; ?>

         <tr>
         <td width="400">
        <? if( $item['not_in_config']) :?>
        <? $item['name'] = str_replace('_', ' ', $item['name']);  ?>


        <input type="hidden" class="newcf"  name="new_cf_<? print $item['param'] ?>" value="<? print $custom_fields[$item['param']]? $custom_fields[$item['param']] : $item['default'] ; ?>" />

        <label><? print $item['name'] ?></label>


        <? else: ?>
        <label><? print $item['name'] ?></label>
        <? endif ?>
        <br />

        <?   if(($item['help'])):  ?>
        <small><? print $item['help'] ?></small>
        <? endif; ?>

        <small style="color:#999"><? print( $item['param_group']) ?></small>
        </td>
        <td>
      <mw module="forms/field" name="custom_field_<? print $item['param'] ?>" value="<? print $custom_fields[$item['param']]? $custom_fields[$item['param']] : $item['default'] ; ?>" values="<? print $item['values']?>" type="<? print $item['type']?>"  />
     </td> </tr>

    </table>


	    <? if($params['no_form'] == false): ?>
	  <? // p($item) ?>
        <input name="save" type="button" onclick="cf_save('cf_edit_<? print $item['param'] ?>')" value="save" />
        <? if( $item['not_in_config']) :?>



        <input name="delete" type="button" onclick="cf_delete('cf_edit_<? print $item['param'] ?>')" value="clear" />
        <? else: ?>
        <input name="delete" type="button" onclick="cf_delete('cf_edit_<? print $item['param'] ?>')" value="clear" />
         <? endif; ?>
      <? endif; ?>



    </div>
    <? endif; ?>

  <? if($params['no_form'] == false): ?>
</form>
<? endif; ?>
<? endforeach; ?>
<? endif; ?>
<? endif; ?>
<? endforeach; ?>
<? endif; ?>
<? endif; ?>

<div style="height: 20px;">&nbsp;</div>

<a href="javascript:void(0)" class="btn" onclick="cf_add()">edit custom fields settings</a>