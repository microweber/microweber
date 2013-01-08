<?php

/*

type: layout

name: Black

description: Default comments template

*/

  //$template_file = false; ?><style>
#comments-<? print $data['id'] ?> form, #comments-<? print $data['id'] ?> input, #comments-<? print $data['id'] ?> select, #comments-<? print $data['id'] ?> textarea {
margin:0;
padding:0;
color:#fff;
}
 #comments-<? print $data['id'] ?> .comment_form_black {
margin:0 auto;
 background:#222;
position:relative;
top:50px;
border:1px solid #262626;
}
 #comments-<? print $data['id'] ?> .comment_form_black h1 {
color:#FFF5CC;
font-size:18px;
text-transform:uppercase;
padding:5px 0 5px 5px;
border-bottom:1px solid #161712;
border-top:1px solid #161712;
}
 #comments-<? print $data['id'] ?> .comment_form_black label {
width:100%;
display: block;
background:#1C1C1C;
border-top:1px solid #262626;
border-bottom:1px solid #161712;
padding:10px 0 10px 0;
}
 #comments-<? print $data['id'] ?> .comment_form_black .comment {
width:100%;
display: block;
background:#333333;
padding:10px;
padding-left:15px;;
border-bottom:1px solid #4F4F4F;
}
 #comments-<? print $data['id'] ?> .comment_form_black .comment-author {
 color:#FFF5CC;
font-size:14px;
 font-weight: bold;
padding:5px 0 5px 5px;
}
 #comments-<? print $data['id'] ?> .comment_form_black .comment-body {
 color:#FBFBFB;
font-size:12px;
padding:10px;
}
 #comments-<? print $data['id'] ?> .comment_form_black label span {
display: block;
color:#bbb;
font-size:12px;
float:left;
width:100px;
text-align:right;
padding:5px 20px 0 0;
}
 #comments-<? print $data['id'] ?> .comment_form_black .input_text {
padding:10px 10px;
width:200px;
background:#262626;
border-bottom: 1px double #171717;
border-top: 1px double #171717;
border-left:1px double #333;
border-right:1px double #333;
}
 #comments-<? print $data['id'] ?> .comment_form_black .message {
padding:7px 7px;
width:350px;
background:#262626;
border-bottom: 1px double #171717;
border-top: 1px double #171717;
border-left:1px double #333;
border-right:1px double #333;
overflow:hidden;
height:150px;
}
#comments-<? print $data['id'] ?> .comment_form_black .button {
margin:0 0 10px 0;
padding:4px 7px;
background:#CC0000;
border:0px;
position: relative;
top:10px;
left:382px;
width:100px;
border-bottom: 1px double #660000;
border-top: 1px double #660000;
border-left:1px double #FF0033;
border-right:1px double #FF0033;
}
</style>

<div class="comments_form" id="comments-<? print $data['id'] ?>">
  <? if (isarr($comments)): ?>
  <div class="comments comment_form_black" id="comments-list-<? print $data['id'] ?>">
    <? foreach ($comments as $comment) : ?>
    <div class="comment" id="comment-<? print $comment['id'] ?>">
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
    <input type="submit" class="button" value="Submit">
    </label>
  </div>
  </form>
</div>