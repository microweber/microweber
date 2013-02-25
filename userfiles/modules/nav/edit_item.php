<?
$rand = uniqid();
if(is_admin() == false){
    error('Must be admin');
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
	}
	if(!isset($params['content_id'])){
		$data['content_id'] = '';
	} else {
		$data['content_id'] = $params['content_id'];
	}
	if(!isset($params['taxonomy_id'])){
		$data['taxonomy_id'] = '';
	} else {
		$data['taxonomy_id'] = $params['taxonomy_id'];
	}
	$data['is_active'] = 'y';
	$data['position'] = '9999';
	$data['url'] = '';
	$data['title'] = '';
//	$data['taxonomy_id'] = '';
} else {

	$data = get_menu_item($id);
}
if( $id != 0){
//$data = menu_tree( $id);
}

?>
<? if($data != false): ?>
<? //$rand = uniqid(); ?>
<script  type="text/javascript">
    mw.require('forms.js', true);
</script>
<script  type="text/javascript">
    if(typeof mw.menu_save_new_item !== 'function'){
        mw.menu_save_new_item = function(selector){
        	mw.form.post(selector, '<? print api_url('edit_menu_item'); ?>', function(){
        		mw.reload_module('nav/edit_items');
        		if(window.parent != undefined && window.parent.mw != undefined){
        			window.parent.mw.reload_module('nav');
        		}
        	});
        }
    }
</script>

<div class="vSpace"></div>

<div class="<? print $config['module_class']; ?> menu_item_edit" id="mw_edit_menu_item_<?  print $rand ?>">
	<? if((!isset($data['title']) or $data['title']=='' ) and isset($data["content_id"]) and intval($data["content_id"]) > 0 ): ?>
	<? $cont = get_content_by_id($data["content_id"]);
	if(isset($cont['title'])){
		$data['title'] = $cont['title'];
		$data['url'] = content_link($cont['id']);
	}
	?>
<? else: ?>
<? if((!isset($data['title']) or $data['title']=='' )and isset($data["taxonomy_id"]) and intval($data["taxonomy_id"])>0): ?>
<? $cont = get_category_by_id($data["taxonomy_id"]);
    if(isset($cont['title'])){
    	$data['title'] = $cont['title'];
    	$data['url'] = category_link($cont['id']);
    }
?>
<? endif; ?>
<? endif; ?>

<div id="custom_link_inline_controller" class="mw-ui-gbox">

    <input type="text" placeholder="<?php _e("Title"); ?>" name="title" value="<?  print $data['title'] ?>" />
    <span class="mw-ui-btn" onclick="mw.$('#menu-selector-<?  print $data['id'] ?>').toggle();"><?php _e("Change"); ?></span>
    <div class="mw_clear vSpace"></div>
    <input type="text" placeholder="<?php _e("URL"); ?>" name="url" value="<?  print $data['url'] ?>" />

    <span class="mw-ui-btn mw-ui-btn-blue left" onclick="mw.menu_save_new_item('#custom_link_inline_controller');">Save</span>

    <div class="mw_clear vSpace"></div>

    <?php if($data['id'] != 0): ?>



    <div id="menu-selector-<?  print $data['id'] ?>" class="mw-ui mw-ui-category-selector mw-tree mw-tree-selector">

    <microweber module="categories/selector" active_ids="<?  print $data['content_id'] ?>" categories_active_ids="<?  print $data['taxonomy_id'] ?>"  for="content" to_table_id="<? print 0 ?>" input-type-categories="radio" input-type-categories="radio" input-name-categories="link_id" input-name="link_id"  />

    </div>

    <script>mw.treeRenderer.appendUI('#menu-selector-<?  print $data['id'] ?>'); </script>

        <? endif; ?>


     <input type="hidden" name="parent_id" value="<?  print $params['menu-id'] ?>" />


</div>

    <input type="hidden" name="id" value="<?  print $data['id'] ?>" />
    <input type="hidden" name="content_id" value="<?  print $data['content_id'] ?>" />
    <input type="hidden" name="taxonomy_id" value="<?  print $data['taxonomy_id'] ?>" />
 <?  if(isset($params['menu-id']) and  intval($data['id']) == 0): ?>
    <input type="hidden" name="parent_id" value="<?  print $params['menu-id'] ?>" />
<?  elseif(isset($params['parent_id'])): ?>
    <input type="hidden" name="parent_id" value="<?  print $params['parent_id'] ?>" />
<? endif; ?>

</div>
<? else: ?>
<? endif; ?>
