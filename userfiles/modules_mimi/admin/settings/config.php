<?



//p($params);


$options = array();
if($params['for_module'] != ''){
	
	$options['module']= $params['for_module'];
	
}
 $options2 =  $options;
  $options2['count_only']  = true;
  //  $options2['debug']  = true;
$all_options_c = CI::model('core')->optionsGet ( $options2 );






  $pp = 50;
   $curent_page = intval(url_param('curent_page_options'));
   
    $pp1 = $pp *  $curent_page;
	$pp2 = $pp *  ($curent_page + 1);
   
   
  
//  p($all_options_c);
  
  $options['limit'] = array($pp1,$pp2);
  
  

$all_options = CI::model('core')->optionsGet ( $options );
if($all_options == false){
$all_options = array();	
}
//p($all_options);
$all_options[] = array();
?>
<div class="paging">
<? $paging =  CI::model('content')->pagingPrepareUrls($base_url = false, $all_options_c, $paging_param = 'curent_page_options', $keyword_param = 'keyword') ;
paging($display = 'default', $paging);
//p($paging);
?>
</div>


<? foreach($all_options as $item): ?>
<script type="text/javascript">
function save_option<? print $item['id']; ?>($form_id){
	data123 = $('#'+$form_id).serialize();
	$.post("<? print site_url('api/content/save_option') ?>",  data123 ,	function(data1){
																					 
										$('#'+$form_id).fadeOut().fadeIn();											 
																					 });
}
</script>


<? //p($item); ?>
<form class="order_options_form" id="opt<? print $item['id']; ?>">
  <table width="100%" border="0" class="order_options_table">
    <? if(intval($item['id']) > 0): ?>
    <tr>
      <td>id</td>
      <td><input name="id" value="<? print $item['id']?>" type="text" /></td>
    </tr>
    <? endif; ?>
    <tr>
      <td>Option Key</td>
      <td><input name="option_key" value="<? print $item['option_key']?>" type="text" /></td>
    </tr>
    <tr>
      <td>Option Value</td>
      <td><?  //p($item)?>
        <textarea name="option_value"><? print ($item['option_value'])?></textarea></td>
    </tr>
    <tr>
      <td>Option Group</td>
      <td><input name="option_group" value="<? print $item['option_group']?>" type="text" /></td>
    </tr>
    <tr>
      <td>Option Key 2</td>
      <td><input name="option_key2" value="<? print $item['option_key2']?>" type="text" /></td>
    </tr>
    <tr>
      <td>Option Value 2</td>
      <td><input name="option_value2" value="<? print $item['option_value2']?>" type="text" /></td>
    </tr>
  </table>
  <input name="save" class="btn" type="button" onClick="save_option<? print $item['id']; ?>('opt<? print $item['id']; ?>')" value="save">
</form>
<? endforeach; ?>
