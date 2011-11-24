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
    <a href="<?php print site_url('dashboard/action:messages'); ?>" class="btn btnhover right"><strong>See All Messages</strong></a>
	<a href="javascript:mw.users.UserMessage.selectAll();" class="btn btnhover right"><strong>Select All</strong></a>
    <a href="javascript:mw.users.UserMessage.deselectAll();" class="btn btnhover right"><strong>Deselect All</strong></a>
    <!--<a href="#" class="btn btnhover right"><strong>Sent</strong></a>
    <a href="#" class="btn btnhover right"><strong>Inbox</strong></a>
 --></div>
 
	<?php foreach ($messages as $message) { 
		require 'message_item.php';
	} ?>
     <div class="c" style="padding-bottom: 20px;">&nbsp;</div>
	<a href="javascript:mw.users.UserMessage.readSelected();" class="btn left"><strong>Mark as Read</strong></a> 
	<a href="javascript:mw.users.UserMessage.unreadSelected();" class="btn left"><strong>Mark as Unread</strong></a> 
	<a href="javascript:mw.users.UserMessage.deleteSelected();" class="btn left" style="margin-left: 30px;"><strong>Delete Marked</strong></a>

<!-- <a href="#" class="btn left"><strong>Mark as Read</strong></a>-->
<!-- <a href="#" class="btn left" style="margin-left: 30px;"><strong>Delate Marked</strong></a>-->

 <ul class="paging right" style="margin: 0">
    <li><span class="paging-label">Browse pages:</span></li>
    <li><a href="http://sob.dnsalias.net/business/curent_page:1" class="active">1</a></li>
    <li><a href="http://sob.dnsalias.net/business/curent_page:2">2</a></li>
    <li><a href="http://sob.dnsalias.net/business/curent_page:3">3</a></li>
  </ul>

<br /><br /><br />

	<form action="">
		<select id="selectMessageReceiver" onchange="$('#receiver').val($('#selectMessageReceiver option:selected').val());">
			<?php $friends = array_merge($following, $followers); ?>
			<?php foreach ($friends as $friend) {?>
				<option value="<?php echo $friend['id']; ?>"><?php echo $friend['first_name'].' '.$friend['last_name'];?></option>
			<?php }?>
		</select>
	</form>
	<?php require 'message_form.php';?>

</div>