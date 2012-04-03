

<? if(intval($params['page_id']) != 0) :  ?>
<? $page_data = get_page($params['page_id']); 
$custom_fields = $page_data['custom_fields' ];
?>
<? endif; ?>

<? 
if($params['file_name'] != ''){
	
	$params['file'] = $params['file_name'] ;
}
if($params['file'] == ''){
	$params['file'] =$page_data['content_layout_file'];
}
?>

<? if(($params['file']) != '') :  ?>
<? $layouts = CI::model('template')->layoutsList(); 
// p($layouts);


?>
<? if(!empty($layouts)): ?>
<? foreach($layouts as $layout): ?>
<? if($layout['filename'] == $params['file']): ?>
<? 

//p($layout);
if(!empty($layout['params'])): ?>

<table width="100%" border="0">
  <? foreach($layout['params'] as $item): ?>
  <?   if(($item['disable_edit']) == false):  ?>
  <tr>
    <td><h4><? print $item['name'] ?></h4>
      <?   if(($item['help'])):  ?>
      <small><? print $item['help'] ?></small>
      <? endif; ?></td>
    <td><? // print $item['type'] ?>
      <?php switch($item['type']): 
case 'category_selector': ?>
      <input name="custom_field_<? print $item['param'] ?>" type="text" value="<? print $custom_fields[$item['param']]? $custom_fields[$item['param']] : $item['default'] ; ?>"  />
      <?php break;?>
      <?php case 'number': ?>
      <?php default: ?>
      <div class="field2">
        <input name="custom_field_<? print $item['param'] ?>" type="text" value="<? print  $custom_fields[$item['param']]? $custom_fields[$item['param']] : $item['default'] ; ?>"  />
      </div>
      <?php break;?>
      <?php endswitch;?></td>
    <td><? // p($item) ?></td>
  </tr>
  <? endif; ?>
  <? endforeach; ?>
</table>
<? endif; ?>
<? endif; ?>
<? endforeach; ?>
<? endif; ?>
<? endif; ?>
