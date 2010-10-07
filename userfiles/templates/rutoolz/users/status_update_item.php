<div>
	<? $thumb = $this->users_model->getUserThumbnail($status_update['created_by'], 80); ?>
	<? $author = $this->users_model->getUserById($status_update['created_by']); ?>
  
  <div>
    <h2><a href="<? print site_url('userbase/action:profile/username:'); ?><? print $author['username']; ?>"><? print $author['username']; ?></a></h2>
    <p>
  		<?php echo $status_update['message'];?>    
    </p>
  </div>
</div>
