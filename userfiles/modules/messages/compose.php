<?
 
$message_to_user = get_user($to);
?>
<?php dbg(__FILE__); ?>

<script type="text/javascript">
function friend_search($kw){
   
   if($kw == false){
	$kw = '';   
   }
   
   $.ajax({
  url: '<? print site_url('api/module') ?>',
   type: "POST",
      data: ({module : 'users/friends' ,
			// user_id : $user_id, 
			 format : 'json',
			
			 keyword : $kw

			 }),
      dataType: "json",
      async:true,

  success: function(resp) {
  $("#compose_suto").remove();

    var div = document.createElement('div');
    div.id = 'compose_suto';
    $(div).css({
       top:$("#receiver_name").offset().top + $("#receiver_name").outerHeight(),
       left:$("#receiver_name").offset().left,
       width:$("#receiver_name").outerWidth()
    });
if(typeof resp == 'object'){
    $(div).html(mw.createuserslist(resp)).appendTo(document.body);
  }
  else{
    $(div).html(resp).appendTo(document.body);
  }



     // alert(resp);
  // $('#users_list_ajax').html(resp);
  //  $('#users_list_ajax').fadeIn();
   // alert('Load was performed.');
  }
    });
}



$(document).ready(function() {
 

 /* $("#receiver_name").onStopWriting(function(){
       friend_search(this.value);
  });*/

});
</script>



<h2 class="title" style="font-size:18px">
<? if($conversation): ?> 
Reply to 

<? else: ?>
Send message to 
 <? endif; ?>


<span id="send-msg-to-name">
  <?php $message_to_user['first_name']? print $message_to_user['first_name'] : print $message_to_user['username'] ;  ?>
  </span></h2>
<form method="post" id="message-compose" class="form">


  <input name="mk" type="hidden" value="<?php print ($CI->core_model->securityEncryptString ( user_id() )) ?>" />
  <input name="from_user" type="hidden" value="<?php print intval( user_id() ) ?>" />
  
<? if($conversation): ?>  
  
  <input name="parent_id" type="hidden" value="<?php print intval( $conversation ) ?>" />
   <input name="receiver" id="message-compose-receiver" type="hidden" value="<?php print intval( $message_to_user['id'] ) ?>" />
  
  <? else: ?>
   <input name="receiver" id="message-compose-receiver" type="hidden" value="<?php print intval( $message_to_user['id'] ) ?>" />
  <label class="block" style="padding: 8px 0 5px">Receiver: <small>(the name of your friend)</small></label>
  <span class="linput">
  <input id="receiver_name" class="required" name="receiver_name" type="text" value="<?php $message_to_user['first_name']? print $message_to_user['first_name'] : print $message_to_user['username'] ;  ?>" style="width:370px" />
  </span>
  <script type="text/javascript">

<?php $tempname =  'setReceiverId_'.rand(); ?>


function <?php print $tempname ?>(id, names){
	$('#message-compose-receiver').val(id);
	 $('#receiver_name').val(names);
	 $('#send-msg-to-name').html(names);
}




  $(document).ready(function(){



	  
  });



</script>
  <? endif; ?>

  
 



<? if(!$conversation): ?>
  <label class="block" style="padding: 8px 0 5px">Subject:</label>
  <span class="linput">
  <input name="subject" type="text" style="width:370px" class="required" value="(no subject)" />
  </span>
   <? endif; ?>
  <label class="block" style="padding: 8px 0 5px">Message</label>
  <span class="larea" style="padding-right:5px;">
  <textarea name="message" class="required" cols="" rows="" style="width:370px;height:140px;"></textarea>
  </span>
  <?php /* <input name="send" value="send" type="button" onClick="mw.users.UserMessage.sendQuick(this)" /> */ ?>
  <div class="c" style="padding-bottom: 12px;">&nbsp;</div>
  <a href="javascript:;" onClick="if($(this).parents('form').isValid()){mw.users.UserMessage.sendQuick(this)}" class="send-msg-btn-modal">Send</a>
</form>

<?php dbg(__FILE__, 1); ?>
