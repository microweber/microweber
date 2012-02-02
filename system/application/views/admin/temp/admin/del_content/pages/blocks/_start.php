
<script type="text/javascript">




  $(function(){
  $('label.required').append('&nbsp;<strong>*</strong>&nbsp;');
   $("#content_url").addClass("required");


 /* $.validator.addMethod("contentTitleRequired", function(value, element) {
		var $element = $(element)
		function match(index) {
			alert(index.html());
			//return current == index && $(element).parents("#sf" + (index + 1)).length;
		}
		if (match(0) || match(1) || match(2)) {
			//return !this.optional(element);
		}
		return "dependency-mismatch";
	}, $.validator.messages.required)





      $("form#content_form_object").validate();




	  */




	  var validator = $("form#content_form_object").validate({


		rules: {
			content_title: "required",
			validation_valid_categories :  {
				required: true,
				minlength: 2
				//remote: "users.php"
			},
			content_url :  {
				required: true,
				minlength: 1
				//remote: "users.php"
			}

            /*,
			lastname: "required",
			username: {
				required: true,
				minlength: 2,
				remote: "users.php"
			},
			password: {
				required: true,
				minlength: 5
			},
			password_confirm: {
				required: true,
				minlength: 5,
				equalTo: "#password"
			},
			email: {
				required: true,
				email: true,
				remote: "emails.php"
			},
			dateformat: "required",
			terms: "required"*/
		},
		messages: {
			content_title: "The content title is requred!",
			content_url : "The content URL cannot be empty!",
			validation_valid_categories: "Please select at least one category!"
			/*,
			lastname: "Enter your lastname",
			username: {
				required: "Enter a username",
				minlength: jQuery.format("Enter at least {0} characters"),
				remote: jQuery.format("{0} is already in use")
			},
			password: {
				required: "Provide a password",
				rangelength: jQuery.format("Enter at least {0} characters")
			},
			password_confirm: {
				required: "Repeat your password",
				minlength: jQuery.format("Enter at least {0} characters"),
				equalTo: "Enter the same password as above"
			},
			email: {
				required: "Please enter a valid email address",
				minlength: "Please enter a valid email address",
				remote: jQuery.format("{0} is already in use")
			},
			dateformat: "Choose your preferred dateformat",
			terms: " "*/
		},
		// the errorPlacement has to take the table layout into account
		errorPlacement: function(error, element) {
		$error_text = (error.html().toString());
		if($error_text != ''){
		$.gritter.add({
				title: 'Form error!',
				image: '<?php print_the_static_files_url() ; ?>images/validation_alert.png',
				time: 3000,
				text: $error_text
			});
		}
		//error.appendTo ( element.next() );
			//error.prepend("form#content_form_object" );
		/*	if ( element.is(":radio") )
				error.appendTo( element.parent().next().next() );
			else if ( element.is(":checkbox") )
				error.appendTo ( element.next() );
			else
				error.appendTo( element.parent().next() );*/
		},
		// specifying a submitHandler prevents the default submit, good for the demo
		submitHandler: function(form) {
		   form.submit();
		 },

		// set this class to error-labels to indicate valid fields
		success: function(label) {

			// set &nbsp; as text for IE
			//label.html("&nbsp;").addClass("checked");
		}
	});







  })




</script>

<script type="text/javascript">

$(document).ready(function () {
   ajax_content_subtype_change();
   
   //$('#tabs').corners({ radio: 5, inColor: '#016D93', outColor: '#ffffff'});

   var flora_tabs = $(".flora").tabs();

   $('#ccl1 a').click(function() {
        var cc_index = $('#ccl1 a').index(this);
        flora_tabs.tabs('select', cc_index);
        return false;
    });




});

function ajax_content_subtype_change(){
	var content_subtype = $("#content_subtype").val();
	var content_subtype_value = $("#content_subtype_value").val();
$.post("<?php print site_url('admin/content/pages_edit_ajax_content_subtype') ?>", { content_subtype: content_subtype, content_subtype_value: content_subtype_value },
  function(data){
    //alert("Data Loaded: " + data);
	$("#content_subtype_changer").html(data);
	
  });
}


function ajax_content_subtype_change_set_form_value(val){
	//alert('test');
	//return;
	//$("input[@name='users']")
	//var val  = this.val();
	
	//alert(val);
	 $("#content_subtype_value").setValue(val);
}



</script>
<form id="content_form_object" action="<?php print site_url($this->uri->uri_string()); ?>" method="post" enctype="multipart/form-data" class="pull obj_pages_form">

  <input name="id" type="hidden" id="id" value="<?php print $form_values['id']; ?>">
    <input name="media_queue_pictures" type="hidden" id="media_queue_pictures" value="<?php print $form_values['id']; ?>_<?php print $this->session->userdata('session_id'); ?>_media_queue_pictures">
  <input name="media_queue_files" type="hidden" id="media_queue_files" value="<?php print $form_values['id']; ?>_<?php print $this->session->userdata('session_id'); ?>_media_queue_files">
