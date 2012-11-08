<?

 $for = 'module';
 if(isset($params['for'])){
	$for = $params['for'];
 }
 if(!isset($params['for_module_id'])){
	 if(isset($params['id'])){
		 $params['for_module_id'] = $params['id'];
	 }
	 
	 
 }
 
 /* if( $for  != 'module'){
	 if(isset($params['for_module_id'])){
	  $params['to_table_id'] = $params['for_module_id'];
	  unset($params['for_module_id']);
	 }
 }*/

  
 if(isset($params['for_module_id'])): ?>


	<?
	// d($params);
	$more = get_custom_fields($for,$params['for_module_id'],1,false);    ?>

    <? if(!empty( $more)):  ?>
    <table class="custom-field-table" cellpadding="0" cellspacing="0" >

      <? foreach( $more as $field): ?>

      <tr class="custom-field-table-tr">
        <td><div class="custom-field-preview"><?  print  make_field($field); ?><span class="edit-custom-field-btn" onclick="$(mw.tools.firstParentWithClass(this, 'custom-field-table-tr')).addClass('active')" title="<?php _e("Edit this field"); ?>"></span></div></td>
        <td><div class="custom-field-set"><? print  make_field($field, false, 2); ?></div></td>
      </tr>


      <? endforeach; ?>

    </table>

    <? endif; ?>


<? endif; ?>



