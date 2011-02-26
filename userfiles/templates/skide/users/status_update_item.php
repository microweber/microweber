<div>
	<?php $thumb = CI::model('users')->getUserThumbnail($status_update['created_by'], 80); ?>
	<?php $author = CI::model('users')->getUserById($status_update['created_by']); ?>
  
  <div>
    <h2><a href="<?php print site_url('userbase/action:profile/username:'); ?><?php print $author['username']; ?>"><?php print $author['username']; ?></a></h2>
    <p>
  		<?php echo $status_update['message'];?>    
    </p>
  </div>
</div>
