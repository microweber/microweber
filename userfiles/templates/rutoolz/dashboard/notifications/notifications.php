<? require (ACTIVE_TEMPLATE_DIR.'dashboard/dashboard_sidebar.php') ?>

<div id="profile-main">
  <ul id="about-nav">
    <li class="active"><a href="<? print site_url('dashboard/'); ?>">dashboard</a></li>
    <li><a href="<? print site_url('dashboard/action:circle-of-influence'); ?>">circle of influEnce</a></li>
    <li><a href="<? print site_url('dashboard/action:following'); ?>">following</a></li>
    <li><a href="<? print site_url('dashboard/action:followers'); ?>">followers</a></li>
  </ul>


<ul>
<?php foreach($notifications as $notification) { 
	include "activity_item.php";
	echo "<br /><br /><br />";
} ?>
</ul>





 <div class="notification new-notification" style="margin-bottom: 30px;">
    <a href="#" class="img">

    </a>
    <div class="notification-content">
        <h3><a href="#">Nikita Mihalkov</a></h3>
        <span class="date inline left">Wensday 19:45</span>
        <span class="msg">commented on your <a href="#">status</a></span>
    </div>
    <div class="box-ico-holder" style="width: 130px">
      <span class="box-ico box-ico-msg title-tip" title="Send message"></span>
      <span class="box-ico box-ico-follow title-tip" title="Follow Dragomir Ivanov"></span>
      <span class="box-ico box-ico-unfollow title-tip" title="Unollow Dragomir Ivanov"></span>
      <span class="box-ico box-ico-c title-tip" title="Lorem ipsum sit amet"></span>
    </div>




 </div>







</div>















