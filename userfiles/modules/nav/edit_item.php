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
 
 
$data['content_id'] = '';
$data['is_active'] = 'y';
$data['position'] = '9999';
 $data['url'] = '';
  $data['title'] = '';
 $data['taxonomy_id'] = '';
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
  mw.require('forms.js');
  </script>
<script  type="text/javascript">








 
 mw.menu_<?  print $rand ?>save_new_item = function(){ 
  
  mw.form.post('#mw_edit_menu_item_<?  print $rand ?>', '<? print api_url('edit_menu_item'); ?>', function(){ 
  
  
  
    mw.reload_module('nav/edit_items');
	 if(window.parent != undefined && window.parent.mw != undefined){
		 
		      window.parent.mw.reload_module('nav');

	 }

  
  });
 
 
 }
 </script>
<script  type="text/javascript">

	$(document).ready(function(){
	mw.$('#add_new_content_to_menu{rand}').find('input[type="radio"]').unbind('change')
	mw.$('#add_new_content_to_menu{rand}').find('input[type="radio"]').bind('change', function(e){
	// d();
	
		var $sel_data = $(this).parents('li').first();
		var is_cat = $sel_data.attr('data-category-id');
		
		
		
		var the_new_page = mw.$('#mw_edit_menu_item_{rand}').find('input[name="content_id"]');
				var the_new_cat = mw.$('#mw_edit_menu_item_{rand}').find('input[name="taxonomy_id"]');

		
		if(is_cat != undefined){
			the_new_cat.val(is_cat)
			the_new_page.val('');
		}

		
		var is_content_id= $sel_data.attr('data-page-id');
		if(is_content_id != undefined){
		the_new_page.val(is_content_id)
		the_new_cat.val('');
		}
			
		
	
	});
 
	});
</script>
<div class="vSpace"></div>

<div class="<? print $config['module_class']; ?> menu_item_edit" id="mw_edit_menu_item_<?  print $rand ?>">

      <br />




  <input type="hidden" name="id" value="<?  print $data['id'] ?>" />



  <input type="hidden" name="content_id" value="<?  print $data['content_id'] ?>" />


  <input type="hidden" name="taxonomy_id" value="<?  print $data['taxonomy_id'] ?>" />
  <?  if(isset($params['menu-id']) and  intval($data['id']) == 0): ?>
  <input type="hidden" name="parent_id" value="<?  print $params['menu-id'] ?>" />
  <?  elseif(isset($params['parent_id'])): ?>
  parent_id
  <input type="hidden" name="parent_id" value="<?  print $params['parent_id'] ?>" />
  <? endif; ?>
  <input type="hidden" name="id" value="<?  print $data['id'] ?>" />
  <button class="mw-ui-btn" onclick="mw.menu_<?  print $rand ?>save_new_item();">Save</button>

</div>
<? else: ?>
<? endif; ?>
