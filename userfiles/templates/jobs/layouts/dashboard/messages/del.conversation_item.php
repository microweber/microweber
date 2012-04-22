<div id="messagesConversation-<?php echo $conversation['id']; ?>" class="notification new-notification" style="margin-bottom: 30px;">

<?php print (__FILE__);
exit();
//p($conversation);
if($conversation['to_user'] != $current_user['id']): ?>
  <input type="checkbox" class="notification_check" />
 <?php endif; ?>
 
  <a href="#" class="img"> </a>
  <div class="notification-content">
    <div class="notification-name">
      <?php $fromUser = CI::model ( 'users' )->getUserById($conversation['from_user']); ?>
      <h3><a href="<?php echo site_url('userbase/action:profile/username:'.$fromUser['username']); ?>"><?php echo $fromUser['first_name'] . ' ' . $fromUser['last_name']; ?></a></h3>
      <span class="date inline left"><?php echo $conversation['created_on']; ?></span> </div>
    <div class="notification-message"> <a href="<?php echo site_url('dashboard/action:messages/conversation:'.$conversation['id']); ?>"> <strong><?php echo $conversation['subject'];?></strong> </a> <?php echo $conversation['message'];?> </div>
  </div>
  <div class="box-ico-holder" style="width: 105px;margin:10px 50px 0 0 ;"> <a href="<?php echo site_url('dashboard/action:messages/conversation:'.$conversation['id']); ?>#message_form"><span class="box-ico box-ico-reply title-tip" title="Reply"></span></a>
    <?php if (!in_array($conversation['from_user'], $following_ids)) { ?>
    <span class="box-ico box-ico-follow title-tip" onclick="mw.users.FollowingSystem.follow(<?php echo $conversation['from_user']?>);" title="Follow"></span>
    <?php } ?>
    <?php if (!in_array($conversation['from_user'], $circle_of_influence_ids)) { ?>
    <span class="box-ico box-ico-c title-tip" onclick="mw.users.FollowingSystem.follow(<?php echo $conversation['from_user']?>, 1);" title="Add to circle"></span>
    <?php } ?>
  </div>
  <span class="delete_notification" onclick="mw.users.UserMessage.del('<?php echo $conversation['id']; ?>', 'messagesConversation-<?php echo $conversation['id']; ?>')">Delete</span>
  
  
   </div>
