<?php dbg(__FILE__); ?>

<div id="profile-main">
  <h2 id="content-type-title">Add New Content</h2>
</div>
<?php /*var_dump($form_values); */?>
<script type="text/javascript">
  $(document).ready(function(){
    $(window).load(function(){
      setTimeout(function(){
          $(".mceEditor #mce_0_ifr").contents().keyup(function(){
             $("#mce_0", window.parent.document).blur();
          });
      }, 1000);
    });
  });
</script>
<script type="text/javascript" src="<?php print TEMPLATE_URL; ?>js/contenttypeevents.js"></script>
<?php if($the_content_is_saved == true): ?>
<div class='clear'></div>
<h2>saved!</h2>
<br />
<a href="<?php print CI::model('content')->getContentURLById($the_saved_id); ?>" target="_blank"><strong>See it</strong></a><br />
<a href="<?php print site_url('users/user_action:post/id:'.$the_saved_id) ; ?>"><strong>Edit it</strong></a>
<?php // var_dump($the_saved_id); ?>
<?php else: ?>
<script type="text/javascript">





function removePostCat(id){
    $("#" + id).uncheck();
}



function deletePicture(id, id_to_fade){
    var answer = confirm("Are you sure you want to delete this picture?")
	if (answer){
		$.post("<?php print site_url('api/media/delete_picture'); ?>", { id: id },
		   function(data){

           var picture_to_remove = $("#picture_"+id_to_fade);
           var pic_parent = picture_to_remove.parent();


           pic_parent.fadeOut(function(){
           if(pic_parent.css("clear")=='both'){
               pic_parent.next().css("clear", "both");
           }
             $(this).remove();
             if($("#post-picture-gallery a").length==0){
                $("#post-picture-gallery").remove();
             }
           });

		});
	}
	else{

	}
	


	
}








 $(document).ready(function(){

  onSelectProduct(function(){
    $(".product-options").show();
     $(".category-tree").slideDown();
     $("#content-type-title").html("Add New Product");
  });

  onSelectBlog(function(){
     $(".category-tree").slideUp();
     $("#content-type-title").html("Add New Blog Post");
     $(".user-post-select-categories  input[type='checkbox']").uncheck();
     $("input[rel='blog']").check();
     $("#TheCategoryTree").removeClass("atleastOneError error");
  });
  onDeSelectBlog(function(){
     $("input[rel='blog']").uncheck();
  });
  onSelectGallery(function(){
     $(".category-tree").slideUp();
     $("#content-type-title").html("Add New Gallery");
     if(typeof tinyMCE =='object'){
        tinyMCE.execCommand('mceRemoveControl', false, 'mce_0');
     }
     $(".user-post-select-categories  input[type='checkbox']").uncheck();
     $("input[rel='gallery']").check();
     $("#TheCategoryTree").removeClass("atleastOneError error");   
  });
  onDeSelectGallery(function(){
    if(typeof tinyMCE =='object'){
      tinyMCE.execCommand('mceAddControl', false, 'mce_0');
    }
     $("input[rel='gallery']").uncheck();
  });
  onSelectArticle(function(){
     $(".category-tree").slideDown();
     $("#content-type-title").html("Add New Article");
  });
  onSelectService(function(){
     $(".category-tree").slideDown();
     $("#content-type-title").html("Add New Service");
  });
  onSelectTraining(function(){
     $(".addChapter").show();
     $(".category-tree").slideDown();
     $("#content-type-title").html("Add New Training");

  });

  onDeSelectProduct(function(){
    $(".product-options").hide();
  });



  onDeSelectTraining(function(){
    if($("#new-chapters .item").length>0){
      var deleteChapters =  confirm("Are you sure you want to delete your chapters?");
      if(deleteChapters){
        $("#new-chapters").html("");
        $(".addChapter").hide();
      }
      else{
        $("#postSet").val("trainings");
        $("#addnav a").removeClass("active");
        $("#addnav a[rel=trainings]").addClass("active");
        $(".product-options").hide();
        $("#content-type-title").html("Add New Training");
      }
    }
    else{
       $(".addChapter").hide();
    }


  });





   var the_tree = document.getElementById('TheCategoryTree');

  $(the_tree).find("input[rel=blog]").parent().hide();
  $(the_tree).find("input[rel=gallery]").parent().hide();






    var postsetval = $("#postSet").val();

   if(postsetval!="" && postsetval!=undefined && postsetval!='blog' && postsetval!='gallery'){
      $(".category-tree").show();
      if($("#postSet").val()=='trainings'){
          $(".addChapter").show();
      }
   }


               $(".category-tree input[type='checkbox']").click(function(){

                var maxCheck = 5;

                 var length = $(".user-post-select-categories .user-post-select-categories input:checked").length;
                 if(length>maxCheck){
                    mw.box.overlay();
                    mw.box.alert("<h3>You can choose maximum<br />" + maxCheck + " categories.</h3>");
                    return false;
                 }
                 else{
                   $(".number-of-categories").html(length+" Categories selected");
                 }
            });

    $("#postform").validate('none', function(){

      //on error
      $("html, body").animate({scrollTop: $("#postform").offset().top}, 700, function(){
        mw.box.overlay();
        mw.box.alert("Please fill out the required fields", 1);
      });
    });


 });




</script>
<?php if(!empty($form_errors)): ?>
<?php p($form_errors); ?>
<?php endif; ?>
<form class="xform pad" action="<?php print site_url($this->uri->uri_string()); ?>"   method="post" enctype="multipart/form-data"   id="postform">
  <ul id="addnav">
    <li> <a href="#" rel="none"> <span><img src="<?php print TEMPLATE_URL; ?>img/add.article.png" alt="" /></span> <strong>Post</strong> </a> </li>
    <li> <a href="#" rel="gallery"> <span><img src="<?php print TEMPLATE_URL; ?>img/add.gallery.png" alt="" /></span> <strong>Gallery</strong> </a> </li>
  </ul>
  <div class="c" style="padding-bottom: 20px;">&nbsp;</div>
  <?php $more = CI::model('core')->getCustomFields('table_content', $form_values['id']);
	 if(!empty($more)){
		ksort($more);
	 }  ?>
  <?php if(($form_values['content_subtype'] == '' ) or ($form_values['content_subtype'] == 'none' )): ?>
  <?php $form_values['content_subtype'] = CI::model('core')->getParamFromURL ( 'type' ); ?>
  <?php endif; ?>
  <div class="notification abshidden" align="center">
    <div class="regw">
      <label class="lbl"><strong>Choose type: *</strong></label>
      <span class="linput">
      <input type="text" name="content_subtype" id="postSet" class="required" value="<?php print $form_values['content_subtype'] ?>"  />
      <?php /*
          <option <?php if(($form_values['content_subtype'] == '' ) ): ?> selected="selected" <?php endif; ?>  value="">Select:</option>
          <option <?php if(($form_values['content_subtype'] == 'article' ) or ($form_values['content_subtype'] == 'none' )): ?> selected="selected" <?php endif; ?>  value="none">Article</option>
          <option <?php if($form_values['content_subtype'] == 'trainings' ): ?> selected="selected" <?php endif; ?>  value="trainings">Trainings</option>
          <option <?php if($form_values['content_subtype'] == 'products' ): ?> selected="selected" <?php endif; ?>  value="products">Product</option>
          <option <?php if($form_values['content_subtype'] == 'services' ): ?> selected="selected" <?php endif; ?>  value="services">Service</option>
          <option <?php if($form_values['content_subtype'] == 'blog' ): ?> selected="selected" <?php endif; ?>  value="blog">Blog</option>
          <option <?php if($form_values['content_subtype'] == 'gallery' ): ?> selected="selected" <?php endif; ?>  value="gallery">Gallery</option>
*/ ?>
      </span> </div>
  </div>
  <div class="category-tree required-atleast-1" id="TheCategoryTree">
    <h2><span class="atleastonerrormessage">Yo must choose at least one category</span>Choose categories </h2>
    <div class="notification" style="margin-bottom: 10px">
      <input name="id" type="hidden" value="<?php print $form_values['id']; ?>" />
      <?php 
    		$categories = CI::model('taxonomy')->getTaxonomiesForContent($form_values['id'], 'categories');
    		
    	  $actve_ids = false;
    	  $actve_ids = $categories;
    	  $active_code = ' checked="checked"  ';
    	  $removed_ids_code = ' disabled="disabled"   ';
     CI::model('content')->content_helpers_getCaregoriesUlTree($content_parent = 0, $link=  "<input id='category_selector_{id}' name='taxonomy_categories[]' type='checkbox'  {active_code}  {removed_ids_code}  rel='{taxonomy_content_type}'   id='category_selector_{id}' value='{id}' /><label>{taxonomy_value}</label>", $actve_ids , $active_code , $remove_ids = $categories_ids_to_remove, $removed_ids_code = $removed_ids_code, $ul_class_name =  'user-post-select-categories ooyesTrees', $include_first = true, $content_type = false, $li_class_name = false, $add_ids = $categories_ids_to_add);


     ?>
    </div>
    <div class="number-of-categories">0 Categories selected</div>
  </div>
  <div class="notification" align="center" id="post-choose-cats">
    <p class="hpad">Choose categories. You can add more than one category</p>
    <a href="#" class="btn choose-cats-btn" onclick="mw.box.overlay();mw.box.element({element:'.category-tree'})">Choose Categories</a>
    <p class="hpad"><strong>Selected Categories:</strong></p>
    <div id="selected-cat"> </div>
  </div>
  <div>
    <div class="item">
      <label>Title: *</label>
      <span class="linput">
      <input class="required type-text" name="content_title" type="text" value="<?php print $form_values['content_title']; ?>">
      </span> </div>
    <div class="item">
      <label >Description: *</label>
      <span class="larea">
      <textarea class="required"  name="content_description" cols="" rows=""><?php print $form_values['content_description']; ?></textarea>
      </span> </div>
    <div class="item" id="regweditor">
      <div id="regweditor-holder">
        <label>Content: *</label>
        <span class="larea">
        <textarea name="content_body" class="required richtext"  cols="" rows=""><?php print $form_values['content_body']; ?></textarea>
        </span> </div>
    </div>
    <div class="hr">&nbsp;</div>
    <div class="c" style="padding-bottom: 12px;"></div>
    <div class="item">
      <?php $pictures =  ($form_values['media']['pictures']); ?>
      <script type="text/javascript">
          $(document).ready(function(){
            if($("#post-picture-gallery a.setgal").length==1){
              $("#post-picture-gallery a.setgal").modal("single");
            }
            else if($("#post-picture-gallery a.setgal").length>1){
              $("#post-picture-gallery a.setgal").modal("gallery");
            }
          });
        </script>
      <?php if(!empty($pictures )) : ?>
      <ul id="post-picture-gallery">
        <?php $i=1 ; foreach($pictures as $pic): ?>
        <li <?php if (($i-1)%4==0): ?> style="clear:both" <?php endif; ?>> <a id="picture_<?php print $pic["id"]; ?>" class="setgal" href="<?php print $pic["urls"]['original'] ; ?>"> <img src="<?php print CI::model('core')->mediaGetThumbnailForMediaId($pic["id"], $size = 140); ?>" alt="" /> </a> <a class="deleteGalleryImage" href="javascript:deletePicture('<?php print CI::model('core')->securityEncryptString ($pic["id"] ) ?>', '<?php print $pic["id"]; ?>' );">delete</a> </li>
        <?php $i++; endforeach; ?>
      </ul>
      <?php endif;  ?>
      <div class="c" style="padding-bottom: 15px">&nbsp;</div>
      <script type="text/javascript">
	$(document).ready(function(){
		$("#more_images").click(function(){
			var up_length = $(".input_Up").length;
            if(up_length<=50){
    			var first_up = $("#more_f input:first");
    			$("#more_f").append("<br><br><input class='input_Up' name='picture_' type='file'>");
    			$("#more_f input:last").attr("name", "picture_" + up_length);
            }
		});
	});
</script>
      <div id="more_f" style="padding-bottom:10px">
        <input class="input_Up" name="picture_" type="file">
      </div>
      <a href="#" id="more_images"> Add more pictures</a> </div>
   
  </div>
  <input name="save" type="submit" value="save" /> 
</form>
<?php endif; ?>
</div>
<?php dbg(__FILE__, 1); ?>
