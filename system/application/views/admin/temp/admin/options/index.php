<?php $option_groups = $this->core_model->optionsGetGroups();  ?>

<?php // var_dump($option_groups); ?>

<script type="text/javascript">
 $(document).ready(function(){
    $("#options_groups_tabs").tabs(
	{ cookie: { expires: 30 } 
	
	}
	
	);
  });
  
  
  
  
  
  
  
  // prepare the form when the DOM is ready 
$(document).ready(function() { 
    var options = { 
      //  target:        '#output1',   // target element(s) to be updated with server response 
        beforeSubmit:  showRequest,  // pre-submit callback 
		type:      'post',
        success:       showResponse  // post-submit callback 
 
        // other available options: 
        //url:       url         // override for form's 'action' attribute 
        //type:      type        // 'get' or 'post', override for form's 'method' attribute 
        //dataType:  null        // 'xml', 'script', or 'json' (expected server response type) 
        //clearForm: true        // clear all form fields after successful submit 
        //resetForm: true        // reset the form after successful submit 
 
        // $.ajax options can be used here too, for example: 
        //timeout:   3000 
    }; 
 
    // bind form using 'ajaxForm' 
    $('.optionsAjaxSaveForm').ajaxForm(options); 
}); 
 
// pre-submit callback 
function showRequest(formData, jqForm, options) { 
    // formData is an array; here we use $.param to convert it to a string to display it 
    // but the form plugin does this for you automatically when it submits the data 
   // var queryString = $.param(formData); 
 
    // jqForm is a jQuery object encapsulating the form element.  To access the 
    // DOM element for the form do this: 
    // var formElement = jqForm[0]; 
 
   // alert('About to submit: \n\n' + queryString); 
 
    // here we could return false to prevent the form from being submitted; 
    // returning anything other than false will allow the form submit to continue 
    return true; 
} 
 
// post-submit callback 
function showResponse(responseText, statusText)  { 
    // for normal html responses, the first argument to the success callback 
    // is the XMLHttpRequest object's responseText property 
 
    // if the ajaxForm method was passed an Options Object with the dataType 
    // property set to 'xml' then the first argument to the success callback 
    // is the XMLHttpRequest object's responseXML property 
 
    // if the ajaxForm method was passed an Options Object with the dataType 
    // property set to 'json' then the first argument to the success callback 
    // is the json data object returned by the server 
 
   // alert('status: ' + statusText + '\n\nresponseText: \n' + responseText + 
      //  '\n\nThe output div should have already been updated with the responseText.'); 
} 
</script>



<script type="text/javascript">



function delete_option_by_id($option_id){
		var del_order_url = '<?php print site_url('admin/options/ajax_delete_by_id') ?>';
		var where_to= confirm("Are you sure you want to delete this option? This cannot be undone!");
		if (where_to== true)
		{
		  
			$.post(del_order_url, { id: $option_id },
			  function(data){
			  	$("#option_id_"+$option_id).fadeOut();
			
			  });
			
		}
}


</script>







<div id="options_groups_tabs">
    <ul>
    <?php foreach($option_groups as $option_group): ?>
        <li><a href="#option_group_<?php print $option_group ?>"><span><?php print $option_group ?></span></a></li>
     <?php endforeach; ?>   
    </ul>
     <?php foreach($option_groups as $option_group): ?>
      <div id="option_group_<?php print $option_group ?>">
       
       
       <?php foreach($all_options as $item) : ?> 
       <?php if($item['option_group'] == $option_group): ?>
<br />
<hr />
<form action="<?php print site_url('admin/options/index') ; ?>" method="post"  enctype="application/x-www-form-urlencoded" class="optionsForm optionsAjaxSaveForm" id="option_id_<?php print $item['id'] ; ?>">
<table border="0">
  <tr>
    <th scope="row">option_key</th>
    <td><input name="option_key" value="<?php print $item['option_key'] ; ?>" type="text" style="width:300px;" /></td>
  </tr>
  <tr>
    <th scope="row">option_value</th>
    <td><textarea name="option_value"  style="width:300px;"  cols="" rows=""><?php print $item['option_value'] ; ?></textarea></td>
  </tr>
   <tr>
    <th scope="row">option_value 2</th>
    <td><textarea name="option_value2"  style="width:300px;"  cols="" rows=""><?php print $item['option_value2'] ; ?></textarea></td>
  </tr>
  <tr>
    <th scope="row">option_group</th>
    <td><input name="option_group"  value="<?php print $item['option_group'] ; ?>"  type="text" /></td>
  </tr>
  <tr>
    <th scope="row">save</th>
    <td><input name="save" value="save" type="submit" /><input name="id" value="<?php print $item['id'] ; ?>" type="hidden" /></td>
  </tr>
  <tr>
    <th scope="row"></th>
    <td><a href="javascript:delete_option_by_id(<?php print $item['id'] ; ?>)">delete</a></td>
  </tr>
</table>
</form>
<?php endif; ?>
<?php endforeach ; ?>
       
       
       
       
    </div>
      
     <?php endforeach; ?>  
   
 
</div>








