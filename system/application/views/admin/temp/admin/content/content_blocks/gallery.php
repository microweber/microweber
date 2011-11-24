
<table width="600" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><script type="text/javascript">

	

	

	function  contentMediaEditPicture($id){



	if($("#content_form_object").hasClass("save_disabled")){

	alert("Error: You cannot delete while uploading!");

	return false;

	} else {
		
		









exposeIt('#pic_edit_form_'+$id)






	

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

 
 $(document).ready(function(){
	
	 contentMediaPicturesRefreshList();
 
	 










	
	
	var media_upload_queue = $('#media_queue_pictures').val();
	
 
	var media_upload_queue_pic = $('#media_queue_pictures').val();
	var to_table_id1 = $('#id').val();


 

 });
</script>
      <br />
    
        <iframe frameborder="0" src="<?php print site_url('admin/media/mediaUploadPicturesIframe') ?>/to_table:table_content/queue_id:<?php print intval( $form_values['id']) ; ?>_<?php print $this->session->userdata('session_id'); ?>_media_queue_pictures/to_table_id:<?php print $form_values['id']; ?>" height="150" width="450" ></iframe>
      <!--<div>
        <div class="fieldset flash" id="fsUploadProgress_pic"> </div>
         <div id="fsUploadProgress_pic_perc"></div>
        <div style="padding-left: 5px;"> <span id="spanButtonPlaceholder_pic"></span>
          <input id="btnCancel_pic" type="button" value="Cancel Uploads" onClick="cancelQueue(swfu_pics);" disabled="disabled" style="margin-left: 2px; height: 22px; font-size: 8pt;" />
          <br />
        </div>
      </div>-->
      <br />
       <input name="media_pictures_to_table_id" type="hidden" id="media_pictures_to_table_id" value="<?php print $form_values['id']; ?>">
      <input name="media_queue_pictures" type="hidden" id="media_queue_pictures" value="<?php print intval($form_values['id']); ?>_<?php print $this->session->userdata('session_id'); ?>_media_queue_pictures"></td>
      
  </tr>
  <tr>
    <td><div id="media_pictures_placeholder">Loading gallery module...</div></td>
  </tr>
</table>
