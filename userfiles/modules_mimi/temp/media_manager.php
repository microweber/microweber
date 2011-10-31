<script> 
  
	$(document).ready(function(){
		 
 
		 
 
	});
    
    
    </script>
<?
  $id = $params['for_id'];
  $for = $params['for'];
  $media_type = $params['type'];
  //p($params);
  
  ?>
<?  $media1 = get_media($id, $for, $media_type);
	 $media1 =  $media1['pictures'];
 //p($media1);

?>
<script type="text/javascript">

	

	

	

	

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
save_media_close<? print $rand ;?>()
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
 
  
  
  
  
  // prepare the form when the DOM is ready 
$(document).ready(function() { 
   

    // bind form using 'ajaxForm' 
    //$('.picsAjaxSaveForm').ajaxForm(media_pics_options); 
}); 
 var save_media_item = function($form_id){
  
  var media_pics_options = { 
      //  target:        '#output1',   // target element(s) to be updated with server response 
        beforeSubmit:  media_pics_showRequest,  // pre-submit callback 
		type:      'post',
		url:       '<?php print site_url('api/media/media_save') ?>',
		clearForm: false,
		async:false,
		resetForm: false   ,
        success:       media_pics_showResponse  // post-submit callback 

        // other available options: 
        //url:       url         // override for form's 'action' attribute 
        //type:      type        // 'get' or 'post', override for form's 'method' attribute 
        //dataType:  null        // 'xml', 'script', or 'json' (expected server response type) 
        //clearForm: true        // clear all form fields after successful submit 
        //resetForm: true        // reset the form after successful submit 
 
        // $.ajax options can be used here too, for example: 
        //timeout:   3000 
    }; 
	
 $("#"+$form_id).ajaxSubmit(media_pics_options);
 
 }
 
 
// pre-submit callback 
function media_pics_showRequest(formData, jqForm, options) { 
 
    return true; 
} 
 
// post-submit callback 
function media_pics_showResponse(responseText, statusText)  { 

	// Modal.close();
	$('.mw_modal').hide();
	// call_media_manager();
	
	 
	 
} 

function  contentMediaEditPicture($id){



	if($("#content_form_object").hasClass("save_disabled")){

	alert("Error: You cannot delete while uploading!");

	return false;

	} else {
		
		
		
		
		
		
		
		
		
$('#pic_edit_form_'+$id).css("background-color","teal");
$('#pic_edit_form_'+$id).show();
	}





}
</script>
<?php if(empty($media1)  ): ?>

Please upload some pictures in the gallery.
<?php else : ?>
<ul id="gallery_module_sortable_pics">
<?php $i = 1; if(!empty($media1)): ?>
<?php foreach($media1 as $pic): ?>
<li>
  <div class="mw_modal" id="pic_edit_form_<?php print $pic['id'] ?>" style="display:none;">
    <form action=""   class="picsAjaxSaveForm" id="picsAjaxSaveForm<?php print $pic['id'] ?>" method="post" enctype="multipart/form-data">
      <input name="id" type="hidden" value="<?php print $pic['id'] ?>" />
      <table cellspacing="1" cellpadding="1" width="100%" class="pic_details_table" id="media_edit_table_<?php print $pic['id'] ?>" >
        <!-- <tr>
                  <td><h4>Filename: <?php print character_limiter( $pic['filename'], 10) ?></h4>
                    </td>
                </tr>-->
        <tr>
          <td><strong>Title:</strong></td>
          <td><input name="media_name" type="text" style="width: 200px;" value="<?php print $pic['media_name'] ?>" /></td>
        </tr>
        <tr>
          <td><b>Description:</b></td>
          <td><textarea name="media_description" cols="" style="width: 200px;" rows="2"><?php print $pic['media_description'] ?></textarea></td>
        </tr>
        <?php /*
                 <tr>
                  <td><b>Special:</b>


             <select name="is_special">
                    <option  <?php if($pic['is_special'] == 'y' ): ?> selected="selected" <?php endif; ?>  value="y">yes</option>
                    <option  <?php if($pic['is_special'] != 'y' ): ?> selected="selected" <?php endif; ?>  value="n">no</option>
                  </select>


                  </td>
                </tr>
                 */ ?>
        <?php /*
                 <tr>
                  <td><b>Banner?:</b>


             <select name="is_banner">
                    <option  <?php if($pic['is_banner'] == 'y' ): ?> selected="selected" <?php endif; ?>  value="y">yes</option>
                    <option  <?php if($pic['is_banner'] != 'y' ): ?> selected="selected" <?php endif; ?>  value="n">no</option>
                  </select>


                  </td>
                </tr>
                 */ ?>
      </table>
      <div class="changes-are-saved" id="pic_saved_txt_<?php print $vid['id'] ?>" style="display:none"> Changes are saved... </div>
      <input name="save" style="float: left;margin-left:249px; " type="button" onclick="save_media_item('picsAjaxSaveForm<?php print $pic['id'] ?>')" value="save" />
    </form>
  </div>
</li>
<?php $thumb = $this->core_model->mediaGetThumbnailForMediaId($pic['id']);
//p($thumb);
?>
<li id="gallery_module_sortable_pics_positions_<?php print $pic['id'] ?>" onmouseover="$(this).find('.pic_link_holder').css('visibility', 'visible')" onmouseout="$(this).find('.pic_link_holder').css('visibility', 'hidden')">
  <div class="pic_bg"></div>
  <div class="pic_obj_content" style="background-image:url('<?php print $thumb;  ?>'); background-repeat:no-repeat; background-position:center center;" id="pictire_id_<?php print $pic['id'] ?>">
    <div class="pic_link_holder">
      <div class="pic_link_holder_bg"></div>
      <div class="pic_link_holder_content"> <a href="javascript:;" class="right" onClick="contentMediaDeletePicture('<?php print $pic['id'] ?>')">delete</a>
        <!-- <a href="javascript:;" class="right" onClick="contentMediaEditPicture('<?php print $pic['id'] ?>')">edit</a>-->
        <a href="javascript:;" onClick="contentMediaEditPicture('<?php print $pic['id'] ?>')">edit</a> </div>
    </div>
    <!-- <span class="pic_obj_New"></span>-->
  </div>
</li>
<?php $i++; endforeach; ?>
<?php endif; ?>
<?php endif; ?>
