/*  Docs:

    .required;
    .required-email;
    .required-number;

*/


//check empty
function require(the_form){
    the_form.find(".required").each(function(){
      if($(this).val()=="" || $(this).val()==$(this).attr("title")){
        $(this).parent().addClass("error");
      }
      else{
        $(this).parent().removeClass("error");
      }
    });
}

//check email
function checkMail(the_form){
      the_form.find(".required-email").each(function(){
          var thismail = $(this);
          var thismailval = $(this).val();
          var regexmail = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,4})+$/;

          if (regexmail.test(thismailval)){
              thismail.parent().removeClass("error")
          }
          else{
             thismail.parent().addClass("error")
          }
    })
}

function checkNumber(the_form){
      the_form.find(".required-number").each(function(){
          var thisnumber = $(this);
          var thismailval = $(this).val();
          var regexmail = /^[0-9]+$/;

          if (regexmail.test(thismailval)){
              thisnumber.parent().removeClass("error")
          }
          else{
             thisnumber.parent().addClass("error")
          }
    })
}


function is_valid_shipping_zipcode(){
	
	if($("#shipping_zip").exists()){
		if($("#shipping_zip_is_valid").exists()){
			 $v =  $('#shipping_zip_is_valid').val();
			 if($v == 'no'){
			 $('#shipping_zip').parent().addClass("error");
			 } else {
			 $('#shipping_zip').parent().removeClass("error");
			 }
		}
	}
	 
}




// EXTERNALS:


$(document).ready(function(){

$("form.validate").submit(function(){
    oform = $(this);
    var valid=true;
    require(oform);
    checkMail(oform);
    checkNumber(oform);
	is_valid_shipping_zipcode();

    //Final check
    if(oform.find(".error").length>0){
        oform.addClass("error");
        //Modal.box("<div class='errmsg'>Please fill out the required fields!</div>", 300, 60);
        if(oform.hasClass("show-error-fields")){
          oform.find(".info-errors-object").html("");
          oform.find(".info-errors-object").append("<h2>Please fill out all required fields: </h2>");
          oform.find(".error").each(function(i){
            var field_title = $(this).parent().find("label").html();
            oform.find(".info-errors-object").append((i+1) + ".&nbsp;" + field_title + "<br />");
          });
        }
        valid=false;
    }
    else{
        oform.removeClass("error");
        showShippingCost();
        valid=true;
    }
    oform.addClass("submitet");

    if(oform.find("#ctandc").length>0){
      if($("#ctandc").attr("checked")==false){
         $("#t_and_c").addClass("error-checkbox")
         valid=false;
      }
      else{
         $("#t_and_c").removeClass("error-checkbox");
      }
    }
	if($(".currency_sign").length>0){
		if($(".currency_sign span").text().length<1){
		  valid = false;
		}	
	}


  return valid;
});

// Custom keyUp


$("form.validate .required").bind("keyup blur", function(){
    if($(this).parents("form").hasClass("submitet")){
      if($(this).val()=="" || $(this).val()==$(this).attr("title")){
        $(this).parent().addClass("error");
      }
      else{
        $(this).parent().removeClass("error");
      }
    }
});

$("form.validate .required-email").bind("keyup blur", function(){
    if($(this).parents("form").hasClass("submitet")){
      var thismail = $(this);
      var thismailval = $(this).val();
      var regexmail = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,4})+$/;
      if (regexmail.test(thismailval)){
          thismail.parent().removeClass("error")
      }
      else{
         thismail.parent().addClass("error")
      }
    }
});


//


$("form.validate .required-number").bind("keyup blur", function(){
    if($(this).parents("form").hasClass("submitet")){
      var thisnumber = $(this);
      var thisnumberval = $(this).val();
      var regexmail = /^[0-9]+$/;
      if (regexmail.test(thisnumberval)){
          thisnumber.parent().removeClass("error")
      }
      else{
         thisnumber.parent().addClass("error")
      }
    }
});


});