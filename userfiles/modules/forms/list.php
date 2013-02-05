<? if(is_admin()==false) { mw_error('You must be logged as admin', 1); } ?>


<script  type="text/javascript">
  mw.require('<? print $config['url_to_module']; ?>forms_data_manager.js');


<? if(isset($params['export_to_excel'])) : ?>

var tableToExcel_<? print $params['id'] ?> = function() {
  window.open('data:application/vnd.ms-excel,' + encodeURIComponent($('#table_data_<? print $params['id'] ?>').html()));

}


 $(document).ready(function () {

tableToExcel_<? print $params['id'] ?>('table_data_<? print $params['id'] ?>','aa')

   });


<? endif; ?>



  </script>


<?

$data = array();
 if(isset($params['load_list'])){
	 if($params['load_list'] == 'default'){
		$params['load_list'] = '0';
	 }
$data['list_id'] =$params['load_list'];
}

if(isset($params['keyword'])){
   $data['keyword'] =$params['keyword'];
 }
 if(isset($params['for_module'])){
	 $data['module_name'] =$params['for_module'];
 }

 $custom_fields = array();

if(isset($data['list_id'])){

 //$custom_fields = get_custom_fields('table_forms_data',$data['list_id']);
//d($custom_fields );

} else {
 $custom_fields = get_custom_fields('table_forms_data','all');

}
$data_paging = $data;
$data_paging['page_count'] = 1;
//
//$data['debug'] = 1;

 $data_paging = get_form_entires($data_paging);
if((url_param('curent_page') != false)){
$data['curent_page'] = url_param('curent_page');
}


 $data = get_form_entires($data);
if(isarr($data)){
  foreach ($data as $item) {
   if(isset($item['custom_fields'])){
    foreach ($item['custom_fields'] as $value) {
     $custom_fields[$value['custom_field_name']] =$value;
    }
   }
  }
}
?>
<? if(isarr($data)) :?>
<div class="pagination">
<? print paging("num=$data_paging"); ?>
</div>

<? if(isset($params['export_to_excel'])) : ?>

<? endif; ?>

<table id="table_data_<? print $params['id'] ?>" class="table table-bordered table-striped">
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
    <tr class="mw-form-entry-item mw-form-entry-item-<? print $item['id'] ?>">
      <td><? print $item['id'] ?>
<a href="javascript:mw.forms_data_manager.delete('<? print $item['id'] ?>','.mw-form-entry-item-<? print $item['id'] ?>');">[x]</a>

      </td>
      <? if(isarr($custom_fields )): ?>
      <? foreach($custom_fields   as $cvk => $custom_field_v): ?>
      <td>
        <? if(isset($item['custom_fields'])): ?>
        <?  foreach ($item['custom_fields'] as $value) :  ?>
         <? if(($value['custom_field_name']) == $cvk): ?>
        <?   print ($value['custom_field_values_plain']); ?>
         <?  endif; ?>
        <? endforeach ; ?>
        <?  endif; ?></td>
      <? endforeach ; ?>
      <? endif; ?>
    </tr>
    <? endforeach; ?>
  </tbody>
</table>

<? if(isset($params['export_to_excel'])) : ?>

<? endif; ?>



<? endif; ?>
