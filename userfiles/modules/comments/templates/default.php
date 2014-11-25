<?php

/*

type: layout

name: Default

description: Default comments template

*/

  //$template_file = false;


  
   ?>
<?php $rand = rand();  ?>
<script>mw.moduleCSS("<?php print $config['url_to_module']; ?>comments.css", true);</script>

<div class="mwcommentsmodule ">
  <div class=" comments-template-stylish">
    <?php
       $cur_user = user_id();
             if($cur_user != false){
              $cur_user_data = get_user($cur_user);
             }
        ?>
    <?php if (is_array($comments)): ?>
    <?php if($form_title != false): ?>
    <h4><?php print $form_title ?></h4>
    <?php elseif($display_comments_from  != false and $display_comments_from   == 'recent'): ?>
    <h4>
      <?php _e("Recent comments"); ?>
    </h4>
    <?php else : ?>
    <h4>
      <?php _e("Comments for"); ?>
      <strong>
      <?php  $post = get_content_by_id($data['rel_id']); print $post['title']; ?>
      </strong></h4>
    <?php endif; ?>
    <div class="comments" id="comments-list-<?php print $data['id'] ?>">
      <?php foreach ($comments as $comment) : ?>
      <?php
    $required_moderation = get_option('require_moderation', 'comments')=='y';
    if(!$required_moderation or $comment['is_moderated'] == 'y' or (!empty($_SESSION) and  $comment['session_id'] == session_id())){
  ?>
      <div class="clearfix comment" id="comment-<?php print $comment['id'] ?>">
        <div class="mw-ui-row">
          <?php
  $avatars_enabled = get_option('avatar_enabled', 'comments')=='y';
  $comment_author =  get_user_by_id($comment['created_by']) ;
  $my_comment = false;
  if($cur_user != false and $comment['created_by'] == $cur_user){
    $my_comment = true;
  }

  ?>
          <?php if($avatars_enabled){ ?>
          <div class="mw-ui-col comment-image-holder">
            <div class="mw-ui-col-container">
              <?php $avatar_style =  get_option('avatar_style', 'comments'); ?>
              <?php  if (isset($comment_author['thumbnail'])  and  trim($comment_author['thumbnail']) != ''){ ?>
              <img src="<?php print ($comment_author['thumbnail']);  ?>" width="60" height="60" class="comment-image" alt="<?php print addslashes($comment['comment_name']) ?>" />
              <?php  }  else  {   ?>
              <?php   if($avatar_style == '4'){ ?>
              <img src="<?php print thumbnail(get_option('avatartype_custom', 'comments'), 60, 60);  ?>" class="comment-image"  width="60" height="60"  alt="<?php print addslashes($comment['comment_name']) ?>" />
              <?php } else if($avatar_style == '1' || $avatar_style == '3'){ ?>
              <img src="<?php print thumbnail($config['url_to_module']. '/img/comment-default-'.$avatar_style.'.jpg', 60, 60);  ?>"  width="60" height="60"  class="comment-image" alt="<?php print addslashes($comment['comment_name']) ?>" />
              <?php } else if($avatar_style == '2'){ ?>
              <span class="comment-image random-color"> <span style="background-color: <?php print mw('format')->random_color(); ?>"> </span> </span>
              <?php } else if(isset($comment_author['thumbnail']) and $comment_author['thumbnail'] != ''){ ?>
              <img src="<?php print ($comment_author['thumbnail']);  ?>" width="60" height="60" class="comment-image" alt="<?php print addslashes($comment['comment_name']) ?>" />
              <?php } else {  ?>
              <img src="<?php print thumbnail($config['url_to_module']. '/img/comment-default-1.jpg', 60, 60);   ?>"  width="60" height="60"  class="comment-image" alt="<?php print addslashes($comment['comment_name']) ?>" />
              <?php } ?>
              <?php } ?>
            </div>
          </div>
          <?php } ?>
          <div class="mw-ui-col">
            <?php   event_trigger('module.comments.item.before', $comment); ?>
            <div class="mw-ui-col-container">
              <div class="comment-content">
                <div class="comment-author">
                  <?php if(isset( $comment['comment_website'])){ ?>
                  <a href="<?php print mw('format')->prep_url($comment['comment_website']); ?>">
                  <?php } ?>
                  <?php print $comment['comment_name'] ?>
                  <?php if(isset( $comment['comment_website'])){ ?>
                  </a>
                  <?php } ?>
                  <?php if(isset($comment['updated_on'])): ?>
                  &nbsp; <small class="muted"> <?php print $comment['updated_on']; ?> </small>
                  <?php endif; ?>
                  <?php   event_trigger('module.comments.item.info', $comment); ?>
                </div>
                <div class="comment-body">
                  <?php if($required_moderation != false and  $comment['is_moderated'] == 'n' ): ?>
                  <em class="comment-require-moderation">
                  <?php _e("Your comment requires moderation"); ?>
                  </em><br />
                  <?php endif; ?>
                  <?php print nl2br($comment['comment_body'] ,1);?>
                  <?php if($my_comment == true): ?>
                  <?php endif; ?>
                  <?php   event_trigger('module.comments.item.body', $comment); ?>
                </div>
                <?php   event_trigger('module.comments.item.body.after', $comment); ?>
              </div>
            </div>
            <?php   event_trigger('module.comments.item.after', $comment); ?>
          </div>
        </div>
      </div>
      <?php } endforeach; ?>
      <?php if($paging != false and intval($paging) > 1 and isset($paging_param)): ?>
      <?php print paging("num={$paging}&paging_param={$paging_param}") ?>
      <?php endif; ?>
    </div>
    <?php else: ?>
    <h4>
      <?php _e("No comments"); ?>
    </h4>
    <?php endif; ?>
    
    <?php if( $are_disabled == false) :  ?>
    <hr>
    <?php if(!$login_required or $cur_user != false): ?>
    <div class="mw-comments-form" id="comments-<?php print $data['id'] ?>">
      <?php   event_trigger('module.comments.form.before', $data); ?>
      <form autocomplete="on" id="comments-form-<?php print $data['id'] ?>" class="form-group">
        <?php   event_trigger('module.comments.form.start', $data); ?>
        <input type="hidden" name="rel_id" value="<?php print $data['rel_id'] ?>">
         <?php print csrf_form(); ?>
        
        <input type="hidden" name="rel" value="<?php print $data['rel'] ?>">
        <input type="hidden" name="module_id" value="<?php print $params['id'] ?>">
        <?php if($form_title != false): ?>
        <input type="hidden" name="comment_subject" value="<?php print $form_title ?>">
        <?php endif; ?>
        <h4>
          <?php _e("Leave a comment"); ?>
        </h4>
        <?php if( $cur_user == false) :  ?>
        <div class="row comments-form-fields">
          <div class="col-sm-4 comment-field">
            <input class="input-medium form-control input-lg" placeholder="<?php _e("Your name"); ?>" required type="text" name="comment_name">
          </div>
          <div class="col-sm-4  comment-field">
            <input class="input-medium form-control input-lg" placeholder="<?php _e("Website"); ?>" type="text" name="comment_website">
          </div>
          <div class="col-sm-4  comment-field">
            <input class="input-medium form-control input-lg" placeholder="<?php _e("Your email"); ?>" required type="email"  name="comment_email">
          </div>
        </div>
        <?php else: ?>
        <span class="comments-user-profile">
        <?php _e("You are commenting as"); ?>
        :
        <?php if(isset($cur_user_data['thumbnail']) and trim($cur_user_data['thumbnail'])!=''): ?>
        <span class="mw-user-thumb mw-user-thumb-small"> <img style="vertical-align:middle" src="<?php print $cur_user_data['thumbnail'] ?>"  height="24" width="24" /> </span>
        <?php endif; ?>
        <span class="comments-user-profile-username"> <?php print user_name($cur_user_data['id']); ?> </span> <small><a href="<?php print api_link('logout') ?>">(
        <?php _e("Logout"); ?>
        )</a></small> </span>
        <?php endif; ?>
        <div class="row comment-textarea">
          <div class="col-sm-12  comment-field">
            <textarea required placeholder="<?php _e("Comment"); ?>" name="comment_body" class="form-control input-lg"></textarea>
          </div>
        </div>
        <div class="mw-ui-row vertical-middle captcha-row">
          <div class="mw-ui-col"> <img
                        title="Click to refresh image"
                        id='comment-captcha-<?php print $rand; ?>'
                        alt="<?php _e("Captcha image"); ?>"
                        class="mw-captcha-img"
                        src="<?php print site_url('api_html/captcha') ?>?id=<?php print $params['id']; ?>"
                        onclick="mw.tools.refresh_image(this);"> </div>
          <div class="mw-ui-col">
            <input type="text" name="captcha" required class="form-control" placeholder="<?php _e("Enter text"); ?>" />
          </div>
          <div class="mw-ui-col"> <span onclick="mw.tools.refresh_image(mwd.getElementById('comment-captcha-<?php print $rand; ?>'));" class="ico irefresh"></span> </div>
        </div>
        <?php   event_trigger('module.comments.form.end', $data); ?>
        <input type="submit" class="btn btn-default pull-right" value="<?php _e("Add comment"); ?>">
      </form>
      <?php   event_trigger('module.comments.form.after', $data); ?>
    </div>
    <?php else :  ?>
    <div class="alert">
      <?php _e("You have to"); ?>
      <a href='<?php print login_url(); ?>' class="comments-login-link">
      <?php _e("log in"); ?>
      </a>
      <?php _e("or"); ?>
      <a class="comments-register-link" href='<?php print register_url(); ?>'>
      <?php _e("register"); ?>
      </a>
      <?php _e("to post a comment"); ?>
      . </div>
    <?php endif; ?>
    <?php else: ?>
    <?php endif; ?>
  </div>
</div>
