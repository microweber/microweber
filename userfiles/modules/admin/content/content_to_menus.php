 <?
$id = $params['id'];



$form_values = get_content($id);
 

?>
      <fieldset>
        <legend>Add this post to menus</legend>
        <?php  $menus = CI::model('content')->content_model->getMenus(array('item_type' => 'menu'));
		
		//p($menus);
		?>
        <?php foreach($menus as $item): ?>
        <?php $is_checked = false; $is_checked = CI::model('content')->content_helpers_IsContentInMenu($id,$item['id'] ); ?>
        
        <? 
 
		
		
		
		
		?>
        <label class="lbl"> <?php print $item['item_title'] ?>&nbsp;
          <input name="menus[]" type="checkbox" <?php if($is_checked  == true): ?> checked="checked"  <?php endif; ?> value="<?php print $item['id'] ?>" />
        </label>
        <?php endforeach; ?>
        <?php //  var_dump( $menus);  ?>
      </fieldset>
      