<?php
$rand = uniqid();
if(is_admin() == false){
    mw_error('Must be admin');
}
$id = false;
if(isset($params['item-id'])){
	$id = intval($params['item-id']);
}
 

if($id == 0){
	$data = array();
	$data['id'] = $id;
	$data['parent_id'] = 0;
	if(isset($params['parent_id'])){
		$data['parent_id'] = intval($params['parent_id']);
	}else if(isset($params['menu-id'])){
		$data['parent_id'] = intval($params['menu-id']);
	}
	if(!isset($params['content_id'])){
		$data['content_id'] = '';
	} else {
		$data['content_id'] = $params['content_id'];
	}
	if(!isset($params['categories_id'])){
		$data['categories_id'] = '';
	} else {
		$data['categories_id'] = $params['categories_id'];
	}
	$data['is_active'] = 1;
	$data['position'] = '9999';
	$data['url'] = '';
	$data['title'] = '';
//	$data['categories_id'] = '';
} else {

	$data = mw()->menu_manager->menu_item_get($id);
}
if( $id != 0){
//$data = menu_tree( $id);
}
 
?>
<?php if($data != false): ?>
<?php //$rand = uniqid(); ?>



<div class="<?php print $config['module_class']; ?> menu_item_edit" id="mw_content/menu_item_save_<?php  print $rand ?>">
  <?php if((!isset($data['title']) or $data['title']=='' ) and isset($data["content_id"]) and intval($data["content_id"]) > 0 ): ?>
  <?php $cont = get_content_by_id($data["content_id"]);
	if(isset($cont['title'])){
		$data['title'] = $cont['title'];
		$item_url = content_link($cont['id']);
	}
	?>
  <?php else: ?>
  <?php if((!isset($data['title']) or $data['title']=='' )and isset($data["categories_id"]) and intval($data["categories_id"])>0): ?>
  <?php $cont = get_category_by_id($data["categories_id"]);
    if(isset($cont['title'])){
    	$data['title'] = $cont['title'];
    	  $item_url = category_link($cont['id']);
    }
?>
  <?php endif; ?>
  <?php endif; ?>
  <?php
  if (isset($data['content_id']) and intval($data['content_id']) != 0) {
		 	$item_url = content_link($data['content_id']);

	}

	if (isset($data['categories_id']) and intval($data['categories_id']) != 0) {

	$item_url = category_link($data['categories_id']);
	}


  if ((isset($item_url)  and $item_url != false) and (!isset($data['url']) or trim($data['url']) == '')) {
    $data['url'] = $item_url ;
  }


  ?>
  <div id="custom_link_inline_controller" class="mw-ui-gbox" style="display: none;">
<div id="custom_link_inline_controller_edit_<?php  print $data['id'] ?>">
    <h4><?php _e("Edit menu item"); ?></h4>

    <input type="hidden" name="id" value="<?php  print $data['id'] ?>" />

    <div class="mw-ui-field-holder">
        <input type="text" placeholder="<?php _e("Title"); ?>" class="mw-ui-field w100" name="title" value="<?php  print $data['title'] ?>"  />
    </div>

	<?php  if(isset($params['menu-id'])): ?>
    <input type="hidden" name="parent_id" value="<?php  print $params['menu-id'] ?>" />
	<?php else: ?>

    <?php endif; ?>

    <div class="mw-ui-field-holder">
    <input type="text" placeholder="<?php _e("URL"); ?>" class="mw-ui-field w100"  name="url" value="<?php  print $data['url'] ?>"  />
    </div>

    <button class="mw-ui-btn" onclick="mw.$('#menu-selector-<?php  print $data['id'] ?>').toggle();">
    <?php _e("Select page from your site"); ?>
    </button>

    <?php if($data['id'] != 0): ?>
    <div id="menu-selector-<?php  print $data['id'] ?>" class="mw-ui mw-ui-category-selector mw-tree mw-tree-selector" style="top: 3px;">
      <microweber module="categories/selector" active_ids="<?php  print $data['content_id'] ?>" categories_active_ids="<?php  print $data['categories_id'] ?>"  for="content" rel_id="<?php print 0 ?>" input-type-categories="radio"  input-name-categories="link_id" input-name="link_id"  />
    </div>
    <script>mw.treeRenderer.appendUI('#menu-selector-<?php  print $data['id'] ?>');</script>
    <?php endif; ?>


    <hr>

    <div class="mw-clear pull-right mw-ui-btn-nav">
        <span onclick="cancel_editing_menu(<?php  print $data['id'] ?>);" class="mw-ui-btn"><?php _e("Cancel"); ?></span>
        <button class="mw-ui-btn mw-ui-btn-info" onclick="mw.menu_save_new_item('#custom_link_inline_controller_edit_<?php  print $data['id'] ?>');"><?php _e("Save"); ?></button>
    </div>
  <input type="hidden" name="id" value="<?php  print $data['id'] ?>" />
  <input type="hidden" name="content_id" value="<?php  print $data['content_id'] ?>" />
  <input type="hidden" name="categories_id" value="<?php  print $data['categories_id'] ?>" />
  <?php  if(isset($params['menu-parent-id'])): ?>
  <input type="hidden" name="parent_id" value="<?php  print $params['menu-parent-id'] ?>" />
  <?php  elseif(isset($data['parent_id']) and $data['parent_id'] !=0): ?>
  <input type="hidden" name="parent_id" value="<?php  print $data['parent_id'] ?>" />
  <?php  elseif(isset($params['parent_id'])): ?>
  <input type="hidden" name="parent_id" value="<?php  print $params['parent_id'] ?>" />
  <?php endif; ?>

  </div>

  </div>
</div>
<?php else: ?>
<?php endif; ?>
