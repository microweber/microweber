

<script type="text/javascript">
 
  
  
  
  
  // prepare the form when the DOM is ready 
$(document).ready(function() { 
    var media_pics_options = { 
      //  target:        '#output1',   // target element(s) to be updated with server response 
        beforeSubmit:  media_pics_showRequest,  // pre-submit callback 
		type:      'post',
		url:       '<?php print site_url('admin/media/mediaSave') ?>',
		clearForm: true,
		resetForm: true   ,
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

    // bind form using 'ajaxForm' 
    $('.picsAjaxSaveForm').ajaxForm(media_pics_options); 
}); 
 
// pre-submit callback 
function media_pics_showRequest(formData, jqForm, options) { 
 
    return true; 
} 
 
// post-submit callback 
function media_pics_showResponse(responseText, statusText)  { 

	 Modal.close();
	 
	 
} 
</script>



<?php 

//p($media1);
//p($media2);
?>



 
<?php if(empty($media1) and empty($media2)): ?>

Please upload some pictures in the gallery.

<?php else : ?>

<?php if(!empty($media2)): ?> 
<? $media1 = array_merge($media1, $media2);?>
<?php endif; ?>



<style type="text/css">

.mw_modal {
background:none repeat scroll 0 0 white;
border:10px solid black;
height:300px;
left:50%;
padding:10px;
position:absolute;
top:0;
width:400px;
z-index:999;
}


</style>
<ul id="gallery_module_sortable_pics">

<?php $i = 1; if(!empty($media1)): ?>

  <?php foreach($media1 as $pic): ?>
  <li>
  <div class="mw_modal" id="pic_edit_form_<?php print $pic['id'] ?>" style="display:none;">


               
                <form action=""   class="picsAjaxSaveForm" method="post" enctype="multipart/form-data">
              <input name="id" type="hidden" value="<?php print $pic['id'] ?>" />
              
               
              
              <table cellspacing="1" cellpadding="1" width="100%" class="pic_details_table" id="media_edit_table_<?php print $pic['id'] ?>" >
             
                <tr>
                <td><strong>Name</strong></td>
                  <td>
                    <input name="media_name" type="text" style="width: 200px;" value="<?php print $pic['media_name'] ?>" /></td>
                </tr>
                <tr>
                <td><b>Description:</b></td>
                  <td>
                    <textarea name="media_description" cols="" style="width: 200px;" rows="2"><?php print $pic['media_description'] ?></textarea></td>
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
                <? if($pic['media_description'] == 'video') :  ?>   
                  <tr>
                <td><b>Embed code:</b></td>
                  <td>
         <textarea name="embed_code" cols="" style="width: 200px;" rows="2"><?php print $pic['embed_code'] ?></textarea>           
</td>
                </tr>
                <? endif; ?>
                
                
                
                

                
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
              <input name="save" style="float: left;margin-left:249px; " type="submit" value="save" />
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

            <div class="pic_link_holder_content">

                <a href="javascript:;" class="right" onClick="contentMediaDeletePicture('<?php print $pic['id'] ?>')">delete</a>

              <!-- <a href="javascript:;" class="right" onClick="contentMediaEditPicture('<?php print $pic['id'] ?>')">edit</a>-->
              
               <a href="javascript:;" onClick="contentMediaEditPicture('<?php print $pic['id'] ?>')">edit</a>
               

               
               
               

            </div>

        </div>

       <!-- <span class="pic_obj_New"></span>-->

    </div>

  </li>



 





  <?php $i++; endforeach; ?>



<?php endif; ?>

