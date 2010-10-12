<!--SUBNAV-->
 <?php include ACTIVE_TEMPLATE_DIR."users/post.js.php" ?>

<div id="RU-subnav">
  <div class="pad3"></div>
  <div id="RU-nav">
    <ul class="nav">
      <?php //include ACTIVE_TEMPLATE_DIR."users/posts_header_nav.php" ?>
      <li><a href="#choose-layout" title="Choose Layout" id="t1"><span>Choose Layout</span></a></li>
      <li><a href="#choose-theme" title="Choose Style" id="t2"><span>Choose Style</span></a></li>
      <li><a href="#edit-template" title="Edit template" id="t3"><span>Edit page</span></a></li>
      <li><a href="#publish" title="Publish" id="t4"><span>Publish</span></a></li>
    </ul>
  </div>
  
  <!--HELP-->
  <div id="RU-help"> <a href="#" title="Help" class="help"></a>
    <div>
      <div>
        <div id="tab1"></div>
      </div>
      <div id="preloader"></div>
    </div>
    
    <!--END HELP--> 
  </div>
  <div class="clr"></div>
  <!--END SUBNAV--> 
</div>
<div id="RU-content">
  <form class="" action="<? print site_url($this->uri->uri_string()); ?>"   method="post" enctype="multipart/form-data"   id="postform">
    <div class="pad2"></div>
    
    <!-- panes -->
    
    <div class="panes" id="form-manager">
      <div id="form-manager-holder" style="left: 0px;">
        <div class="tab" id="choose-layout">
          <div id="layouts-list" > 
            <!-- comes from axaj--> Loading layouts... </div>
          <div class="scroller">
            <div class="scroller-content" > </div>
            <span class="scroll-left"></span> <span class="scroll-right"></span> </div>
          <a class="next tabs-next" href="#">Continue to the next step</a> </div>
        <div class="tab" id="choose-theme">
          <h2>Choose style</h2>
          <div id="layout-styles-list" class="layouts-list-2"> <!-- comes from axaj--> Loading styles...</div>
        </div>
        <div class="tab" id="edit-template">
          <? if($the_content_is_saved == true): ?>
          <h2>saved!</h2>
          <br />
          <a href="<? print $this->content_model->getContentURLById($the_saved_id); ?>" target="_blank"><strong>See it</strong></a><br />
          <a href="<? print site_url('users/user_action:post/id:'.$the_saved_id) ; ?>"><strong>Edit it</strong></a>
          <? // var_dump($the_saved_id); ?>
          <? else: ?>
        
          <? if(!empty($form_errors)): ?>
          <? p($form_errors); ?>
          <? endif; ?>
          <input name="content_layout_name" type="hidden" id="content_layout_name" value="<? print $form_values['content_layout_name'] ?>" />
          <input name="content_layout_style" type="hidden" id="content_layout_style" value="<? print $form_values['content_layout_style'] ?>" />
           <textarea name="content_body" id="content_body" class="required richtext"  cols="" rows=""><? print $form_values['content_body']; ?></textarea>
          
          <!--        <label class="lbl"><strong>Choose type: *</strong></label>
        <span class="linput">
        <select name="content_subtype" style="width:230px;" onchange="$('#post-choose-cats').show()">
          <option <? if(($form_values['content_subtype'] == '' ) or ($form_values['content_subtype'] == 'none' )): ?> selected="selected" <? endif; ?>  value="none">Select:</option>
          <option <? if($form_values['content_subtype'] == 'article' ): ?> selected="selected" <? endif; ?>  value="services">Article</option>
          <option <? if($form_values['content_subtype'] == 'trainings' ): ?> selected="selected" <? endif; ?>  value="trainings">Trainings</option>
          <option <? if($form_values['content_subtype'] == 'products' ): ?> selected="selected" <? endif; ?>  value="products">Product</option>
          <option <? if($form_values['content_subtype'] == 'services' ): ?> selected="selected" <? endif; ?>  value="services">Service</option>
        </select>
        </span>-->
          
          <?
	$comments = array ();
			$comments ['to_table'] = 'table_content';
			$comments ['to_table_id'] = $form_values ['id'];
			$comments = $this->comments_model->commentsGet ( $comments );

?>
          <? if(!empty($comments)): ?>
          <? foreach($comments as $item): ?>
          <div class="comment" id="comment-<? print $item['id'] ?>"> <a href="#" class="img"> <span style="background-image: url('<?php echo gravatar( $item['comment_email'], $rating = 'X', $size = '30', $default =  TEMPLATE_URL .'img/gravatar.jpg' ); ?>)'"></span> </a>
            <h3 class="post-title"><a href="<? print prep_url($item['comment_website']) ?>"><? print $item['comment_name'] ?></a></h3>
            <span class="date"><? print $item['created_on'] ?></span>
            <p><? print ($item['comment_body']); ?></p>
            <a href="javascript:usersCommentDelete('<? print $item['id'] ?>')">Delete this comment</a> </div>
          <?  endforeach ; ?>
          <? endif; ?>
          <!--<div>
      <label>Thumb</label>
      <? $pictures =  ($form_values['media']['pictures']); ?>
      <? if(!empty($pictures )) : ?>
      <ul>
        <? $i=1 ; foreach($pictures as $pic): ?>
        <li <? if($i == 1): ?> style="display:block" <? endif; ?>><a <? if($i == 1): ?> class="active" <? endif; ?> href="<? print $pic["urls"]['original'] ; ?>"><img src="<? print $pic["urls"]['original'] ; ?>" alt="" /></a></li>
        <? $i++; endforeach; ?>
      </ul>
      <? endif;  ?>
      <script type="text/javascript">
	$(document).ready(function(){
		$("#more_images").click(function(){
			var up_length = $(".input_Up").length;
            if(up_length<=2){
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
      <a style="font:bold 12px Arial;color#456;text-decoration:none" href="javascript:;" id="more_images"><img src="<?php print_the_static_files_url() ; ?>admin/icons/silk/picture_add.png" style="padding-right:7px;position:relative;top:2px"  border="0" alt=" " />?????? ??? ??????</a> </div>-->
          
          <? endif; ?>
        </div>
        <div class="tab" id="publish">
          <div class="box-holder">
            <div class="box-top">&nbsp;</div>
            <div class="box-inside">
              <h2 class="box-title">Please fill the fields correctly</h2>
              <div class="hr">&nbsp;</div>
              <label class="label-160">Choose category</label>
              <input name="id" type="hidden" value="<? print $form_values['id']; ?>" />
              <?
    		//  $categories_ids_to_remove = false;
    		//p($categories_ids_to_remove);
    		//p($categories_ids_to_add);
    		$categories = $this->taxonomy_model->getTaxonomiesForContent($form_values['id'], 'categories');
    		//var_dump($categories);
    	//$last = count($categories);
    	  $actve_ids = false;
    	  $actve_ids = $categories;
    	  $active_code = ' checked="checked"  ';
    	  $removed_ids_code = ' disabled="disabled"   ';
     $this->content_model->content_helpers_getCaregoriesUlTree($content_parent = 0, $link=  "<input id='category_selector_{id}' name='taxonomy_categories[]' type='radio'  {active_code}  {removed_ids_code}   id='category_selector_{id}' value='{id}' /><label>{taxonomy_value}</label>", $actve_ids , $active_code , $remove_ids = $categories_ids_to_remove, $removed_ids_code = $removed_ids_code, $ul_class_name =  'user-post-select-categories ooyesTrees', $include_first = false, $content_type = false, $li_class_name = false, $add_ids = $categories_ids_to_add);


     ?>
              <div class="hr">&nbsp;</div>
              <label class="label-160">Title *</label>
              <span class="field">
              <input style="width: 550px;" class="required type-text" name="content_title" type="text" value="<? print $form_values['content_title']; ?>" />
              </span>
              <div class="hr">&nbsp;</div>
              <label class="label-160">Description: *</label>
              <span class="field">
              <textarea style="width: 550px;height: 50px;" class="required"  name="content_description" cols="" rows=""><? print $form_values['content_description']; ?></textarea>
              </span>
             <!-- <div class="hr">&nbsp;</div>
              <label class="label-160">Comments enabled?</label>
              <input name="comments_enabled" type="radio" value="y" <? if($form_values['comments_enabled'] != 'n') : ?> checked="checked" <? endif; ?> />
              Yes
              &nbsp;&nbsp;&nbsp;
              <input  name="comments_enabled" type="radio" value="n" <? if($form_values['comments_enabled'] == 'n') : ?> checked="checked" <? endif; ?> />
              No <br />
              <br />-->
              <div class="hr">&nbsp;</div>
              <label class="label-160">Save</label>
              <input name="save" type="submit" value="save" />
   
             
            </div>
            <div class="box-bottom">&nbsp;</div>
          </div>
        </div>
      </div>
    </div>
    <!-- /RU-content -->
    <div class="pad2" style="height: 20px;"></div>
  </form>
  <!-- formata --> 
  <!--END CONTENT--> 
</div>
<script type="text/javascript">
    function Sizes(){
      //alert(windowLoaded)
        if(windowLoaded == true){
          //alert($("#content_body_ifr").attr('scrollHeight'))
            var window_width = $(window).width()   
            $("#content_body_tbl").width(window_width);
            $(".tab").width(window_width-40);
            $("#form-manager-holder").width($("#form-manager-holder .tab").length*window_width+100);
            var href = $(".nav a.current").attr("href");
            var scroller_left = parseFloat($('#form-manager-holder').css('left'));
            var href_item_offset = $(href + '-tab').offset().left;
            var tab_left = href_item_offset - $('#form-manager').offset().left;
            $("#form-manager-holder").not(":animated").css({"left":scroller_left-tab_left});
            if(window.location.hash=='#edit-template'){
              $('body, html').css('overflow', 'hidden');
            }
            else{
               $('body, html').removeAttr('style')
            }
            $("#content_body_ifr").height($(window).height() - $('.mceToolbar').height() - $('#RU-header').height() - $('#RU-subnav').height() - 47)
            $("#content_body_tbl").height($(window).height() - $('#RU-header').height() - $('#RU-subnav').height() - 100)
        }
    }
    setInterval("Sizes()", 100);

    $(window).load(function(){
      if(window.location.hash==''){
        window.location.hash='#choose-layout';
      }
    })

</script>















