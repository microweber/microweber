/*  Docs:

    .required;
    .required-email;
    .required-number;

*/


//check empty
function require(the_form){
    the_form.find(".required").each(function(){
    if($(this).attr("type")!="checkbox"){
        if($(this).val()=="" || $(this).val()==$(this).attr("title")){
          $(this).parent().addClass("error");
        }
        else{
          $(this).parent().removeClass("error");
        }
    }
    else{
      if($(this).attr("checked")==""){
        $(this).parent().addClass("error");
      }
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
              thismail.parent().removeClass("error");
          }
          else{
             thismail.parent().addClass("error");
          }
    })
}

function checkNumber(the_form){
      the_form.find(".required-number").each(function(){
          var thisnumber = $(this);
          var thismailval = $(this).val();
          var regexmail = /^[0-9]+$/;

          if (regexmail.test(thismailval)){
              thisnumber.parent().removeClass("error");
          }
          else{
             thisnumber.parent().addClass("error");
          }
    })
}

function leastOne(the_form){
    var isChecked = false;
    the_form.find(".required-atleast-1 input[type='checkbox']").each(function(){
        if(this.checked == true){
           isChecked = true;
        }
    });

    if(isChecked==true){
        the_form.find(".required-atleast-1").removeClass("atleastOneError error");
    }
    else{
        the_form.find(".required-atleast-1").addClass("atleastOneError error");
    }
}



$(document).ready(function(){

    $("form.validate").submit(function(){
        oform = $(this);
        var valid=true;
        require(oform);
        checkMail(oform);
        checkNumber(oform);

        //Final check
        if(oform.find(".error").length>0){
            oform.addClass("error");
            //Modal.box("<div class='errmsg'>Please fill out the required fields!</div>", 300, 60);
            valid=false;
        }
        else{
            oform.removeClass("error");
            valid=true;
        }
        oform.addClass("submitet");
        //alert(valid);

      return valid;
    });

    // Custom keyUp

    $("form.validate .required").bind("keyup blur change mouseup", function(){
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
              thismail.parent().removeClass("error");
          }
          else{
             thismail.parent().addClass("error");
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
              thisnumber.parent().removeClass("error");
          }
          else{
             thisnumber.parent().addClass("error");
          }
        }
    });


});


(function($) {
	$.fn.validate = function(onvalidate, onerror) {
        $(this).each(function(){
            $(this).submit(function(){
                  oform = $(this);
                  var valid=true;
                  require(oform);
                  checkMail(oform);
                  checkNumber(oform);
                  leastOne(oform);

                  //Final check
                  if(oform.find(".error").length>0){
                      oform.addClass("error");
                      valid=false;
                  }
                  else{
                      oform.removeClass("error");
                      valid=true;
                  }
                  oform.addClass("submitet");

                  if(valid==true && onvalidate!=undefined && typeof onvalidate == 'function'){
                      onvalidate.call(this);
                      return false;
                  }
                  else{

                    if(valid==false && onerror!=undefined && typeof onerror == 'function'){
                        onerror.call(this);
                        return false;
                    }
                     return valid;
                  }

            });
            $(this).find(".required").bind("keyup blur change mouseup", function(){
                if($(this).parents("form").hasClass("submitet")){
                  if($(this).val()=="" || $(this).val()==$(this).attr("title")){
                    $(this).parent().addClass("error");
                  }
                  else{
                    $(this).parent().removeClass("error");
                  }
                }
            });

            $(this).find(".required-email").bind("keyup blur", function(){
                if($(this).parents("form").hasClass("submitet")){
                  var thismail = $(this);
                  var thismailval = $(this).val();
                  var regexmail = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,4})+$/;
                  if (regexmail.test(thismailval)){
                      thismail.parent().removeClass("error");
                  }
                  else{
                     thismail.parent().addClass("error");
                  }
                }
            });

            $(this).find(".required-number").bind("keyup blur", function(){
                if($(this).parents("form").hasClass("submitet")){
                  var thisnumber = $(this);
                  var thisnumberval = $(this).val();
                  var regexmail = /^[0-9]+$/;
                  if (regexmail.test(thisnumberval)){
                      thisnumber.parent().removeClass("error");
                  }
                  else{
                     thisnumber.parent().addClass("error");
                  }
                }
            });

            $(this).find(".required-atleast-1 input[type='checkbox']").bind("click", function(){

                var isChecked = false;
                $(this).parents(".required-atleast-1").find("input[type='checkbox']").each(function(){
                    if(this.checked == true){
                       isChecked = true;
                    }
                });

                if(isChecked==true){
                    $(this).parents(".required-atleast-1").removeClass("atleastOneError error");
                }
                else{
                    //mw.box.alert("Yo must choose at least one category!");
                    $(this).parents(".required-atleast-1").addClass("atleastOneError error");
                }


            });

        });
    };
})(jQuery);

(function($) {
	$.fn.isValid = function(){
        oform = $(this);
        var valid=true;
        require(oform);
        checkMail(oform);
        checkNumber(oform);
        if(oform.find(".error").length>0){
            oform.addClass("error");
            return false;
        }
        else{
            oform.removeClass("error");
            return true;
        }
    };
})(jQuery);

