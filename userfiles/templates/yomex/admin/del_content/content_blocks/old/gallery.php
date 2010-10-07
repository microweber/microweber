
<table width="600" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><script type="text/javascript">

	

	

	function  contentMediaEditPicture($id){



	if($("#content_form_object").hasClass("save_disabled")){

	alert("Error: You cannot delete while uploading!");

	return false;

	} else {

	

	}





}

	

function  contentMediaDeletePicture($id){



if($("#content_form_object").hasClass("save_disabled")){

alert("Error: You cannot delete while uploading!");

return false;

}







var answer = confirm("Are you sure?")

	if (answer){

		$.post("<?php print site_url('admin/core/mediaDelete') ?>", { id: $id, time: "2pm" },

  function(data){

	  //$("#gallery_module_sortable_pics_positions_"+$id).fadeOut();

	  $("#gallery_module_sortable_pics_positions_"+$id).remove();

	// contentMediaPicturesRefreshList();

   //alert("Data Loaded: " + data);

  });

	}

	else{

		//alert("Thanks for sticking around!")

	}

 

}







function contentMediaPicturesRefreshList(){

var media_upload_queue_pic = $('#media_queue_pictures').val();

var to_table_id1 = $('#id').val();

var media_pictures_to_table_id = $('#media_pictures_to_table_id').val();

$.post("<?php print site_url('admin/media/contentMediaPicturesList') ?>/to_table:table_content/queue_id:"+media_upload_queue_pic+"/to_table_id:"+media_pictures_to_table_id+"/random_stuff:"+Math.random(), function(data){

  $("#media_pictures_placeholder").html(data);



if ( $("#gallery_module_sortable_pics").exists() ){

	$("#gallery_module_sortable_pics").sortable(

	{

	update : function () {

	var order = $('#gallery_module_sortable_pics').sortable('serialize');

	$.post("<?php print site_url('admin/media/reorderMedia') ?>", order,

	function(data){

	});

	}

	}				

	);

}









 

});



}

</script>
      <script type="text/javascript">

 
 function pic_uploadProgress(file, bytesLoaded, bytesTotal) {
        try {
                var percent = Math.ceil((bytesLoaded / bytesTotal) * 100);

                var progress = new FileProgress(file, this.customSettings.progressTarget);
                progress.setProgress(percent);
				
				
				 $("#fsUploadProgress_pic_perc").html(percent +'% completed...');
                progress.setStatus("Uploading...");
        } catch (ex) {
                this.debug(ex);
        }
}



function pic_uploadComplete(){
	 $("#fsUploadProgress_pic_perc").html('');
	contentMediaPicturesRefreshList();
	
}

var swfu_pics;
 $(document).ready(function(){
	
	var media_upload_queue_pic = $('#media_queue_pictures').val();
	var media_pictures_to_table_id = $('#media_pictures_to_table_id').val();
	var settings_object_pics = {
	  upload_url : '<?php print site_url('admin/media/mediaUploadPictures') ?>/to_table:table_content/queue_id:'+media_upload_queue_pic+'/to_table_id:'+media_pictures_to_table_id,
	  flash_url : "<?php print_the_static_files_url() ; ?>js/swfupload/swfupload.swf",
	 // post_params: {"PHPSESSID" : "<?php echo session_id(); ?>"},
	 //post_params: {"PHPSESSID" : "<?php print $this->session->userdata('session_id'); ?>"},
	 post_params: {"<?php echo $this->config->item('sess_cookie_name'); ?>" :"<?php echo $this->session->get_cookie_data(); ?>", "PHPSESSID" : "<?php print $this->session->userdata('session_id'); ?>"},
	  // File Upload Settings
	  file_size_limit : "102400",	// 100MB
	  file_types : "*.jpg;*.png;;*.gif;;*.jpeg;",
	  file_types_description : "Images",
	  //file_upload_limit : 10,
	  //file_queue_limit : 0,
	  // Event Handler Settings (all my handlers are in the Handler.js file)
	  swfupload_preload_handler : preLoad,
	  swfupload_load_failed_handler : loadFailed,
	  file_dialog_start_handler : fileDialogStart,
	  file_queued_handler : fileQueued,
	  file_queue_error_handler : fileQueueError,
	  file_dialog_complete_handler : fileDialogComplete,
	  upload_start_handler : uploadStart,
	  upload_progress_handler : pic_uploadProgress,
	  upload_error_handler : uploadError,
	  upload_success_handler : uploadSuccess,
	  upload_complete_handler : pic_uploadComplete,
	  
	  button_placeholder_id  : "spanButtonPlaceholder_pic",
	  button_image_url : "<?php print_the_static_files_url() ; ?>js/swfupload/XPButtonUploadText_61x22.png",
	  button_width: 61,
	  button_height: 22,
	  custom_settings : {
	  progressTarget : "fsUploadProgress_pic",
	  cancelButtonId : "btnCancel_pic"
	  }
	};
	
	swfu_pics = new SWFUpload(settings_object_pics);










	
	contentMediaPicturesRefreshList();
	var media_upload_queue = $('#media_queue_pictures').val();
	
 
	var media_upload_queue_pic = $('#media_queue_pictures').val();
	var to_table_id1 = $('#id').val();


 

 });
</script>
      <br />
      <div>
        <div class="fieldset flash" id="fsUploadProgress_pic"> </div>
         <div id="fsUploadProgress_pic_perc"></div>
        <div style="padding-left: 5px;"> <span id="spanButtonPlaceholder_pic"></span>
          <input id="btnCancel_pic" type="button" value="Cancel Uploads" onClick="cancelQueue(swfu_pics);" disabled="disabled" style="margin-left: 2px; height: 22px; font-size: 8pt;" />
          <br />
        </div>
      </div>
      <br />
       <input name="media_pictures_to_table_id" type="hidden" id="media_pictures_to_table_id" value="<?php print $form_values['id']; ?>">
      <input name="media_queue_pictures" type="hidden" id="media_queue_pictures" value="<?php print $form_values['id']; ?>_<?php print $this->session->userdata('session_id'); ?>_media_queue_pictures"></td>
      
  </tr>
  <tr>
    <td><div id="media_pictures_placeholder">Loading gallery module...</div></td>
  </tr>
</table>
