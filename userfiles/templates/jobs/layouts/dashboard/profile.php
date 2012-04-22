<? $dashboard_user = user_id_from_url();
 
?>
<? if($dashboard_user != $user_id) : ?>
  <? 	$is_friend = CI::model ( 'users' )->realtionsCheckIfUserIsConfirmedFriendWithUser($user_id, $dashboard_user, $is_special = false); ?>
  <? if($is_friend  == true): ?>
  
   <microweber module="users/profile_view" user_id="<? print $dashboard_user ?>"></microweber>
  
   <? else: ?>
  <h3>You must be friend with <? print user_name($dashboard_user) ?> in order to view the contact info.</h3>
   <? endif; ?>
  
  
  <? else: ?>
  <microweber module="users/profile"></microweber>
   <? endif; ?>
   
   

