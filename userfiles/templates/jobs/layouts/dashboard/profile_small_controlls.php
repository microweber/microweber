<?php


if($to_user != $this->users_model->userId ()): ?>


<?php   $author  = $this->users_model->getUserById($to_user); ?>

<span 	class="box-ico box-ico-msg title-tip"      	title="Send new message to <?php echo $author['first_name']; ?>"       	onclick="mw.users.UserMessage.compose(<?php echo $author['id']; ?>);"></span>
<?php if ($this->users_model->realtionsCheckIfUserIsFollowedByUser(false,$to_user ) == false) : ?>
<span class="box-ico box-ico-follow title-tip" onclick="mw.users.FollowingSystem.follow(<?php echo $to_user?>);" title="Follow <?php print $this->users_model->getPrintableName($to_user, 'first'); ?>"></span>
<?php  else : ?>
<span class="box-ico box-ico-unfollow title-tip" onclick="mw.users.FollowingSystem.unfollow(<?php echo $to_user?>);" title="UnFollow <?php print $this->users_model->getPrintableName($to_user, 'first'); ?>"></span>
<?php endif; ?>
<?php if ($this->users_model->realtionsCheckIfUserIsFollowedByUser(false,$to_user, true) == false) : ?>
<span class="box-ico box-ico-c title-tip" onclick="mw.users.FollowingSystem.follow(<?php echo $to_user?>, 1);" title="Add <?php print $this->users_model->getPrintableName($to_user, 'first'); ?> to your cicle of influence."></span>
<?php  else : ?>
<span class="box-ico box-ico-c title-tip" onclick="mw.users.FollowingSystem.follow(<?php echo $to_user?>, 0);" title="Remove <?php print $this->users_model->getPrintableName($to_user, 'first'); ?> from your circle."></span>
<?php endif; ?>
<?php endif; ?>
