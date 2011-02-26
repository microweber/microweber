<?php require (ACTIVE_TEMPLATE_DIR.'dashboard/dashboard_sidebar.php') ?>

<div id="profile-main">
  <ul id="about-nav">
    <li class="active"><a href="<?php print site_url('dashboard/'); ?>">dashboard</a></li>
    <li><a href="<?php print site_url('dashboard/action:circle-of-influence'); ?>">circle of influEnce</a></li>
    <li><a href="<?php print site_url('dashboard/action:following'); ?>">following</a></li>
    <li><a href="<?php print site_url('dashboard/action:followers'); ?>">followers</a></li>
  </ul>

 <div id="notification-bar">
    <h2 class="title inline left">Messages</h2>
    <a href="<?php print site_url('dashboard/action:messages/show_sent:1'); ?>" class="btn btnhover right"><strong>Sent</strong></a>
    <a href="<?php print site_url('dashboard/action:messages/show_inbox:1'); ?>" class="btn btnhover right"><strong>Inbox</strong></a>
    <a href="javascript:mw.users.UserMessage.selectAll();" class="btn btnhover right"><strong>Select All</strong></a>
    <a href="javascript:mw.users.UserMessage.deselectAll();" class="btn btnhover right"><strong>Deselect All</strong></a>
    <a href="javascript:mw.users.UserMessage.compose();" class="btn btnhover right"><strong>Compose</strong></a>
 </div>

	<?php foreach ($messages as $message) {
		require 'message.php';
	} ?>

 <div class="checkbar" style="margin-top: -22px">
    <strong>Choose:</strong>
    <a href="#" onclick="$('.notification input[type=checkbox]').check()">Select All</a>,&nbsp;
    <a href="#" onclick="$('.notification input[type=checkbox]').uncheck()">Select None</a>
 </div>
 <div class='c' style="padding-bottom: 20px;">&nbsp;</div>

 <a href="javascript:mw.users.UserMessage.readSelected();" class="btn left"><strong>Mark as Read</strong></a>
 <a href="javascript:mw.users.UserMessage.unreadSelected();" class="btn left"><strong>Mark as Unread</strong></a>
 <a href="javascript:mw.users.UserMessage.deleteSelected();" class="btn left" style="margin-left: 30px;"><strong>Delete Marked</strong></a>

 <?php require ACTIVE_TEMPLATE_DIR.'dashboard/pagination.php'; ?>

</div>