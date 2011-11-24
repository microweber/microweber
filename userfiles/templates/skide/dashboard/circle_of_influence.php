<?php dbg(__FILE__); ?>
<!--Dashboard circle of influence page-->
<?php require (ACTIVE_TEMPLATE_DIR.'dashboard/dashboard_sidebar.php') ?>

<div id="profile-main">
  <ul id="about-nav">
	<li><a href="<?php print site_url('dashboard/'); ?>">dashboard</a></li>
    <li class="active"><a href="<?php print site_url('dashboard/action:circle-of-influence'); ?>">circle of influEnce</a></li>
    <li><a href="<?php print site_url('dashboard/action:following'); ?>">following</a></li>
    <li><a href="<?php print site_url('dashboard/action:followers'); ?>">followers</a></li>
  </ul>

<?php if (!empty($circle_of_influence)) { ?>
	<ul>
	<?php foreach ($circle_of_influence as $friend) {?>
		<li><?php echo $friend['first_name'].' '.$friend['last_name'];?></li>
	<?php }?>
	</ul>
<?php } else { ?>
No people in your circle of influence.
<?php }?>

</div><!-- /#profile-main -->
<?php dbg(__FILE__, 1); ?>