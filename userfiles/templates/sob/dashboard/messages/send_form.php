<script type="text/javascript">

$(document).ready(function(){

	$("#message_form input, #message_form textarea").focus(function(){$(this).addClass("focus")});
    $("#message_form input, #message_form textarea").blur(function(){$(this).removeClass("focus")});
  
    $('#message_form').submit(function(){
        return mw.users.UserMessage.send();
    });

});

</script>

<h2 class="title" style="padding: 10px 0 10px 0">Send Message</h2>
<div id="messageForm">
  <?php $user = $this->session->userdata('user');?>
  <?php
  
  
   $messageKey = ($this->core_model->securityEncryptString($user['id']));?>
  <form method="post" action="#" id="message_form" class="validate xform">
    <input type="hidden" name="mk" id="mk"  value="<?php echo $messageKey;?>" />
    <input type="hidden" name="conversation" id="conversation"  value="<?php echo $message_parent;?>" />
    <input type="hidden" name="receiver" id="receiver"  value="<?php echo $message_receiver;?>" />
    
    <?php //var_dump($conversation); ?>
    <?php if (!$message_receiver) { ?>
	    <div style="width: 224px;"> 
	      <label>To:*</label>
	      <input type="text" id="friends_autocomplete" name="friends_autocomplete" class="cinput" />
	    </div>
	    <br />
	    
	    <script type="text/javascript" >
		var data = <?php echo $friends_json; ?>;
		$("#friends_autocomplete").autocomplete(data, {
				formatItem: function(item) {
					return item.first_name + ' ' + item.last_name;
				},
				matchContains: true
			}).result(function(event, item) {
				$('#receiver').val(item.id);
			});
					
		</script>
	    
    <?php } ?>
    <div style="width: 224px;">
      <label>Subject:*</label>
      <span class="linput"><input type="text" name="subject" style="width: 340px;" /></span>
    </div>
    <br />
    <div style="width: 404px;">
      <label>Message:*</label>
      <span class="larea"><textarea style="width: 340px;height: 100px;" rows="" cols="" id="message" name="message" class="required"></textarea></span>
<!--      <span class="errmsg">This field is required</span>-->
    </div>
    <br />
    <a class="btnAlert left" style="margin-left: 0;position: static" href="#" onclick="return mw.users.UserMessage.send();">Send</a>
  </form>
</div>
