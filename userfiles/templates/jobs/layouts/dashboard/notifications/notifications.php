<?php dbg(__FILE__); ?>
<?php require (ACTIVE_TEMPLATE_DIR.'dashboard/dashboard_sidebar.php') ?>

<div id="profile-main">
 <a class="master-help right" href="#help-notifications">What is this?</a>
     <h2 class="title inline left">Notifications</h2>
     <div class="c" style="padding-bottom: 10px">&nbsp;</div>

     <div class="master-help" id="help-notifications">
      <p>Real time notifications for the ongoing communication and activity in your network appear in this section. </p>
      <p>If you wish to see the most recent activity displayed on your Notification Board you must refresh the webpage. You can easily do that by clicking on the "Refresh" button of your browser or by hitting the F5 key on your keyboard.  </p>
     </div>


<?php if (!empty($notifications)) { 
	foreach($notifications as $notification) { 
		include "activity_item.php";
	}
	
	require (ACTIVE_TEMPLATE_DIR.'articles_paging.php') ;
    
	
} else {?>
	<div class="noposts">You have no new notifications</div>
<?php } ?>


</div>
<?php dbg(__FILE__, 1); ?> 
