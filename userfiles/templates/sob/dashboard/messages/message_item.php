<?php if($message['deleted_from_sender'] == 'y'): ?>
<?php else: ?>
<?php $conversation =  $this->core_model->getParamFromURL ( 'conversation' ); ?>

<div id="messageItem-<?php echo $message['id']?>" class="<?php echo ($message['is_read'] == 'n' && $user_session['user_id'] == $message['to_user']) ? 'messageUnread' : 'messageRead'?> notification new-notification" style="margin-bottom: 0;">
  <?php if($message['from_user'] != $current_user['id']): ?>
  <input id="messageReadControl-<?php echo $message['id'];?>" type="checkbox" class="notification_check" />
  <?php else : ?>
  <input type="checkbox" class="notification_check" style="visibility:hidden" disabled="disabled" />
  <?php endif; ?>
  <?php $fromUser = $this->users_model->getUserById($message['from_user']); ?>
  <?php $thumb = $this->users_model->getUserThumbnail( $message['from_user'], 45); ?>
  <a href="<?php echo site_url('userbase/action:profile/username:'.$fromUser['username']); ?>" class="img"  <?php if($thumb != ''): ?>style="background-image:url('<?php print $thumb; ?>')"; <?php endif; ?>></a>
  <div class="notification-content"
   <?php if($message['is_read'] == 'n'): ?>
    onmouseout="mw.users.UserMessage.read('<?php echo $message['id']; ?>')"
    <?php endif; ?>
    >
    <div class="notification-name">
      <?php if($conversation ): ?>
      <h3><a href="#messageItem-<?php echo $message['id']?>"><?php echo $fromUser['first_name'] . ' ' . $fromUser['last_name']; ?></a></h3>
      <?php else: ?>
      <h3><a href="<?php echo site_url('dashboard/action:messages/conversation:'.($message['parent_id'] ? $message['parent_id'] : $message['id'])); ?>#messageItem-<?php echo $message['id']?>"><?php echo $fromUser['first_name'] . ' ' . $fromUser['last_name']; ?></a></h3>
      <?php endif; ?>
      <?php if($message['deleted_from_receiver'] == 'y'): ?>
      <!--<span class="date inline left"><small>the recepient deleted this message</small></span>-->
      <?php endif; ?>
      <span class="date inline left"><?php echo date(DATETIME_FORMAT, strtotime($message['created_on']));?></span> </div>
    <div class="notification-message"> <a href="<?php echo site_url('dashboard/action:messages/conversation:'.($message['parent_id'] ? $message['parent_id'] : $message['id'])); ?>"> <strong><?php echo $message['subject'];?></strong> </a>
      <div onclick="window.location='<?php echo site_url('dashboard/action:messages/conversation:'.($message['parent_id'] ? $message['parent_id'] : $message['id'])); ?>#messageItem-<?php print  $message['id'] ?>'"> <?php echo $message['message'];?> </div>
    </div>
  </div>

  <?php if($message['from_user'] != $this->users_model->userId ()): ?>

  <div class="box-ico-holder" style="width: 105px;margin:10px 50px 0 0 ;"> <a href="<?php echo site_url('dashboard/action:messages/conversation:'.($message['parent_id'] ? $message['parent_id'] : $message['id'])); ?>#messageItem-<?php print  $message['id'] ?>"> <span class="box-ico box-ico-reply title-tip" title="Reply <?php print $this->users_model->getPrintableName($message['from_user'], 'first'); ?>"></span> </a>
    <?php $to_user = $message['from_user'];
	   include (ACTIVE_TEMPLATE_DIR.'dashboard/profile_small_controlls.php') ?>
  </div>
  <?php endif; ?>

  <span title="Delete" class="delete_notification" onclick="mw.users.UserMessage.del('<?php echo $message['id']; ?>', 'messageItem-<?php echo $message['id']?>')">Delete</span> </div>
<?php endif; ?>
