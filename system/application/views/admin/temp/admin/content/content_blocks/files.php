

<table width="600" border="0" cellspacing="0" cellpadding="0">

  <tr>

    <td><script type="text/javascript">



	



	



	function  contentMediaEditfile($id){







	if($("#content_form_object").hasClass("save_disabled")){



	alert("Error: You cannot delete while uploading!");



	return false;



	} else {

		

		



















exposeIt('#file_edit_form_'+$id)













	



	}











}



	



function  contentMediaDeletefile($id){







if($("#content_form_object").hasClass("save_disabled")){



alert("Error: You cannot delete while uploading!");



return false;



}















var answer = confirm("Are you sure?")



	if (answer){



		$.post("<?php print site_url('admin/core/mediaDelete') ?>", { id: $id, time: "2pm" },



  function(data){



	  //$("#gallery_module_sortable_files_positions_"+$id).fadeOut();


file_uploadComplete();
	//  $("#gallery_module_sortable_files_positions_"+$id).remove();



	// contentMediafilesRefreshList();



   //alert("Data Loaded: " + data);



  });



	}



	else{



		//alert("Thanks for sticking around!")



	}



 



}















function contentMediafilesRefreshList(){



var media_upload_queue_file = $('#media_queue_files').val();



var to_table_id1 = $('#id').val();



var media_files_to_table_id = $('#media_files_to_table_id').val();



$.post("<?php print site_url('admin/media/contentMediaFilesList') ?>/to_table:table_content/queue_id:"+media_upload_queue_file+"/to_table_id:"+media_files_to_table_id+"/random_stuff:"+Math.random(), function(data){



  $("#media_files_placeholder").html(data);







if ( $("#gallery_module_sortable_files").exists() ){



	$("#gallery_module_sortable_files").sortable(



	{



	update : function () {



	var order = $('#gallery_module_sortable_files').sortable('serialize');



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



 

 function file_uploadProgress(file, bytesLoaded, bytesTotal) {

        try {

                var percent = Math.ceil((bytesLoaded / bytesTotal) * 100);



                var progress = new FileProgress(file, this.customSettings.progressTarget);

                progress.setProgress(percent);

				

				

				 $("#fsUploadProgress_file_perc").html(percent +'% completed...');

                progress.setStatus("Uploading...");

        } catch (ex) {

                this.debug(ex);

        }

}







function file_uploadComplete(){

	 $("#fsUploadProgress_file_perc").html('');

	contentMediafilesRefreshList();

	

}



 

 $(document).ready(function(){

	

	 contentMediafilesRefreshList();

   var media_upload_queue = $('#media_queue_files').val();

	

 

	var media_upload_queue_file = $('#media_queue_files').val();

	var to_table_id1 = $('#id').val();





 



 });

</script>

      <br />

        <iframe frameborder="0" src="<?php print site_url('admin/media/mediaUploadFilesIframe') ?>/to_table:table_content/queue_id:<?php print intval( $form_values['id']) ; ?>_<?php print $this->session->userdata('session_id'); ?>_media_queue_files/to_table_id:<?php print $form_values['id']; ?>" height="150" width="450" ></iframe>


      <br />

       <input name="media_files_to_table_id" type="hidden" id="media_files_to_table_id" value="<?php print $form_values['id']; ?>">

      <input name="media_queue_files" type="hidden" id="media_queue_files" value="<?php print intval($form_values['id']); ?>_<?php print $this->session->userdata('session_id'); ?>_media_queue_files"></td>

      

  </tr>

  <tr>

    <td><div id="media_files_placeholder">Loading files module...</div></td>

  </tr>

</table>

