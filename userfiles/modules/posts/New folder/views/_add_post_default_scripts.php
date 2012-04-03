<? $form_id = md5(serialize(json_encode($params))); ?>
<?
if($id){
$the_post = get_post($id);
}
if($post_id){
$the_post = get_post($post_id);
}


if(!empty($the_post)){
	$more = false;
						$more = CI::model('core')->getCustomFields ( 'table_content', $the_post ['id'] );
						$the_post ['custom_fields'] = $more;
	
}

?>
<script type="text/javascript">
$(document).ready(function() { 
    var options = {
       url:       '<? print site_url('api/content/save_post'); ?>',
	   type: 'post',
	   
	   //target:        '#output2',   // target element(s) to be updated with server response 
        beforeSubmit:  showRequest,  // pre-submit callback 
		
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
 
    // bind to the form's submit event 
    $('#<? print $form_id ?>').submit(function() { 
        // inside event callbacks 'this' is the DOM element so we first 
        // wrap it in a jQuery object and then invoke ajaxSubmit 
        $(this).ajaxSubmit(options); 
 
        // !!! Important !!! 
        // always return false to prevent standard browser submit and page navigation 
        return false; 
    }); 
}); 
 
// pre-submit callback 
function showRequest(formData, jqForm, options) { 
    // formData is an array; here we use $.param to convert it to a string to display it 
    // but the form plugin does this for you automatically when it submits the data 
    var queryString = $.param(formData); 
 
    // jqForm is a jQuery object encapsulating the form element.  To access the 
    // DOM element for the form do this: 
    // var formElement = jqForm[0]; 
 
   // alert('About to submit: \n\n' + queryString); 
 
    // here we could return false to prevent the form from being submitted; 
    // returning anything other than false will allow the form submit to continue 
    return $('#<? print $form_id ?>').isValid();
} 
 
// post-submit callback 
function showResponse(responseText, statusText, xhr, $form)  { 
    // for normal html responses, the first argument to the success callback 
    // is the XMLHttpRequest object's responseText property 
//  modal.close(); 
  //alert(responseText);
  <? if($redirect_on_success == false): ?>
  window.location.reload(true);
  <? else: ?>
  window.location.href= '<? print $redirect_on_success; ?>';
  <? endif; ?>
  
    // if the ajaxSubmit method was passed an Options Object with the dataType 
    // property set to 'xml' then the first argument to the success callback 
    // is the XMLHttpRequest object's responseXML property 

    // if the ajaxSubmit method was passed an Options Object with the dataType
    // property set to 'json' then the first argument to the success callback 
    // is the json data object returned by the server 

   // alert('status: ' + statusText + '\n\nresponseText: \n' + responseText +    '\n\nThe output div should have already been updated with the responseText.'); 
} 





</script>

<script>

$(document).ready(function(){
    val = $(".taxonomy_categories:last").val();
    $("#cat_select").val(val);
    $("#cat_select").change(function(){
       val = $(this).val();
       $(".taxonomy_categories").val(val)
    });
});

</script>

 