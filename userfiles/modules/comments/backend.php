<script type="text/javascript">
    var murl = "<? print $config['url_to_module'] ?>";

    mw.require(murl+'style.css');
    mw.require('color.js');
</script>
<script type="text/javascript">


mw.on.hashParam("search", function(){
    if(this  !== ''){
    	$('#mw_admin_posts_with_comments').attr('data-search-keyword',this);
    } else {
    	$('#mw_admin_posts_with_comments').removeAttr('data-search-keyword');
    }

    mw.reload_module('#mw_admin_posts_with_comments', function(){
          mw.$(".mw-ui-searchfield, input[type='search']").removeClass('loading');
    });

});


mw.on.hashParam("comments_for_content", function(){  /*
    if(this  !== '' && this  != '0'){
		$('#mw_comments_admin_dashboard').hide();
		$('#mw_admin_posts_with_comments_edit').show();
    	$('#mw_admin_posts_with_comments_edit').attr('data-content-id',this);
		 mw.load_module('comments/manage', '#mw_admin_posts_with_comments_edit');
    } else {
    	$('#mw_admin_posts_with_comments_edit').removeAttr('data-content-id');
		$('#mw_comments_admin_dashboard').show();
		$('#mw_admin_posts_with_comments_edit').hide();

    }

     */




});

$(document).ready(function(){


mw.tools.tabGroup({
   nav:".comments-group",
   tabs:".comments-tab",
   activeNav:"mw-ui-btn-blue"
});



});







</script>



<style type="text/css">

#comments-nav{
  clear: both;
  padding: 10px 0;
}

#comments-nav a{
  width:135px;
  margin: 5px auto;
  display: block;
}

#module-settings .mw-ui-label-inline{
  width: 180px;
  text-align: right;
  padding-right: 20px;
  top: 2px;
}

.comments-settings-right{
  float: left;
  width: 450px;
}

.comments-settings-right .mw-ui-field-holder:first-child{
  padding-top: 0;
}

#other-settings{
  position: relative;
  overflow: hidden;
}


.avatartype{
  display: inline-block;
  width:32px;
  height: 32px;
  position: absolute;
  top: -10px;
  left: 0;
}

.avatars-holder .mw-ui-check input + span + span{
  position: relative;
  padding-left: 40px;
}

.avatartype-mysteryman{
    background: url(<?php print INCLUDES_URL; ?>img/avatars.jpg) no-repeat;
}

.avatartype-randomcolor{
  background: #9F41AA;
  transition: background-color 0.2s;
  -moz-transition: background-color 0.2s;
  -webkit-transition: background-color 0.2s;
  -o-transition: background-color 0.2;
}

.avatartype-mwuser{
    background: url(<?php print INCLUDES_URL; ?>img/avatars.jpg) no-repeat 0 -70px;
}
.avatartype-upload{
    width:30px;
  height: 30px;
  border: 1px solid  #CACACA;
  background-repeat: no-repeat;
  background-size: cover;
  background-image: url(<?php print get_option('avatartype_custom', 'comments'); ?>);
}

.avatars-holder{
  padding-top: 3px;
}





</style>



<div id="comments_module">

<div id="mw_edit_page_left" style="width: 192px;">


<div id="comments-nav">

  <a class="mw-ui-btn comments-group mw-ui-btn-blue" href="javascript:;">My Comments</a>
  <a class="mw-ui-btn comments-group" href="javascript:;" >Settings</a>
</div>


</div>

<div class="mw_edit_page_right" style="padding: 20px; width: 730px;">




<div class="comments-tabs mw_simple_tabs mw_tabs_layout_stylish active">

    <div class="comments-tab" id="the_comments">
      <div id="comments-admin-side">


      <h2>My Comments</h2>
      <small>Read, moderate & public commets</small>
          <input
              autocomplete="off"
              style="width: 120px;margin: 20px;"
              type="search"
              placeholder="<?php _e("Search for post"); ?>"
              onkeyup="mw.form.dstatic(event);mw.on.stopWriting(this, function(){mw.url.windowHashParam('search', this.value)});" />

          <module type="comments/search_content" id="mw_admin_posts_with_comments"  />



     </div>



     <?php  /*

          <div class="<? print $config['module_class'] ?> mw_comments_admin_dashboard" id="mw_comments_admin_dashboard">
            <div class="new-comments"><module type="comments/manage" is_moderated="n" /></div>
            <div class="old-comments"><module type="comments/manage"  is_moderated="y" /></div>
          </div>
          <div class="<? print $config['module_class'] ?> mw_comments_admin_for_post" id="mw_admin_posts_with_comments_edit"> </div>

          */ ?>

    </div>
    <div class="comments-tab" style="display: none">


<script>mw.require("files.js");</script>
<script  type="text/javascript">

     var uploader = mw.files.uploader({
         filetypes:"images"
     });


    $(document).ready(function(){

      mw.options.form('.<? print $config['module_class'] ?>', function(){
        mw.notification.success("<?php _e("All changes are saved"); ?>.");
      });

     mw.$("[name='enable_comments']").commuter(function(){
        mw.$("#other-settings").removeClass("deactivated");
     }, function(){
        mw.$("#other-settings").addClass("deactivated");
     });


     mw.$("[name='email_on_new_comment']").commuter(function(){
        mw.$("#receive_email_holder").removeClass("deactivated");
     }, function(){
        mw.$("#receive_email_holder").addClass("deactivated");
     });

     mw.$("[name='avatar_enabled']").commuter(function(){
        mw.$(".avatars-holder").removeClass("deactivated");
     }, function(){
        mw.$(".avatars-holder").addClass("deactivated");
     });






      mw.$(".avatartype-randomcolor").parent().parent().hover(function(){
        mw.$(".avatartype-randomcolor").css("backgroundColor", mw.color.random());
      });



     mw.$("#avatar_uploader").append(uploader);

     $(uploader).bind("FileUploaded" ,function(e, a){

          mw.$(".avatartype-upload").css("backgroundImage", "url("+a.src+")");
          mw.$("[name='avatartype_custom']").val(a.src).trigger("change");
     });



    });
</script>
    <div id="module-settings">
   <div class="<? print $config['module_class'] ?>">

   <div class="vSpace"></div>
   <h2>
    Settings
    <label class="mw-ui-label"><small>Define comments settings</small></label>
    </h2>
    <div class="vSpace"></div>


   <label class="mw-ui-label-inline">Default comments settings</label>

   <label class="mw-ui-check">

    <?php  $are_enabled = get_option('enable_comments', 'comments')=='y';  ?>

     <input
          type="checkbox"
          name="enable_comments"
          value="y"
          class="mw_option_field"
          option-group="comments"
          <? if($are_enabled): ?>   checked="checked"  <? endif; ?>
        />   <?php



        ?>

    <span></span>
    <span>Allow people to post comments</span>
   </label>

   <div class="vSpace"></div>


   <div id="other-settings" class="<?php if($are_enabled==false) {print " deactivated";}; ?>">

    <label class="mw-ui-label-inline">Other comments settigs</label>

   <div class="comments-settings-right">

    <div class="mw-ui-field-holder">
        <label class="mw-ui-check">
            <input
                type="checkbox"
                name="user_must_be_logged"
                value="y"
                class="mw_option_field"
                option-group="comments"
                <? if(get_option('user_must_be_logged', 'comments')=='y'): ?>   checked="checked"  <? endif; ?>
              />
            <span></span><span>Users must be registered and logged in to comment</span>
        </label>
    </div>
    <div class="mw-ui-field-holder">
      <label class="mw-ui-check">
          <input
              type="checkbox"
              name="require_moderation"
              value="y"
              class="mw_option_field"
              option-group="comments"
              <? if(get_option('require_moderation', 'comments')=='y'): ?>   checked="checked"  <? endif; ?>
            />
          <span></span><span>New comments require moderation</span>
      </label>
    </div>
    <div class="mw-ui-field-holder">
      <label class="mw-ui-check">
          <input
              type="checkbox"
              name="paging"
              value="y"
              class="mw_option_field"
              option-group="comments"
              <? if(get_option('paging', 'comments')=='y'): ?>   checked="checked"  <? endif; ?>
            />
          <span></span><span>Set paging in the comments</span>
      </label>

      <div option-group="comments" name="comments_per_page" class="mw-ui-select right" style="min-width: 70px;">
        <select name="paging" option-group="comments" class="mw_option_field">

        <?php




        ?>


         <?php
        $per_page = get_option('paging', 'comments');
          $found = false;
          for($i=5; $i<40; $i+=5){
              if($i == $per_page){
                 $found = true;
                  print '<option selected="selected" value="'. $i .'">'. $i . '</option>';
              } else{
                  print '<option value="'. $i .'">'. $i . '</option>';
              }
          }
          if( $found == false){
                print '<option selected="selected" value="'. $per_page .'">'. $per_page . '</option>';
          }
    ?>



        </select>
      </div>

    </div>

  </div>




 <div class="vSpace"></div>
 <div class="vSpace"></div>

<label class="mw-ui-label-inline">Email me on</label>
<div class="comments-settings-right">
    <div class="mw-ui-field-holder">
      <label class="mw-ui-check">
          <?php $email_enabled = get_option('email_on_new_comment', 'comments')=='y';  ?>
          <input
              type="checkbox"
              name="email_on_new_comment"
              value="y"
              class="mw_option_field"
              option-group="comments"
              <? if($email_enabled): ?>   checked="checked"  <? endif; ?>
            />
          <span></span><span>New comment</span>
      </label>
      <div class="right <?php if($email_enabled==false){ print " deactivated"; }; ?>" id="receive_email_holder">
          <input type="text" name="email_on_new_comment_value" option-group="comments" placeholder="Type email here" value="<?php print get_option('email_on_new_comment_value', 'comments'); ?>" />
      </div>
    </div>
</div>


 <div class="vSpace"></div>
 <div class="vSpace"></div>






<label class="mw-ui-label-inline">Avatar Display</label>
<div class="comments-settings-right">
    <div class="mw-ui-field-holder">
      <label class="mw-ui-check">
          <?php $avatar_enabled = get_option('avatar_enabled', 'comments')=='y';  ?>
          <input
              type="checkbox"
              name="avatar_enabled"
              value="y"
              class="mw_option_field"
              option-group="comments"
              <? if($avatar_enabled): ?>   checked="checked"  <? endif; ?>
            />
          <span></span><span>Show Avatars</span>
      </label>
    </div>
</div>

    <div class="vSpace"></div>
    <div class="vSpace"></div>

<label class="mw-ui-label-inline">Avatar Style</label>

<div class="comments-settings-right avatars-holder <? if(!$avatar_enabled){ ?>deactivated<?php } ?>">
 <div class="mw-ui-field-holder">
 <label class="mw-ui-check">
 <input
        type="radio"
        name="avatar_style"
        value="1"
        class="mw_option_field"
        option-group="comments"
        <? if(get_option('avatar_style', 'comments')=='1'): ?>   checked="checked"  <? endif; ?>
    />
 <span></span><span><i class="avatartype avatartype-mysteryman"></i>Super User</span></label>

 </div>
 <div class="mw-ui-field-holder">
 <label class="mw-ui-check">
 <input
        type="radio"
        name="avatar_style"
        value="2"
        class="mw_option_field"
        option-group="comments"
        <? if(get_option('avatar_style', 'comments')=='2'): ?>   checked="checked"  <? endif; ?>
    />
 <span></span><span><i class="avatartype avatartype-randomcolor"></i>Random Color</span></label>
 </div>
 <div class="mw-ui-field-holder">
 <label class="mw-ui-check">
 <input
        type="radio"
        name="avatar_style"
        value="3"
        class="mw_option_field"
        option-group="comments"
        <? if(get_option('avatar_style', 'comments')=='3'): ?>   checked="checked"  <? endif; ?>
    />
 <span></span><span><i class="avatartype avatartype-mwuser"></i>MW User Picture</span></label>
 </div>
 <div class="mw-ui-field-holder">
 <label class="mw-ui-check relative" id="avatar_uploader">
 <input
        type="radio"
        name="avatar_style"
        value="4"
        class="mw_option_field"
        option-group="comments"
        <? if(get_option('avatar_style', 'comments')=='4'): ?>   checked="checked"  <? endif; ?>
    />
 <span></span><span>
 <input type="hidden" name="avatartype_custom" class="mw_option_field"  option-group="comments" value="<?php print get_option('avatartype_custom', 'comments'); ?>" />


 <i class="avatartype avatartype-upload"></i>

 Upload Picture</span></label>


</div>






</div>



      </div>
      </div>
      </div>




    </div>
</div>





</div>
</div>
