<?php only_admin_access(); ?>
<script  type="text/javascript">
    mw.require("<?php print $config['url_to_module']; ?>export.js");
</script>

<script type="text/javascript">
$(document).ready(function(){


});
</script>

<style>
label {
font-size:16px;
}
</style>

<div class="mw-ui-row admin-section-bar">
    <div class="mw-ui-col">
        <h2><?php _e("Export Content"); ?></h2>
    </div>
</div>
<div class="admin-side-content">
    <div class="mw-module-admin-wrap">
<div id="mw_export_to_page_holder">
  <?php $all_pages = get_pages(); ?>
  <?php if(!empty($all_pages)): ?>
   <h3><?php _e("Select a content wich want to export"); ?>:</h3>
    
    <br />
    <h4>Webiste</h4>
    <label><input type="checkbox" name="export_pages" value="1"> <?php _e("Pages"); ?></label> <br />
    <label><input type="checkbox" name="export_posts" value="1"> <?php _e("Posts"); ?></label><br />
    <label><input type="checkbox" name="export_categories" value="1"> <?php _e("Categories"); ?></label><br />
    
     
    <br />
    <h4>Shop</h4>
    <label><input type="checkbox" name="export_products" value="1"> <?php _e("Products"); ?></label> <br />
    <label><input type="checkbox" name="export_orders" value="1"> <?php _e("Orders"); ?></label><br />
    <label><input type="checkbox" name="export_clients" value="1"> <?php _e("Clients"); ?></label><br />
    <label><input type="checkbox" name="export_coupons" value="1"> <?php _e("Coupons"); ?></label><br />
  
  <?php endif; ?>
 	<hr />
  <button onclick="mw.ok_export_file()" class="mw-ui-btn">
  <?php _e("Start export"); ?>
  </button>
  
</div>


  
  </div>


<module type="admin/export/manage" />
</div>