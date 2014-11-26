<?php if(!isset($_REQUEST['no_toolbar'])): ?>
<?php
 $last_page_front =   session_get('last_content_id');
 
 if( $last_page_front == false){
	  if(isset($_COOKIE['last_page'])){
		  $last_page_front = $_COOKIE['last_page'];
	  }
 }

  if( $last_page_front  != false){
   // $past_page = site_url($last_page_front);
    
        $cont_by_url = mw()->content_manager->get_by_id($last_page_front , true);

    if(isset($cont_by_url) and $cont_by_url == false){
        $past_page=get_content("order_by=updated_on desc&limit=1");
        $past_page = mw()->content_manager->link($past_page[0]['id']);
    } else {
		 $past_page = mw()->content_manager->link($last_page_front);
	}

  }
  else {
  	$past_page=get_content("order_by=updated_on desc&limit=1");
      $past_page = mw()->content_manager->link($past_page[0]['id']);
 
  }
  
 ?>

<div class="mw-v-table" id="mw_toolbar_nav">
    <a class="mw-cube-holder " id="mw-admin-toolbar-cube" href="<?php print admin_url(); ?>view:content">
        <span class="mw-cube">  <span class="mw-cube1"><span id="mw_toolbar_logo" href="<?php print admin_url(); ?>"></span></span> </span> </a>
	<div class="mw-v-cell" style="width: 100%">
		<?php if(is_admin()): ?>
		<?php   $active = mw('url')->param('view'); ?>
		<ul id="mw_tabs">
			<li <?php if($active == 'dashboard' or $active == false): ?>class="active"<?php endif; ?>><a href="<?php print admin_url(); ?>view:dashboard" title="<?php _e("Dashboard"); ?>"><i class="ico inavdashboard"></i><span>
				<?php _e("Dashboard"); ?>
				</span></a></li>
			<li <?php if($active == 'content'): ?> class="active" id="mw-admin-nav-website" <?php endif; ?>><a href="<?php print admin_url(); ?>view:content" title="<?php _e("Website"); ?>"><i class="ico inavwevsite"></i><span>
				<?php _e("Website"); ?>
				</span></a></li>
			<?php event_trigger('admin_header_menu_start'); ?>
			<li <?php if($active == 'modules'): ?> class="active" <?php endif; ?>><a href="<?php print admin_url(); ?>view:modules" title="<?php _e("Modules"); ?>"><i class="ico inavmodules"></i><span>
				<?php _e("Modules"); ?>
				</span></a></li>
			<?php event_trigger('admin_header_menu'); ?>
			<li <?php if($active == 'users'): ?> class="active" <?php endif; ?>><a href="<?php print admin_url(); ?>view:users" title="<?php _e("Users"); ?>"><i class="ico inavusers"></i><span>
				<?php _e("Users"); ?>
				</span></a></li>
			<?php event_trigger('admin_header_menu_end'); ?>
		</ul>
	</div>
	<div class="mw-v-cell">
		<div id="mw-admin-toolbar-right"> <a title="<?php _e("Logout"); ?>" class="ico ilogout"  href="<?php print mw('url')->api_link('logout'); ?>"><span></span></a> <a title="<?php _e("Go Live Edit"); ?>" id="mw-go_livebtn_admin" class="mw-ui-btn mw-ui-btn-blue right back-to-admin-cookie mw-admin-go-live-now-btn" href="<?php print $past_page; ?>?editmode=y"><span class="ico ilive"></span>
			<?php _e("Go Live Edit"); ?>
			</a>
			<div class="mw-toolbar-notification">
				<module type="admin/notifications" view="toolbar"  limit="5" id="mw-header-notif" />
			</div>
			<a href="javascript:;" id="helpbtn" class="ico ihelpico right" onclick="mw.helpinfo.init(true);" title="<?php _e("Help"); ?>"></a> </div>
	</div>
</div>
<script type="text/javascript">
   try{
	   var tabaddclass = mwd.querySelector('#mw_tabs li.active');
	   if(tabaddclass.length > 0){
		   tabaddclass.previousElementSibling.className = 'active-prev';
	   }
    
  }catch(e){}

  $(document).ready(function() {
       var navli = mw.$("#mw_tabs li");
       navli.click(function(){
         if(!$(this).hasClass('active')){
         navli.removeClass('active');
         $(this).addClass('active');}
       });
	   
	   var nav_go_live_btns = mw.$(".mw-admin-go-live-now-btn");
       nav_go_live_btns.click(function(e){

		var go_cont = $(this).attr('content-id');
		if(go_cont != undefined && go_cont != null){
			if(go_cont == 0){
			    return;
			}
			 $.get('<?php print site_url('api_html/content_link/?id=') ?>'+go_cont, function(data) {
    			 if(mw.notification != undefined){
    			    mw.notification.success('Going to live edit...', 1000);
    			 }
				 window.top.location.href = data+'/editmode:y';
    		 });
			mw.e.cancel(e,true);
		}
		

		//}
       });
	   
	   
});
</script>
<?php endif; ?>
</div>
<?php endif; ?>
<div class="mw_clear" style="height: 0;">&nbsp;</div>
<div id="mw-admin-content">
