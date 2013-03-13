<?php

/*

type: layout

name: Default

description: Default comments template

*/

  //$template_file = false; ?>
   <?php


       $cur_user = user_id();
             if($cur_user != false){
              $cur_user_data = get_user($cur_user);
             }



        ?>
<? if (isarr($comments)): ?>

<div class="comments" id="comments-list-<? print $data['id'] ?>">
  <? foreach ($comments as $comment) : ?>
  <div class="clearfix comment" id="comment-<? print $comment['id'] ?>">
    <?  $comment_author =  get_user($comment['created_by']) ;  ?>
    <?php $avatar_style =  get_option('avatar_style', 'comments'); ?>
    <?   if($avatar_style != '2'){      ?>
    <?php  if (isset($comment_author['thumbnail'])  and isset($comment_author['thumbnail']) != ''){ ?>
    <img src="<?php print  thumbnail($comment_author['thumbnail'], 67, 67);  ?>" class="img-polaroid img-rounded pull-left comment-image" alt="" />
    <?php } else if($avatar_style == '4'){ ?>
    <img src="<?php print   thumbnail(get_option('avatartype_custom', 'comments'), 67, 67);;  ?>" class="img-polaroid img-rounded pull-left comment-image" alt="" />
    <?php } else { ?>
    <img src="<?php print   thumbnail($config['url_to_module']. '/img/comment-default-'.$avatar_style.'.jpg', 67, 67);;  ?>" class="img-polaroid img-rounded pull-left comment-image" alt="" />
    <?php } ?>
    <?php } else { ?>
    <span class="img-polaroid img-rounded random-color"> <span style="background-color: <?php print random_color(); ?>"> </span> </span>
    <?php } ?>
    <div class="comment-content">
      <div class="comment-author"> <? print $comment['comment_name'] ?> </div>
      <div class="comment-body"> <? print $comment['comment_body'] ?> </div>
    </div>
  </div>
  <? endforeach; ?>
</div>
<? endif; ?>
<hr>
<?php if(!$login_required){ ?>
<div class="mw-cooments-form" id="comments-<? print $data['id'] ?>">
  <form autocomplete="off" id="comments-form-<? print $data['id'] ?>">
    <input type="hidden" name="to_table_id" value="<? print $data['to_table_id'] ?>">
    <input type="hidden" name="to_table" value="<? print $data['to_table'] ?>">




    <?php if(user_id()!=0){  ?>

    <div class="row">
      <div class="span2">
        <input class="input-medium" placeholder="Your name" type="text" name="comment_name">
      </div>
      <div class="span2">
        <input class="input-medium" placeholder="Website" type="text" name="comment_website">
      </div>
      <div class="span2">
        <input class="input-medium" placeholder="Your email" type="text" name="comment_email">
      </div>
    </div>

  <?php } ?>

    <div class="row">
      <div class="span6">
        <textarea class="input-xxlarge" placeholder="Comment" name="comment_body"></textarea>
      </div>
    </div>
    <div class="row">
      <div class="span6">
        <div class="input-prepend captcha pull-left"> <span class="add-on"> <img title="Click to refresh image" alt="Captcha image" class="mw-captcha-img" src="<? print site_url('api_html/captcha') ?>" onclick="mw.tools.refresh_image(this);"> </span>
          <input type="text" name="captcha" placeholder="Enter text from the picture">
        </div>
        <input type="submit" class="btn pull-right" value="Add comment">
      </div>
    </div>
  </form>
</div>
<?php } ?>