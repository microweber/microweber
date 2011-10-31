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

<div id="wall"> <br />
  <div class="bluebox" >
    <div class="blueboxcontent">
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
        <h4>Converstion between <a  class="mw_blue_link" href="<?php print $between1_link ?>"><?php print $between1 ?></a> and <a class="mw_blue_link" href="<?php print $between2_link ?>"><?php print $between2 ?></a></h4>
        <br />
        <? foreach($thread as $thread_msg):  ?>
        <div rel="<?php echo $thread_msg['id']?>"  class="tread_msg <?php if($thread_msg['is_read'] == 'n'): ?> unread<?php endif; ?>"> <a href="<?php echo user_link('userbase/action:profile/username:'); ?><? print user_name($thread_msg['created_by'], 'username'); ?>" class="ui_photo"> <span style="background-image: url('<? print user_thumbnail($thread_msg['created_by'], 75); ?>');"></span> <? print user_name($thread_msg['from_user']); ?> </a> <span class="date">
          <?php if($thread_msg['is_read'] == 'n'): ?>
          <small class="gray" id="new_<?php echo $thread_msg['id']?>">(new)</small>
          <?php endif; ?>
          <?php echo date(DATETIME_FORMAT, strtotime($thread_msg['from_user']));?></span>
          <!--<span class="subject"><strong></strong></span>-->
          <p><strong><?php echo $thread_msg['subject'];?><br />
            </strong> <?php echo $thread_msg['message'];?></p>
          <? if($user_id == $thread_msg['created_by']): ?>
          <a href="javascript: mw.users.UserMessage.del('<?php echo $thread_msg['id']; ?>', 'messageItem-<?php echo $thread_msg['id']?>')" class="right mw_btn_x_orange"><span>Delete</span></a>
          <? endif; ?>
          <? $thread_id = $thread_msg['parent_id'];
  if(intval($thread_id) ==0 ){
	  
	  $thread_id =  $thread_msg['id'];
  }
  
  ?>
          <div class="c"></div>
          </<br />
        </div>
        <? endforeach; ?>
        <hr />
        <a href="javascript:mw.users.UserMessage.compose(<?php echo $thread_msg['from_user']; ?>, <?php echo $thread_id; ?>);" class="mw_btn_s right" style="margin-right:5px;"><span   title="Reply" >Reply</span></a> </div>
      <? endif; ?>
      <? endforeach; ?>
      <? else: ?>
      You don't have any messages.
      <? endif; ?>
    </div>
  </div>
</div>
