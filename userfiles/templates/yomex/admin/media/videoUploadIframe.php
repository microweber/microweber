<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Videos upload</title>
<link rel="stylesheet" href="<?php print_the_static_files_url() ; ?>admin/style.css" type="text/css" media="all"  />
<!--<script type="text/javascript" src="<?php print_the_static_files_url() ; ?>js/tree/source/_lib.js"></script>-->
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.js"></script>
<script type="text/javascript" src="<?php print_the_static_files_url() ; ?>jquery/jquery.form.js"></script>
<script type="text/javascript"> 
       
	   
	   
	   
	   
	   
	   // prepare the form when the DOM is ready 
$(document).ready(function() { 
    var options = { 
       // target:        '#output2',   // target element(s) to be updated with server response 
        beforeSubmit:  showRequest,  // pre-submit callback 
	  clearForm: true,
	  resetForm: true       , 
        success:       showResponse  // post-submit callback 
 
        // other available options: 
        //url:       url         // override for form's 'action' attribute 
        //type:      type        // 'get' or 'post', override for form's 'method' attribute 
        //dataType:  null        // 'xml', 'script', or 'json' (expected server response type) 
        //clearForm: true        // clear all form fields after successful submit 
        //resetForm: true        // reset the form after successful submit 
 
        // $.ajax options can be used here too, for example: 
        //timeout:   3000 
    }; 
 
    // bind to the form's submit event 
    $('#vid_upload_form').submit(function() { 
        // inside event callbacks 'this' is the DOM element so we first 
        // wrap it in a jQuery object and then invoke ajaxSubmit 
        $(this).ajaxSubmit(options); 
 
        // !!! Important !!! 
        // always return false to prevent standard browser submit and page navigation 
        return false; 
    }); 
}); 
 
// pre-submit callback 
function showRequest(formData, jqForm, options) { 
    // formData is an array; here we use $.param to convert it to a string to display it 
    // but the form plugin does this for you automatically when it submits the data 
    var queryString = $.param(formData); 
  $('#vid_upload_output').html(' <img src="<?php print_the_static_files_url() ; ?>ajax-loader.gif" align="left" alt="Uploading..." />    Uploading videos, please wait. It can take several minutes.');
    // jqForm is a jQuery object encapsulating the form element.  To access the 
    // DOM element for the form do this: 
    // var formElement = jqForm[0]; 
 
 //   alert('About to submit: \n\n' + queryString); 
 
    // here we could return false to prevent the form from being submitted; 
    // returning anything other than false will allow the form submit to continue 
    return true; 
} 
 
// post-submit callback 
function showResponse(responseText, statusText, xhr, $form)  { 
    // for normal html responses, the first argument to the success callback 
    // is the XMLHttpRequest object's responseText property 
 
    // if the ajaxSubmit method was passed an Options Object with the dataType 
    // property set to 'xml' then the first argument to the success callback 
    // is the XMLHttpRequest object's responseXML property 
 
    // if the ajaxSubmit method was passed an Options Object with the dataType 
    // property set to 'json' then the first argument to the success callback 
    // is the json data object returned by the server 
 $('#vid_upload_output').html(responseText);
  parent.vid_uploadComplete();
 
   // alert('status: ' + statusText + '\n\nresponseText: \n' + responseText + 
     //   '\n\nThe output div should have already been updated with the responseText.'); 
} 
	   
	   
	   
	   
	   
	   
	   
	   
	   
	   
	   
	   
	   
	   
    </script>
</head>
<body>

<form id="vid_upload_form" action="<?php print site_url('admin/media/mediaUploadVideos') ?>/to_table:table_content/queue_id:<?php print $queue_id ?>/to_table_id:<?php print $to_table_id ?>" method="post" enctype="multipart/form-data">
  <input name="video" type="file" />
  <input name="upload" type="submit" value="Upload" />
</form>
<br />
<small>Please upload only <strong>.flv</strong> and <strong>.f4v</strong> formats</small>
<?php // p($_FILES);  ?>
<div id="vid_upload_output"></div>
</body>
</html>
