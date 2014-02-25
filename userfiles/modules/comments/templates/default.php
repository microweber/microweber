<?php

/*

type: layout

name: Default

description: Default comments template

*/

  //$template_file = false;
  

  
   ?>

   <style>

.mwcommentsmodule .comment-image-holder{
  width: 95px;
}

/* Comments  */

.mwcommentsmodule .mw-comments-form .captcha .add-on {
	background-color: white;
}

.captcha-row{
  padding-top: 20px;
}

.captcha-row img{
  border-top:
}

.mwcommentsmodule .mw-comments-form .comment-field input, .mwcommentsmodule .mw-comments-form .comment-field textarea {
	width: 100%;
}
.mwcommentsmodule .mw-comments-form textarea {
	height: 100px;
	min-height: 50px;
	max-height: 250px;
	resize: vertical;
}
.mwcommentsmodule .mw-comments-form input[type='submit'] {
	position: relative;
	z-index: 1;
}
.mwcommentsmodule .comment-content {
	padding-left: 10px;
}
.mwcommentsmodule .comment {
	clear: both;
	overflow: hidden;
	zoom:1;
	padding: 20px 0;
}



.mwcommentsmodule .comment .img-polaroid,  .mwcommentsmodule .comment .img-rounded {
	display: block;
	width: 67px;
	max-height: 67px;
	margin: 5px 0;
	text-align: center;
}
.mwcommentsmodule .comment .random-color span {
	display: block;
	width: 67px;
	height: 67px;
}
.mwcommentsmodule .comment-author {
	font-weight: bold;
	color: #333;
}
/* Comments Simple Template  */


.mwcommentsmodule .comments-template-simple .comment {
	padding: 10px;
	border: 1px solid #D3D3D3;
	margin: 10px 0;
	background: white;
	border-radius: 4px;
    color: #111;
}
.mwcommentsmodule .comments-template-simple .comment:nth-child(2n) {
	border-color:transparent;
	background-color: transparent;
}
/* /Comments Simple Template  */


   /* Comments Stylish Template  */

    .mwcommentsmodule .comments-template-stylish .comment .comment-content {
	background:#fff;
	box-shadow: inset 0px 1px 1px #CCCCCC;
	padding: 15px 25px;
	position: relative;
    color: #111;
}
.mwcommentsmodule .comments-template-stylish .comment a {
	 color:#0099CC;
}

 



.mwcommentsmodule .comments-template-stylish .comment .comment-content:before {
	content: "";
	display: block;
	position: absolute;
	left: -8px;
	top: 15px;
	width: 9px;
	height: 19px;
	z-index: 1;
	background: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAkAAAATCAYAAABC3CftAAAABHNCSVQICAgIfAhkiAAAAAlwSFlzAAACJwAAAicBvhJUCAAAABx0RVh0U29mdHdhcmUAQWRvYmUgRmlyZXdvcmtzIENTNui8sowAAAAWdEVYdENyZWF0aW9uIFRpbWUAMDMvMTQvMTMdJSPhAAAAbklEQVQokY3SUQ2AMAxF0W4KJgEJlYAUJCEBSUhAAg4uPyPZlnXtS/p3kiavTYA4KdkBKiKPANYo8LIA+w8sdDDEBSM6Z6BFlwWIAIBcu1gHKMDtrXOhRODY0xTOGt9oTmKh7rgrFPqCDib8z9QPLjTkjtGkpGIAAAAASUVORK5CYII=) no-repeat;

}
.mwcommentsmodule .comments-template-stylish form input[type='text'],  .mwcommentsmodule .comments-template-stylish form input[type='email'],  .mwcommentsmodule .comments-template-stylish form textarea, .mwcommentsmodule .comments-template-stylish form .add-on {
	border-radius: 0;
}

/* /Comments Stylish Template  */

.comments-form-fields .comment-field{
  padding-bottom: 20px;
}

@media (max-width:768px){
  .comments-template-stylish .comment-image-holder .mw-ui-col-container{
    display: none;
  }

  .comments-template-stylish .mw-ui-col-container img.comment-image{
    margin-left: auto;
    margin-right: auto;
  }
}



    /* /Comments  */

   </style>

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
  if(!empty($comment_author)){
	  // $comment['comment_name'] = user_name($comment_author['id']);
  }
  
  if($cur_user != false and $comment['created_by'] == $cur_user){
 $my_comment = true;	  
  }

  
  
  
  

  ?>
				<?php if($avatars_enabled){ ?>
				<div class="mw-ui-col comment-image-holder" >
					<div class="mw-ui-col-container">
						<?php $avatar_style =  get_option('avatar_style', 'comments'); ?>
						<?php  if (isset($comment_author['thumbnail'])  and  trim($comment_author['thumbnail']) != ''){ ?>
						<img src="<?php print ($comment_author['thumbnail']);  ?>" width="60" height="60" class="img-polaroid comment-image" alt="<?php print addslashes($comment['comment_name']) ?>" />
						<?php  }  else  {   ?>
						<?php   if($avatar_style == '4'){ ?>
						<img src="<?php print thumbnail(get_option('avatartype_custom', 'comments'), 60, 60);  ?>" class="img-polaroid comment-image"  width="60" height="60"  alt="<?php print addslashes($comment['comment_name']) ?>" />
						<?php } else if($avatar_style == '1' || $avatar_style == '3'){ ?>
						<img src="<?php print thumbnail($config['url_to_module']. '/img/comment-default-'.$avatar_style.'.jpg', 60, 60);  ?>"  width="60" height="60"  class="img-polaroid comment-image" alt="<?php print addslashes($comment['comment_name']) ?>" />
						<?php } else if($avatar_style == '2'){ ?>
						<span class="img-polaroid  random-color"> <span style="background-color: <?php print mw('format')->random_color(); ?>"> </span> </span>
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
								<?php if(isset( $comment['comment_website'])){ ?>
								<a href="<?php print $comment['comment_website']; ?>">
								<?php } ?>
								<?php print $comment['comment_name'] ?>
								<?php if(isset( $comment['comment_website'])){ ?>
								</a>
								<?php } ?>
								<?php if(isset($comment['updated_on'])): ?>
								<small class="muted">
								<date><?php print $comment['updated_on']; ?></date>
								</small>
								<?php endif; ?>
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
	<h4>
		<?php _e("No comments"); ?>
	</h4>
	<?php endif; ?>
	
	
	<?php if( $are_disabled == false) :  ?>
	<hr>
	
	
	
	<?php if(!$login_required or $cur_user != false): ?>
	<div class="mw-comments-form" id="comments-<?php print $data['id'] ?>">
		<form autocomplete="on" id="comments-form-<?php print $data['id'] ?>" class="form-group">
			<input type="hidden" name="rel_id" value="<?php print $data['rel_id'] ?>">
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
			<div class="row">
				<div class="col-sm-12  comment-field">
					<textarea required placeholder="<?php _e("Comment"); ?>" name="comment_body" class="form-control input-lg"></textarea>
				</div>
			</div>
			<div class="row captcha-row">
				<div class="col-sm-6">
                        <div class="inline-block" style="background: white;">
                          <img title="Click to refresh image" alt="<?php _e("Captcha image"); ?>" class="mw-captcha-img" src="<?php print site_url('api_html/captcha') ?>?id=<?php print $params['id']; ?>" onclick="mw.tools.refresh_image(this);">
                          <input type="text" name="captcha" required class="form-control" style="width:90px;display:inline" placeholder="<?php _e("Enter text"); ?>">
                        </div>



				</div>
                <div class="col-sm-6"><input type="submit" class="btn btn-default pull-right" value="<?php _e("Add comment"); ?>"></div>
			</div>
		</form>
	</div>
	<?php else :  ?>
	<div class="alert">
		<?php _e("You have to"); ?>
		<a href='<?php print site_url(); ?>login' class="comments-login-link">
		<?php _e("log in"); ?>
		</a>
		<?php _e("or"); ?>
		<a class="comments-register-link" href='<?php print site_url(); ?>register'>
		<?php _e("register"); ?>
		</a>
		<?php _e("to post a comment"); ?>
		. </div>
	<?php endif; ?>
	<?php else: ?>
	 
	<?php endif; ?>
	

</div>
</div>
