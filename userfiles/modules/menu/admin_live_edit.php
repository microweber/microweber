<?php //include_once($config['path_to_module'].'functions.php'); ?>
<?php  
  $rand = crc32(serialize($params));
  
  $menu_name = get_option('menu_name', $params['id']);
  if($menu_name == false and isset($params['menu_name'])){
	  $menu_name = $params['menu_name'];
  } elseif($menu_name == false and isset($params['name'])){
	  $menu_name = $params['name'];
  }
  
 
    
  ?>
<script type="text/javascript">

 mw.add_new_page_to_menu = function(id){
	 
		 if(id == undefined){
			var id = 0; 
		 }
	 
	 	   mw.simpletab.set(mwd.getElementById('add_new_content_tab'));
	     $('#mw_page_create_live_edit').removeAttr('data-content-id');

 	 	 $('#mw_page_create_live_edit').attr('from_live_edit',1);
	 	 $('#mw_page_create_live_edit').attr('content_type', 'page'); 
	     $('#mw_page_create_live_edit').attr('content-id', id); 
		 $('#mw_page_create_live_edit').attr('quick_edit',1);
		 $('#mw_page_create_live_edit').removeAttr('live_edit');
		 $('#mw_page_create_live_edit').attr('add-to-menu', '<?php print $menu_name ?>');

		var v = $('#menu_selector_<?php  print $params['id'] ?>').val();
		if(v != undefined){
		 $('#mw_page_create_live_edit').attr('add-to-menu', v);

		}
  



      mw.load_module('content/edit_page', '#mw_page_create_live_edit', function(){
        parent.mw.tools.modal.resize("#"+thismodal.main[0].id, 710, mw.$('#settings-container').height()+25, false);
      })
 	}
	
</script>

<div class="mw_simple_tabs mw_tabs_layout_simple">
	<ul class="mw-ui-btn-nav">
		<li><a href="javascript:;" class="active">
			<?php _e("My menus"); ?>
			</a></li>
		<li><a href="javascript:;">
			<?php _e("Skin/Template"); ?>
			</a></li>
		<li style="display:none"><a href="javascript:;" id="add_new_menu_tab"></a></li>
		<li style="display:none"><a href="javascript:;" id="add_new_content_tab"></a></li>
	</ul>
	<a href="javascript:mw.add_new_page_to_menu();" class="mw-ui-btn" style="position: absolute;right: 13px;top: 12px;z-index: 1"><span>
	<?php _e("Create new page"); ?>
	</span></a>
	<div class="tab">
		<?php include($config['path_to_module'].'admin_live_edit_tab1.php');   ?>
	</div>
	<div class="tab">
		<module type="admin/modules/templates"  />
	</div>
	<div class="tab">
		<div id="add_new_menu" style="overflow: hidden">
			<input name="menu_id"  type="hidden"  value="0"    />
			<div style="overflow: hidden">
				<input class="left mw-ui-field" style="width:300px" type="text" name="title" placeholder="<?php _e("Menu Name"); ?>" />
				<button type="button" class="mw-ui-btn pull-right" onclick="mw.menu_save('#add_new_menu')">
				<?php _e("Add"); ?>
				</button>
			</div>
		</div>
	</div>
	<div class="tab" id="add_new_page">
		<div id="mw_page_create_live_edit"></div>
		
	</div>
</div>
