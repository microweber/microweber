<div>
	<? $thumb = $this->users_model->getUserThumbnail($notification['created_by'], 80); ?>
	<? $author = $this->users_model->getUserById($notification['created_by']); ?>
  
  <div>
  	<img src="<?php echo $thumb;?>" />
    <h2><a href="<? print site_url('userbase/action:profile/username:'); ?><? print $author['username']; ?>"><? print $author['username']; ?></a></h2>
    <p>
  		<?php echo $notification['message'];?>    
    </p>
  </div>
</div>
