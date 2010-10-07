<!--Dashboard circle of influence page-->
<? require (ACTIVE_TEMPLATE_DIR.'dashboard/dashboard_sidebar.php') ?>

<div id="profile-main">
  <ul id="about-nav">
    <li><a href="<? print site_url('dashboard/'); ?>">dashboard</a></li>
    <li><a href="<? print site_url('dashboard/action:circle-of-influence'); ?>">circle of influEnce</a></li>
    <li class="active"><a href="<? print site_url('dashboard/action:following'); ?>">following</a></li>
    <li><a href="<? print site_url('dashboard/action:followers'); ?>">followers</a></li>
  </ul>

<ul>
<?php foreach ($following as $friend) {?>
	<li><?php echo $friend['first_name'].' '.$friend['last_name'];?></li>
<?php }?>
</ul>

</div><!-- /#profile-main -->