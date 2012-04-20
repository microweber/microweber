<?
  
   
   $user = $u = get_user(url_param('username'));
    $me =  get_user();
 
   ?>
<? // p( $user); ?>
<script type="text/javascript">
  
  
          
$(function() {
	$('#send_msg').submit(function() {
	  //alert($(this).serialize());
	  
	  
	  $.post('<? print site_url('api/user/message_send') ?>',$(this).serialize(), function(data) {
			 // $('.result').html(data);
			 
			 $('#send_msg').fadeOut();
			 
			 $('.sendcv_dn_arr').fadeIn();
			  $('.sendcv_success').fadeIn();
			 
			});
	  
	  
	  
	  return false;
	});
});

</script>

<div class="page_tit"><? print user_name($u['id']); ?></div>
<div class="body_part_inner"> <a class="jobseaker_pic" style="background-image:url('<? print user_picture($u['id'], 200); ?>')" href="<? print user_picture($u['id'], 'original'); ?>"   target="_blank" ></a>
  <div class="jobseaker_desc"><strong> <? print user_name($u['id']); ?></strong><br />
    <br />
    <p><? print character_limiter($user['custom_fields']['about'], 500); ?></p>
  </div>
  <div class="jobseaker_tit">Information</div>
  <? include (TEMPLATE_DIR. "layouts".DS."companies".DS."profile_box.php"); ?>
  <div class="contactme_tit">Contact Me</div>
  <form id="send_msg" method="post" action="">
    <input type="hidden" name="mk" value="<?php print CI::model ( 'core' )->securityEncryptString ( user_id() ); ?>" />
    <input type="hidden" name="to_user" value="<?php print $u['id']; ?>" />
    <input type="hidden" name="subject" value="Message from: <?php print user_name(); ?>" />
    <input type="hidden" name="send_email" value="1" />
    <div class="jobseaker_contact_box">
      <div class="jobseaker_contact_left">
        <div class="jobseaker_pic"><img src="<? print user_picture($u['id'], 200); ?>" alt="<? print addslashes(user_name($u['id'])); ?>" /></div>
        <div class="jobseaker_name_arr"><? print user_name($u['id']); ?></div>
      </div>
      <div class="jobseaker_contact_rt">
        <input type="text" class="jobseaker_formtext" value="<?php print user_name(); ?>" />
        <input type="text" class="jobseaker_formtext" value="<?php print $me['email']; ?>" />
        <textarea   cols="20" rows="20" class="jobseaker_mesg" name="message"></textarea>
        <div class="jobseaker_sendbut">
          <input type="image" src="<? print TEMPLATE_URL ?>images/jobseaker_send_but.jpg" />
        </div>
      </div>
    </div>
  </form>
  <div class="sendcv_success hidden">Your message was sent successfuly to sent</div>
</div>
