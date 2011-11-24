<?php require (ACTIVE_TEMPLATE_DIR.'dashboard/dashboard_sidebar.php') ?>



<div id="profile-main">
  <div id="notification-bar">
  <?php require (ACTIVE_TEMPLATE_DIR.'dashboard/messages/messages_nav.php') ?>
  <br />

   <a class="master-help right" href="#help-messages">What is this?</a>
    <h2 class="title inline left">Messages</h2>
    <div class="c"></div>
    <div class="master-help" id="help-messages"> You can manage all of your messages here. You can review, reply and delete any communication with your followers and friends. </div>
    <?php if (!empty($conversations)) : ?>
    <?php if ($unreded_messages_count) { ?>
    <a href="<?php print site_url('dashboard/action:messages/unreaded:1'); ?>" class="btn btnhover right"><strong><?php echo $unreded_messages_count; ?> unreaded messages</strong></a>
    <?php } ?>
    <a href="javascript:mw.users.UserMessage.selectAll();" class="btn btnhover right"><strong>Select All</strong></a> <a href="javascript:mw.users.UserMessage.deselectAll();" class="btn btnhover right"><strong>Deselect All</strong></a><!--
    <a href="#" class="btn btnhover right"><strong>Sent</strong></a>
    <a href="#" class="btn btnhover right"><strong>Inbox</strong></a>
 --></div>
  <?php foreach ($conversations as $message) {
		require 'message_item.php';
	} ?>
    <div class="c" style="padding-bottom: 20px;">&nbsp;</div>
  <a href="javascript:mw.users.UserMessage.readSelected();" class="btn left" style="margin-right: 15px;"><strong>Mark as Read</strong></a> <a href="javascript:mw.users.UserMessage.unreadSelected();" class="btn left" style="margin-right: 15px;"><strong>Mark as Unread</strong></a> <a href="javascript:mw.users.UserMessage.deleteSelected();" class="btn left"><strong>Delete Marked</strong></a>
  <?php else: ?>
  <div class="c">&nbsp;</div>
  <div class="no-posts">You don't have any messages.</div>
  <?php endif; ?>
  
  <!-- <ul class="paging right" style="margin: 0">
    <li><span class="paging-label">Browse pages:</span></li>
    <li><a href="http://sob.dnsalias.net/business/curent_page:1" class="active">1</a></li>
    <li><a href="http://sob.dnsalias.net/business/curent_page:2">2</a></li>
    <li><a href="http://sob.dnsalias.net/business/curent_page:3">3</a></li>
  </ul>-->
  
  <br />
  <br />
  <br />
 <?php require (ACTIVE_TEMPLATE_DIR.'articles_paging.php') ; ?>
  <!--	<form action="">
		<select id="selectMessageReceiver" onchange="$('#receiver').val($('#selectMessageReceiver option:selected').val());">
			<?php $friends = array_merge($following, $followers); ?>
			<?php foreach ($friends as $friend) {?>
				<option value="<?php echo $friend['id']; ?>"><?php echo $friend['first_name'].' '.$friend['last_name'];?></option>
			<?php }?>
		</select>
	</form>-->
  <?php // require 'message_form.php';?>
</div>
