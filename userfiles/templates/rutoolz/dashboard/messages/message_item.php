<div id="messageItem-<?php echo $message['id']?>" class="<?php echo ($message['is_read'] == 'n') ? 'messageUnread' : 'messageRead'?> notification new-notification" style="margin-bottom: 30px;">
	<input id="messageReadControl-<?php echo $message['id'];?>" type="checkbox" class="notification_check" />
	<a href="#" class="img">
    </a>
    <div class="notification-content">
        <div class="notification-name">
        	<?php $fromUser = $this->users_model->getUserById($message['from_user']); ?>
            <h3><a href="<?php echo site_url('userbase/action:profile/username:'.$fromUser['username']); ?>"><?php echo $fromUser['first_name'] . ' ' . $fromUser['last_name']; ?></a></h3>
            <span class="date inline left"><?php echo date(DATETIME_FORMAT, strtotime($message['created_on']));?></span>
        </div>
        <div class="notification-message">
            <a href="<?php echo site_url('dashboard/action:messages/conversation:'.($message['parent_id'] ? $message['parent_id'] : $message['id'])); ?>">
            	<strong><?php echo $message['subject'];?></strong>
            </a>
            <?php echo $message['message'];?>
        </div>
    </div>
    <div class="box-ico-holder" style="width: 105px;margin:10px 50px 0 0 ;">
      <a href="<?php echo site_url('dashboard/action:messages/conversation:'.$message['id']); ?>#message_form">
      	<span class="box-ico box-ico-reply title-tip" title="Reply"></span>
      </a>
      <?php if (false) { ?>
	      <?php if (!in_array($message['from_user'], $following_ids)) { ?>
	      	<span class="box-ico box-ico-follow title-tip" onclick="FollowinSystem.follow(<?php echo $message['from_user']?>);" title="Follow"></span>
	      <?php } ?>
	      <?php if (!in_array($message['from_user'], $circle_of_influence_ids)) { ?>
			<span class="box-ico box-ico-c title-tip" onclick="FollowinSystem.follow(<?php echo $message['from_user']?>, 1);" title="Lorem ipsum sit amet"></span>
	      <?php } ?>
      <?php } ?>
    </div>

    <span title="Delete" class="delete_notification" onclick="UserMessage.del('<?php echo $message['id']; ?>', 'messageItem-<?php echo $message['id']?>')">Delete</span>

</div>
