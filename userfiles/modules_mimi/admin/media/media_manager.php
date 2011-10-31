<? 
//p($params);
?>
<?
$rand = rand();
    
?>
<?
 if($params['module_id'] == false){
  $id = $params['for_id'];
  $for = $params['for'];
  
   if(strval($params['for_what']) != ''){
	 $for = $params['for_what'];
	 
 }
 } else {
	// $id = $params['for_id']= $params['module_id'];
 // $for =  $params['for_what'] = 'module';
 }
  
   if(intval($params['for_id'])  > 0){
 
	$id = $params['for_id'];
}
  $queue_id = $params['queue_id'];
  
  
   
  $media_type = $params['type'];
 if($id  == false and $for == false){
 if($params['module_id']){
	 $collection = $params['module_id'];
	 $queue_id = false;
 }
 
 }
 
 
  //p($params);
//  var_dump($id,  $media_type,  $queue_id, $collection);
  ?>
<?  $media1 = get_media($id, $for = 'post', $media_type,  $queue_id, $collection);
 
// p($media1);
 

   
   
   
	
	 if($media_type  == 'video'){
		  $media_videos = $media1['videos'];
		   $media1 =$media_videos;
	 } else {
		   
		  $media1 = $media1['pictures'];
	 }
	 
 //p($media_videos);

?>
<script type="text/javascript">

	

	

	

	









function contentMediaPicturesRefreshList<? print $rand ?>(){

var media_upload_queue_pic = $('#media_queue_pictures').val();

var to_table_id1 = $('#id').val();

var media_pictures_to_table_id = $('#media_pictures_to_table_id').val();

$.post("<?php print site_url('admin/media/contentMediaPicturesList') ?>/to_table:table_content/queue_id:"+media_upload_queue_pic+"/to_table_id:"+media_pictures_to_table_id+"/random_stuff:"+Math.random(), function(data){

  $("#media_pictures_placeholder").html(data);

 mw.reload_module('media/gallery');

if ( $(".gallery_module_sortable_pics<? print $rand ?>").exists() ){

	$(".gallery_module_sortable_pics<? print $rand ?>").sortable(

	{

	update : function () {

	var order = $('.gallery_module_sortable_pics<? print $rand ?>').sortable('serialize');

	$.post("<?php print site_url('admin/media/reorderMedia') ?>", order,

	function(data){
 mw.reload_module('media/gallery');
	});

	}

	}				

	);

}









 

});



}

</script>
<script type="text/javascript">
 
  
  
  
  
 

function  contentMediaEditPicture<? print $rand ?>($id){



	if($("#content_form_object").hasClass("save_disabled")){

	alert("Error: You cannot delete while uploading!");

	return false;

	} else {
		
		
		
		
		
		
		
		
		
$('#pic_edit_form_'+$id).css("background-color","teal");
$('#pic_edit_form_'+$id).show();
	}





}



function load_media_edit_module<? print $rand ?>($media_id){
	
	   $.ajax({
  url: '<? print site_url('api/module'); ?>',
   type: "POST",
      data: ({module : 'admin/media/edit_item' ,id : $media_id }),
     // dataType: "html",
      async:false,
      
  success: function(resp) {
     // alert(resp);
   $('#edit_media_cloned_form<? print $rand ?>').html(resp);
 mw.reload_module('media/gallery');
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
						    mw.reload_module('media/gallery');
  $(".gallery_module_sortable_pics<? print $rand ?>").sortable({ 
    handle : '.handle', 
    update : function () { 
      var order = $('.gallery_module_sortable_pics<? print $rand ?>').sortable('serialize'); 
	 // alert(order);
	 
	 //
 
   $.ajax({
  url: '<? print site_url('api/media/reorder') ?>',
   type: "POST",
      data: order,

      async:true,

  success: function(resp) {

  // $('#media_manager').html(resp);
 mw.reload_module('media/gallery');
 

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
<div class="gallery_module_sortable_holder <? print $qe_class ?>"  >
  <ul class="gallery_module_sortable_pics<? print $rand ?> <? print $qe_class ?> gallery_module_sortable_pics">
    <?php $i = 1; if(!empty($media1)): ?>
    <?php foreach($media1 as $pic): ?>
    <?php $thumb = $this->core_model->mediaGetThumbnailForMediaId($pic['id']);
 
?>
    <li id="picture_id_<?php print $pic['id'] ?>" onclick="load_media_edit_module<? print $rand ?>('<?php print $pic['id'] ?>')">
      <center>
        <img class="handle" src="<?php print $thumb;  ?>" />
      </center>
      <!-- <a href="javascript:;" class="right" onClick="contentMediaDeletePicture<? print $rand ?>('<?php print $pic['id'] ?>')">delete</a>-->
      <!--<a href="javascript:;" onClick="contentMediaEditPicture<? print $rand ?>('<?php print $pic['id'] ?>')">edit</a> <a href="javascript:;" class="right" onClick="contentMediaDeletePicture<? print $rand ?>('<?php print $pic['id'] ?>')">delete</a>-->
      <div class="mw_modal" id="pic_edit_form_<?php print $pic['id'] ?>" style="display:none;">
        
      </div>
    </li>
    <?php $i++; endforeach; ?>
  </ul>
  <?php endif; ?>
  <br />
  <br />
  <br />
  <div id="edit_media_cloned_form<? print $rand ?>"> </div>
</div>
<?php endif; ?>
