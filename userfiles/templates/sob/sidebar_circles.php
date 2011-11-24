 <?php dbg(__FILE__); ?>
<?php $who = $this->core_model->getParamFromURL ( 'username' );  $who = $this->users_model->getIdByUsername($who); ?>
<?php $who = $this->users_model->getUserById($who); 
$which_is_the_sidebar_user = $who;
//var_dump($who );
 $query_options = array();

 $query_options ['order'] = array ('RAND()', ' ' );
		$query_options ['limit'] = 8;
		 
$circle = $this->users_model->realtionsGetFollowedIdsForUser(intval($who['id']), 'y', $query_options );
//var_dump($circle );


?>


<div class="tabs-holder tabs-circle">
  <h2 class="circle-title">Circle of influence</h2>
  <div class="tab" id="tabs-circle-of-influence">
    <div class="circle">
      <?php if (!empty($circle)) { ?>
      <?php $circle = array_pad($circle, 8, array(0=>'empty')); ?>
      
      <ul>
        <?php foreach ($circle as $user) { 
		if($user[0] != 'empty'){
		$user_data =  $this->users_model->getUserById($user);
		  $thumb = $this->users_model->getUserThumbnail( $user, 62);
		}
		//p($user_data);
		?>
        
        <li>
        <?php if($user[0] != 'empty'){ ?>
        <a style="background-image: url(<?php print $thumb ?>)" href="<?php print site_url('userbase/action:profile/username:'); ?><?php print $user_data['username']; ?>" class="IMGcircle title-tip" title="<?php echo $user_data['username'];?>">
           <span></span>

          </a>
          <?php } else { ?>
          
          
          <a style="background-color:#D9E8F2" class="IMGcircle">
           <span style="cursor: default"></span>

          </a>
          
           
           <?php } ?>
          </li>
        <?php } ?>
      </ul>
      <script type="text/javascript">
										//var circle_json = <?php echo $circle_ids_json; ?>;
									</script>
      <a href="javascript:;" onclick="mw.users.FollowingSystem.makeSpecial(<?php print  $who['id'] ?>);" 	class="circle-link">Add to my <br />
      circle of influence</a>
      <?php } else { ?>
      <a class="circle-link" style="width:240px;margin: -12px 0 0 -123px">No one in my circle of infuence yet.</a>
      <?php } ?>
    </div>
  </div>
</div>



<?php $query_options = array();
$query_options ['limit'] = 10;
$following = $this->users_model->realtionsGetFollowedIdsForUser(intval($who['id']), false, $query_options );

 
 ?>

<div class="c" style="padding-bottom: 15px;"></div>
<div class="tabs-holder tabs-circle follow">
  <ul class="tabs-nav">
    <li><a href="#tabs-following">Following</a></li>
    <li><a href="#tabs-followers">Followers</a></li>
  </ul>
  <div class="tab" id="tabs-following">
    <?php if ($following) { ?>

    <ul>
      <?php foreach ($following as $user) {
		  $user_data =  $this->users_model->getUserById($user);  
		  $thumb = $this->users_model->getUserThumbnail( $user, 50);
		  
		   ?>
      <li>

      <a href="<?php print site_url('userbase/action:profile/username:'); ?><?php print $user_data['username']; ?>" class="title-tip" title="<?php echo $user_data['username'];?>" style="background-image: url(<?php print $thumb; ?>);">&nbsp;</a> </li>
      <?php } ?>
    </ul>
    <?php } else { ?>
    <div class="no-follow">
    I am following no one!
    </div>
    <?php } ?>
  </div>
  
  
  
  
  
  <?php $query_options = array();
$query_options ['limit'] = 10;
$followers = $this->users_model->realtionsGetFollowersIdsForUser($aUserId = $who['id'], false, $query_options ); ?>

  <div class="tab" id="tabs-followers">
    <?php if ($followers) { ?>
    <ul>
      <?php foreach ($followers as $user) { 
	   $user_data =  $this->users_model->getUserById($user);  
	   $thumb = $this->users_model->getUserThumbnail( $user, 50);  
	  ?>
      <li>
        <a href="<?php print site_url('userbase/action:profile/username:'); ?><?php print $user_data['username']; ?>" class="title-tip" title="<?php echo $user_data['username'];?>" style="background-image: url(<?php print $thumb; ?>);">&nbsp;</a>
      </li>
      <?php } ?>
    </ul>
    <?php } else { ?>
    <div class="no-follow">
       Nobody is following me!
    </div>

    <?php } ?>
  </div>
</div>
 <?php dbg(__FILE__, 1); ?>