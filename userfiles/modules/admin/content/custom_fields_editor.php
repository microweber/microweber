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
<? if(($params['file']) != '') :  ?>
<? $layouts = CI::model('template')->layoutsList(); 
 
?>
<? if(!empty($layouts)): ?>
<? foreach($layouts as $layout): ?>
<? if($layout['filename'] == $params['file']): ?>
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

 
 

 
 
 
 
if(!empty($layout['custom_fields'][$for])): ?>


<script type="application/javascript">


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
      
       <small style="color:#999"><? print( $item['param_group']) ?></small>
      </td>
    <td><? // print $item['type'] ?>
      <?php switch($item['type']): 
case 'category_selector': ?>
      <input name="custom_field_<? print $item['param'] ?>"  type="text" value="<? print $custom_fields[$item['param']]? $custom_fields[$item['param']] : $item['default'] ; ?>"  />
      <?php break;?>
      
      <?php case 'image': ?>
      
      <script type="text/javascript">
	  
	  function uploadr(){
								  
 $("#mw_nic_uploader_<? print $item['param'] ?>").pluploadQueue({
		// General settings
		runtimes: 'gears,html5,htm4,browserplus',
		url: "<? print site_url('api/media/upload'); ?>",
		max_file_size: '10mb',
		chunk_size: '1mb',
		unique_names: true,
		//resize: {width: 320, height: 240, quality: 90},
		filters: [
			{title: "Image files", extensions: "jpg,gif,png,bmp"},
		 
		],


		flash_swf_url: '<?php   print( ADMIN_STATIC_FILES_URL);  ?>js/plupload/js/plupload.flash.swf',
        silverlight_xap_url: '<?php print( ADMIN_STATIC_FILES_URL);  ?>js/plupload/js/plupload.silverlight.xap',
		preinit: {

		},


		init: {
         FilesAdded:function(up, files){
           this.start();
            
         },
         FileUploaded: function(up, file, info) {
          var obj = eval("(" + info.response + ")");
            //$(document.body).append(obj.url);
            var image = new Image();
            image.src = obj.url;
			//alert(image.src);
			 $("#custom_field_<? print $item['param'] ?>").val(image.src);
			
            
             
         }
		}
	});
 
								 
		//flash_swf_url : static_url + 'js/plupload/js/plupload.flash.swf',
		//silverlight_xap_url : static_url + 'js/plupload/js/plupload.silverlight.xap'
 
								  
								  
 
	  }

	  
	  

</script>

       <div id="mw_nic_uploader_<? print $item['param'] ?>" style="display:block; height:100px; width:200px; background-color:red;"></div>
       <input name="custom_field_<? print $item['param'] ?>" id="custom_field_<? print $item['param'] ?>" onclick="uploadr();" type="text" value="<? print $custom_fields[$item['param']]? $custom_fields[$item['param']] : $item['default'] ; ?>"  />
       
 
       
      
      <?php break;?>
      
      <?php case 'dropdown': ?>
      <select name="custom_field_<? print $item['param'] ?>">
      <?  $dd_vals = explode(',',$item['values']);  ?>
      <? foreach($dd_vals as $dd_val): ?>
      
      <? $whats_the_val =  $custom_fields[$item['param']]? $custom_fields[$item['param']] : $item['default'] ; 
	  //var_dump($whats_the_val);
	  ?>
      
      <option value="<? print $dd_val ?>"  <?   if($dd_val ==$whats_the_val ):  ?> selected="selected"   <? endif; ?> ><? print $dd_val ?></option>
      <? endforeach; ?>
      </select>
     
      
      
      <?php break;?>
      
      <?php case 'number': ?>
      <?php default: ?>
      <div class="field2">
        <input name="custom_field_<? print $item['param'] ?>" type="text" value="<? print  $custom_fields[$item['param']]? $custom_fields[$item['param']] : $item['default'] ; ?>"  />
      </div>
      <?php break;?>
      <?php endswitch;?></td>
    <td><? // p($item) ?><input name="save" type="button" onclick="cf_save('cf_edit_<? print $item['param'] ?>')" value="save" /></td>
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


