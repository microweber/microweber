<h1>Unreaded messages</h1>

<?php foreach ($messages as $message) { ?>
<?php require 'message_item.php';?>
 | <a href="<?php echo site_url('users/user_action:messages/conversation:'.($message['parent_id'] ? $message['parent_id'] : $message['id'])); ?>" >Reply</a>
<br />
<?php } ?>

<br /><br />
<a href="<?php print site_url('users/user_action:messages'); ?>">Messages list</a>
