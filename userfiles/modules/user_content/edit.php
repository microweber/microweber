<?php $posts_parent_page = get_option('data-parent', $params['id']); ?>
<?php
if($posts_parent_page == false){
$posts_parent_page = CONTENT_ID;	
}

 

if(isset($params['content_id'])){
	$edit_post_id = $params['content_id'];
} else {
	$edit_post_id = 0;
}

  $selected_cat = false;
   $url_cat = url_param('category');
   if( $url_cat != false){  $selected_cat = get_category_by_id($url_cat);  }
   
   

$data  = array();
$data['id'] = 0;	
$data['content_type'] = 'post';	
$data['subtype'] = 'post';	
$data['title'] = false;	
$data['content'] = false;	
$data['url'] = '';	
$data['is_active'] = 1;	
$data['description'] = '';	
if($edit_post_id != 0){
	$post_data = get_content_by_id($edit_post_id);
	if($post_data != false){
		$data = $post_data;	
		$selected_cats = content_categories($edit_post_id);
		if(isset($selected_cats[0])){
			$selected_cat = $selected_cats[0];
		}
		
		
		
		$posts_parent_page = $post_data['parent'];
	}
	
}
 
 
 //d($selected_cat );
 
?>
<script type="text/javascript">
  mw.require('events.js', true);
  mw.require('forms.js', true);
</script>
<script type="text/javascript">

    $(document).ready(function () {
        mw.$('#new-user-content-form').bind("submit",function () {
            if(!mw.$(".mw-publish-forum-post").hasClass("disabled")){
               mw.$(".mw-publish-forum-post").addClass("disabled");
                mw.form.post(mw.$('#new-user-content-form'), '<?php print site_url('api') ?>/save_content_admin', function () {
                    mw.$(".mw-publish-forum-post").removeClass("disabled");
                    mw.response('#new-user-content-resp', this);
                    if(!this.error){
                    var $id  = this;
					
					<?php if(isset($params['callback'])): ?>

                      <?php print $params['callback'] ?>(this);

					   <?php  else: ?>

					    $.get('<?php print site_url('api_html/content_link/') ?>?id='+$id, function(data) {
                         window.top.location.href = data;
                       });
					   <?php  endif; ?>
					   
					   
                    }
                });
            }
            return false;
        });
		mw.$('.mw_dropdown_topic_cat li').bind("click",function(){

             mw.$('.topic_cat_val').html($(this).html());
             mw.$('#topic-seleceted-category').val($(this).attr('value'));
             mw.$("#cptitle").focus();
		});

    });
	function mw_reload_login(){
	    window.location.href = '<?php print mw()->url->current(1); ?>&rand=<?php print uniqid(); ?>';
	}


    cTitleCount = function(){
      var field = mwd.getElementById('cptitle')
      var n = field.value.length;
      var max = parseFloat($(field).attr("maxlength"));
      if((max - n) < 11){
         $(mwd.getElementById('title-max')).show();
      }
      else{
        $(mwd.getElementById('title-max')).hide();
      }
      mwd.getElementById('title-max').innerHTML = 'Characters left: ' + (max - n)
    }

</script>
<?php

 
?>
<?php if(user_id() == false) :  ?>
<module type="users/login"  callback="mw_reload_login" />
<?php else: ?>

<div class="mw-ui-row valign center768 ">
  <div class="mw-ui-col" style="width: 120px;">
    <?php

              $uid = user_id();
              $user = get_user($uid);
            ?>
    <?php if (isset($user['thumbnail']) and $user['thumbnail'] != ''){ ?>
    <span class="cico" style="background-image: url(<?php print thumbnail($user['thumbnail'], 120, 120); ?>);"></span>
    <?php } else { ?>
    <span class="cico"><i class="cicon cicon-human"></i></span>
    <?php } ?>
  </div>
  <div class="mw-ui-col">
    <h1 style="font-size:21px"><?php print user_name(false,'first'); ?>, add new content.</h1>
  </div>
</div>
<div class="vpad"></div>
<form action="#" method="post" id="new-user-content-form">
  <div id="new-user-content-resp"></div>
  <span class="field-max" id="title-max" style="display: none">Characters left: 155</span>
  <input
            
            value="<?php print $data['title'] ?>"
            type="text"
            class="rfield rfield-white"
            tabindex="2"
            required
            name="title"
            id="cptitle"
            placeholder="Post Title"
            maxlength="155"
            onkeyup="cTitleCount()"
            style="width: 100%;position: relative;left: -1px;" />
  <div
                id="discussion-catselector"
                class="mw_dropdown mw_dropdown_type_domain mw_dropdown_type_domain_nolinks"
                data-value="-1" style="font-size:21px;"
                tabindex="1"> <span class="mw_dropdown_val_holder">
    <?php if($url_cat == false): ?>
    <span class="mw_dropdown_val topic_cat_val">Choose a category</span>
    <?php else: ?>
    <span class="mw_dropdown_val topic_cat_val"><?php print $selected_cat['title'] ?></span>
    <?php endif; ?>
    <span class="mw-dropdown-arrow"></span> </span>
    <div class="xmw_dropdown_fields mw_dropdown_topic_cat">
      <div class="ul">
        <?php $cats = category_tree('link={title}&rel_id='.$posts_parent_page.'&rel=content&users_can_create_content=y');  ?>
      </div>
    </div>
  </div>
  <input type="hidden"  name="id"  value="<?php print $data['id'] ?>" />
  <input type="hidden"  name="content_type"  value="<?php print $data['content_type'] ?>" />
  <input type="hidden"  name="subtype"  value="<?php print $data['subtype'] ?>" />
  <input type="hidden"  name="category" id="topic-seleceted-category" <?php if($selected_cat != false): ?> value="<?php print $selected_cat['id'] ?>" <?php endif; ?> />
  <div class="vpad">
    <?php /*<label>Describe your discussion below</label>*/ ?>
  </div>
  <textarea id="topic-editor" name="content" style="width: 100%;height:200px;"></textarea>
  <script>
        $(document).ready(function(){
            if(!mw.sut.isMobile){
                var editor_area = mwd.getElementById('topic-editor');
                var ed_params = {}
                ed_params.empty_content=true;
                var wysiwyg = mw.tools.iframe_editor(editor_area,ed_params );
                wysiwyg.style.width = "100%";
                wysiwyg.style.height = "300px";
            }
        });
        </script>
  <div id="new-user-content-footer">
    <div class="mw-ui-row">
      <div class="mw-ui-col">
        <div class="box box-content"> <img class="mw-captcha-img" src="<?php print site_url('api/captcha') ?>" onclick="mw.tools.refresh_image(this);" />
          <input type="text" placeholder="<?php _e("Enter the text"); ?>" class="invisible-field" name="captcha" style="margin: 0">
        </div>
      </div>
      <div class="mw-ui-row">
        <div class="mw-ui-col">
          <module type="pictures" view="admin"  content_id="<?php print $edit_post_id; ?>" />
        </div>
      </div>
      <div class="mw-ui-col text-right">
        <input type="submit" value="Publish Topic" class="mw-publish-forum-post kbtn kbtn-blue">
      </div>
    </div>
  </div>
</form>
<?php endif; ?>
