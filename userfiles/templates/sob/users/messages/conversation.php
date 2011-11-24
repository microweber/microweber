<h1>Conversation</h1>
<?php foreach ($messages as $message) { ?>
<?php require 'message_item.php';?>
<br />
<?php } ?>

<?php require 'message_form.php';?>

<br /><br />
<a href="<?php print site_url('users/user_action:messages'); ?>">Back to messages list</a>
