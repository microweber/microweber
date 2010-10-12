<?php require (ACTIVE_TEMPLATE_DIR.'dashboard/dashboard_sidebar.php') ?>
<?php dbg(__FILE__); ?>

<style type="text/css">
.product-options{
  display: none;
}


</style>

<div id="profile-main">
  <div style="padding: 10px 0;" id="home-title">
  <a href="#help-new-content" class="master-help right">What is this?</a>
    <h2 id="content-type-title">Add New Content</h2>

    <div class="master-help" id="help-new-content">

    <div style="height: 250px;overflow-x: hidden;overflow-y: scroll;padding-right: 12px">

<p>    In this section you can add your new content in terms of articles, trainings, products, services.
    That's where you go when you want to update your blog, too. You also have at your disposal a huge
    gallery in this section, where you can upload an unlimited number of pictures, organize them in different albums and describe each album.</p>



<p>
<p>When you hit the Article button a set of categories appears bellow. You're invited to choose the section for which your article is supposed.
So, simply check the desired box, for example Entrepreneur. You might want to be more specific, so you may also choose Leader or Author for example.
That's where your article is going to appear after you submit it. Please note that you must select a category in order to be able to post your text.
This is the only way that the system can know where your article belongs.</p>

<p>You may select more than 1 category if your article corresponds with the content in the selected categories.
For example, if you write a text on "How to Grow Your Business Online", obviously it corresponds directly to categories such as
"Business", "Internet Business", "Work From Home", "Internet Marketing", etc. In this way your content will appear in all of the
selected categories and will reach the optimal number of people, interested in this specific topic.</p>

<p>When finished with this you should fill in a title in the "Title" box. This field is required, too. </p>

<p>Next is the "Description" box where you fill in a short description of your content. This short text will
appear in search listings with a "Read more" option. Try to extract the essence of your message in order to
attract attention to your article. This short description is what everyone will see first and decide if they
are interested in what you have to share. Be creative!</p>

<p>The final step is to actually enter your content. You can insert different images, embed videos, format the text and share external links. </p>

<p>There is also an extra gallery for you on the bottom of your screen, bellow the "Content" box.
You can add more pictures by clicking on the "Add more pictures" blue text. Simply upload the desired pictures and they will appear on the bottom of your text as an additional gallery.</p>

<p>You can also choose if you want to allow comments for this content by checking the "Yes" or "No" field following the question "Do you want the visitors to be able to comment on this post?" under the "Add more pictures" field.

To complete the process just hit "Save content". You're ready! You content will now appear in the desired directory.</p></p>




<p>All of the above applies to the next button - <strong>Trainings</strong>. First select a category, then a title, write a short description and then add your content. Hit "Save content" when finished editing.
</p>
<p>All of the above applies to the next button - <strong>Products</strong>. First select a category, then a title, write a short description and then add your content. When ready with that it's recommended to also add an "External Buy Link". You will see this field right under the "Content" box and the additional Gallery. Simply enter a direct link to an external webpage where one can instantly purchase your product. There is one more field - The "Product Price in USD" box. Fill in the price of your product in US Dollars.
</p>
Hit "Save content" when finished editing.

<p>Next is the <strong>Services</strong> button. All of the above guidelines apply to this field as well. First select a category, then a title, write a short description and then add your content. However there is no option to fill in an "External Buy Link" here or a price. This is something you should personally discuss with your prospects and clients.
Hit "Save content" when finished editing.
</p>

<p>Next is the <strong>Blog</strong> button. Here you can add personal articles that won't be published in any other category. The content will be visible solely on your personal SOB Blog. All of the above guidelines apply to this field as well, except the category check. The dropdown menu with the category list won't be visible here. So, first select a title, then write a short description and then add your content. Hit "Save content" when finished editing.
</p>
<p>
  <strong>Gallery</strong> is the last button in the "Add new content" section.
  Here you have the same options as before – title, description and content. In the content box you can freely tell a whole story if you want.
  Tells us about your last event, and then show us the pictures. Write a poem about you vacation and decorate it with some photographs.
  In other words, simply let your creativity breed. It's the best if you do it right away.

  As you already know, the short description is what everyone will see first in the search results right under the title.

  When ready with your editing just hit the "Save changes" button. Your pictures and content are published.
</p>




   </div>
    </div>
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
  <a href="<?php print $this->content_model->getContentURLById($the_saved_id); ?>" target="_blank"><strong>See it</strong></a><br />
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
    <li>
        <a href="#" rel="none">
            <span><img src="<?php print TEMPLATE_URL; ?>img/add.article.png" alt="" /></span>
            <strong>Article</strong>
        </a>
    </li>
    <li>
        <a href="#" rel="trainings">
            <span><img src="<?php print TEMPLATE_URL; ?>img/add.training.png" alt="" /></span>
            <strong>Traning</strong>
        </a>
    </li>
    <li>
        <a href="#" rel="products">
            <span><img src="<?php print TEMPLATE_URL; ?>img/add.product.png" alt="" /></span>
            <strong>Products</strong>
        </a>
    </li>
    <li>
        <a href="#" rel="services">
            <span><img src="<?php print TEMPLATE_URL; ?>img/add.service.png" alt="" /></span>
            <strong>Services</strong>
        </a>
    </li>
    <li>
        <a href="#" rel="blog">
            <span><img src="<?php print TEMPLATE_URL; ?>img/add.blog.png" alt="" /></span>
            <strong>Blog</strong>
        </a>
    </li>
    <li>
        <a href="#" rel="gallery">
            <span><img src="<?php print TEMPLATE_URL; ?>img/add.gallery.png" alt="" /></span>
            <strong>Gallery</strong>
        </a>
    </li>


  </ul>

 <div class="c" style="padding-bottom: 20px;">&nbsp;</div>
    <?php $more = $this->core_model->getCustomFields('table_content', $form_values['id']);
	 if(!empty($more)){
		ksort($more);
	 }  ?>
    <?php if(($form_values['content_subtype'] == '' ) or ($form_values['content_subtype'] == 'none' )): ?>
    <?php $form_values['content_subtype'] = $this->core_model->getParamFromURL ( 'type' ); ?>
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
      <h2><span class="atleastonerrormessage">Yo must choose at least one category</span>Choose categories

      </h2>
      <div class="notification" style="margin-bottom: 10px">
        <input name="id" type="hidden" value="<?php print $form_values['id']; ?>" />
        <?php //  $categories_ids_to_remove = false;
    		//p($categories_ids_to_remove);
    		//p($categories_ids_to_add);
    		$categories = $this->taxonomy_model->getTaxonomiesForContent($form_values['id'], 'categories');
    		//var_dump($categories);
    	//$last = count($categories);
    	  $actve_ids = false;
    	  $actve_ids = $categories;
    	  $active_code = ' checked="checked"  ';
    	  $removed_ids_code = ' disabled="disabled"   ';
     $this->content_model->content_helpers_getCaregoriesUlTree($content_parent = 0, $link=  "<input id='category_selector_{id}' name='taxonomy_categories[]' type='checkbox'  {active_code}  {removed_ids_code}  rel='{taxonomy_content_type}'   id='category_selector_{id}' value='{id}' /><label>{taxonomy_value}</label>", $actve_ids , $active_code , $remove_ids = $categories_ids_to_remove, $removed_ids_code = $removed_ids_code, $ul_class_name =  'user-post-select-categories ooyesTrees', $include_first = true, $content_type = false, $li_class_name = false, $add_ids = $categories_ids_to_add);


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
    <div class="notification">
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
          </span>
        </div>
      </div>
      <div class="hr">&nbsp;</div>
      <div class="c" style="padding-bottom: 12px;"></div>
      <div class="item">
        <label>Add picture gallery<span class="Help">?<span>These pictures will be displayed in <br />the bottom of your post like <strong>a gallery</strong></span></span></label>
        <?php /*
        <small>if you add more than 1 picture we will create a gallery :)</small>
        */ ?>
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
          <li <?php if (($i-1)%4==0): ?> style="clear:both" <?php endif; ?>>
            <a id="picture_<?php print $pic["id"]; ?>" class="setgal" href="<?php print $pic["urls"]['original'] ; ?>">
              <img src="<?php print $this->core_model->mediaGetThumbnailForMediaId($pic["id"], $size = 140); ?>" alt="" />
            </a>
            <a class="deleteGalleryImage" href="javascript:deletePicture('<?php print $this->core_model->securityEncryptString ($pic["id"] ) ?>', '<?php print $pic["id"]; ?>' );">delete</a>
          </li>
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
        <a href="#" id="more_images"><?php /*
<img src="<?php print_the_static_files_url() ; ?>admin/icons/silk/picture_add.png" style="padding-right:7px;position:relative;top:2px"  border="0" alt=" " />
*/ ?>Add more pictures</a>


</div>

      <div class="hr">&nbsp;</div>
      <div class="c" style="padding-bottom: 12px;"></div>

      <div class="item comments-enable"> <span>Do you want the visitors to be able to comment on this post?</span>

        <input  name="comments_enabled" type="radio" value="y" <?php if($form_values['comments_enabled'] != 'n') : ?> checked="checked" <?php endif; ?> />
        <b>Yes</b>
        <input  name="comments_enabled" type="radio" value="n" <?php if($form_values['comments_enabled'] == 'n') : ?> checked="checked" <?php endif; ?> />
        <b> No</b> </div>
      <?php $comments = array ();
			$comments ['to_table'] = 'table_content';
			$comments ['to_table_id'] = $form_values ['id'];
			$comments = $this->comments_model->commentsGet ( $comments );

?>
      <?php if(!empty($comments)): ?>
      <div class="c" style="padding-top: 10px;">&nbsp;</div>
      <?php foreach($comments as $item): ?>
      <div class="comment" id="comment-<?php print $item['id'] ?>"> <a href="#" class="img"> <span style="background-image: url('<?php echo gravatar( $item['comment_email'], $rating = 'X', $size = '30', $default =  TEMPLATE_URL .'img/gravatar.jpg' ); ?>)'"></span> </a>
        <h3 class="post-title"><a href="<?php print prep_url($item['comment_website']) ?>"><?php print $item['comment_name'] ?></a></h3>
        <span class="date"><?php print date(DATETIME_FORMAT, strtotime($item['created_on'])) ?></span>
        <p><?php print ($item['comment_body']); ?></p>
        <a href="javascript:usersCommentDelete('<?php print $item['id'] ?>')">Delete this comment</a> </div>
      <?php endforeach ; ?>
      <?php endif; ?>
      <div class="c">&nbsp;</div>
      <?php if(!empty($more)): ?>
      <?php $i = 1;
	  foreach($more as $k => $v): ?>
      <?php if(stristr($k, 'content_title_') == true) : ?>
      <label>Chapter <?php print $i ?> Title * </label>
      <span class="linput">
      <input style="width:643px" type="text" value="<?php print ( $more['content_title_'.$i]);  ?>" name="custom_field_content_title_<?php print $i ?>" class="required type-text">
      </span>
      <label>Chapter <?php print $i ?> Content * </label>
      <textarea name="custom_field_content_body_<?php print $i ?>" class="required richtext"  cols="" rows=""><?php print html_entity_decode( $more['content_body_'.$i]);  ?></textarea>
      <?php $i++; endif; ?>
      <?php endforeach; ?>
      <?php endif; ?>
      <div id="new-chapters"></div>

      <div class="item product-options"> <br />
        <br />
        <label>External buy link <span class="Help">?<span>Simply enter a direct link to an external webpage <br />where one can instantly purchase your product. </span></span></label>
        <span class="linput">
        <input style="width:643px" type="text" value="<?php print ( $more['product_buy_link']);  ?>" name="custom_field_product_buy_link" >
        </span> <br />
        <br />
        <label>Product price in USD <span class="Help">?<span>Fill in the price of your product in USD</span></span></label>
        <span class="linput">
        <input style="width:100px" type="text" value="<?php print ( $more['product_price']);  ?>" name="custom_field_product_price">
        </span> <br />
        <br />
      </div>
      <div class="c">&nbsp;</div>
      <a href="#" class="btn left addChapter" onclick="addChapter();"><b>Add chapter</b></a>
      <a href="#" class="btn submit save_content right"><strong>Save Content</strong></a>
      <script type="text/javascript">

      function deleteChapter(elem){
        var index = parseFloat($(elem).attr("index"));
        var confirmDeleteChapter = confirm("Are you sure you want to delete Chapter " + index + " ?");
        if(confirmDeleteChapter){
            $(document.getElementById('chapter-' + index)).slideUp('normal', function(){



                var nexts =  $(".newChapterHolder:gt(" + (index-1) +")");

                nexts.each(function(i){
                   $(this).find('.item_label').html("Chapter " + (index+i) + " Content: *");
                   $(this).find('.chapter_label_title').html("Chapter " + (index+i) + " Title: *");
                   $(this).find('.newChapterHolder').attr("id", "chapter-" + (index+i));
                   $(this).attr("id", "chapter-" + (index+i));

                   $(this).find('.deleteChapter').attr("index", (index+i));
                   $(this).find('textarea').attr("name", "custom_field_content_body_"+(index+i));

                });

                  $(document.getElementById('chapter-' + index)).destroy();

            });



        }

      }

    function addChapter(){

        var newChapter = document.createElement('div');
        newChapter.className = 'newChapterHolder';

        var hr = document.createElement('div');
        hr.className='hr';
        hr.style.paddingTop='25px';
        newChapter.appendChild(hr);


        var index = $('textarea.richtext').length;
        newChapter.id = 'chapter-' + index;
        var item_title = document.createElement('div');
        item_title.className='item';
        item_title.style.paddingTop='20px';
        item_title.innerHTML='<a href="javascript:void(0)" class="deleteChapter" index="' + index +'" onclick="deleteChapter(this)">Delete chapter</a><label class="chapter_label_title">Chapter ' + index + ' Title * </label><span class="linput"><input style="width:643px" type="text" value="" name="custom_field_content_title_' + index + '" class="required type-text"></span>';

        $(newChapter).append(item_title);

        var content_title = document.createElement('label');
        content_title.innerHTML = 'Chapter ' + index + ' Content: *';
        content_title.className = 'item_label';
        content_title.style.paddingBottom = '12px';

        $(newChapter).append(content_title);



        var textarea = document.createElement('textarea');
        textarea.name = 'custom_field_content_body_'+ index;
        textarea.className='richtext';
        $(newChapter).append(textarea);
        $("#new-chapters").append(newChapter);

        $(textarea).tinymce({
			script_url : '<?php print_the_static_files_url() ; ?>js/tiny_mce/tiny_mce.js',

			theme : "advanced",
			plugins : mcePlugins,

			theme_advanced_buttons1 : mceButtons.buttons1,
			theme_advanced_buttons2 : mceButtons.buttons2,
			theme_advanced_buttons3 : mceButtons.buttons3,
			theme_advanced_toolbar_location : "top",
			theme_advanced_toolbar_align : "left",
			theme_advanced_statusbar_location : "bottom",
			theme_advanced_resizing : true,
			relative_urls : false,
			convert_urls : false,
			remove_script_host : false,
			document_base_url : "<?php print site_url(); ?>",
			valid_elements : validElements , //in ooyes.api.js
			remove_linebreaks : false,
			height : "200",
			width : "652",
			file_browser_callback : "ajaxfilemanager",
            theme_advanced_resizing_use_cookie : false,
            theme_advanced_resizing : false,



			template_replace_values : {
				username : "Some User",
				staffid : "991234"
			}
		});


        msRoundedField();



    }

  </script>
      <div class="c">&nbsp;</div>
    </div>
  </form>
  <?php endif; ?>
</div>
<?php dbg(__FILE__, 1); ?>
