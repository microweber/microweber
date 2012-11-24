<?
 if(is_admin() == false){
	 error('Must be admin'); 
 }
 $id = false;
 if(isset($params['item-id'])){
 $id = intval($params['item-id']);
 }
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
 
if( $id != 0){
//$data = menu_tree( $id);	
}
 
?>
<? if($data != false): ?>
<? $rand = uniqid(); ?>
<script  type="text/javascript">
  mw.require('forms.js');
  </script>
<script  type="text/javascript">
 
 
 </script>

<div class="<? print $config['module_class']; ?> menu_item_edit" id="mw_edit_menu_item_<?  print $rand ?>"> Add item to menu
  parent_id
  <input type="text" name="parent_id" <?  print $data['parent_id'] ?> />
  <br />
  content_id
  <input type="text" name="content_id" <?  print $data['content_id'] ?> />
  <br />
  taxonomy_id
  <input type="text" name="taxonomy_id" <?  print $data['taxonomy_id'] ?> />
  <br />
  <br />
  url
  <input type="text" name="url" <?  print $data['url'] ?> />
  <br />
  <br />
  title
  <input type="text" name="title" <?  print $data['title'] ?> />
  <br />
</div>
<? else: ?>
<? endif; ?>
