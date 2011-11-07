<?
// p($params);
$cf_cfg = array ();

if(intval($params['id'] ) == 0){
	 $params ['id'] = ($params['cf_id'] ) ;	
	
}
 if($page_data != false){
$cf_cfg ['page_id'] = $page_data['id'];				 
		} 
		
		
		
		if(($params['post_id']) > 0){
			
			$cf_cfg ['post_id'] = $params['post_id'];
			
		}
		
		
		
		
	 if($params['id'] != false){
		 
		  if(intval($params['id'] ) == 0){
			 $new = true;
			
		 }

		 
		 
$cf_cfg ['id'] = ($params['id'] ) ;				 
		} 	
		if(intval($cf_cfg ['id'] )== 0){
			  $cf_cfg ['id'] = ($params['cf_id'] ) ;	
		}
		   
		 
//p($cf_cfg);		




$data =  CI::model('core')->getCustomFieldsConfig($cf_cfg);


if(empty($data )){
	if(intval($params['post_id']) > 0){
		if(intval($cf_cfg['id']) > 0){
			$cf_cfg2 = array ();
			unset($cf_cfg['post_id']);
			$data =  CI::model('core')->getCustomFieldsConfig($cf_cfg);
			$new_for_post = true;
		}		
	} 
}




//p($data);
?>
<? if(($item['post_id'])> 0 ): ?>
<? else : ?>
<? $item['id'] =date('YmdHis'); 
 

 ?>
<? endif; ?>
<script type="text/javascript">
function save_cf($form_id){
	 // data1 = ($('.'+$form_id).serialize());
	 
	  data1 = ($('.cf_form').serialize());
	 
	  $.ajax({
	  type: 'POST',
	  url: '<? print site_url('api/content/save_cf') ?>',
	  data: data1,
	   success: function(){
   mw.reload_module('admin/content/custom_fields');
   
   mw.reload_module('content/custom_fields');
    mw.reload_module('forms/mail_form');
   
   
   
  } 
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
			 mw.reload_module('admin/content/custom_fields');
			  mw.reload_module('content/custom_fields');
	  }
    });
	
	
	
  

}
<? 
if($new == true){
	$data2 = array();
$data2[] = array();	
$data = $data2;
}
?>



$(document).ready(function() {
		  $t = $('*[name="type"]').val(); 
		   cf_type_set($t)
		   $('.custom_fields_table_edit_cf tr').hide();
		   $('.custom_fields_table_edit_cf .cf_values_row').show();
		   
		   
		   
});



function cf_type_set_show_all_types($form_selector){
	
	$('.cf_form_select_type li', $form_selector).show();
	$('.cf_form_type_selector_arrow', $form_selector).hide();
	
	$($form_selector).find('a[old_href!=""]').each(function(index){
	$v1 = this.getAttribute('old_href');
	
	if($v1 != undefined && $v1 != null && $v1 != ''){
	
		  this.setAttribute('href',$v1);
		   this.setAttribute('old_href','');
	 
	}
 
});

	
	
	
	
	
	
	
	
	
}

function edit_cf_show_advanced(){
	
	   $('.custom_fields_table_edit_cf tr').toggle();
		   $('.custom_fields_table_edit_cf .cf_values_row').show();
}


function close_cf($id){
	
	$('#cf_edit_btn_id_'+$id).fadeIn();
	
	$('#cf_edit_resp_id_'+$id).fadeOut();
}


function cf_type_set($type, $form_selector){
	
	
	if($form_selector == undefined){
	$form_selector = '.cf_form';	
	}
	
	
	$('.cf_form_select_type li').hide();
	
	  $('*[name="type"]' , $form_selector).val($type);
	  $('*[name="type"]' , $form_selector).val($type);
	
	 $('.active' , $form_selector).removeClass('active');
	 $('.cf_type_select_'+$type , $form_selector).addClass('active');
	 
	 $old_hr = $('.cf_type_select_'+$type , $form_selector).attr('href');
	 
	 
	 $('.cf_type_select_'+$type , $form_selector).attr('old_href', $old_hr);
	  $('.cf_type_select_'+$type , $form_selector).attr('href',"javascript:cf_type_set_show_all_types('"+$form_selector+"')");
	 
	 
	 
	 
	 
	  $('.cf_type_select_'+$type+'').parents('li').show();
	 $('.cf_form_type_selector_arrow', $form_selector).show();
	 
	 if($type =='text'){
		 $('*[name="param_values"]' , $form_selector).hide();
	 } else {
		 		 $('*[name="param_values"]' , $form_selector).show();

		 
	 }
	 
	param_default_v =  $('*[name="param_default"]' , $form_selector).val();
	param_values_v =  $('*[name="param_values"]' , $form_selector).val();
	cf_id =  $('*[name="id"]' , $form_selector).val();
	 
	 data1 = {}
	data1.module = 'admin/content/custom_fields/' + $type;
	data1.param_default =param_default_v;
	data1.param_values =param_values_v;
	data1.cf_id =cf_id;
	
	$('.mw_cf_values_edit_by_type').load('<? print site_url('api/module') ?>',data1);
	$('.mw_cf_values_edit_by_type').show();
	 
	 
	 
	 
	 
	 
	 
	 
	
	
}


</script>
<? foreach($data as $item): ?>
<?

// p( $item);
?>

<form class="cf_form" action="" method="post" id="cf_form_<? print $item['id'] ?>">
  <? if(($params['post_id'])> 0 ): ?>
  <? if(($item['post_id'])> 0 ): ?>
  <input name="id" type="hidden" class="cf_form_<? print $item['id'] ?>" value="<? print intval($item['id']) ?>" />
  <? else : ?>
  <? //$item['id'] =date('YmdHis'); 
 
 // p($item);
 ?>
  <input name="id" type="hidden" class="cf_form_<? print $item['id'] ?>" value="<? print intval($item['id']) ?>" />
  <? endif; ?>
  <? else : ?>
  <input name="id" type="hidden" class="cf_form_<? print $item['id'] ?>" value="<? print intval($item['id']) ?>" />
  <? endif; ?>
 
  <input name="field_order" type="hidden" class="cf_form_<? print $item['id'] ?>" value="<? print $item['field_order'] ?>" />
  <? if(($params['post_id'])): ?>
  <input name="post_id" type="hidden" class="cf_form_<? print $item['id'] ?>"  value="<? print $params['post_id'] ?>" />
  <?  else:  ?>
  <input name="page_id" type="hidden" class="cf_form_<? print $item['id'] ?>"  value="<? print $params['page_id'] ?>" />
  <? endif;  ?>
  <h3>Editing custom field <strong><? print $item['name'] ?></strong></h3>
  <input name="param" type="hidden" class="cf_form_<? print $item['id'] ?>" value="<? print $item['param'] ?>"  />
  <table width="80%" border="0" cellspacing="4" cellpadding="4">
    <tr>
      <td><? print $item['type'] ?> (<small><a class="blue" href="javascript:edit_cf_show_advanced()">Edit</a></small>)</td>
    </tr>
    <tr>
      <td><? print $item['help'] ?></td>
    </tr>
  </table>
  <table border="0" class="custom_fields_table_edit_cf">
    <tr>
      <td><span class="darkblue">Name:</span>
        <div class="formitem"><span class="formfield">
          <input name="name" type="text" class="cf_form_<? print $item['id'] ?>" value="<? print $item['name'] ?>"  />
          </span> </div></td>
    </tr>
    <tr>
      <td><span class="darkblue">Type:</span>
        <div class="formitem cf_form_type_selector">
          <input name="type" type="hidden" class="cf_form_<? print $item['id'] ?>"  value="<? print $item['type'] ?>" />
          <ul class="cf_form_select_type cf_form_type_selector" >
            <li> <a class="btn_big cf_type_select_dropdown" href="javascript:cf_type_set('dropdown', '#cf_form_<? print $item['id'] ?>')" ><img height="16"  src="<?php   print( ADMIN_STATIC_FILES_URL);  ?>img/dropdown.png" />Dropdown</a> </li>
            <li> <a class="btn_big cf_type_select_checkbox" href="javascript:cf_type_set('checkbox', '#cf_form_<? print $item['id'] ?>')" ><img height="16" src="<?php   print( ADMIN_STATIC_FILES_URL);  ?>img/checkbox.png" />Checkbox</a> </li>
            <li> <a class="btn_big cf_type_select_comment" href="javascript:cf_type_set('textarea', '#cf_form_<? print $item['id'] ?>')" ><img height="16"  src="<?php   print( ADMIN_STATIC_FILES_URL);  ?>img/silk/accessories-text-editor.png" />Text area</a> </li>
            <li> <a class="btn_big cf_type_select_images" href="javascript:cf_type_set('images', '#cf_form_<? print $item['id'] ?>')" ><img height="16" src="<?php   print( ADMIN_STATIC_FILES_URL);  ?>img/image.png" />Images</a> </li>
            <li> <a class="btn_big cf_type_select_link" href="javascript:cf_type_set('link', '#cf_form_<? print $item['id'] ?>')" ><img height="16" src="<?php   print( ADMIN_STATIC_FILES_URL);  ?>img/link.png" />Link</a> </li>
            <li> <a class="btn_big cf_type_select_price" href="javascript:cf_type_set('price', '#cf_form_<? print $item['id'] ?>')" ><img height="16" src="<?php   print( ADMIN_STATIC_FILES_URL);  ?>img/silk/money_add.png" />Price</a> </li>
            <li> <a class="btn_big cf_type_select_text" href="javascript:cf_type_set('text', '#cf_form_<? print $item['id'] ?>')" ><img src="<?php   print( ADMIN_STATIC_FILES_URL);  ?>img/silk/accessories-text-editor.png" height="16"  />Simple text</a> </li>
          </ul>
        </div>
        <a  href="javascript:cf_type_set_show_all_types('#cf_form_<? print $item['id'] ?>')" ><img height="28" class="cf_form_type_selector_arrow" width="28" src="<?php   print( ADMIN_STATIC_FILES_URL);  ?>img/arr_down.png" /></a></td>
    </tr>
    <tr class="cf_values_row">
      <td><span class="darkblue">Values:</span>
        <div class="formitem"> <span class="formfield">
          <div class="mw_cf_values_edit_by_type">
            <microweber module="admin/content/custom_fields/<? print $item['type'] ?>"  cf_id="<? print $item['id'] ?>" param_default="<? print $item['param_default'] ?>" param_values="<? print $item['param_values'] ?>" />
          </div>
          <!--
          <input name="param_values" class="cf_form_<? print $item['id'] ?>" type="text" value="<? print $item['param_values'] ?>"  />
          
          <input name="param_default" class="cf_form_<? print $item['id'] ?>" type="text" value="<? print $item['param_default'] ?>"  />
          -->
          </span> </div></td>
    </tr>
    <tr style="display:none">
      <td><span class="darkblue">Group:</span>
        <div class="formitem"> <span class="formfield">
          <input name="param_group" type="text" class="cf_form_<? print $item['id'] ?>" value="<? print $item['param_group'] ?>"  />
          </span> </div></td>
    </tr>
    <tr>
      <td><span class="darkblue">Help:</span>
        <div class="formitem"> <span class="formfield">
          <input name="help" type="text" class="cf_form_<? print $item['id'] ?>" value="<? print $item['help'] ?>"  />
          </span> </div></td>
    </tr>
    </tr>
    
  </table>
  <input class="btn" name="save"  value="Save" type="button" onClick="save_cf('cf_form_<? print $item['id'] ?>')" />
  <? if(($item['id']) != false) :  ?>
  <a class="xbtn"  href="javascript:delete_cf('cf_form_<? print $item['id'] ?>')" >Delete</a> <a class="xbtn"  href="javascript:close_cf('<? print $item['id'] ?>')" >Close</a>
  <? endif; ?>
  <? if(($params['post_id'])== false ): ?>
  <span class="darkblue"> Apply to:</span>
  <? endif; ?>
  <? if(($params['post_id'])== false ): ?>
  <select name="content_type" class="cf_form_<? print $item['id'] ?>">
    <option <? if(($item['content_type']) == 'page') :  ?>  selected="selected" <? endif; ?> value="page">Use only on pages</option>
    <option  <? if(($item['content_type']) == 'post') :  ?>  selected="selected" <? endif; ?> value="post">Use in all posts in <? print strip_tags(page_title($params['page_id'])); ?> page</option>
  </select>
  <? else: ?>
  <input name="content_type" type="hidden" class="cf_form_<? print $item['id'] ?>"  value="post" />
  <? endif; ?>
</form>
<? endforeach; ?>
