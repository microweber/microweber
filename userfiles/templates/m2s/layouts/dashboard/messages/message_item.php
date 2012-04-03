
<div id="messageItem-<?php echo $message['id']?>" class="inbox_msg" <?php if($message['is_read'] == 'n'): ?>
    onmouseout="mw.users.UserMessage.read('<?php echo $message['id']; ?>')"
    <?php endif; ?> > <a href="<?php echo site_url('userbase/action:profile/username:'); ?><? print user_name($message['from_user'], 'username'); ?>" class="ui_photo"> <span style="background-image: url('<? print user_thumbnail($message['from_user'], 50); ?>');"></span> <? print user_name($message['from_user']); ?> </a>
  <div class="inbox_content"> <span class="date"><?php echo date(DATETIME_FORMAT, strtotime($message['created_on']));?></span> <span class="subject"><strong>Subject: </strong><?php echo $message['subject'];?></span>
    <p><strong>Message: </strong> <?php echo $message['message'];?></p>
  </div>
  <div class="c">&nbsp;</div>
  <a href="javascript: mw.users.UserMessage.del('<?php echo $message['id']; ?>', 'messageItem-<?php echo $message['id']?>')" class="right mw_btn_x_orange"><span>Delete</span></a> <a href="<?php echo site_url('dashboard/action:messages/conversation:'.($message['parent_id'] ? $message['parent_id'] : $message['id'])); ?>#messageItem-<?php print  $message['id'] ?>" class="right mw_btn_x"><span>Reply</span></a> </div>
