<?php dbg(__FILE__); ?>
<!-- <?php print __FILE__ ?>  -->
<?php $user_id = $this->core_model->userId(); 
$current_user =  $this->users_model->getUserById($user_id);
?>



<div class="dashboard-sidebar">
  <div class="edit-image" onclick="window.location.href='<?php print site_url('users/user_action:profile#edit-profile-image'); ?>'">
    <?php $thumb = $this->users_model->getUserThumbnail( $current_user['id'], 205); ?>
    <img src="<?php print $thumb ?>" alt="" class="user-image-triger" />
    <div>
      <div>&nbsp;</div>
      <span onclick="window.location='<?php print site_url('users/user_action:profile#user_image'); ?>';">Change Picture</span> </div>
  </div>
  <a id="preview-profile" href="<?php print site_url('userbase/action:profile/username:'.$current_user['username']) ?>">Preview your profile</a>
  <div class="c" style="padding-bottom: 10px;">&nbsp;</div>
  <ul class="dashboard-sidenav" id="d-nav">
    <li class="ds_dashboard"><a href="<?php print site_url('dashboard'); ?>"><span class="Help">?<span>See the activity of your friends.</span></span>Dashboard</a></li>
    <li class="ds_message"><a href="<?php print site_url('dashboard/action:messages/show_inbox:1'); ?>">
    <span class="Help">?<span>You can manage all of your messages here.</span></span>
    Messages
      <?php $unread = $this->users_model->messagesGetUnreadCountForUser();
	  if ($unread) : ?>
      <strong>(<?php print $unread ?>)</strong>
      <?php endif; ?>
      </a></li>
    <li class="ds_notifications"><a href="<?php print site_url('dashboard/action:notifications'); ?>">
    <span class="Help">?<span>Real time notifications for the ongoing communication and activity <br />in your network appear in this section.</span></span>
    notifications
      <?php $unreded_notifications_count = $this->users_model->notificationsGetUnreadCountForUser($user_session ['user_id']);  ?>
      <?php if ($unreded_notifications_count) : ?>
      <strong>(<?php print $unreded_notifications_count ?>)</strong>
      <?php endif; ?>
      </a></li>
    <li class="ds_posts"><a href="<?php print site_url('users/user_action:posts/type:all'); ?>"><span class="Help">?<span>In this section you can review, edit and delete all of your content</span></span>My content</a></li>
    <li class="ds_addpost"><a href="<?php print site_url('users/user_action:post'); ?>"><span class="Help">?<span>Publish new content.</span></span>Publish</a></li>
    <li class="ds_sidebar_manager"><a href="<?php print site_url('users/user_action:sidebar-manager'); ?>">
    <span class="Help">?<span>Embed a video, a picture, an external link or any other HTML code</span></span>
    Sidebar manager</a></li>

    
    
    <li class="ds_settings"><a href="<?php print site_url('users/user_action:profile'); ?>">
    <span class="Help">?<span>Edit your personal information</span></span>
    Profile settings</a></li>
  </ul>

<script type="text/javascript">
    var u = window.location.href;
    var dlinks = document.getElementById('d-nav').getElementsByTagName('a');
    for(var i=0;i<dlinks.length;i++){
      if(dlinks[i].href == u){
        dlinks[i].className = 'active';
      }
    }
</script>


  <h2 class="profile-title">You follow</h2>
  <div class="follow-box">
    <ul>
      <?php $relations_options = array ();
		$relations_options ['order'] = array ('RAND()', ' ' );
		$relations_options ['limit'] = 12;
	$followed_ids = $this->users_model->realtionsGetFollowedIdsForUser ( $this->core_model->userId(), 'n', $relations_options );
	
	
	 ?>
      <?php if(empty($followed_ids)): ?>
      <li>You don't follow anyone.</li>
      <?php else: ?>
      <?php foreach($followed_ids as $item): ?>
      <?php $user = $this->users_model->getUserById($item);
		 $thumb = $this->users_model->getUserThumbnail( $user['id'], 50); ?>
      <li> <a class="title-tip" title="<?php print $user['username']; ?>" href="<?php print site_url('userbase/action:profile/username:'.$user['username']); ?>" style="background-image:url(<?php print $thumb; ?>)"></a> </li>
      <?php endforeach; ?>
      <?php endif; ?>
    </ul>
    <?php if(!empty($followed_ids)): ?>
    <a href="<?php print site_url('dashboard/action:following'); ?>" class="btn right">See All</a>
    <?php endif; ?>
  </div>
  <h2 class="profile-title">Your circle</h2>
  <div class="follow-box">
    <ul>
      <?php $relations_options = array ();
		$relations_options ['order'] = array ('RAND()', ' ' );
		$relations_options ['limit'] = 12;
 
	
	$circleOfInfluenceIds = $this->users_model->realtionsGetFollowedIdsForUser ( $this->core_model->userId(), 'y', $relations_options );
		if (! empty ( $circleOfInfluenceIds )) {
			shuffle ( $circleOfInfluenceIds );
			}
	
	
	
	
	
	 ?>
      <?php if(empty($circleOfInfluenceIds)): ?>
      <li>Nobody is in your circle of influence.</li>
      <?php else: ?>
      <?php foreach($circleOfInfluenceIds as $item): ?>
      <?php $user = $this->users_model->getUserById($item);
		 $thumb = $this->users_model->getUserThumbnail( $user['id'], 50); ?>
      <li> <a class="title-tip" title="<?php print $user['username']; ?>" href="<?php print site_url('userbase/action:profile/username:'.$user['username']); ?>" style="background-image:url(<?php print $thumb; ?>)"></a> </li>
      <?php endforeach; ?>
      <?php endif; ?>
    </ul>
    <?php if(!empty($circleOfInfluenceIds)): ?>
    <a href="<?php print site_url('dashboard/action:circle-of-influence'); ?>" class="btn right">See All</a>
    <?php endif; ?>
  </div>
  <h2 class="profile-title">Followers</h2>
  <div class="follow-box">
    <ul>
      <?php $relations_options = array ();
		$relations_options ['order'] = array ('RAND()', ' ' );
		$relations_options ['limit'] = 12;
		$relations = $this->users_model->realtionsGetFollowersIdsForUser (  $this->core_model->userId(), false, $relations_options );
		if (! empty ( $relations )) {
			shuffle ( $relations );
			$followers_ids = $relations;
		}



?>
      <?php if(empty($followers_ids)): ?>
      <li>Nobody is following you.</li>
      <?php else: ?>
      <?php foreach($followers_ids as $item): ?>
      <?php $user = $this->users_model->getUserById($item);
		 $thumb = $this->users_model->getUserThumbnail( $user['id'], 50); ?>
      <li> <a class="title-tip" title="<?php print $user['username']; ?>" href="<?php print site_url('userbase/action:profile/username:'.$user['username']); ?>" style="background-image:url(<?php print $thumb; ?>)"></a> </li>
      <?php endforeach; ?>
      <?php endif; ?>
    </ul>
    <?php if(!empty($followers_ids)): ?>
    <a href="<?php print site_url('dashboard/action:following'); ?>" class="btn right">See All</a>
    <?php endif; ?>
  </div>
</div>
<!-- /.dashboard-sidebar -->
<?php dbg(__FILE__, 1); ?>
