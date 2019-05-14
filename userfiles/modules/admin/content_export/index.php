<?php only_admin_access(); ?>

<script type="text/javascript">
$(document).ready(function(){

	$('.js-mw-content-export-form').submit(function(e) {
		e.preventDefault();
		var formData = $(this).serialize();
		
		 mw.notification.success("Export started...");
		 $.post(mw.settings.api_url+'content_export_start', formData , function(data) {

	 		mw.notification.success(data.success);
		    window.location = mw.settings.api_url + 'content_export_download?filename=' + data.filename;
		 	
		});
		
	});
		
});
</script>

<style>
label {
    font-size:16px;
}
</style>

<div class="mw-ui-row admin-section-bar">
    <div class="mw-ui-col">
        <h2><?php _e("Content Export"); ?></h2>
    </div>
</div>
<div class="admin-side-content">
 <div class="mw-module-admin-wrap">
<div id="mw_export_to_page_holder">
	<form class="js-mw-content-export-form">
    <h3><?php _e("Select a content wich want to export"); ?>:</h3>
    <br />
    <h4><?php _e("Website"); ?></h4>
    <label><input type="checkbox" name="export_pages" checked="checked" value="1"> <?php _e("Pages"); ?></label> <br />
    <label><input type="checkbox" name="export_posts" checked="checked" value="1"> <?php _e("Posts"); ?></label><br />
     <label><input type="checkbox" name="export_comments" checked="checked" value="1"> <?php _e("Comments"); ?></label><br />
    <label><input type="checkbox" name="export_categories" checked="checked" value="1"> <?php _e("Categories"); ?></label><br />
    
     
    <br />
    <h4> <?php _e("Shop"); ?></h4>
    <label><input type="checkbox" name="export_products" checked="checked" value="1"> <?php _e("Products"); ?></label> <br />
    <label><input type="checkbox" name="export_orders" checked="checked" value="1"> <?php _e("Orders"); ?></label><br />
    <label><input type="checkbox" name="export_clients" checked="checked" value="1"> <?php _e("Clients"); ?></label><br />
    <label><input type="checkbox" name="export_coupons" checked="checked" value="1"> <?php _e("Coupons"); ?></label><br />

 	<hr />
  <button type="submit" class="mw-ui-btn">
  <?php _e("Start export"); ?>
  </button>
  </form>
</div>
 </div>
</div>