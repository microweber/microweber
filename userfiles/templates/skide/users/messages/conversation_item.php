<?php p($conversation); ?>
<a href="<?php echo site_url('users/user_action:messages/conversation:'.$conversation['id']); ?>" >Read</a> | 
<a href="javascript:;" onclick="mw.users.UserMessage.del('<?php echo $conversation['id']; ?>')">Delete</a>