
<?
$all_options = CI::model('core')->optionsGet ( false );

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
 
<form id="opt<? print $item['id']; ?>">
<table width="100%" border="0">
  
  
  
 
      
  
  <tr>
    <td><input name="save" type="button" onClick="save_option('opt<? print $item['id']; ?>')" value="save">id</td>
    <td><input name="id" value="<? print $item['id']?>" type="text" /></td>
  </tr>
  
  <tr>
    <td>option_key</td>
    <td><input name="option_key" value="<? print $item['option_key']?>" type="text" /></td>
  </tr>
  
  <tr>
    <td>option_value</td>
    <td><input name="option_value" value="<? print $item['option_value']?>" type="text" /></td>
  </tr>
  
  
  <tr>
    <td>option_group</td>
    <td><input name="option_group" value="<? print $item['option_group']?>" type="text" /></td>
  </tr>
  
  
  
  <tr>
    <td>option_key2</td>
    <td><input name="option_key2" value="<? print $item['option_key2']?>" type="text" /></td>
  </tr>
  
  
  <tr>
    <td>option_value2</td>
    <td><input name="option_value2" value="<? print $item['option_value2']?>" type="text" /></td>
  </tr>

</table>


</form>
<br>
<hr>

<? endforeach; ?>