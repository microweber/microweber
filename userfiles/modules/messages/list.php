<? 
$user_id = user_id();



$msg_params = array(); 
 //  $msg_params['user_id'] = $user_id; //the user id
   //$msg_params['show'] = false; // params: read, unread, 'all'
//$msg_params['parent_id']= '0';
//$msg_params['from_user'] = $user_id; //the user id
$msg = get_messages($msg_params); 

	
?>
<script type="text/javascript">
$(document).ready(function(){
    $('.unread').mouseover(function(){
        //$(this).addClass('red');
		 $(this).removeClass('unread');
		 var getvalue = $(this).attr('rel');
 // alert(getvalue);
 if(getvalue != 0){
		 mw.users.UserMessage.read(getvalue)
		 $('#new_'+getvalue).fadeOut();
		 
 }
    }).mouseout(function(){
      //  $(this).removeClass('red');
		 $(this).attr('rel',0);
    });
});
</script>

<div class="messages_container">
  <? if(!empty($msg)): 
	  
	  ?>
  <?php foreach ($msg as $ms)  : ?>
  <? $message = get_message_by_id($ms); ?>
  <? // p( $message); ?>
  <? $thread = CI::model('messages')->messagesThread($message['id']);	?>
  <? if(!empty($thread)):
		//$message = $thread[0];
		?>
  <div  id="messageItem-<?php echo $message['id']?>" class="inbox_msg">
    <?php $your_id = $user_id ;
 
 if(intval($your_id ) == intval($message['from_user'])){
	 $between1 = 'you';
	 
	 $between1_link = profile_link($message['from_user']);
	 
	 $contevsation_with = $message['to_user'];
	  $between2 = user_name(intval($message['to_user']), 'first'); 
	  $between2_link = profile_link($message['to_user']);
	  
	   } else {
		   
		   $between1_link = profile_link($message['to_user']);
		   
		   $between2_link = profile_link($message['from_user']);
		   
		    $contevsation_with = $message['from_user'];
		   	 $between2 = user_name(intval($message['from_user']), 'first'); 
	  $between1 = user_name(intval($message['to_user']), 'first'); 
	 
 }
 
 
   
  
  ?>
    <h4 class="conv-title">Converstion between <a    href="<?php print $between1_link ?>"><?php print $between1 ?></a> and <a   href="<?php print $between2_link ?>"><?php print $between2 ?></a></h4>
    <? foreach($thread as $thread_msg):  ?>
    <div class="avatar-bubble js-comment-container"> <a class="avatar"  style="background-image: url('<? print user_thumbnail($thread_msg['created_by'], 65); ?>');" href="<?php echo user_link($thread_msg['created_by']); ?><? print user_name($thread_msg['created_by'], 'username'); ?>" class="ui_photo avatar-bubble"> <span></span> </a>
      <div class="bubble">
        <div id="" class="normal-comment">
          <div class="cmeta">
            <div class="author"> <a href=""><? print user_name($thread_msg['from_user']); ?> </a> commented </div>
            <div class="info"> <span class="date">
              <?php if($thread_msg['from_user'] != user_id()): ?>
              <?php if($thread_msg['is_read'] == 'n'): ?>
              <small class="msg-new" id="new_<?php echo $thread_msg['id']?>">(new)</small>
              <?php endif; ?>
              <?php endif; ?>
              <?php echo date(DATETIME_FORMAT, strtotime($thread_msg['from_user']));?></span> </div>
          </div>
          <div class="formatted-content">
            <div class="comment-body">
              <p>
              <div rel="<?php echo $thread_msg['id']?>"  class="tread_msg <?php if($thread_msg['is_read'] == 'n'): ?> unread<?php endif; ?>">
                <!--<span class="subject"><strong></strong></span>-->
                <strong><?php echo $thread_msg['subject'];?><br />
                </strong> <?php echo $thread_msg['message'];?>
                <? if($user_id == $thread_msg['created_by']): ?>
                <a href="javascript: mw.users.UserMessage.del('<?php echo $thread_msg['id']; ?>', 'messageItem-<?php echo $thread_msg['id']?>')" class="mw_btn_delete_msg"><span>Delete</span></a>
                <? endif; ?>
                <? $thread_id = $thread_msg['parent_id'];
  if(intval($thread_id) ==0 ){
	  
	  $thread_id =  $thread_msg['id'];
  }
  
  ?>
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <? endforeach; ?>
    <a href="javascript:mw.users.UserMessage.compose(<?php echo $thread_msg['from_user']; ?>, <?php echo $thread_id; ?>);" class="mw_btn_reply" ><span   title="Reply" >Reply</span></a> </div>
  <? endif; ?>
  <? endforeach; ?>
  <? else: ?>
  You don't have any messages.
  <? endif; ?>
</div>
