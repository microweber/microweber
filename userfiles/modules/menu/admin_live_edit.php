<?php //include_once($config['path_to_module'].'functions.php'); ?>
<?php  
  $rand = crc32(serialize($params));
  
  $menu_name = get_option('menu_name', $params['id']);

  if($menu_name == false and isset($params['menu_name'])){
	  $menu_name = $params['menu_name'];
  } elseif($menu_name == false and isset($params['menu-name'])){
	  $menu_name = $params['menu-name'];
  }elseif($menu_name == false and isset($params['name'])){
	  $menu_name = $params['name'];
  }


    
  ?>
<script type="text/javascript">

 mw.add_new_page_to_menu = function(id){
        mw.tools.loading(true);
	 
		 if(id == undefined){
			var id = 0;
		 }
	 
	 	   MenuTabs.set(3);


	     mw.$('#mw_page_create_live_edit').removeAttr('data-content-id');

 	 	 mw.$('#mw_page_create_live_edit').attr('from_live_edit',1);
	 	 mw.$('#mw_page_create_live_edit').attr('content_type', 'page');
	     mw.$('#mw_page_create_live_edit').attr('content-id', id);
		 mw.$('#mw_page_create_live_edit').attr('quick_edit',1);
		 mw.$('#mw_page_create_live_edit').removeAttr('live_edit');
		 mw.$('#mw_page_create_live_edit').attr('add-to-menu', '<?php print $menu_name ?>');

		var v = mw.$('#menu_selector_<?php  print $params['id'] ?>').val();
		if(v){
		 mw.$('#mw_page_create_live_edit').attr('add-to-menu', v);
		}
      mw.load_module('content/edit_page', '#mw_page_create_live_edit', function(){
        parent.mw.tools.modal.resize("#"+thismodal.main[0].id, innerWidth, mw.$('#settings-container').height()+25, false);
        $('.fade-window').removeClass('closed').addClass('active').css({
            position:'static'
        });
        $(".mw-edit-content-item-admin").css({
            boxShadow:'none'
        })
        mw.tools.loading(false)
      });
 	}

    $(mwd).ready(function(){
       MenuTabs = mw.tabs({
          nav:'#menu-tabs a',
          tabs:'.tab'
       });
    });

</script>

<style>
.tab{
  display: none;
}

</style>
<div  class="module-live-edit-settings">
	<div class="mw-ui-btn-nav mw-ui-btn-nav-tabs" id="menu-tabs">
		<a href="javascript:;" class="mw-ui-btn active">
		    <?php _e("My menus"); ?>
		</a>
		<a href="javascript:;" class="mw-ui-btn">
			<?php _e("Skin/Template"); ?>
		</a>
		<span style="display:none">
            <a href="javascript:;" id="add_new_menu_tab"></a>
        </span>
		<span style="display:none">
            <a href="javascript:;" id="add_new_content_tab"></a>
        </span>
	</div>
	<a href="javascript:mw.add_new_page_to_menu();" class="mw-ui-btn mw-ui-btn-medium pull-right"><?php _e("Create new page"); ?></a>
    <div class="mw-ui-box mw-ui-box-content">
    <div class="tab" style="display: block">
		<?php include($config['path_to_module'].'admin_live_edit_tab1.php');   ?>
	</div>
	<div class="tab">
		<module type="admin/modules/templates" />
	</div>
	<div class="tab">
		<div id="add_new_menu" style="overflow: hidden">
			<input name="menu_id"  type="hidden" value="0" />
			<div style="overflow: hidden">
				<input class="mw-ui-field w100" type="text" name="title" placeholder="<?php _e("Menu Name"); ?>" />
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
    </div>