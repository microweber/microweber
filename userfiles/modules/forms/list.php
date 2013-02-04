<? if(is_admin()==false) { mw_error('You must be logged as admin', 1); } ?>
<?  
$data = array();
 if(isset($params['load_list'])){
	 if($params['load_list'] == 'default'){
		$params['load_list'] = '0'; 
	 }
$data['list_id'] =$params['load_list'];
}


 if(isset($params['for_module'])){
	 $data['module_name'] =$params['for_module'];
 }
if(isset($data['list_id'])){
	d($data);
	$custom_fields = get_custom_fields('table_forms',$data['list_id']);
	d($custom_fields);
} else {
	
}
$custom_fields = get_custom_fields('table_forms_data','all');
$data = get_form_entires($data);
 
  
?>
<? if(isarr($data)) :?>

<table class="table table-bordered table-striped">
  <thead>
    <tr>
      <th>id</th>
      <? if(isarr($custom_fields )): ?>
      <? foreach($custom_fields   as $k=>$item): ?>
      <th><? print   titlelize($k); ?></th>
      <? endforeach ; ?>
      <? endif; ?>
    </tr>
  </thead>
  <tbody>
    <? foreach ($data as $item) : ?>
    <tr class="mw-cart-item mw-cart-item-<? print $item['id'] ?>">
      <td><? print $item['id'] ?></td>
      <? if(isarr($custom_fields )): ?>
      <? foreach($custom_fields   as $custom_field_k=>$custom_field_v): ?>
      <td><? if(isset($item['custom_fields'][$custom_field_k]) or isset($item['custom_fields'][titlelize($custom_field_k)])): ?>
        <? print $custom_field_v; ?>
        <?  endif; ?></td>
      <? endforeach ; ?>
      <? endif; ?>
    </tr>
    <? endforeach; ?>
  </tbody>
</table>
<? endif; ?>
