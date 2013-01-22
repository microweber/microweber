<?

  $for = 'module';
 if(isset($params['for'])){
	$for = $params['for'];
 }
 
 $live_edit = false;
  if(isset($params['live_edit'])){
	$live_edit = $params['live_edit'];
 }
 
  if(isset($params['to_table_id'])){
	 $params['for_module_id'] = $params['to_table_id'];
 }
 
   if(!isset($params['for_module_id'])){
	 if(isset($params['id'])){
		 $params['for_module_id'] = $params['id'];
	 }
 }
 
 
 $diff = false;

  if(isset($params['save_to_content_id'])){
			//  d($params['save_to_content_id']); 
			 $diff = get_custom_fields($for,$params['save_to_content_id'],1,false,false);
	   
		  }
		  
?>
<?

 if(isset($params['for_module_id'])): ?>
<?	$more = get_custom_fields($for,$params['for_module_id'],1,false,false);   

 
if(isarr( $diff) and isarr($more) ){
 foreach($diff as $item1){
	 $i=0;
	  foreach($more as $item2){
	 if($item1['custom_field_name'] == $item2['custom_field_name']){
		 unset($more[$i]);
	 }
	 $i++;
 		}
 }
}
 
?>
<? if(!empty( $more)):  ?>

<div class="custom-field-table">
  <? if(isset($params['save_to_content_id']) and isset($params["to_table_id"]) and intval(($params["to_table_id"]) > 0)): ?>
  <? $p = get_content_by_id($params["to_table_id"]); ?>
  <? if(isset($p['title'])): ?>
  <div class="mw-custom-fields-from-page-title"> From <span class="mw-custom-fields-from-page-title-text"><? print $p['title'] ?></span> </div>
  <? endif; ?>
  <? endif; ?>
  <? foreach( $more as $field): ?>
  <div class="custom-field-table-tr" data-field-id="<? print $field['id'] ?>">
    <div class="custom-field-preview-cell" onclick="$(this).parent().addClass('active')">
      <div class="custom-field-preview">
 <?  //d(($field)); ?>
        <a class="edit-custom-field-btn" href="javascript:mw.custom_fields.edit('.mw-admin-custom-field-edit-item','<? print $field['id'] ?>');">
        <?php _e("Edit this field"); ?>
        </a>
        
         Type <?    print  ($field['type']); ?><br  />
         
         Title <?    print  ($field['title']); ?><br  />
         
      
        
        
        
        <?  // print  make_field($field); ?>
       <!-- <span class="edit-custom-field-btn" title="<?php _e("Edit this field"); ?>"></span>--></div>
    </div>
    <!--<div class="second-col">
      <div class="custom-field-set-holder"> <span class="ico iMove custom-field-handle-row right" onmousedown="mw.custom_fields.sort_rows()"></span>
        <div class="custom-field-set">
          <? 
		  if(isset($params['save_to_content_id'])){
			  
			 $field['save_to_content_id']   = strval($params['save_to_content_id']);
			 
		  }
		  
		  
		 //  print  make_field($field, false, 2); ?>
        </div>
      </div>
    </div>-->
  </div>
  <? endforeach; ?>
</div>
<? else : ?>
<? if(!isset($params['save_to_content_id'])): ?>
You dont have any custom fields
<? endif; ?>
<? endif; ?>
<? endif; ?>
