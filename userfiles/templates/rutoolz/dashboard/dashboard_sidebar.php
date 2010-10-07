<div class="dashboard-sidebar">
	<div class="edit-image">
		<? $thumb = $this->users_model->getUserThumbnail( $current_user['id'], 204); ?>
		<img src="<? print $thumb ?>" alt="" />
    	<div>
        	<div>&nbsp;</div>
        	<span onclick="window.location='<? //print site_url('users/user_action:profile#user_image'); ?>';">Change PIcture</span>
        </div>
    </div>

    <ul class="dashboard-sidenav">
    
      <li class="ds_posts"><a href="<? print site_url('users/user_action:posts'); ?>">Pages</a></li>
      <li class="ds_addpost"><a href="<? print site_url('users/user_action:post'); ?>">Add Pages</a></li>
      <li class="ds_settings"><a href="<? print site_url('users/user_action:profile'); ?>">Settings</a></li>
    </ul>

    
    
</div><!-- /.dashboard-sidebar -->
