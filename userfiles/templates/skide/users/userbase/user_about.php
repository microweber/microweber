<?php dbg(__FILE__); ?>
<?php require (ACTIVE_TEMPLATE_DIR.'articles_read_top_profile_box_navigation.php') ?>
<?php $action = CI::model('core')->getParamFromURL ( 'action' ); ?>
<?php $who = CI::model('core')->getParamFromURL ( 'username' );  $who = CI::model('users')->getIdByUsername($who); ?>
<?php $user_about = CI::model('users')->getUserById($who);
 // p($user_about );
   ?>

<div class="dashboard-sidebar">
  <?php $thumb = CI::model('users')->getUserThumbnail( $author['id'], 205); ?>
  <?php if($thumb != ''): ?>
  <a style="float: none;margin: auto;" class="profile-image" href="<?php print site_url('userbase/action:profile/username:'.$author['username']); ?>">
    <img src="<?php print $thumb; ?>" alt="" />

  <span class="level level-1">&nbsp;</span> </a>
  <?php endif; ?>
  <div class="c" style="padding-bottom: 10px;">&nbsp;</div>
  <h2 class="profile-title">About:</h2>
  <div class="follow-box">
    <ul class="contact-list">
      <?php if(trim($user_about['country']) != ''): ?>
      <li><strong>Country:</strong><?php echo ($user_about['country']);  ?></li>
      <?php endif; ?>
      <?php if(trim($user_about['city']) != ''): ?>
      <li><strong>City:</strong><?php echo ($user_about['city']);  ?></li>
      <?php endif; ?>
      <?php if(trim($user_about['state']) != ''): ?>
      <li><strong>State:</strong><?php echo ($user_about['state']);  ?></li>
      <?php endif; ?>
      <?php if(trim($user_about['addr1']) != ''): ?>
      <li><strong>Address:</strong><?php echo ($user_about['addr1']);  ?></li>
      <?php endif; ?>
      <?php if(trim($user_about['zip']) != ''): ?>
      <li><strong>Zip:</strong><?php echo ($user_about['zip']);  ?></li>
      <?php endif; ?>
      <?php if(trim($user_about['addr2']) != ''): ?>
      <li><strong>Address:</strong><?php echo ($user_about['addr2']);  ?></li>
      <?php endif; ?>
      <?php if(trim($user_about['phone']) != ''): ?>
      <li><strong>Phone:</strong><?php echo ($user_about['phone']);  ?></li>
      <?php endif; ?>
      <?php $more = CI::model('core')->getCustomFields('table_users', $user_about['id']); ?>
      <?php if(!empty($more)): ?>
      <?php foreach($more as $k => $v): ?>
      <?php if(trim($v) != '')  : ?>
      <?php if(stristr($k, 'website_') != FALSE)  : ?>
      <li><strong>Website:</strong><a href="<?php print (prep_url(trim($v))); ?>"><?php print (trim($v)); ?></a></li>
      <?php endif; ?>
      <?php endif; ?>
      <?php endforeach; ?>
      <?php endif; ?>
    </ul>
  </div>
  <div style="padding-bottom: 10px;" class="c">&nbsp;</div>
  <?php $query_options = array();
 $query_options['limit']  = array(0,10);
$circle = CI::model('users')->realtionsGetFollowedIdsForUser($aUserId = $user_about['id'], $special = 'y', $query_options );
 
?>
  <h2 class="profile-title"><?php print CI::model('users')->getPrintableName (  $user_about['id'], 'first' ); ?>'s circle</h2>
  <div class="follow-box">
    <?php if (!empty($circle)) { ?>
    <ul>
      <?php foreach ($circle as $user) { 
		
		$user_data =  CI::model('users')->getUserById($user);  
		  $thumb = CI::model('users')->getUserThumbnail( $user, 50);
		//p($user_data);
		?>
      <li> <a class="title-tip" href="<?php print site_url('userbase/action:profile/username:'); ?><?php print $user_data['username']; ?>"  title="<?php echo $user_data['username'];?>" style="background-image: url('<?php print $thumb ?>');" > </a> </li>
      <?php } ?>
    </ul>
    <?php } else { ?>
    I dont have anybody in my circle of infuence yet.
    <?php } ?>
  </div>
  <h2 class="profile-title"><?php print CI::model('users')->getPrintableName (  $user_about['id'], 'first' ); ?> follows</h2>
  <div class="follow-box">
    <?php $query_options = array();
 $query_options['limit']  = array(0,10);
$circle = CI::model('users')->realtionsGetFollowedIdsForUser($aUserId = $user_about['id'], $special = 'n', $query_options );
 
?>
    <?php if (!empty($circle)) { ?>
    <ul>
      <?php foreach ($circle as $user) { 
		
		$user_data =  CI::model('users')->getUserById($user);  
		  $thumb = CI::model('users')->getUserThumbnail( $user, 50);
		//p($user_data);
		?>
      <li> <a class="title-tip" href="<?php print site_url('userbase/action:profile/username:'); ?><?php print $user_data['username']; ?>"  title="<?php echo $user_data['username'];?>" style="background-image: url('<?php print $thumb ?>');" > </a> </li>
      <?php } ?>
    </ul>
    <?php } else { ?>
    I dont follow anyone yet.
    <?php } ?>
  </div>
</div>
<div class="main">
  <?php //require (ACTIVE_TEMPLATE_DIR.'articles_read_top_profile_box_inner.php') ?>
  <h2 class="title" style="padding-left: 20px;"><?php print $action  ?> <?php print CI::model('users')->getPrintableName($user_about['id'], $mode = 'full'); ?></h2>
  <?php //print __FILE__ ?>
  <div class="pad">
    <div class="richtext">
      <?php if(trim($user_about['user_information']) == ''): ?>
      <div class="noposts"> <?php print CI::model('users')->getPrintableName($user_about['id'], $mode = 'full'); ?> doesn't have any info :( </div>
      <?php else : ?>
      <?php print html_entity_decode( $user_about['user_information']); ?>
      <?php endif; ?>
    </div>
  </div>
</div>
<div class="c">&nbsp;</div>
<?php dbg(__FILE__, 1); ?>
