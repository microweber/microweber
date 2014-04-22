<?php only_admin_access(); ?>

      <style type="text/css">

#module-settings .mw-ui-label-inline {
	width: 180px;
	text-align: right;
	padding-right: 20px;
	top: 2px;
}
.comments-settings-right {
	float: left;
	width: 450px;
}
.comments-settings-right .mw-ui-field-holder:first-child {
	padding-top: 0;
}
#other-settings {
	position: relative;
	overflow: hidden;
}
.avatartype {
	display: inline-block;
	width:32px;
	height: 32px;
	position: absolute;
	top: -10px;
	left: 0;
}
.avatars-holder .mw-ui-check input + span + span {
	position: relative;
	padding-left: 40px;
}
.avatartype-mysteryman {
 background: url(<?php print MW_INCLUDES_URL; ?>img/avatars.jpg) no-repeat;
}
.avatartype-randomcolor {
	background: #9F41AA;
	transition: background-color 0.2s;
	-moz-transition: background-color 0.2s;
	-webkit-transition: background-color 0.2s;
	-o-transition: background-color 0.2;
}
.avatartype-mwuser {
 background: url(<?php print MW_INCLUDES_URL;?>img/avatars.jpg) no-repeat 0 -70px;
}
.avatartype-upload {
	width:30px;
	height: 30px;
	border: 1px solid #CACACA;
	background-repeat: no-repeat;
	background-size: cover;
    background-image: url(<?php print get_option('avatartype_custom', 'comments');
?>);
}
.avatars-holder {
	padding-top: 3px;
}

</style>

      
      
        <script>mw.require("files.js");</script>
        <script type="text/javascript">

  mw.require('options.js', true);
    mw.require('<?php print $config['url_to_module'] ?>style.css');
    mw.require('color.js', true);
</script>

 
        <script  type="text/javascript">

     var uploader = mw.files.uploader({
         filetypes:"images"
     });


    $(document).ready(function(){

      mw.options.form('.<?php print $config['module_class'] ?>', function(){
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



  /*    mw.tools.tabGroup({
         nav:".comments-group",
         tabs:".comments-tab"
      });*/


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
          <div class="<?php print $config['module_class'] ?>">
            <div class="comments-admin-header">
              <div class="comments-admin-header-info">
                <h2><?php _e("Settings"); ?></h2>
                <small><?php _e("Define comments settings"); ?></small> </div>
            </div>
            <label class="mw-ui-label-inline"><?php _e("Default comments settings"); ?></label>
            <label class="mw-ui-check">
              <?php  $are_enabled = get_option('enable_comments', 'comments')=='y';  ?>
              <input
                type="checkbox"
                name="enable_comments"
				parent-reload="true"
                value="y"
                class="mw_option_field"
                option-group="comments"
                <?php if($are_enabled): ?>   checked="checked"  <?php endif; ?>
              />
              <?php



        ?>
              <span></span> <span><?php _e("Allow people to post comments"); ?></span> </label>
            <div class="vSpace"></div>
            <div id="other-settings" class="<?php if($are_enabled==false) {print " deactivated";}; ?>">
              <label class="mw-ui-label-inline"><?php _e("Other comments settings"); ?></label>
              <div class="comments-settings-right">
                <div class="mw-ui-field-holder">
                  <label class="mw-ui-check">
                    <input
                type="checkbox"
                name="user_must_be_logged"
                value="y"
				parent-reload="true"
                class="mw_option_field"
                option-group="comments"
                <?php if(get_option('user_must_be_logged', 'comments')=='y'): ?>   checked="checked"  <?php endif; ?>
              />
                    <span></span><span><?php _e("Users must be registered and logged in to comment"); ?></span> </label>
                </div>
                <div class="mw-ui-field-holder">
                  <label class="mw-ui-check">
                    <input
              type="checkbox"
			  parent-reload="true"
              name="require_moderation"
               data-reload="comments/comments_for_post"                       
              value="y"
              class="mw_option_field"
              option-group="comments"
              <?php if(get_option('require_moderation', 'comments')=='y'): ?>   checked="checked"  <?php endif; ?>
            />
                    <span></span><span><?php _e("New comments require moderation"); ?></span> </label>
                </div>
                <div class="mw-ui-field-holder">
                  <label class="mw-ui-check">
                    <input
              type="checkbox"
              name="set_paging"
			  parent-reload="true"
              value="y"
              class="mw_option_field"
              option-group="comments"
              <?php if(get_option('set_paging', 'comments')=='y'): ?>   checked="checked"  <?php endif; ?>
            />
                    <span></span><span><?php _e("Set paging in the comments"); ?></span> </label>
                  <div option-group="comments" name="comments_per_page" parent-reload="true" class="right" style="min-width: 70px;">
                    <select name="paging" parent-reload="true" option-group="comments" parent-reload="true" class="mw-ui-field mw_option_field">
                    
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
              <label class="mw-ui-label-inline"><?php _e("Email me on"); ?></label>
              <div class="comments-settings-right">
                <div class="mw-ui-field-holder">
                  <label class="mw-ui-check">
                    <?php $email_enabled = get_option('email_on_new_comment', 'comments')=='y';  ?>
                    <input
              type="checkbox"
              name="email_on_new_comment"
              value="y"
			  parent-reload="true"
              class="mw_option_field"
              option-group="comments"
              <?php if($email_enabled): ?>   checked="checked"  <?php endif; ?>
            />
                    <span></span><span><?php _e("New comment"); ?></span> </label>
                  <div class="right <?php if($email_enabled==false){ print " deactivated"; }; ?>" id="receive_email_holder">
                    <input type="text" name="email_on_new_comment_value" option-group="comments" placeholder="<?php _e("Type email here"); ?>" parent-reload="true" class="mw-ui-field mw_option_field" value="<?php print get_option('email_on_new_comment_value', 'comments'); ?>" />
                  </div>
                </div>
              </div>
              <div class="vSpace"></div>
              <div class="vSpace"></div>
              <label class="mw-ui-label-inline"><?php _e("Avatar Display"); ?></label>
              <div class="comments-settings-right">
                <div class="mw-ui-field-holder">
                  <label class="mw-ui-check">
                    <?php $avatar_enabled = get_option('avatar_enabled', 'comments')=='y';  ?>
                    <input
              type="checkbox"
              name="avatar_enabled"
              value="y"
			  parent-reload="true"
              class="mw_option_field"
              option-group="comments"
              <?php if($avatar_enabled): ?>   checked="checked"  <?php endif; ?>
            />
                    <span></span><span><?php _e("Show Avatars"); ?></span> </label>
                </div>
              </div>
              <div class="vSpace"></div>
              <div class="vSpace"></div>
              <label class="mw-ui-label-inline"><?php _e("Default avatar style"); ?></label>
              <div class="comments-settings-right avatars-holder <?php if(!$avatar_enabled){ ?>deactivated<?php } ?>">
                <div class="mw-ui-field-holder">
                  <label class="mw-ui-check">
                    <input
        type="radio"
        name="avatar_style"
        value="1"
        class="mw_option_field"
		parent-reload="true"
        option-group="comments"
        <?php if(get_option('avatar_style', 'comments')=='1'): ?>   checked="checked"  <?php endif; ?>
    />
                    <span></span><span><i class="avatartype avatartype-mysteryman"></i><?php _e("Super User"); ?></span></label>
                </div>
                <div class="mw-ui-field-holder">
                  <label class="mw-ui-check">
                    <input
        type="radio"
        name="avatar_style"
        value="2"
        class="mw_option_field"
		parent-reload="true"
        option-group="comments"
        <?php if(get_option('avatar_style', 'comments')=='2'): ?>   checked="checked"  <?php endif; ?>
    />
                    <span></span><span><i class="avatartype avatartype-randomcolor"></i><?php _e("Random Color"); ?></span></label>
                </div>
                <div class="mw-ui-field-holder">
                  <label class="mw-ui-check">
                    <input
        type="radio"
        name="avatar_style"
        value="3"
        class="mw_option_field"
		parent-reload="true"
        option-group="comments"
        <?php if(get_option('avatar_style', 'comments')=='3'): ?>   checked="checked"  <?php endif; ?>
    />
                    <span></span><span><i class="avatartype avatartype-mwuser"></i><?php _e("MW User Picture"); ?></span></label>
                </div>
                <div class="mw-ui-field-holder">
                  <label class="mw-ui-check relative" id="avatar_uploader">
                    <input
        type="radio"
        name="avatar_style"
        value="4"
        class="mw_option_field"
		parent-reload="true"
        option-group="comments"
        <?php if(get_option('avatar_style', 'comments')=='4'): ?>   checked="checked"  <?php endif; ?>
    />
                    <span></span><span>
                    <input type="hidden" parent-reload="true" name="avatartype_custom" class="mw_option_field"  option-group="comments" value="<?php print get_option('avatartype_custom', 'comments'); ?>" />
                    <i class="avatartype avatartype-upload"></i> <?php _e("Upload Picture"); ?></span></label>
                </div>
              </div>
            </div>
          </div>
        </div>
 