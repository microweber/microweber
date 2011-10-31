$(document).ready(function(){

});


mw.comments = {};

mw.comments = new function() {

	this.servicesUrl = '{SITEURL}/ajax_helpers/',
	
	 this.ajax_element_update = false,
	
 
	

	

	
	
	this._beforeSend = function(formData, jqForm, options) {
		var queryString = $.param(formData);
		var isValid;
		this.ajax_element_update = false;



		if ($(".ajax_comment_form").hasClass("error")) {
			isValid = false;
		//	this.ajax_element_update = false;
		} else {
			for (var i=0; i < formData.length; i++) {
		        if (formData[i].value) {
		        	//alert(formData[i].name);
		            if(formData[i].name == 'update_element'){
		            //	alert(formData[i].value);
		            	 this.ajax_element_update = formData[i].value;
		            //	alert(mw.comments. ajax_element_update);
		            }


		            if(formData[i].name == 'hide_element'){
		            	$(formData[i].value).fadeOut();
		            }
		            
		            if(formData[i].name == 'show_element'){
		            	$(formData[i].value).fadeIn();
		            }
		            
		           // return false; 
		        } 
		    }
			

		}

		return isValid;
	}

	this._afterSend = function(responseText, statusText, xhr, $form) {
	//	alert( this.ajax_element_update);
		                    eventlistener = 'onAfterComment';
		mw.comments.getComments($form, $update_element_selector = this.ajax_element_update);
	}

	/* ~~~ public methods ~~~ */

	this.postComment = function(form) {

		var requestOptions = {
			url : '{SITEURL}api/comments/' + 'comments_post',
			clearForm : false,
			async : false,
			type : 'post',
			data: form.fieldSerialize(),
			beforeSubmit : this._beforeSend,
			success : this._afterSend
		};

		form.ajaxSubmit(requestOptions);
		return false;
	};
 

	this.getComments = function(form) {
 
		 
		
		$upd =($update_element_selector);
		//alert($upd);
		var requestOptions = {
				url : '{SITEURL}api/comments/' + 'comments_list',
				resetForm : true,
				async : false,
				type : 'post',
			//	data: form.fieldSerialize(),
				success: function(resp){
			$($upd).html(resp);
			
		     //alert( "Data Saved: " + resp );
		   }
			};

			form.ajaxSubmit(requestOptions);
			return false;
 
	};

}


			  mw.ready(".ajax_comment_form", function(){
		         $(this).submit(function(){
                      if($(this).isValid()){
                          mw.comments.postComment($(this));
  					  }
      				  return false;
		         })
			  });

$(document).ready(function() {
			
			     $(window).load(function(){

			     })




			
			

			
			
			
			

			
			
			
			

			
			

			
			$('.commentForm').submit(function(){

                if($(this).isValid()){
                  mw.comments.postComment($(this), $(this).find(
                  		"input[name='to_table']").val(), $(this).find(
                  		"input[name='to_table_id']").val(), $(this)
                  		.find("input[name='update_element']").val());
                  $(this).fadeOut();

                  $(this).find('.commentFormSuccess').fadeIn();
                  location.reload(true);



                }
                else{

                }

                return false;
			});

			
		});




mw.comments.post = function($form_selector, $callback) {
	$data = ($($form_selector).serialize());

	$.ajax( {
		type : "POST",
		url : "{SITE_URL}api/comments/comments_post",
		data : $data,
		dataType: 'json',
		success : function(msg) {
			//alert("Data: " + msg);
		 // $('.cart_items_qty').html(msg);
 
		if (typeof  $callback == 'function') {
			$callback.call(this, msg);
		} else {
			$($callback).fadeOut();
		}
		 
		
		
		
			
		}
	});

}






mw.comments.del = function(id, hide_element_or_callback) {
	var answer = confirm("Are you sure you want to delete this comment?");

	if (answer) {

		$.post('{SITEURL}api/comments/delete', {
			id : id
		}, function(response) {
			if (response.indexOf('yes')!=-1) {
				if (typeof (hide_element_or_callback) == 'function') {
					hide_element_or_callback.call(this);
				} else {
					$(hide_element_or_callback).fadeOut();
				}
			} else {
				alert(response);
			}
		});

	}

}
