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

<div class="mw-ui-field mw-tag-selector mw-custom-fields-tags">
  <? if(isset($params['save_to_content_id']) and isset($params["to_table_id"]) and intval(($params["to_table_id"]) > 0)): ?>
  <? $p = get_content_by_id($params["to_table_id"]); ?>
  <? if(isset($p['title'])): ?>
  <div class="mw-custom-fields-from-page-title"> From <span class="mw-custom-fields-from-page-title-text"><? print $p['title'] ?></span> </div>
  <? endif; ?>
  <? endif; ?>
  <? foreach( $more as $field): ?>





            <a class="mw-ui-btn mw-ui-btn-small" href="javascript:;" onclick="mw.custom_fields.edit('.mw-admin-custom-field-edit-item','<? print $field['id'] ?>', false, event);">
                <? print ($field['title']); ?>
                <span class="mw-ui-btnclose"></span>
            </a>






  <? endforeach; ?>
</div>
<? else : ?>
<? if(!isset($params['save_to_content_id'])): ?>
You dont have any custom fields
<? endif; ?>
<? endif; ?>
<? endif; ?>
