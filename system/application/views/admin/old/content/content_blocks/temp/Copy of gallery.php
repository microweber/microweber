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

var media_upload_queue1 = $('#media_queue_pictures').val();
var to_table_id1 = $('#id').val();


$.post("<?php print site_url('admin/media/contentMediaPicturesList') ?>/to_table:table_content/queue_id:"+media_upload_queue1+"/to_table_id:"+to_table_id1, function(data){
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
jQuery.fn.bindAll = function(options) {
	var $this = this;
	jQuery.each(options, function(key, val){
		$this.bind(key, val);
	});
	return this;
}
 $(document).ready(function(){
contentMediaPicturesRefreshList();
	
	var listeners = {
		swfuploadLoaded: function(event){
			//$('.log', this).append('<li>Loaded</li>');
		},
		fileQueued: function(event, file){
			//$('.log', this).append('<li>File queued - '+file.name+'</li>');
			// start the upload once it is queued
			// but only if this queue is not disabled
		//	if (!$('input[name=disabled]:checked', this).length) {
				$(this).swfupload('startUpload'); 
			//}
		},
		fileQueueError: function(event, file, errorCode, message){
			$("#content_form_object").removeClass("save_disabled");
			//$('.log', this).append('<li>File queue error - '+message+'</li>');
		},
		fileDialogStart: function(event){
			//$('.log', this).append('<li>File dialog start</li>');
			$("#content_form_object").addClass("save_disabled");
		},
		fileDialogComplete: function(event, numFilesSelected, numFilesQueued){
			$("#content_form_object").removeClass("save_disabled");
			//$('.log', this).append('<li>File dialog complete</li>');
		},
		uploadStart: function(event, file){
			$("#content_form_object").addClass("save_disabled");
			//$('.log', this).append('<li>Upload start - '+file.name+'</li>');
			// don't start the upload if this queue is disabled
			//if ($('input[name=disabled]:checked', this).length) {
			//	event.preventDefault();
			//}
			$('#pictures_upload_progressbar').progressbar('option', 'value', 0);
		},
		uploadProgress: function(event, file, bytesLoaded){
		//alert(file.size);
		
		var index = 0;
var file_alll = this.getFile(index++);
var queued_file_size = 0;
while (file_alll !== null) {
if (file_alll.filestatus === this.FILE_STATUS.QUEUED) {
queued_file_size += file_alll.size;
}
file_alll = this.getFile(index++);
}
		
		
		this.transferredBytes = bytesLoaded;
		a = this.transferredBytes;
		b = queued_file_size;
		c = a/b;
		d = Math.round(c*100);
		$('#pictures_upload_progressbar').progressbar('option', 'value', d);
		$('.log', this).html('<li>Upload progress - '+bytesLoaded+' of ' +file.size+ '</li>');
			//$('.log', this).append('<li>Upload progress - '+bytesLoaded+'</li>');
		},
		uploadSuccess: function(event, file, serverData){
		$("#content_form_object").removeClass("save_disabled");
		contentMediaPicturesRefreshList();
		$('#pictures_upload_progressbar').progressbar('option', 'value', 0);
			//$('.log', this).append('<li>Upload success - '+file.name+'</li>');
		},
		uploadComplete: function(event, file){
			$("#content_form_object").removeClass("save_disabled");
			//$('.log', this).append('<li>Upload complete - '+file.name+'</li>');
			// upload has completed, lets try the next one in the queue
			// but only if this queue is not disabled
		
			//if (!$('input[name=disabled]:checked', this).length) {
				$(this).swfupload('startUpload');
			//}
		},
		uploadError: function(event, file, errorCode, message){
			$("#content_form_object").removeClass("save_disabled");
			//$('.log', this).append('<li>Upload error - '+message+'</li>');
		}
	};

	$('.swfupload-control').bindAll(listeners);
	
	
	// start the queue if the queue is already disabled
	$('.swfupload-control input[name=disabled]').click(function(){
		//if (!this.checked) {
			//$(this).parents('.swfupload-control').swfupload('startUpload');
		//}
	});
	

		
var media_upload_queue = $('#media_queue_pictures').val();
//alert(media_upload_queue);
	$('.swfupload-control').each(function(){
		$(this).swfupload({
			upload_url: "<?php print site_url('admin/media/mediaUploadPictures') ?>/to_table:table_content/queue_id:"+media_upload_queue,
			file_size_limit : "10240000",
			//file_types : "*.*",
			file_types : "*.jpg;*.gif;*.jpeg;*.png;",
			file_types_description : "Image Files",
			file_upload_limit : "0",
			flash_url : swfupload_flash_url,
			button_image_url : swfupload_button_image_url,
			button_width : 61,
			button_height : 22,
		 
			button_placeholder : $('.pictures_upload_button', this)[0],
			debug: false
		});
	
});	


 });
</script>


<script type="text/javascript">
	$(function() {
		$("#pictures_upload_progressbar").progressbar({
			value: 0
		});
	});
	</script>

 



<table border="0">
  <tr>
    <td width="500"><div id="pictures_upload_progressbar"></div></td>
    <td><div class="swfupload-control">
    <ol class="log"></ol>
        <input type="button" class="pictures_upload_button" />
      </div></td>
  </tr>
</table>


      
      <div id="media_pictures_placeholder">Loading gallery module...</div></td>
  </tr>
</table>
 