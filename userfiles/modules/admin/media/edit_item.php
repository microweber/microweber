<?
 $pic = get_mediaby_id(intval($params['id']));
 $rand=intval($params['id']).rand().rand().rand();
?>
<script type="text/javascript">
 var save_media_item<? print $rand ;?> = function(){
  
  var media_pics_options<? print $rand ;?> = { 
      //  target:        '#output1',   // target element(s) to be updated with server response 
        beforeSubmit:  media_pics_showRequest<? print $rand ;?>,  // pre-submit callback 
		type:      'post',
		url:       '<?php print site_url('api/media/media_save') ?>',
		clearForm: false,
		async:false,
		resetForm: false   ,
        success:       media_pics_showResponse<? print $rand ;?>  // post-submit callback 

        // other available options: 
        //url:       url         // override for form's 'action' attribute 
        //type:      type        // 'get' or 'post', override for form's 'method' attribute 
        //dataType:  null        // 'xml', 'script', or 'json' (expected server response type) 
        //clearForm: true        // clear all form fields after successful submit 
        //resetForm: true        // reset the form after successful submit 
 
        // $.ajax options can be used here too, for example: 
        //timeout:   3000 
    }; 
	
 $("#mw_media_edit_<? print $rand ;?>").ajaxSubmit(media_pics_options<? print $rand ;?>);
 
 }
 
 
// pre-submit callback 
function media_pics_showRequest<? print $rand ;?>(formData, jqForm, options) { 
 
    return true; 
} 
 
// post-submit callback 
function media_pics_showResponse<? print $rand ;?>(responseText, statusText)  { 

	// Modal.close();
	$('.mw_modal').hide();
	if( parent.mw.reload_module != undefined){
	 parent.mw.reload_module('media/gallery');
	}
	// call_media_manager();
	
	 
	 
} 
function save_media_close<? print $rand ;?>()  { 


	 $('#mw_media_edit_<? print $rand ;?>').fadeOut();
	 
}

 function  contentMediaDeletePicture<? print $rand ?>($id){



if($("#content_form_object").hasClass("save_disabled")){

alert("Error: You cannot delete while uploading!");

return false;

}







var answer = confirm("Are you sure?")

	if (answer){

		$.post("<?php print site_url('api/media/media_delete') ?>", { id: $id, time: "2pm" },

  function(data){

	  //$(".gallery_module_sortable_pics<? print $rand ?>_positions_"+$id).fadeOut();
save_media_close<? print $rand ;?>()
	  $("#picture_id_"+$id).fadeOut();
 mw.reload_module('media/gallery');
	// contentMediaPicturesRefreshList<? print $rand ?>();

   //alert("Data Loaded: " + data);

  });

	}

	else{

		//alert("Thanks for sticking around!")

	}

 

}

</script>

<form action=""  id="mw_media_edit_<? print $rand ;?>"  method="post" enctype="multipart/form-data">
  <input name="id" type="hidden" value="<?php print $pic['id'] ?>" />
  <span class="mw_sidebar_module_box_title">Edit picture</span>
  <div class="mw_admin_rounded_box">
    <div class="mw_admin_box_padding">
     <?php $thumb = $this->core_model->mediaGetThumbnailForMediaId($pic['id'], 120); ?>
     <img src="<? $thumb ?>" width="120" />
      <!-- <tr>
                  <td><h4>Filename: <?php print character_limiter( $pic['filename'], 10) ?></h4>
                    </td>
                </tr>-->
      <label>Title</label>
      <input name="media_name" type="text"  value="<?php print $pic['media_name'] ?>" />
      <label>Description</label>
      <textarea name="media_description" cols=""  rows="2"><?php print $pic['media_description'] ?></textarea>
      <div style="display:none;">
        <label>Type</label>
        <select name="media_type">
          <option value="picture" <? if($pic['media_description'] == 'picture') :  ?>  selected="selected" <? endif; ?>  >picture</option>
          <option value="video" <? if($pic['media_description'] == 'video') :  ?>  selected="selected" <? endif; ?> >video</option>
        </select>
      </div>
      <? if($pic['media_type'] == 'video') :  ?>
      <b>Embed code:</b>
      <textarea name="embed_code" cols="" style="width: 200px;" rows="2"><?php print $pic['embed_code'] ?></textarea>
      <b>Original link:</b>
      <textarea name="original_link" cols="" style="width: 200px;" rows="2"><?php print $pic['original_link'] ?></textarea>
      <? endif; ?>
      <div class="changes-are-saved" id="pic_saved_txt_<?php print $vid['id'] ?>" style="display:none"> Changes are saved... </div>
      <input name="save"  class="mw_form_button" type="button" onclick="save_media_item<? print $rand ;?>()" value="Save" />
      <input name="close"    type="button" onclick="save_media_close<? print $rand ;?>()" value="Close" />
      <input name="Delete"    type="button" onclick="contentMediaDeletePicture<? print $rand ?>(<?php print $pic['id'] ?>)" value="Delete" />
    </div>
  </div>
</form>
