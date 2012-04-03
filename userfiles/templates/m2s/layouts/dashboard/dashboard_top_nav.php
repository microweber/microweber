

        <? $dashboard_user = user_id_from_url();

?>   <? if($dashboard_user != false) : ?>
  <? if($dashboard_user != $user_id) : ?>
  <?php


$to_user = $dashboard_user;
$author  = CI::model ( 'users' )->getUserById($to_user); ?>
  <a href="javascript:mw.users.UserMessage.compose(<?php echo $author['id']; ?>);" class="mw_btn_x"><span class="box-ico box-ico-msg title-tip" title="Send new message to <?php echo $author['first_name']; ?>" >Send a message</span></a>
  <?php if (CI::model ( 'users' )->realtionsCheckIfUserIsConfirmedFriendWithUser(false,$to_user ) == false) : ?>
  <a href="javascript:mw.users.FollowingSystem.follow(<?php echo $to_user?>);" class="mw_btn_x_orange"><span class="box-ico box-ico-follow title-tip"  title="Add as friend <?php print CI::model ( 'users' )->getPrintableName($to_user, 'first'); ?>">Add as friend</span></a>
  <?php  else : ?>
  <a href="javascript:mw.users.FollowingSystem.unfollow(<?php echo $to_user?>);" class="mw_btn_x"><span class="box-ico box-ico-unfollow title-tip"  title="Remove friend <?php print CI::model ( 'users' )->getPrintableName($to_user, 'first'); ?>">Remove friend</span></a>
  <? endif; ?>
  <? endif; ?>
  <? endif; ?>


