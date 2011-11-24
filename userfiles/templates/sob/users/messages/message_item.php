<?php // p($message); ?>
<?php if ($user_session['user_id'] == $message['to_user']) :?>
<a href="javascript:;" onclick="mw.users.UserMessage.read('<?php echo $message['id']; ?>')">Mark as read</a> | 
<a href="javascript:;" onclick="mw.users.UserMessage.unread('<?php echo $message['id']; ?>')">Mark as unread</a> | 
<a href="javascript:;" onclick="mw.users.UserMessage.del('<?php echo $message['id']; ?>')">Delete</a>
<?php endif; ?>
