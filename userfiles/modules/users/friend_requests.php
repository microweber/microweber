<?

$req = friend_requests();
 
?>
<? if(!empty($req)): ?>
<h2>You have <? print count($req) ?> friend requests</h2>
<br />
<mw module="users/list" user_id="<? print  user_id(); ?>"  wrap_element='div'  wrap_element_items='div' wrap_element_class='field friend_request' wrap_element_items_class='fieldcontent' show_user_controls="true" ids="<? print encode_var($req) ?>"   />
 
<? else: ?>
<h2>You don't have friend requests</h2>
<br />
 <a href="<?php print site_url('dashboard/action:my-friends'); ?>" class="btn">See all of your friends</a> 

<? endif; ?>
