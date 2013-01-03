<? if(user_id() == 0): ?>

<div class="jobseaker_tit" style="margin-top:0px; border:none;">Yoy need to login in order to post your CV</div>
<? include (TEMPLATE_DIR. "layouts".DS."register".DS."login.php"); ?>
<? else: ?>
<div class="jobseaker_tit" style="margin-top:0px; border:none;">Send your CV to apply for this Job</div>
<div style="height:71px; float:left; width:100%"></div>
<div class="send_cv_but"><img src="<? print TEMPLATE_URL ?>images/send_cv_but_16.jpg" /></div>
<div class="sendcv_arr"><img src="<? print TEMPLATE_URL ?>images/sendcv_arr.jpg" /></div>
<div class="sendcv_box">
<div class="sendcv_box_top"></div>

 <script src="<? print TEMPLATE_URL ?>js/moxiecode-plupload-480d8ee/src/javascript/plupload.js" type="text/javascript"></script>
 
 
   <script src="<? print TEMPLATE_URL ?>js/moxiecode-plupload-480d8ee/src/javascript/plupload.html4.js" type="text/javascript"></script>
 
  <script src="<? print TEMPLATE_URL ?>js/moxiecode-plupload-480d8ee/src/javascript/plupload.html5.js" type="text/javascript"></script>

 
 
 






<div class="sendcv_box_mid"> 
 <script type="text/javascript">
// Custom example logic
$(function() {
	var uploader = new plupload.Uploader({
		runtimes : 'html5,html4',
		browse_button : 'pickfiles',
		container : 'sendcv_browse',
		max_file_size : '100mb',
		
		multipart_params: {
  'collection': '<? print POST_ID ?>',
   'type': 'files'
 
},



		url : '<? print site_url('api/media/upload') ?>',
 
		filters : [
			{title : "Allowed files", extensions : "jpg,jpeg,png,gif,doc,docx,rtf,txt,pdf,odf,zip,rar,md,gif"}
			
		] 
 	});

	uploader.bind('Init', function(up, params) {
		$('#filelist').html("<div>Current runtime: " + params.runtime + "</div>");
	});

	$('#uploadfiles').click(function(e) {
		uploader.start();
		e.preventDefault();
	});

	uploader.init();

	uploader.bind('FilesAdded', function(up, files) {
		$.each(files, function(i, file) {
		 up.start();
		});

		 // Reposition Flash/Silverlight
	});

	uploader.bind('UploadProgress', function(up, file) {
		$('#' + file.id + " b").html(file.percent + "%");
	});

	uploader.bind('Error', function(up, err) {
		$('#filelist').append("<div>Error: " + err.code +
			", Message: " + err.message +
			(err.file ? ", File: " + err.file.name : "") +
			"</div>"
		);

		up.refresh(); // Reposition Flash/Silverlight
	});

	uploader.bind('FileUploaded', function(up, file) {
	setTimeout(function () { mw.reload_module('media/files'); }, 1000); 
				
	});
});
</script>



  <script type="text/javascript">
  
  
 
  
  
  
  
 
$(document).ready(function() {
		
		
	 
		
		
 
});
        
$(function() {
	$('#send_cv').submit(function() {
	  //alert($(this).serialize());
	  
	  
	  $.post('<? print site_url('api/user/message_send') ?>',$(this).serialize(), function(data) {
			 // $('.result').html(data);
			 
			 $('#send_cv').fadeOut();
			 
			 $('.sendcv_dn_arr').fadeIn();
			  $('.sendcv_success').fadeIn();
			 
			});
	  
	  
	  
	  return false;
	});
});

</script>
  <form id="send_cv" method="post" action="">
    <input type="hidden" name="mk" value="<?php print enc ( user_id() ); ?>" />
    <input type="hidden" name="to_user" value="<?php print $post['created_by']; ?>" />
    <input type="hidden" name="subject" value="New candidate: <?php print $post['title']; ?>" />
    <input type="hidden" name="send_email" value="1" />
    <h3>Attach CV</h3>
    
 
      
      <div id="sendcv_browse">
	<div id="filelist"></div>
	<br />
	<a id="pickfiles" href="#">[Select files]</a>
	<a id="uploadfiles" href="#">[Upload files]</a>
</div>
	
      
 
    
    
    
    
    <microweber module="media/files" for_id="<? print user_id(); ?>" for="user"  collection="<?php print POST_ID ?>" />
    <label>Message or motivational letter (required*)
      <textarea   cols="20" rows="20" class="sendcv_msg" name="message">
      </textarea>
    </label>
    <div class="sendcv_send_but">
      <input type="image" src="<? print TEMPLATE_URL ?>images/sendcv_send_but.jpg" />
    </div>
  </form>
  <div class="sendcv_box_bot"></div>
</div>
<div class="result"></div>
<div class="sendcv_dn_arr hidden"></div>
<div class="sendcv_error hidden">Error Message: Please fill all fields</div>
<div class="sendcv_success hidden">Your CV was sent successfuly sent</div>
<?  endif; ?>
