 <?  if($user_id == false){
	
	$user_id = user_id();
}


?>
  <? $form_values = get_user($user_id); ?>
   <div class="c" style="padding-bottom: 4px">&nbsp;</div>

    <a  href="<? print profile_link($user_id) ?>" class="user_photo left" style="background-image:url(<? print user_thumbnail($user_id, 90); ?>);margin-right: 10px"></a>



<p style="padding-bottom: 5px">  Name :
  <a class="" href="<? print profile_link($user_id) ?>"><? print user_name($user_id) ?></a></p>





  

  <? ($res['custom_fields']['country'])? print $res['custom_fields']['country'].', ':false;  ?> <? ($res['custom_fields']['city'])? print $res['custom_fields']['city'].'':false;  ?>
  

    <a href="javascript:mw.users.UserMessage.compose(<?php echo $user_id; ?>);" class="btn" title="Send new message"><span>Send a message</span></a>
