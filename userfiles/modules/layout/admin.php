<div class="mw_simple_tabs mw_tabs_layout_simple">
  <ul class="mw_simple_tabs_nav">
    <li><a href="javascript:;" class="active">Content list</a></li>
    <li><a href="javascript:;">Skin/Template</a></li>
  </ul>
  <div class="tab">
<?php 
$params['global'] = 1;


include_once($config['path_to_module'].'../posts/admin_live_edit_tab1.php'); ?>
  </div>
  <div class="tab">
   
 <?php $layout =  get_option('data-layout', $params['id']); ?>
 <input name="data-layout" id="data-layout-set-val"    class="mw_option_field semi_hidden" value="<?php print $layout; ?>" />
 <script>
$(document).ready(function(){

	mw.$(".dynamic_layout_choooser .list-elements li").click(function(){
	   $('#data-layout-set-val').val($(this).attr('data-module-name'));
	    $('#data-layout-set-val').trigger('change');
	});
 
});
</script>
 
 <div class="dynamic_layout_choooser">

  <microweber module="admin/modules/list_elements" layout_type="dynamic" />
  
  </div>
  </div>
  <div class="mw_clear"></div>
  <div class="vSpace"></div>
</div>

 
 