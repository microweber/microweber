 <?
 $for = 'module';
 
  $copy_from = false;
 if(isset($params['for'])){
	$for = $params['for'];
 }
 
 if(isset($params['copy_from'])){
	$copy_from = $params['copy_from'];
 }
  
$module_id = $params['id'];
  if(isset($params['to_table_id'])){
	$module_id = $params['to_table_id'];
 }
 
 

$rand = rand();
 
?>
<button onclick="mw_make_new_field('text'); return false;"  >mw_make_new_field('text')</button>
<button onclick="mw_make_new_field('checkbox'); return false;" >mw_make_new_field('checkbox')</button>
<button onclick="mw_make_new_field('price'); return false;">mw_make_new_field('price')</button>

<div  class="custom-fields-form-wrap custom-fields-form-wrap-<? print $rand ?>" id="custom-fields-form-wrap-<? print $rand ?>"></div>
<script type="text/javascript">
    function mw_make_new_field($type, $copy){
		$copy_str = ''	
		if($copy != undefined){
		$copy_str = '/copy_from:'+ $copy;	
		}
        mw.$('#custom-fields-form-wrap-<? print $rand ?>').load('<? print site_url('api_html/make_custom_field/settings:y/basic:y/for_module_id:') ?><? print $module_id; ?>/for:<? print $for  ?>/custom_field_type:'+$type+$copy_str);
return false;

    }

    $(document).ready(function(){
<? if($copy_from != false): ?>
mw_make_new_field('', '<? print $copy_from ?>')
<? endif; ?>
        //make_new_field()

    });
</script>
<module type="custom_fields" view="list" for="<? print $for  ?>" for_module_id="<? print $module_id ?>" id="mw_custom_fields_list_<? print $params['id']; ?>" />
