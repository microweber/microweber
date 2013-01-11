<script type="text/javascript">

function checkout_callback(data,selector){
	//
	data = $.parseJSON(data);

	if(data.success !== undefined){
		$(selector).empty().append(data.success);	 
	} else if(data.error !== undefined){
		
		$(selector).find('.cc_process_error').html(data.error);
		
		//alert(data.error);	
	} else {
	alert(data);	
	}
}

 
</script>
<table  border="0">
  <tr>
    <td>first_name</td>
    <td><input name="cc_first_name"  type="text" value="" /></td>
  </tr>
  <tr>
    <td>last_name</td>
    <td><input name="cc_last_name"  type="text" value="" /></td>
  </tr>
  <tr>
    <td>cc_type</td>
    <td><select name="cc_type">
        <option value="Visa" selected>Visa</option>
        <option value="MasterCard">MasterCard</option>
        <option value="Discover">Discover</option>
        <option value="Amex">American Express</option>
      </select></td>
  </tr>
  <tr>
    <td>number</td>
    <td><input name="cc_number"  type="text" value="" /></td>
  </tr>
  <tr>
    <td>month</td>
    <td><input name="cc_month"  type="text" value="" /></td>
  </tr>
  <tr>
    <td>year</td>
    <td><input name="cc_year"  type="text" value="" /></td>
  </tr>
 
  
  <tr>
    <td>verification_code</td>
    <td><input name="cc_verification_value"  type="text" value="" /></td>
  </tr>
</table>
<div class="cc_process_error"></div>