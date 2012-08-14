<? $rand = rand(); 

$rand = round($rand);

 
$is_for_module = url_param('for_module_id',1);
 
if(!empty($data)){
	
if(trim($data['field_type']) != ''){
	
$field_type = 	$data['field_type'];
}
	
}
   ?>
<script type="text/javascript">


function save_cf_<? print $rand ?>(){
 	var serializedForm = serializedForm = $("#custom_fields_edit<? print $rand ?> :input").serialize();
	$.post("<? print site_url('api/forms/save_field') ?>",    serializedForm, function(data)         {
		
		mw.reload_module('custom_fields')
		mw.reload_module('#mw_custom_fields_list_<? print strval($is_for_module) ?>');
		
		
		if(serializedForm.id == undefined){
		//$('#custom_fields_edit<? print strval($rand) ?>').fadeOut();	
			
		}
	 
			 
			
 
		
		
        });
}

	
function remove_cf_<? print $rand ?>(){
	var serializedForm = serializedForm = $("#custom_fields_edit<? print $rand ?> :input").serialize();
	$.post("<? print site_url('api/forms/remove_field') ?>",    serializedForm, function(data)         {
		
		mw.reload_module('custom_fields')
		mw.reload_module('#mw_custom_fields_list_<? print strval($is_for_module) ?>');
	   $('#custom_fields_edit<? print strval($rand) ?>').fadeOut();
		
        });

}
		 
</script>

<div class="form-horizontal" id="custom_fields_edit<? print $rand ?>"  >
<fieldset>
<? if(intval($data['id']) != 0): ?>
<input type="hidden" name="id" value="<? print intval($data['id']) ?>" />
<? endif; ?>
<? if($is_for_module != false): ?>
<input type="hidden" name="to_table" value="table_modules" />
<input type="hidden" name="to_table_id" value="<? print strval($is_for_module) ?>" />
<? endif; ?>
<div class="control-group">
  <label class="control-label" for="input_field_label<? print $rand ?>">Field label</label>
  <div class="controls">
    <input type="text" class="input-xlarge"  value="<? print ($data['custom_field_name']) ?>" name="custom_field_name" id="input_field_label<? print $rand ?>">
  </div>
</div>
<div class="control-group">
  <label class="control-label" for="select_custom_field_type<? print $rand ?>">Field type</label>
  <div class="controls">
    <select id="select_custom_field_type<? print $rand ?>" name="custom_field_type">
      <option <? if(trim($field_type) == 'text'): ?> selected="selected" <? endif; ?> value="text">text</option>
      <option  <? if(trim($field_type) == 'dropdown'): ?>  selected="selected"  <? endif; ?>  value="dropdown">dropdown</option>
       <option  <? if(trim($field_type) == 'checkbox'): ?>  selected="selected"  <? endif; ?>  value="checkbox">checkbox</option>
     
      
      
      
    </select>
  </div>
</div>
