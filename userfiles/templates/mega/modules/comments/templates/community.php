<?php

/*

type: layout

name: Mega

description: Mega comments template

*/

  //$template_file = false;



   ?>

   <br>

<div class="comments-template-mw-site">
  <?php
       $cur_user = user_id();
             if($cur_user != false){
              $cur_user_data = get_user($cur_user);
             }
        ?>
  <?php if (isarr($comments)): ?>
  <?php if($form_title != false): ?>
  <h3><?php print $form_title ?></h3>
  <?php elseif($display_comments_from  != false and $display_comments_from   == 'recent'): ?>
  <h3><?php _e("Recent replies"); ?></h3>
  <?php else : ?>

  <?php endif; ?>
  <div class="comments" id="comments-list-<?php print $data['id'] ?>">
    <?php foreach ($comments as $comment) : ?>
    <?php
    $required_moderation = get_option('require_moderation', 'comments')=='y';

    if(!$required_moderation or $comment['is_moderated'] == 'y' or (!empty($_SESSION) and  $comment['session_id'] == session_id())){
  ?>








    <div class="post-item-reply comment" id="comment-<?php print $comment['id'] ?>">
      <div class="post-item">
       <div class="post-item-content">
        <?php

  $avatars_enabled = get_option('avatar_enabled', 'comments')=='y';

  $comment_author =  get_user($comment['created_by']) ;
  if(!empty($comment_author)){
	  // $comment['comment_name'] = user_name($comment_author['id']);
  }

  
  
  
  

  ?>
        <?php if($avatars_enabled){ ?>
        <div class="mw-ui-col comment-image-holder">
        <div class="mw-ui-col-container">
          <?php $avatar_style =  get_option('avatar_style', 'comments'); ?>
          <?php  if (isset($comment_author['thumbnail'])  and isset($comment_author['thumbnail']) != ''){ ?>
          <img src="<?php print ($comment_author['thumbnail']);  ?>" width="60" height="60" class="img-polaroid comment-image" alt="<?php print addslashes($comment['comment_name']) ?>" />
          <?php  }  else  {   ?>
          <?php   if($avatar_style == '4'){ ?>
          <img src="<?php print thumbnail(get_option('avatartype_custom', 'comments'), 60, 60);  ?>" class="img-polaroid comment-image"  width="60" height="60"  alt="<?php print addslashes($comment['comment_name']) ?>" />
          <?php } else if($avatar_style == '1' || $avatar_style == '3'){ ?>
          <img src="<?php print thumbnail($config['url_to_module']. '/img/comment-default-'.$avatar_style.'.jpg', 60, 60);  ?>"  width="60" height="60"  class="img-polaroid comment-image" alt="<?php print addslashes($comment['comment_name']) ?>" />
          <?php } else if($avatar_style == '2'){ ?>
          <span class="img-polaroid  random-color"> <span style="background-color: <?php print random_color(); ?>"> </span> </span>
          <?php } else if(isset( $comment_author['thumbnail'])){ ?>
          <img src="<?php print ($comment_author['thumbnail']);  ?>" width="60" height="60" class="img-polaroid comment-image" alt="<?php print addslashes($comment['comment_name']) ?>" />
          <?php } else {  ?>
          <img src="<?php print thumbnail($config['url_to_module']. '/img/comment-default-1.jpg', 60, 60);   ?>"  width="60" height="60"  class="img-polaroid comment-image" alt="<?php print addslashes($comment['comment_name']) ?>" />
           <?php } ?>
          <?php } ?>
        </div>
        </div>
        <?php } ?>

        <div class="mw-ui-col">
        <div class="mw-ui-col-container">
          <div class="comment-content">
            <div class="comment-author">
                <?php if(isset( $comment['comment_website'])){ ?> <a class="blue" href="<?php print $comment['comment_website']; ?>"> <?php } ?>
                    <?php print $comment['comment_name'] ?>
                <?php if(isset( $comment['comment_website'])){ ?> </a> <?php } ?>
          </div>
            <div class="comment-body">
              <?php if($required_moderation != false and  $comment['is_moderated'] == 'n' ): ?>
              <em class="comment-require-moderation"><?php _e("Your reply requires moderation"); ?></em><br />
              <?php endif; ?>
              <?php print nl2br($comment['comment_body'] ,1);?> </div>
          </div>
        </div>
        </div>
      </div>
      </div>
    </div>
    <?php } endforeach; ?>
    <?php if($paging != false and intval($paging) > 1 and isset($paging_param)): ?>
    <?php print paging("num={$paging}&paging_param={$paging_param}") ?>
    <?php endif; ?>
  </div>
  <?php else: ?>
  <h3><?php _e("No replies"); ?></h3>
  <?php endif; ?>
  <hr>
  <?php if(!$login_required or $cur_user != false): ?>
  <div class="mw-comments-form" id="comments-<?php print $data['id'] ?>">
    <form autocomplete="on" id="comments-form-<?php print $data['id'] ?>">
      <input type="hidden" name="rel_id" value="<?php print $data['rel_id'] ?>">
      <input type="hidden" name="rel" value="<?php print $data['rel'] ?>">
      <?php if($form_title != false): ?>
      <input type="hidden" name="comment_subject" value="<?php print $form_title ?>">
      <?php endif; ?>
      <h2><?php _e("Leave a reply"); ?></h2>
      <?php if( $cur_user == false) :  ?>

      <div class="mw-ui-row" style="width: 100%;">
        <div class="mw-ui-col"><input class="box" placeholder="<?php _e("Your name"); ?>" required type="text" name="comment_name"></div>
        <div class="mw-ui-col"><input class="box" placeholder="<?php _e("Website"); ?>" type="text" name="comment_website"></div>
        <div class="mw-ui-col"><input class="box" placeholder="<?php _e("Your email"); ?>" required type="email"  name="comment_email"></div>
      </div>

      <?php else: ?>
      <span class="comments-user-profile"><?php _e("You are replying as"); ?>:


      <?php if(isset($cur_user_data['thumbnail']) and trim($cur_user_data['thumbnail'])!=''): ?>
      <span class="mw-user-thumb mw-user-thumb-small"> <img style="vertical-align:middle" src="<?php print $cur_user_data['thumbnail'] ?>"  height="24" width="24" /> </span>
      <?php endif; ?>
      <span class="comments-user-profile-username"> <?php print user_name($cur_user_data['id']); ?> </span> <small><a href="<?php print api_url('logout') ?>">(<?php _e("Logout"); ?>)</a></small> </span>
      <?php endif; ?>

            <div class="vpad"><textarea required class="box" placeholder="<?php _e("Reply"); ?>" name="comment_body" style="width:100%;height:120px;"></textarea></div>


            <div class="box pull-left box-field">
                <div class="box-content">
                  <img title="Click to refresh image" alt="<?php _e("Captcha image"); ?>" class="mw-captcha-img" src="<?php print site_url('api_html/captcha') ?>" onclick="mw.tools.refresh_image(this);">
                  <input type="text" name="captcha" required class="invisible-field" placeholder="<?php _e("Enter text"); ?>">
                </div>
            </div>


          <input type="submit" class="orangebtn pull-right" value="<?php _e("Add Reply"); ?>">
          <div style="clear: both;padding-top: 20px;"></div>
          <div class="alert" style="display: none"></div>
    </form>
  </div>
  <?php else :  ?>
  <div class="alert"> <?php _e("You have to"); ?> <a href='<?php print site_url(); ?>login' class="comments-login-link"><?php _e("log in"); ?></a> <?php _e("or"); ?> <a class="comments-register-link" href='<?php print site_url(); ?>register'><?php _e("register"); ?></a> <?php _e("to post a comment"); ?>. </div>
  <?php endif; ?>
</div>
