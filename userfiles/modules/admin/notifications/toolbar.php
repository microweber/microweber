<?php
only_admin_access();
$notif_params = $params;

if(isset($notif_params['id'])){
	unset($notif_params['id']);
}
if(isset($notif_params['module'])){
	unset($notif_params['module']);
}
if(!isset($notif_params['wrapper-id'])){
	 $notif_params['wrapper-id'] = "mw-admin-toolbar-notif-list";
}
if(!isset($notif_params['quick'])){
	 $notif_params['quick'] =true;
}
 ?>
<?php $notif_count = mw()->notifications_manager->get('is_read=0&count=1'); ?>

<span class="mw-ui-btn mw-btn-single-ico mw-ui-btn-hover mw-ui-btn-hover-white<?php if( $notif_count == 0): ?> notif-faded<?php endif; ?>"> <span class="ico inotification" id="toolbar_notifications">
<?php if( $notif_count > 0): ?>
<sup class="mw-notif-bubble"><?php print  $notif_count ?></sup>

<?php endif; ?>
<script>
 $(document).ready(function() {
	  mw.$("#toolbar_notifications").click(function(){
		 var el = $(this.parentNode);
		if(el.hasClass("active")){
		   el.removeClass("active");
		   mw.$(".mw-toolbar-notif-items-wrap").invisible();
		}
		else{
		   el.addClass("active");
		   mw.$(".mw-toolbar-notif-items-wrap").visible();
		}
		$(mwd.body).click(function(e){
		  if(!mw.tools.hasParentsWithClass(e.target, 'mw-toolbar-notification')){
			  var toolbar_notifications = $(mwd.getElementById('toolbar_notifications').parentNode);
			  if(toolbar_notifications.hasClass("active")){
				 toolbar_notifications.removeClass("active");
				 mw.$(".mw-toolbar-notif-items-wrap").invisible();
			  }
		  }
		});
	  });
  
  
  });
 <?php if( $notif_count > 0): ?> 
 mw.tools.fav( <?php if($notif_count < 100 ) { print $notif_count; } else { print "99+"; }; ?> );
 <?php endif; ?>
 </script>
</span> </span>
<div class="mw-toolbar-notif-items-wrap">
	 
	
		<?php include(__DIR__.DS.'index.php'); ?>
	
	<a  class="mw-ui-link sell-all-notifications" href="<?php print admin_url('view:admin__notifications'); ?>">
	<?php _e("See all"); ?>
	</a></div>
