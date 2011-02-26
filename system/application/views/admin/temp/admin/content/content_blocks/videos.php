

<table width="600" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><script type="text/javascript">

	

	

	function  contentMediaEditVideo($id){



	if($("#content_form_object").hasClass("save_disabled")){

	alert("Error: You cannot delete while uploading!");

	return false;

	} else {

	

	}





}

	

function  contentMediaDeleteVideo($id){

  if($("#content_form_object").hasClass("save_disabled")){

  alert("Error: You cannot delete while uploading!");

return false;

}







var answer = confirm("Are you sure?")

	if (answer){

		$.post("<?php print site_url('admin/core/mediaDelete') ?>", { id: $id, time: "2pm" },

  function(data){

	  //$("#sortable_video_objects_positions_"+$id).fadeOut();

	  $("#videos_module_sortable_vids_positions_"+$id).remove();

	// contentMediaVideosRefreshList();

   //alert("Data Loaded: " + data);

  });

	}

	else{

		//alert("Thanks for sticking around!")

	}

 

}







function contentMediaVideosRefreshList(){

var media_upload_queue_vid = $('#media_queue_videos').val();

var to_table_id1 = $('#id').val();


var media_pictures_to_table_id = $('#media_pictures_to_table_id').val();


$.post("<?php print site_url('admin/media/contentMediaVideosList') ?>/to_table:table_content/queue_id:"+media_upload_queue_vid+"/to_table_id:"+media_pictures_to_table_id+"/random_stuff:"+Math.random(), function(data){

  $("#media_videos_placeholder").html(data);




    $("#sortable_video_objects").sortable("destroy");
	$("#sortable_video_objects").sortable({
	 handle:".sortable_video_objects_title",  
	update: function(){
	var order = $('#sortable_video_objects').sortable('serialize');

	    $.post("<?php print site_url('admin/media/reorderMedia') ?>", order,
    	    function(data){

    	});
	  }
	}

	);










 

});



}

</script>
      <script type="text/javascript">

function vid_uploadProgress(file, bytesLoaded, bytesTotal) {
        try {
                var percent = Math.ceil((bytesLoaded / bytesTotal) * 100);

                var progress = new FileProgress(file, this.customSettings.progressTarget);
                progress.setProgress(percent);
				
				
				 $("#fsUploadProgress_vid_perc").html(percent +'% completed...');
                progress.setStatus("Uploading...");
        } catch (ex) {
                this.debug(ex);
        }
}



function vid_uploadComplete(){
//	contentMediaVideosRefreshList();
	 $("#fsUploadProgress_vid_perc").html('');
	setTimeout("contentMediaVideosRefreshList()",2000);
	
}
 $(document).ready(function(){
		
		contentMediaVideosRefreshList();
 
			 

  

 });
							
/*
var swfu_vids;
<?php $user_data = ($this->session->userdata ( 'the_user' )); ?>


							
							
				var media_upload_queue_videos = $('#media_queue_videos').val();
				var media_videos_to_table_id = $('#media_videos_to_table_id').val();
							
							
							

			var settings_object_vids = {
	  upload_url : '<?php print site_url('admin/media/mediaUploadVideos') ?>/to_table:table_content/queue_id:'+media_upload_queue_videos+'/to_table_id:'+media_videos_to_table_id,
	  flash_url : "<?php print_the_static_files_url() ; ?>js/swfupload/swfupload.swf?<?php print rand() ?>",
	  flash9_url : "<?php print_the_static_files_url() ; ?>js/swfupload/swfupload_fp9.swf?<?php print rand() ?>",
	  
	  post_params: {"username" : "<?php print $user_data['username']; ?>", "password" : "<?php print $user_data['password']; ?>"},
	  // File Upload Settings
	  file_post_name : "filedata_videos",  
	  file_size_limit : "102400",	// 100MB
	 file_types : "*.f4v;*.flv;",
	  file_types_description : "Videos",
	  //file_upload_limit : 10,
	  //file_queue_limit : 0,
	  // Event Handler Settings (all my handlers are in the Handler.js file)
	  use_query_string : false,
	  requeue_on_error : true,
	  //http_success : [201, 202],
	  assume_success_timeout : 0,
	  prevent_swf_caching: true,
      swfupload_preload_handler : preLoad,
	  swfupload_load_failed_handler : loadFailed,
	  file_dialog_start_handler : fileDialogStart,
	  file_queued_handler : fileQueued,
	  file_queue_error_handler : fileQueueError,
	  file_dialog_complete_handler : fileDialogComplete,
	  upload_start_handler : uploadStart,
	  
	  upload_error_handler : uploadError,
	  upload_success_handler : uploadSuccess,
	  upload_progress_handler : uploadProgress,
	  upload_complete_handler : vid_uploadComplete,
	  debug : true,
	  button_placeholder_id  : "spanButtonPlaceholder_vid",
	  button_image_url : "<?php print_the_static_files_url() ; ?>js/swfupload/XPButtonUploadText_61x22.png?<?php print rand() ?>",
	  button_width: 61,
	  button_height: 22,
	  custom_settings : {
	  progressTarget : "fsUploadProgress_vid",
	  cancelButtonId : "btnCancel_vid"
	  }
	};
	
	swfu_vids = new SWFUpload(settings_object_vids);				
							
 					
							



*/

</script>
      <div id="fsUploadProgress_vid"></div>
      <div id="fsUploadProgress_vid_perc"></div>
  <div id="spanButtonPlaceholder_vid"></div>
  
  <iframe frameborder="0" src="<?php print site_url('admin/media/mediaUploadVideosIframe') ?>/to_table:table_content/queue_id:<?php print intval( $form_values['id']) ; ?>_<?php print $this->session->userdata('session_id'); ?>_media_queue_videos/to_table_id:<?php print $form_values['id']; ?>" height="150" width="450" ></iframe>
  
  <!--
          <input id="btnCancel_vid" type="button" value="Cancel Uploads" onClick="cancelQueue(swfu_vids);" disabled="disabled" style="margin-left: 2px; height: 22px; font-size: 8pt;" />
          <br />-->
     
 
      <input name="media_queue_videos" type="hidden" id="media_queue_videos" value="<?php print intval( $form_values['id']) ; ?>_<?php print $this->session->userdata('session_id'); ?>_media_queue_videos">
        <input name="media_videos_to_table_id" type="hidden" id="media_videos_to_table_id" value="<?php print $form_values['id']; ?>">
      <br /></td>
  </tr>
  <tr>
    <td><div id="media_videos_placeholder">Loading videos module...</div></td>
  </tr>
</table>
