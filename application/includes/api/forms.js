window.mw = window.mw ? window.mw : {};

FieldUnify = function(a){
  return typeof a === 'string' ? a : ( typeof a === 'object' && a.tagName !== undefined ? a.value : mw.error('Parameter must be string or DOM node.'));
}





mw.form = {
  typeNumber:function(el){
    el.value = el.value.replace(/[^0-9\.,]/g,'');
  },
  fixNum:function(el){
    el.value = el.value.replace(/,/g,'');
    var arr = el.value.split('.');
    var len = arr.length;
    if(len>1){
      if(arr[len-1]===''){
          arr[len-1] = '.00';
      }
      else{
         arr[len-1] = '.' + arr[len-1];
      }
      el.value = arr.join('');
    }

  },
  dstatic:function(event, d){
    var d = d || $(event.target).dataset('default') || false;
    if(!!d){
        var type = event.type;
        var target = event.target;
        if(type=='focus'){
           target.value==d?target.value='':'';
        }
        else if(type=='blur'){
           target.value==''?target.value=d:'';
        }
        else if(type=='keyup'){
           $(target).addClass('loading');
        }
    }
  },
  post:function(selector, url_to_post, callback){
    var is_form_valid = mw.form.validate.init(selector);

	if(!mw.is.defined(url_to_post)){

		url_to_post = mw.settings.site_url+'api/post_form';
		
	} else {
		url_to_post = url_to_post;
	}
	

    if(is_form_valid){
        var obj = mw.form.serialize(selector);
      	$.post(url_to_post, obj, function(data){
			if(mw.is.func(callback)){
				//callback.call(selector);
				callback.call(data);

			} else {
				//alert(data);
				return data;
			}

        });
    }
	return false;
  },
  validate:{
    checkbox: function(obj){
        return obj.checked == true;
    },
    field:function(obj){
        return FieldUnify(obj).replace(/\s/g, '') != '';
    },
    email:function(obj){
        var regexmail = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,6})+$/;
        return regexmail.test(FieldUnify(obj));
    },
    url:function(obj){
       var rurl = /^((https?|ftp):\/\/)?(((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:)*@)?(((\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5]))|((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?)(:\d*)?)(\/((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)+(\/(([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)*)*)?)?(\?((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|[\uE000-\uF8FF]|\/|\?)*)?(\#((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|\/|\?)*)?$/;
       return rurl.test(FieldUnify(FieldUnify(obj)));
    },
    radio:function(objname){
        var radios = document.getElementsByName(objname), i = 0, len = radios.length;
        this_radio_valid = false;
        for( ; i < len ; i++){
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
  },
  serialize : function(id){
    return mw.serializeFields(id);
  }
}









