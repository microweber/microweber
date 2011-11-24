<script type="text/javascript">
 /*$(document).ready(function() {
  var user_register_options = {
        //target:        '#output1',   // target element(s) to be updated with server response
		url:       '<? print site_url('main/mailform_send2'); ?>'  ,
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
   url: "<? print site_url('ajax_helpers/users_register'); ?>",
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
	   url: "<? print site_url('users/user_action:post_delete/id:') ?>"+post_id,
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
	   url: "<? print site_url('users/user_action:comment_delete/id:') ?>"+comment_id,
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
 
// example voting <div onclick="voteUp('<? print base64_encode('table_users_statuses') ?>', '<? print base64_encode(15) ?>', 'status-votes-15');">vote up status</div>
function voteUp(toTable, toTableId, updateElementId)
{
    $.post(
        '<? print site_url();  ?>ajax_helpers/votes_cast', 
        { t: toTable, tt: toTableId },
        function(response){
            if (response == 'yes') {
                // increase votes count
            	$('#'+updateElementId).html(parseInt($('#'+updateElementId).html()) + 1);
				
            }
        }
	);
}

function userPictureDelete(){
	if(confirm('Do You realy want to delete this photo?')){
		$.post("<?php  print site_url('ajax_helpers/user_delete_picture')  ?>", { time: Math.random() },
				   function(data){			
			 			$("#user_image").hide();
			 			$("#user_image_href").hide();
				   });
	}
	
	
}
 
                    $(document).ready(function(){

                        var options = {
                            //target:        '#output2',   // target element(s) to be updated with server response
                            beforeSubmit:  Before_submit,  // pre-submit callback
                            success:       After_submit,  // post-submit callback
                            // other available options:
                            url:       '<? print site_url('main/mailform_send2'); ?>'         // override for form's 'action' attribute
                            //type:      type        // 'get' or 'post', override for form's 'method' attribute
                            //dataType:  null        // 'xml', 'script', or 'json' (expected server response type)
                            //clearForm: true        // clear all form fields after successful submit
                            //resetForm: true        // reset the form after successful submit

                            // $.ajax options can be used here too, for example:
                            //timeout:   3000
                        };

                        $("#contact-form").submit(function(){
                          if($(this).hasClass("error")){
                             /*  */
                          }
                          else{

                             $("#contact-form").ajaxSubmit(options);

                             return false;
                          }
                        });

                    });

                      function Before_submit(){
                          //alert(1)
                          $("#cloading").css("visibility", "visible");
                          return true;
                      }

                      function After_submit(data){
                          $("#cloading").css("visibility", "hidden");
                           var message = "<div id='msgSent'>Thank you. <br />Your message has been sent.</div>"+data;
                           sobbox.displayHtml(message, 400, 100);
                      }
</script>
