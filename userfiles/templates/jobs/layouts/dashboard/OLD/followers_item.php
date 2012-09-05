<?php dbg(__FILE__); ?>
<?php $friend_data = CI::model ( 'users' )->getUserById($friend); ?>



 <div id="friendItem-<?php echo $friend_data['id']?>" class="notification new-notification" style="margin-bottom: 0px;">


	<?php $thumb = CI::model ( 'users' )->getUserThumbnail( $friend_data['id'], 80);  ?>
	<a href="<?php echo site_url('userbase/action:profile/username:'.$friend_data['username']); ?>" class="img"  <?php if($thumb != ''): ?>style="background-image:url('<?php print $thumb; ?>')"; <?php endif; ?>></a>
    
    <div class="notification-content"    >
        <div class="notification-name">

              <h3><a href="<?php echo site_url('userbase/action:profile/username:'.$friend_data['username']); ?>"><?php print CI::model ( 'users' )->getPrintableName($friend_data['id'], 'first'); ?></a></h3>
            <span class="date inline left"><?php echo ($friend_data['username']);?></span>
        </div>
         
    </div>
    <div class="box-ico-holder" style="width: 105px;margin:10px 50px 0 0 ;">
      <a href="javascript:mw.users.UserMessage.compose(<?php echo $friend_data['id']; ?>);">
      	<span class="box-ico box-ico-reply title-tip" title="Send message to <?php print CI::model ( 'users' )->getPrintableName($friend_data['id'], 'first'); ?>"></span>
      </a>
      <?php
	  
	  if($friend['from_user'] != $current_user['id']): ?>
	    
	      <?php if (CI::model ( 'users' )->realtionsCheckIfUserIsFollowedByUser(false,$friend_data['id'] ) == false) { ?>
	      	<span class="title-tip box-ico box-ico-follow" onclick="mw.users.FollowingSystem.follow(<?php echo $friend_data['id']?>);" title="Follow <?php print CI::model ( 'users' )->getPrintableName($friend_data['id'], 'first'); ?>">&nbsp;</span>
	      <?php } ?>
	      <?php if (CI::model ( 'users' )->realtionsCheckIfUserIsFollowedByUser(false,$friend_data['id'], true) == false) { ?>
			<span class="title-tip box-ico box-ico-c" onclick="mw.users.FollowingSystem.follow(<?php echo $friend_data['id']?>, 1);" title="Add <?php print CI::model ( 'users' )->getPrintableName($friend_data['id'], 'first'); ?> to your cicle of influence.">&nbsp;</span>
	      <?php } else { ?>
          <span class="title-tip box-ico box-ico-c" onclick="mw.users.FollowingSystem.follow(<?php echo $friend_data['id']?>, 0);" title="Remove <?php print CI::model ( 'users' )->getPrintableName($friend_data['id'], 'first'); ?> from your cicle of influence.">&nbsp;</span>
          <?php } ?>
           <?php endif; ?>
          <span title="Unfollow <?php print CI::model ( 'users' )->getPrintableName($friend_data['id'], 'first'); ?>" class="box-ico box-ico-unfollow title-tip" onclick="mw.users.FollowingSystem.unfollow('<?php echo $friend_data['id']; ?>', 'friendItem-<?php echo $friend_data['id']?>')">&nbsp;</span>

    </div>



</div>

<?php dbg(__FILE__, 1); ?>