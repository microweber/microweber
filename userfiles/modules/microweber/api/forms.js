
var getFieldValue = function(a){
  return typeof a === 'string' ? a : ( typeof a === 'object' && a.tagName !== undefined ? a.value : null);
};


//Cross-browser placeholder






mw.form = {
  typeNumber:function(el){
    el.value = el.value.replace(/[^0-9\.,]/g,'');
  },
  fixPrice:function(el){
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
    d = d || mw.$(event.target).dataset('default') || false;
    var type = event.type;
    var target = event.target;
    if(!!d){
        if(type === 'focus'){
           target.value==d?target.value='':'';
        }
        else if(type=='blur'){
           target.value==''?target.value=d:'';
        }
    }
    if(type=='keyup'){
        mw.$(target).addClass('loading');
    }
  },
  post:function(selector, url_to_post, callback, ignorenopost, callback_error, callback_user_cancel){
    mw.session.checkPause = true;
    if(selector.constructor === {}.constructor){
      return mw.form._post(selector);
    }

    callback_error = callback_error || false;
    ignorenopost = ignorenopost || false;
    var is_form_valid = mw.form.validate.init(selector);

	if(!url_to_post){

		url_to_post = mw.settings.site_url + 'api/post_form';

	}

 // var is_form_valid = true;


    if(is_form_valid){
        var obj = mw.form.serialize(selector, ignorenopost);
      	var xhr = $.ajax({
            url: url_to_post,
            data: obj,
            success: function(data){
                mw.session.checkPause = false;
                if(typeof callback === 'function'){
                    callback.call(data, mw.$(selector)[0]);
                } else {
                    return data;
                }
            },
            onExternalDataDialogClose: function() {
                if(callback_user_cancel) {
                    callback_user_cancel.call();
                }
            }
      	});
        xhr.fail(function(a,b) {
           mw.session.checkPause = false;
           if(typeof callback_error === 'function'){
              callback_error.call(a,b);
           }
        });
    }
	return false;
  },
  _post:function(obj){
    mw.form.post(obj.selector, obj.url, obj.done, obj.ignorenopost, obj.error);
  },
  validate:{
    checkbox: function(obj){
        return obj.checked === true;
    },
    field:function(obj){
		return getFieldValue(obj).replace(/\s/g, '') != '';
    },
    email:function(obj){
        var regexmail = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,6})+$/;
        return regexmail.test(getFieldValue(obj));
    },
    url:function(obj){
	  /* var rurl =/(\b(https?|ftp|file):\/\/[-A-Z0-9+&@#\/%?=~_|!:,.;]*[-A-Z0-9+&@#\/%=~_|])/ig; */
       var rurl = /^((https?|ftp):\/\/)?(((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:)*@)?(((\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5]))|((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?)(:\d*)?)(\/((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)+(\/(([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)*)*)?)?(\?((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|[\uE000-\uF8FF]|\/|\?)*)?(\#((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|\/|\?)*)?$/;
       return rurl.test(getFieldValue(getFieldValue(obj)));
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
        var parent = mw.$(document.getElementsByName(objname)[0].parentNode);
        if(this_radio_valid){
           parent.removeClass("error");
        }
        else{
           parent.addClass("error");
        }
        return this_radio_valid;
    },
    image_url:function(url, valid, invalid){
        var url = url.replace(/\s/gi,'');
        if(url.length<6){
            typeof invalid =='function'? invalid.call(url) : '';
            return false;
        }
        else{
          if(!url.contains('http')){var url = 'http://'+url}
          if(!window.ImgTester){
              window.ImgTester = new Image();
              document.body.appendChild(window.ImgTester);
              window.ImgTester.className = 'semi_hidden';
              window.ImgTester.onload = function(){
                typeof valid =='function'? valid.call(url) : '';
              }
              window.ImgTester.onerror = function(){
                typeof invalid =='function'? invalid.call(url) : '';
              }
          }
          window.ImgTester.src = url;
        }
    },
    proceed:{
      checkbox:function(obj){
        if(mw.form.validate.checkbox(obj)){
            mw.$(obj).parents('.field').removeClass("error");
        }
        else{
            mw.$(obj).parents('.field').addClass("error");
        }
      },
      field:function(obj){
        if(mw.form.validate.field(obj)){
           mw.$(obj).parents('.field').removeClass("error");
         }
         else{
           mw.$(obj).parents('.field').addClass("error");
         }
      },
      email:function(obj){
        if(mw.form.validate.email(obj)){
           mw.$(obj).parents('.field').removeClass("error");
        }
        else{
           mw.$(obj).parents('.field').addClass("error");
        }
      }
    },
    checkFields:function(form){
        mw.$(form).find(".required,[required]").each(function(){
          var type = mw.$(this).attr("type");
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
        mw.$(form).find(".required-email").each(function(){
            mw.form.validate.proceed.email(this);
        });
    },
    init:function(obj){
        mw.form.validate.checkFields(obj);
        if($(obj).find(".error").length>0){
            mw.$(obj).addClass("error submited");
            return false;
        }
        else{
           mw.$(obj).removeClass("error");
            return true;
        }
    }
  },
  serialize : function(id, ignorenopost){
    var ignorenopost = ignorenopost || false;
    return mw.serializeFields(id, ignorenopost);
  }
}


mw.postForm = function(o){
  return mw.form._post(o);
}













