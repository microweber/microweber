<?php  //$rand = $params['id']; ?>

<div id="mw_index_menus">
  <div class="mw_edit_page_left mw_edit_page_default" id="mw_edit_page_left">
    <h2 style="padding:30px 0 0 25px;"><span class="ico imanage-module"></span><?php _e("Menus"); ?></h2>
    <div class="mw-admin-side-nav" id="menus_categories_tree_{rand}" >
      <div id="menus_admin_categories_{rand}">
        <?php $menus = get_menus(); ?>
        <ul>
          <?php foreach($menus as $item): ?>
          <li><a  href="<?php print $config['url'] ?>?menu_name=<?php print $item['title'] ?>"><?php print  $item['title'] ?></a></li>
          <?php endforeach; ?>
        </ul>
      </div>
    </div>
  </div>
  <div class="mw_edit_page_right" style="padding: 20px;">
    <div class="menus-index-bar">     
       <?php $params['menu_name'] = $item['title'];
		if(isset($_REQUEST['menu_name'])){
			$params['menu_name'] =$_REQUEST['menu_name'];
		}
	 include($config['path_to_module'].'admin_live_edit_tab1.php');   ?>
    </div>
    <div class="vSpace"></div>
  </div>
</div>



 