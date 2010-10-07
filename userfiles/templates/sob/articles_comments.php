 <?php dbg(__FILE__); ?>
<?php if($this->content_model->optionsGetByKey ( 'require_login_to_comment' ) == 'y'): ?>
<?php if(strval($user_session['is_logged'] ) != 'yes'):  ?>

<form method="post" class="validate" action="<?php print site_url('users/user_action:login'); ?>/back_to:<?php print base64_encode(getCurentURL()); ?>" id="logIn">
  <h2 style="padding-bottom: 12px;" class="title">Sign in to comment</h2>
  <div class="login-item" style="margin-right: 90px;">
    <label>Username or Email: <strong>*</strong></label>
    <span class="linput">
        <input name="username" class="required" type="text" />
        <a class="reg-help" href="<?php print site_url('users/user_action:forgotten_pass'); ?>">Lost your password?</a>
    </span>
  </div>
  <div class="login-item">
    <label>Password: <strong>*</strong></label>
    <span class="linput">
        <input name="password" class="required" type="password" />
        <a class="reg-help" href="<?php print site_url('users/user_action:forgotten_pass'); ?>">Lost your password?</a>
    </span>

  </div>
  <div style="height: 31px;" class="clear">&nbsp;</div>
  <label class="clear left" style="margin-top: 3px;">
    <input type="checkbox"  name="remember" value="y" class="left" id="rbox" />
    Remember me</label>


  <a href="#" class="btn submit">Log in</a>
  <span class="right" style="margin:3px 10px"><a href="<?php print site_url('users/user_action:register'); ?>"><u>Register</u></a> or </span>
  <br class="clear" />
</form>
<div class="not-a-member"> <a href="<?php print site_url('users/user_action:register'); ?>" class="login">Join free</a> <a href="<?php print site_url('users/user_action:register'); ?>"><strong>Not a member?</strong></a> <a href="<?php print site_url('users/user_action:register'); ?>">Join now <em>It's fast and free</em></a> </div>
<?php else: ?>
<?php //p($post);
if($post['comments_enabled'] == 'y') : ?>
<!--<script type="text/javascript">
     bkLib.onDomLoaded(function() {
     	new nicEditor({iconsPath : nicToolbarPath, fullPanel : true }).panelInstance('comment_body');
     });
</script>-->
<script type="text/javascript">
                        $(document).ready(function(){
                            var options ={
                                      target:        'test.php',
                                      beforeSubmit:  bSubmit,
                                      success:       aSubmit
                            }
                            $("#comments-form").submit(function(){
                                if($(this).hasClass("error")){}
                                else{
                                  $(this).ajaxSubmit(options);
                                }
                                return false;
                            });

                        });

                        function bSubmit(formData){
                            alert($.param(formData))

                        }
                        function aSubmit(){
                            alert("Comment sent")
                        }
                    </script>
<script type="text/javascript">

function refresh_after_post_comment(){
		var refresh_the_page = "<?php print $this->content_model->contentGetHrefForPostId($post['id']) ; ?>#the_comments_anchor";
		//window.location=refresh_the_page;
		window.location.reload();
	}



    $(document).ready(function(){
       $("#comments_form input, #comments_form textarea").focus(function(){$(this).addClass("focus")});
       $("#comments_form input, #comments_form textarea").blur(function(){$(this).removeClass("focus")});

    //   $("#comments_form").validate();



    CommentOptions = {

		url:       '<?php print site_url('main/comments_post'); ?>'  ,
		clearForm: true,
		type:      'post',
        beforeSubmit:  comments_before,
        success:       comments_after

    };

    $('#comments_form').submit(function(){

        $(this).ajaxSubmit(CommentOptions);
        return false;
    });

    function comments_before(){
        var TF;
        if($("#comments_form").hasClass("error")){
            TF = false;
        }
        else{
          TF=true
        }
       //alert(TF)
       return TF;

    }
    function  comments_after(){
       var success_elem = document.createElement("span");
       success_elem.className = "success_elem";
       //success_elem.innerHTML = "Your message has been sent."
       $("#comments_form").append(success_elem);
	   refresh_after_post_comment();
    }



    });

</script>
<!--<h2 class="title" style="padding: 15px 0 10px 0">Leave your comment</h2>  -->
<div id="commentForm">
<?php if(empty($comments)) : ?>
<h2>Be the 1st to comment on this post</h2>
<?php endif; ?>
  <?php $table_content = base64_encode($this->core_model->securityEncryptString('table_content')); ?>
  <?php $table_id = base64_encode($this->core_model->securityEncryptString($post['id']));
//var_dump($table_content, $table_id);
?>
  <form method="post" action="#" id="comments_form" class="validate xform">
    <input type="hidden" name="to_table_id" id="to_table_id"  value="<?php print ($table_id) ; ?>"  />
    <input type="hidden" name="to_table" id="to_table"  value="<?php print ($table_content ); ?>"  />

    <?php /*
    <div style="width: 224px;">
      <label>Name:*</label>
      <input type="text" name="comment_name" class="required cinput" />
      <span class="errmsg">This field is required</span> </div>
    <div style="width: 224px;">
      <label>Email:*</label>
      <input type="text" name="comment_email" class="required-email cinput" />
      <span class="errmsg">Valid E-mail is required</span> </div style="width: 224px;">
    <div style="width: 224px;">
      <label>Website:</label>
      <input name="comment_website" class="cinput" type="text" />
    </div>

       */ ?>
     <div class="item">
     <?php $user_data = $this->users_model->getUserById ( $user_session ['user_id'] );   ?>
     
      <?php $thumb = $this->users_model->getUserThumbnail( $user_data['id'], 45); ?>
        <?php if($thumb != ''): ?>
   

      <div class="add-comment-img" style="background-image:url(<?php print $thumb; ?>)"></div>
        <?php endif; ?>
     
        <span class="larea"><textarea rows="" cols="" id="comment_body" name="comment_body" class="required"></textarea></span>

     </div>
      <div class="c">&nbsp;</div>
      <a href="<?php print site_url('main/rss_comments/post:').$post['id']; ?>" class="get-rss">RSS Comments</a>
      <a class="btn submit" href="#">Add comment</a>

  </form>
</div>
<?php endif; ?>
<?php endif; ?>
<?php endif; ?>
 <?php dbg(__FILE__, 1); ?>