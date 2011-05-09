
<? 
//p($params);
?>
<script> 
  
	$(document).ready(function(){
		 
 
		 
 
	});
    
    
    </script>
<?
  $id = $params['for_id'];
  $for = $params['for'];
  
   if(strval($params['for_what']) != ''){
	 $for = $params['for_what'];
	 
 }
  
  
  $queue_id = $params['queue_id'];
  
  
   
  $media_type = $params['type'];
 
 if($params['module_id']){
	 $collection = $params['module_id'];
	 
 }
 
 
 
 //p($params);
  
  ?>
<?  $media1 = get_media($id, $for, $media_type,  $queue_id, $collection);
 
 
 

   
   
   
	
	 if($media_type  == 'video'){
		  $media_videos = $media1['videos'];
		   $media1 =$media_videos;
	 } else {
		   
		  $media1 = $media1['pictures'];
	 }
	 
 //p($media_videos);

?>
<script type="text/javascript">

	

	

	

	

function  contentMediaDeletePicture($id){



if($("#content_form_object").hasClass("save_disabled")){

alert("Error: You cannot delete while uploading!");

return false;

}







var answer = confirm("Are you sure?")

	if (answer){

		$.post("<?php print site_url('api/media/media_delete') ?>", { id: $id, time: "2pm" },

  function(data){

	  //$(".gallery_module_sortable_pics_positions_"+$id).fadeOut();

	  $("#picture_id_"+$id).remove();
 parent.mw.reload_module('media/gallery');
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

 parent.mw.reload_module('media/gallery');

if ( $(".gallery_module_sortable_pics").exists() ){

	$(".gallery_module_sortable_pics").sortable(

	{

	update : function () {

	var order = $('.gallery_module_sortable_pics').sortable('serialize');

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


function  contentMediaEditPicture($id){



	if($("#content_form_object").hasClass("save_disabled")){

	alert("Error: You cannot delete while uploading!");

	return false;

	} else {
		
		
		
		
		
		
		
		
		
$('#pic_edit_form_'+$id).css("background-color","teal");
$('#pic_edit_form_'+$id).show();
	}





}



function load_media_edit_module($media_id){
	
	   $.ajax({
  url: '<? print site_url('api/module'); ?>',
   type: "POST",
      data: ({module : 'admin/media/edit_item' ,id : $media_id }),
     // dataType: "html",
      async:false,
      
  success: function(resp) {
     // alert(resp);
   $('#edit_media_cloned_form').html(resp);
 
   // alert('Load was performed.');
  }
    });
	   
	   
	   
	//$('#edit_media_cloned_form').empty();
	
//$("#"+$form_id).clone().appendTo('#edit_media_cloned_form');	





}
</script>
<?php if(empty($media1)  ): ?>

Please upload some media in the gallery.
<?php else : ?>
<script type="text/javascript"> 
// When the document is ready set up our sortable with it's inherant function(s) 
$(document).ready(function() { 
  $(".gallery_module_sortable_pics").sortable({ 
    handle : '.handle', 
    update : function () { 
      var order = $('.gallery_module_sortable_pics').sortable('serialize'); 
	 // alert(order);
	 
	 //
 
   $.ajax({
  url: '<? print site_url('api/media/reorder') ?>',
   type: "POST",
      data: order,

      async:true,

  success: function(resp) {

  // $('#media_manager').html(resp);
 parent.mw.reload_module('media/gallery');
 

  }
    });
	
	 
      //$("#info").load("process-sortable.php?"+order); 
    } 
  }); 
}); 
</script>
<? if($params['quick_edit']) { $qe_class = 'qiuck_edit' ;} else { $qe_class = false ;}  ?>
<br />
<br />
<br />
<div class="gallery_module_sortable_holder <? print $qe_class ?>">
  <ul class="gallery_module_sortable_pics">
    <?php $i = 1; if(!empty($media1)): ?>
    <?php foreach($media1 as $pic): ?>
    <?php $thumb = $this->core_model->mediaGetThumbnailForMediaId($pic['id']);
//p($thumb);
?>
    <li id="picture_id_<?php print $pic['id'] ?>" onclick="load_media_edit_module('<?php print $pic['id'] ?>')"> <img class="handle" src="<?php print $thumb;  ?>" />
      <!--<a href="javascript:;" onClick="contentMediaEditPicture('<?php print $pic['id'] ?>')">edit</a> <a href="javascript:;" class="right" onClick="contentMediaDeletePicture('<?php print $pic['id'] ?>')">delete</a>-->
      <div class="mw_modal" id="pic_edit_form_<?php print $pic['id'] ?>" style="display:none;">
        <?
   /* <form action=""   class="picsAjaxSaveForm" id="picsAjaxSaveForm<?php print $pic['id'] ?>" method="post" enctype="multipart/form-data">
      <input name="id" type="hidden" value="<?php print $pic['id'] ?>" />
      <table cellspacing="1" cellpadding="1" width="100%" class="pic_details_table" id="media_edit_table_<?php print $pic['id'] ?>" >
        <!-- <tr>
                  <td><h4>Filename: <?php print character_limiter( $pic['filename'], 10) ?></h4>
                    </td>
                </tr>-->
        <tr>
          <td>
           
          
          <strong>Title:</strong></td>
          <td><input name="media_name" type="text" style="width: 200px;" value="<?php print $pic['media_name'] ?>" /></td>
        </tr>
        <tr>
          <td><b>Description:</b></td>
          <td><textarea name="media_description" cols="" style="width: 200px;" rows="2"><?php print $pic['media_description'] ?></textarea></td>
        </tr>
        
        
        
        
        <tr>
                <td><b>Type:</b></td>
                  <td>
                   <select name="media_type">
  <option value="picture" <? if($pic['media_description'] == 'picture') :  ?>  selected="selected" <? endif; ?>  >picture</option>
 <option value="video" <? if($pic['media_description'] == 'video') :  ?>  selected="selected" <? endif; ?> >video</option>
 </select>
</td>
                </tr>
                <? if($pic['media_type'] == 'video') :  ?>   
                  <tr>
                <td><b>Embed code:</b></td>
                  <td>
         <textarea name="embed_code" cols="" style="width: 200px;" rows="2"><?php print $pic['embed_code'] ?></textarea>           
</td>
                </tr>
                
                  <tr>
                <td><b>Original link:</b></td>
                  <td>
         <textarea name="original_link" cols="" style="width: 200px;" rows="2"><?php print $pic['original_link'] ?></textarea>           
</td>
                </tr>
                <? endif; ?>
        
              
      </table>
      <div class="changes-are-saved" id="pic_saved_txt_<?php print $vid['id'] ?>" style="display:none"> Changes are saved... </div>
      <input name="save" style="float: left;margin-left:249px; " type="button" onclick="save_media_item('picsAjaxSaveForm<?php print $pic['id'] ?>')" value="save" />
    </form>*/
	
	
	?>
      </div>
    </li>
    <?php $i++; endforeach; ?>
  </ul>
  <?php endif; ?>
  <?php endif; ?>
  <br />
  <br />
  <br />
  <div id="edit_media_cloned_form"> </div>
</div>
