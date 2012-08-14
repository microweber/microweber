window.mw = window.mw ? window.mw : {};

 
mw.form = {
  post:function(selector){
    var is_form_valid = mw.form.validate.init(selector);
    if(is_form_valid){
        var obj = mw.form.serialize.init(selector);
      	$.post("{SITE_URL}api/forms/post_form", obj, function(data){

        });
    }
	return false;
  },
  serialize : {
      init:function(form){
        return $(form).serialize();
      }
  },
  validate:{
    checkbox: function(obj){
        return obj.checked == true;
    },
    field:function(obj){
        return obj.value != '';
    },
    email:function(obj){
        var regexmail = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,4})+$/;
        return regexmail.test(obj.value);
    },
    radio:function(objname){
        var radios = document.getElementsByName(objname);
        this_radio_valid = false;
        for(i=0; i < radios.length; i++){
            if(radios[i].checked){
                this_radio_valid = true;
                break;
            }
        }
        var parent = $(document.getElementsByName(objname)[0].parentNode);
        if(this_radio_valid){
           parent.removeClass("error");
        }
        else{
           parent.addClass("error");
        }
        return this_radio_valid;
    },
    proceed:{
      checkbox:function(obj){
        if(mw.form.validate.checkbox(obj)){
               $(obj).parents('.field').removeClass("error");
        }
        else{
            $(obj).parents('.field').addClass("error");
        }
      },
      field:function(obj){
        if(mw.form.validate.field(obj)){
           $(obj).parents('.field').removeClass("error");
         }
         else{
           $(obj).parents('.field').addClass("error");
         }
      },
      email:function(obj){
        if(mw.form.validate.email(obj)){
           $(obj).parents('.field').removeClass("error");
        }
        else{
           $(obj).parents('.field').addClass("error");
        }
      }
    },
    checkFields:function(form){
        $(form).find(".required").each(function(){
          var type = $(this).attr("type");
          if(type=='checkbox'){
             mw.form.validate.proceed.checkbox(this);
          }
          else if(type=='radio'){
             mw.form.validate.radio(this.name);
          }
          else{
            mw.form.validate.proceed.field(this);
          }
        });
        $(form).find(".required-email").each(function(){
            mw.form.validate.proceed.email(this);
        });
    },
    init:function(obj){
        mw.form.validate.checkFields(obj);
        if($(obj).find(".error").length>0){
            $(obj).addClass("error submited");
            return false;
        }
        else{
           $(obj).removeClass("error");
            return true;
        }
    }
  }
}


