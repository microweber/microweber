<?php

/*

type: layout

name: Default

description: Default comments template

*/

  //$template_file = false; ?>

<div class="comments_form" id="comments-<? print $data['id'] ?>">
  <? if (isarr($comments)): ?>
  <div class="comments comment_form_black" id="comments-list-<? print $data['id'] ?>">
    <? foreach ($comments as $comment) : ?>
    <div class="clearfix comment" id="comment-<? print $comment['id'] ?>">
      <div class="comment-author"> <? print $comment['comment_name'] ?> </div>
      <div class="comment-body"> <? print $comment['comment_body'] ?> </div>
    </div>
    <? endforeach; ?>
  </div>
  <? endif; ?>
  <form id="comments-form-<? print $data['id'] ?>">
  <div class="comment_form_black">
    <h1>Post your comment</h1>
    <input type="hidden" name="to_table_id" value="<? print $data['to_table_id'] ?>">
    <input type="hidden" name="to_table" value="<? print $data['to_table'] ?>">
    <label> <span>Your name *</span>
      <input type="text" class="input_text" name="comment_name"  />
    </label>
    <label> <span>Message *</span>
      <textarea name="comment_body" class="message" rows="15" cols="50"></textarea>
    </label>
    <label> <span>Verification *</span> <img src="<? print site_url('api/captcha') ?>" onclick="this.src='<? print site_url('api/captcha') ?>'" />
      <input type="text"   name="captcha" class="input_text" placeholder="?">
    </label>
    <input type="submit" class="btn" value="Submit">
    </label>
  </div>
  </form>
</div>