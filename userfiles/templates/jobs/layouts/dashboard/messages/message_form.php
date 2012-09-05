<script type="text/javascript">

$(document).ready(function(){

	$("#message_form input, #message_form textarea").focus(function(){$(this).addClass("focus")});
    $("#message_form input, #message_form textarea").blur(function(){$(this).removeClass("focus")});
  
    $('#message_form').submit(function(){
    	return mw.users.UserMessage.send($(this));
    });

});

</script>

<h2 class="title" style="padding: 10px 0 10px 0">
<?php if($reply_txt == true): ?>
Reply to <?php print CI::model ( 'users' )->getPrintableName($message_receiver, 'first'); ?>
<?php else : ?>
Send message to <?php print CI::model ( 'users' )->getPrintableName($message_receiver, 'first'); ?>

<?php endif; ?>
</h2>
<div id="messageForm">
  <?php $user = $this->session->userdata('user');


  ?>
  <?php $messageKey = ( CI::model ( 'core' )->securityEncryptString($user['id']));?>
  <form method="post" action="#" id="message_form" class="form">
  <div class="bluebox">
  <div class="blueboxcontent">
    <input type="hidden" name="mk" id="mk"  value="<?php echo $messageKey;?>" />
    <input type="hidden" name="conversation" id="conversation"  value="<?php echo  CI::model ( 'core' )->getParamFromURL ( 'conversation' ); //$message_parent;?>" />
    <input type="hidden" name="receiver" id="receiver"  value="<?php echo $message_receiver;?>" />

    <div style="width: 224px;">
      <label>Subject:*</label>
      <span class="linput"><input type="text" name="subject" style="width: 460px;" /></span>

    </div>
    <br />
    <div style="width: 404px;">
      <label>Message:*</label>
      <span class="larea"><textarea style="width: 460px;height: 100px;" rows="" cols="" id="message" name="message" class="required"></textarea></span>

<!--      <span class="errmsg">This field is required</span>-->
    </div>
    </div> </div>
    <br />
    <a class="mw_btn_x submit right" href="#"><span style="padding: 15px 30px">Send</span></a>
  </form>
</div>
