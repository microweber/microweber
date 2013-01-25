<? if(is_admin()==false) { mw_error('You must be logged as admin', 1); } ?>
<? if(!isset($params['for-module']) or !isset($params['for-module-id'])): ?>
<?  mw_warn('Error: in module "'.$config['module'].'" - You must set "for-module" and "for-module-id" parameters on this module'); return; ?>
<? endif; ?>
<script  type="text/javascript">
  mw.require("forms.js");
</script>
<? $rand = uniqid(); ?>
<script  type="text/javascript">
 



function mw_create_new_list_<? print $rand ?>(){
	
	  mw.form.post('.mw_create_new_forms_list<? print $rand ?>', '<? print api_url('save_form_list'); ?>');
    
	mw.reload_module('<? print $config['module'] ?>');
	return false;
 
	
}


</script>
<? $selected_list = get_option('list_id', $params['for-module-id']);
$data = get_form_lists('module_name='.$params['for-module']);;
 
 ?>
<? if(isarr($data )): ?>

<strong>Save form entires to existing list</strong>
<select name="list_id"   class="mw_option_field"  >
  <? foreach($data  as $item): ?>
  <option    value="<? print $item['id'] ?>"  <? if((intval($selected_list) == intval($item['id']))): ?>   selected="selected"  <? endif; ?>><? print $item['title'] ?></option>
  <? endforeach ; ?>
</select>
<? endif; ?>
<div class="mw_create_new_forms_list<? print $rand ?>">
  <legend>Create new list</legend>
  <input type="text" name="mw_new_forms_list" value="" />
  <input type="hidden" name="for_module" value="<? print $params['for-module'] ?>"  />
  <input type="hidden" name="for_module_id" value="<? print $params['for-module-id'] ?>"  />
  <button class="mw-ui-btn" onclick="mw_create_new_list_<? print $rand ?>()">Create</button>
</div>
