
<?php if(empty($videos)  ): ?>

Please upload some videos in the videos.
<?php else : ?>
<script type="text/javascript">
 
  
  
  
  
  // prepare the form when the DOM is ready 
$(document).ready(function() { 
    var media_vids_options = { 
      //  target:        '#output1',   // target element(s) to be updated with server response 
        beforeSubmit:  media_vids_showRequest,  // pre-submit callback 
		type:      'post',
		url:       '<?php print site_url('admin/media/mediaSave') ?>',
		clearForm: true,
		resetForm: true   ,
        success:       media_vids_showResponse  // post-submit callback 
 
        // other available options: 
        //url:       url         // override for form's 'action' attribute 
        //type:      type        // 'get' or 'post', override for form's 'method' attribute 
        //dataType:  null        // 'xml', 'script', or 'json' (expected server response type) 
        //clearForm: true        // clear all form fields after successful submit 
        //resetForm: true        // reset the form after successful submit 
 
        // $.ajax options can be used here too, for example: 
        //timeout:   3000 
    }; 
 
    // bind form using 'ajaxForm' 
    $('.vidsAjaxSaveForm').ajaxForm(media_vids_options); 
}); 
 
// pre-submit callback 
function media_vids_showRequest(formData, jqForm, options) { 
    // formData is an array; here we use $.param to convert it to a string to display it 
    // but the form plugin does this for you automatically when it submits the data 
   // 
   var queryString = $.param(formData); 
  // alert(queryString);
  //  alert(formData);
   
 $('#saving-videos').fadeIn();
    // jqForm is a jQuery object encapsulating the form element.  To access the 
    // DOM element for the form do this: 
    // var formElement = jqForm[0]; 
// var formElement = jqForm[0]; 
 //formElement.fadeOut();
   // alert('About to submit: \n\n' + queryString); 
 
    // here we could return false to prevent the form from being submitted; 
    // returning anything other than false will allow the form submit to continue 
    return true; 
} 
 
// post-submit callback 
function media_vids_showResponse(responseText, statusText)  { 
    // for normal html responses, the first argument to the success callback 
    // is the XMLHttpRequest object's responseText property 
	$('.sortable_video_objects_holder').html('<img src="<?php print_the_static_files_url() ; ?>ajax-loader.gif" align="left" alt="Working..." /> Reloading videos...'); 
   $('#saving-videos').fadeOut();
   
   
   
 vid_uploadComplete();
 //$('#media_edit_table_'+responseText).fadeOut();
 
// $('#vid_saved_txt_'+responseText).fadeIn('slow', function(){
  //  $('#vid_saved_txt_'+responseText).fadeOut();
	
 //});




  //$('#media_edit_table_'+responseText).fadeIn();

 
    // if the ajaxForm method was passed an Options Object with the dataType 
    // property set to 'xml' then the first argument to the success callback 
    // is the XMLHttpRequest object's responseXML property 
 
    // if the ajaxForm method was passed an Options Object with the dataType 
    // property set to 'json' then the first argument to the success callback 
    // is the json data object returned by the server 
 
   // alert('status: ' + statusText + '\n\nresponseText: \n' + responseText + 
      //  '\n\nThe output div should have already been updated with the responseText.'); 
} 
</script>
<br />

 <div id="saving-videos" style="display:none"><img src="<?php print_the_static_files_url() ; ?>ajax-loader.gif" align="left" alt="Working..." /> Saving... please wait</div>
<br />

<ul id="sortable_video_objects">
  <?php $i = 1; if(!empty($videos)): ?>
  <?php foreach($videos as $vid): ?>
  <li id="videos_module_sortable_vids_positions_<?php print $vid['id'] ?>">
    <h5 class="sortable_video_objects_title">
      <?php if(strval($vid['media_name'] != '')): ?>
      <?php print $vid['media_name'] ?>
      <?php else: ?>
      <?php print $vid['filename'] ?>
      <?php endif; ?>
    </h5>
    <div class="sortable_video_objects_holder">
      <table width="100%" cellspacing="1" cellpadding="1">
        <tr>
          <td><object id="player-<?php print $vid['id'] ?>" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" name="player" width="215" height="150">
              <param name="movie" value="<?php print_the_static_files_url() ; ?>js/media_player/player-viral.swf" />
              <param name="allowfullscreen" value="true" />
              <param name="allowscriptaccess" value="always" />
              <param name="flashvars" value="file=<?php print $vid['url'] ?>&image=<?php print $this->core_model->mediaGetThumbnailForMediaId($vid['id'], '300');	?>" />
              <embed
			type="application/x-shockwave-flash"
			id="player2-<?php print $vid['id'] ?>"
			name="player2-<?php print $vid['id'] ?>"
			src="<?php print_the_static_files_url() ; ?>js/media_player/player-viral.swf"
			width="215"
			height="150"
			allowscriptaccess="always"
			allowfullscreen="true"
			flashvars="file=<?php print $vid['url'] ?>&image=<?php print $this->core_model->mediaGetThumbnailForMediaId($vid['id'], '300');	?>"
		/>
            </object></td>
          <td>
          <?php //print $this->core_model->mediaGetThumbnailForMediaId($vid['id'], '200');	?>
          <form action=""   class="vidsAjaxSaveForm" method="post" enctype="multipart/form-data">
              <input name="id" type="hidden" value="<?php print $vid['id'] ?>" />
              
               
              
              <table cellspacing="1" cellpadding="1" width="100%" class="video_details_table" id="media_edit_table_<?php print $vid['id'] ?>" >
                <!-- <tr>
                  <td><h4>Filename: <?php print character_limiter( $vid['filename'], 10) ?></h4>
                    </td>
                </tr>-->
                <tr>
                  <td><b>Title:</b>
                    <input name="media_name" type="text" value="<?php print $vid['media_name'] ?>" /></td>
                </tr>
                <tr>
                  <td><b>Description:</b>
                    <textarea name="media_description" cols="" rows=""><?php print $vid['media_description'] ?></textarea></td>
                </tr>
                
                <tr>
                  <td><b>Thumbnail:</b>
                     <input name="picture_0" type="file" /></td>
                </tr>
                
              </table>
              <div class="changes-are-saved" id="vid_saved_txt_<?php print $vid['id'] ?>" style="display:none"> Changes are saved... </div>
              <input name="save" style="float: left;margin-left:249px; " type="submit" value="save" />
            </form>
           
            
            </td>
        </tr>
      </table>
      <a href="javascript:;" class="sortable_video_objects_delete" onClick="contentMediaDeleteVideo('<?php print $vid['id'] ?>')">delete</a> </div>
    <!-- <span class="vid_obj_New"></span>-->
  </li>
  <?php $i++; endforeach; ?>
  <?php endif; ?>
</ul>
<?php endif; ?>
<br />
<br />
<br />
<br />
<br />
<br />
<br />
The videos must be uploaded in .FLV or .F4V format. You need <a href="<?php print_the_static_files_url() ; ?>RivaEncoderSetup.exe" target="_blank">THIS software</a> to convert your videos info FLV format. If you need help on how to use the software <a href="http://forum.videohelp.com/threads/202228-How-to-convert-flv-(flash-video)-to-avi-or-mpg" target="_blank">see this page</a>. 