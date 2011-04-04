
<?



//p($params);


$options = array();
if($params['for_module'] != ''){
	
	$options['module']= $params['for_module'];
	
}


 

$all_options = CI::model('core')->optionsGet ( $options );
if($all_options == false){
$all_options = array();	
}
//p($all_options);
$all_options[] = array();
?>
<script type="text/javascript">
var save_option = function($form_id){
	data123 = $('#'+$form_id).serialize();
	$.post("<? print site_url('api/content/save_option') ?>",  data123 ,	function(data1){
																					 
										$('#'+$form_id).fadeOut().fadeIn();											 
																					 });
}
</script>

<? foreach($all_options as $item): ?>
 
<form class="order_options_form" id="opt<? print $item['id']; ?>">
<table width="100%" border="0" class="order_options_table">
  

  
 
      
  
  <tr>
    <td>id</td>
    <td><input name="id" value="<? print $item['id']?>" type="text" /></td>
  </tr>
  
  <tr>
    <td>Option Key</td>
    <td><input name="option_key" value="<? print $item['option_key']?>" type="text" /></td>
  </tr>
  
  <tr>
    <td>Option Value</td>
    <td>
    <?  //p($item)?>
    
    
    
 <mw module="forms/field" name="option_value" value="<? print encode_var($item['option_value'])?>" type="<? print $item['type']?>" />
  
    
    

 
    
    
    </td>
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

 <input name="save" class="btn" type="button" onClick="save_option('opt<? print $item['id']; ?>')" value="save">

</form>


<? endforeach; ?>