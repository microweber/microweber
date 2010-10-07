<script type="text/javascript">

$(document).ready(function(){

	$("#message_form input, #message_form textarea").focus(function(){$(this).addClass("focus")});
    $("#message_form input, #message_form textarea").blur(function(){$(this).removeClass("focus")});

    $('#message_form').submit(function(){
    	return mw.users.UserMessage.send($(this));
    });

});

</script>

<div class="border-top">&nbsp;</div>
<h2 class="title" style="padding: 15px 0 10px 0">Send Message to </h2>
<div id="messageForm">
  <?php $user = $this->session->userdata('user'); ?>
  <?php $messageKey = ($this->core_model->securityEncryptString($user['id']));?>
  <form method="post" action="#" id="message_form" class="validate xform">
    <input type="hidden" name="mk" id="mk"  value="<?php echo $messageKey;?>" />
    <input type="hidden" name="conversation" id="conversation"  value="<?php echo $message_parent;?>" />
    <input type="hidden" name="receiver" id="receiver"  value="<?php echo $message_receiver;?>" />
    <div style="width: 224px;">
      <label>Subject:*</label>
      <input type="text" name="subject" class="cinput" />
    </div>
    <div style="width: 404px;">
      <label>Message:*</label>
      <textarea rows="2" cols="20" id="message" name="message" class="required cinput"></textarea>
      <span class="errmsg">This field is required</span>
    </div>
    <a class="btn left submit" href="#"><span>Send</span></a>
  </form>
</div>
