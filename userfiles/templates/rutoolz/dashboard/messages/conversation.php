<? require (ACTIVE_TEMPLATE_DIR.'dashboard/dashboard_sidebar.php') ?>

<div id="profile-main">
  <ul id="about-nav">
    <li class="active"><a href="<? print site_url('dashboard/'); ?>">dashboard</a></li>
    <li><a href="<? print site_url('dashboard/action:circle-of-influence'); ?>">circle of influEnce</a></li>
    <li><a href="<? print site_url('dashboard/action:following'); ?>">following</a></li>
    <li><a href="<? print site_url('dashboard/action:followers'); ?>">followers</a></li>
  </ul>
  
  <div id="notification-bar">
    <h2 class="title inline left"><?php echo $messages[0]['subject']?></h2>
    <a href="#" class="btn btnhover right"><strong>Sent</strong></a>
    <a href="#" class="btn btnhover right"><strong>Inbox</strong></a>
 </div>
  
<?php foreach ($messages as $message) { ?>
<?php require 'message_item.php';?>
<?php } ?>

 <a href="javascript:UserMessage.readSelected();" class="btn left"><strong>Mark as Read</strong></a> 
 <a href="javascript:UserMessage.unreadSelected();" class="btn left"><strong>Mark as Unread</strong></a> 
 <a href="javascript:UserMessage.deleteSelected();" class="btn left" style="margin-left: 30px;"><strong>Delete Marked</strong></a>

<br><br>

<a name="message_form"><!--  --></a>
<?php require 'message_form.php';?>

</div>