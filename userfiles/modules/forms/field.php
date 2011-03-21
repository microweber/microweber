<?  // p($params); 

if($params['value']){
$value = decode_var($params['value']);	
}

if($value == false){
$value = ($params['value']);	
}
//p($value);
if($value == false){
$value = '';	
}
//p($value);
?>
<? if($params['quick_edit'] == true):   ?>

<textarea name="<? print $params['name'];?>" class="<? print $params['class'];?>"><? print $value ?></textarea>
<? else:  ?>
<?php switch ($params['type']){ 
         case ('text'): ?>
<textarea name="<? print $params['name'];?>" class="<? print $params['class'];?>"><? print $value ?></textarea>
<?php break; ?>
<?php case ('dropdown'): ?>
<select name="<? print $params['name'];?>" class="<? print $params['class'];?>">
  <?php $v2 = explode(',',$value); ?>
  <? if(!empty($v2)): ?>
  <? foreach($v2 as $v): ?>
  <option value="<? print $value ?>" ><? print $value ?></option>
  <?  endforeach; ?>
  <?  endif; ?>
</select>
<?php break; ?>
<?php case ('image'): ?>
<? $rand = rand();  ?>
<script type="text/javascript">
	  
	  function uploadr_<? print $rand ?>(){
								  
 $("#mw_nic_uploader_<? print $rand ?>").pluploadQueue({
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
			 $("#upl_field_<? print $rand ?>").val(image.src);
			
            
             
         }
		}
	});
 
								 
		//flash_swf_url : static_url + 'js/plupload/js/plupload.flash.swf',
		//silverlight_xap_url : static_url + 'js/plupload/js/plupload.silverlight.xap'
 
								  
								  
 
	  }

	  
	  

</script>
<div id="mw_nic_uploader_<? print $rand ?>" style="display:block; height:100px; width:200px; background-color:red;"></div>
<input name="<? print $params['name'];?>"  id="upl_field_<? print $rand ?>"  onclick="uploadr_<? print $rand ?>();" type="text" value="<? print $value ?>"  />
<?php break; ?>
<?php case (2): ?>
<?php default: ?>
<textarea name="<? print $params['name'];?>" class="<? print $params['class'];?>"><? print $value ?></textarea>
<?php break; ?>
<?php } //end switch  ?>
<?  endif; ?>
