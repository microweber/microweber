<?php require (ACTIVE_TEMPLATE_DIR.'dashboard/dashboard_sidebar.php') ?>

<div id="profile-main">
  <!--<ul id="about-nav">
    <li class="active"><a href="<?php print site_url('dashboard/'); ?>">dashboard</a></li>
    <li><a href="<?php print site_url('dashboard/action:circle-of-influence'); ?>">circle of influEnce</a></li>
    <li><a href="<?php print site_url('dashboard/action:following'); ?>">following</a></li>
    <li><a href="<?php print site_url('dashboard/action:followers'); ?>">followers</a></li>
  </ul>-->
  
  <div id="notification-bar">
    <?php require (ACTIVE_TEMPLATE_DIR.'dashboard/messages/messages_nav.php') ?>
 </div>
  
  <?php $your_id = $this->users_model->userId ();
 
 if(intval($your_id ) == intval($messages[0]['from_user'])){
	 $between1 = 'you';
	  $between2 = $this->users_model->getPrintableName(intval($messages[0]['to_user']), 'first'); 
	   } else {
		   
		   	 $between2 = 'you';
	  $between1 = $this->users_model->getPrintableName(intval($messages[0]['to_user']), 'first'); 
	 
 }
 
 
   
  
  ?>
  
  <h2>Converstion between <?php print $between1 ?> and <?php print $between2 ?></h2><br />

  
  

  
<?php if(!empty($messages)): ?>
  
<?php foreach ($messages as $message) { ?>

<?php require  ACTIVE_TEMPLATE_DIR.'dashboard/messages/message_item.php';?>
<?php } ?>

<div class="c" style="padding-bottom: 20px">&nbsp;</div>

 <a href="javascript:mw.users.UserMessage.readSelected();" class="btn left" style="margin-right: 15px;"><strong>Mark as Read</strong></a>
 <a href="javascript:mw.users.UserMessage.unreadSelected();" class="btn left" style="margin-right: 15px;"><strong>Mark as Unread</strong></a>
 <a href="javascript:mw.users.UserMessage.deleteSelected();" class="btn left"><strong>Delete Marked</strong></a>

<br><br>

<a name="message_form"><!--  --></a>
<?php $reply_txt = true ;?>
<?php require ACTIVE_TEMPLATE_DIR.'dashboard/messages/message_form.php';?>

<?php endif; ?>
</div>