<a href="<?php echo site_url('users/user_action:messages/unreaded:1'); ?>">See Unread messages</a>
<br /><br />

<h1>My messages (conversations)</h1>

<?php foreach ($conversations as $conversation) { ?>
<?php require 'conversation_item.php';?>
<?php } ?>

<br /><br />
<small>TODO: this form send messaged only to user stoil by now. Choosing receiver depends on design.</small>

<?php $message_receiver = 1500014; ?>
<?php require 'message_form.php';?>