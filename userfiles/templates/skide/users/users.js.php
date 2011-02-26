<script type="text/javascript">
 /*$(document).ready(function() {
  var user_register_options = {
        //target:        '#output1',   // target element(s) to be updated with server response
		url:       '<?php print site_url('main/mailform_send2'); ?>'  ,
		clearForm: true,
		type:      'post',
		contentType: 'multipart/form-data',

        beforeSubmit:  mail_form_showRequest,  // pre-submit callback
        success:       mail_form_showResponse  // post-submit callback

    };

    $('#contact_form').submit(function(){
        $(this).ajaxSubmit(options);
        return false;

    });
	
 
	
	
	
});



$(document).ready(function() {
	$(".user_registation_trigger").handleKeyboardChange(1000).change(function()
	{
	setUserRegistration()
	});
});



function setUserRegistration(){
var queryString = $('#register-form .user_registation_trigger').fieldSerialize();



$.ajax({
   type: "POST",
   async: true,
   url: "<?php print site_url('ajax_helpers/users_register'); ?>",
   data: queryString,
   success: function(data){
	   if(data != 'ok'){
       alert(data);
	   }
   }
 });





//alert(queryString);
	
} 

*/
 




function usersPostDelete(post_id){
	
	
var agree=confirm("Are you sure you want to delete this post?");
if (agree){

	$.ajax({
	   type: "POST",
	   async: true,
	   url: "<?php print site_url('users/user_action:post_delete/id:') ?>"+post_id,
	   //data: queryString, 
	   success: function(data){
		   if(data != 'ok'){
		   alert(data);
		   } else {
			   $('#post-id-'+post_id).fadeOut();
		   }
	   }
	});
}
	
}

 
function usersCommentDelete(comment_id){
	
	
var agree=confirm("Are you sure you want to delete this comment?");
if (agree){

	$.ajax({
	   type: "POST",
	   async: true,
	   url: "<?php print site_url('users/user_action:comment_delete/id:') ?>"+comment_id,
	   //data: queryString, 
	   success: function(data){
		   if(data != 'ok'){
		   alert(data);
		   } else {
			   $('#comment-'+comment_id).fadeOut();  
		   }
	   }
	}); 
}
	
}
 
// example voting <div onclick="voteUp('<?php print base64_encode('table_users_statuses') ?>', '<?php print base64_encode(15) ?>', 'status-votes-15');">vote up status</div>
// moved to the users api
function voteUp(toTable, toTableId, updateElementId)
{
    $.post(
        '<?php print site_url();  ?>ajax_helpers/votes_cast', 
        { t: toTable, tt: toTableId },
        function(response){
            if (response == 'yes') {
                // increase votes count
            	$('#'+updateElementId).html(parseInt($('#'+updateElementId).html()) + 1);
				
            }
        }
	);
}

</script>
