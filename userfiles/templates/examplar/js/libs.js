function css_browser_selector(u) {
    var ua = u.toLowerCase(),
        is = function (t) {
            return ua.indexOf(t) > -1
        },
        g = 'gecko',
        w = 'webkit',
        s = 'safari',
        o = 'opera',
        m = 'mobile',
        h = document.documentElement,
        b = [(!(/opera|webtv/i.test(ua)) && /msie\s(\d)/.test(ua)) ? ('ie ie' + RegExp.$1) : is('firefox/2') ? g + ' ff2' : is('firefox/3.5') ? g + ' ff3 ff3_5' : is('firefox/3.6') ? g + ' ff3 ff3_6' : is('firefox/3') ? g + ' ff3'  : is('firefox/4') ? g + ' ff4'  : is('firefox/5') ? g + ' ff5'  : is('firefox/6') ? g + ' ff6' : is('gecko/') ? g : is('opera') ? o + (/version\/(\d+)/.test(ua) ? ' ' + o + RegExp.$1 : (/opera(\s|\/)(\d+)/.test(ua) ? ' ' + o + RegExp.$2 : '')) : is('konqueror') ? 'konqueror' : is('blackberry') ? m + ' blackberry' : is('android') ? m + ' android' : is('chrome') ? w + ' chrome' : is('iron') ? w + ' iron' : is('applewebkit/') ? w + ' ' + s + (/version\/(\d+)/.test(ua) ? ' ' + s + RegExp.$1 : '') : is('mozilla/') ? g : '', is('j2me') ? m + ' j2me' : is('iphone') ? m + ' iphone' : is('ipod') ? m + ' ipod' : is('ipad') ? m + ' ipad' : is('mac') ? 'mac' : is('darwin') ? 'mac' : is('webtv') ? 'webtv' : is('win') ? 'win' + (is('windows nt 6.0') ? ' vista' : '') : is('freebsd') ? 'freebsd' : (is('x11') || is('linux')) ? 'linux' : '', 'js'];
    c = b.join(' ');
    h.className += ' ' + c;
    return c;
};
var browser_info = css_browser_selector(navigator.userAgent);


isobj = function(obj){
    if( obj == undefined){
      return false;
    }
    else{return true}
}

$.fn.multiWrap = function(each, wrapString){
    var results =[];
    var elements = $(this);
    if(elements.length>0){
        $.map(elements, function(i, n){
            if(n%each === 0 ){
                results.push(n);
            }
        });
        $.each(results , function(i,v){
            elements.slice(v, v+each).wrapAll(wrapString);
        });
    }
};


slide = {
  init:function(obj){
    var elem = $(obj.elem);
    var ctrl_left = elem.find(".slide_left:first");
    ctrl_left.hide();
    var ctrl_right = elem.find(".slide_right:first");
    var items = isobj(obj.items)?elem.find(obj.items):elem.find("li");
    var width = (items.length)*(items.outerWidth(true));
    if(width<=items.outerWidth(true)){
        ctrl_left.hide();
        ctrl_right.hide();
    }
    var item_width = items.outerWidth(true);
    var step = isobj(obj.step)?obj.step*item_width:item_width;
    var speed = isobj(obj.speed)?obj.speed:400;
    var engine = elem.find(".slide_engine:first");
    engine.css({
      left:0,
      width:width,
      position:"relative"
    });
    ctrl_left.click(function(){
      var curr = parseFloat(engine.css("left"));
      if(curr<0){
        ctrl_right.show()
         engine.not(":animated").animate({left:curr+step}, speed, function(){
            var curr = parseFloat(engine.css("left"));
            if(curr>=0){
              ctrl_left.hide()
            }
         });
      }
    });
    ctrl_right.click(function(){
      var curr = parseFloat(engine.css("left"));
      var max = width + curr - engine.parent().outerWidth()-item_width-1;
      if(max>0){
         ctrl_left.show();
         engine.not(":animated").animate({left:curr-step}, speed, function(){
             var curr = parseFloat(engine.css("left"));
             var max = width + curr - engine.parent().width()-items.outerWidth(true);
             if(max<=0){
                ctrl_right.hide()
             }
         });
      }
    });
  }
}




// VALIDATE


//Change log:
// added events: valid:function(){....},
// error:function(){....},
// preventSubmit:true, false - the default form submit after validation

//check empty
function require(the_form){
    the_form.find(".required").each(function(){
          if($(this).attr("type")!="checkbox"){
              if($(this).val()=="" ||  $(this).val()==$(this).attr("default")){
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
function checkEqual(the_form){
      the_form.find(".required-equal").each(function(){
          var equalto = $(this).attr("equalto");
          val1 = $(this).parents("form").find("[equalto='" + equalto + "']").eq(0).val();
          val2 = $(this).parents("form").find("[equalto='" + equalto + "']").eq(1).val();
          if(val1!=val2 || val1=='' || val2==''){
              $(this).parents("form").find("[equalto='" + equalto + "']").parent().addClass("error");
          }
          else{
              $(this).parents("form").find("[equalto='" + equalto + "']").parent().removeClass("error");
          }
      });
}


(function($) {
	$.fn.validate = function(options) {
        $(this).each(function(){
            $(this).submit(function(){
                  oform = $(this);
                  var valid=true;
                  require(oform);
                  checkMail(oform);
                  checkNumber(oform);
                  checkEqual(oform);

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

                  if(valid==true){
                    if(options.valid!=undefined && typeof options.valid == 'function'){
                       options.valid.call(this);
                       if(options.preventSubmit){
                         return false;
                       }
                    }
                    else{
                      if(options.preventSubmit){
                         return false;
                       }
                    }

                  }
                  else{
                    if(valid==false){
                      if( options.error!=undefined && typeof options.error == 'function'){
                        options.error.call(this);
                        return valid;
                      }
                      else{
                          return valid;
                      }
                    }
                    else{
                      return valid;
                    }

                  }


            });
            $(this).find(".required").bind("keyup blur change mouseup", function(){
                if($(this).parents("form").hasClass("submitet")){
                  if($(this).val()=="" || $(this).val()==$(this).attr("default")){
                    $(this).parent().addClass("error");
                  }
                  else{
                    $(this).parent().removeClass("error");
                  }
                }
            });
            $(this).find(".required-equal").bind("keyup blur change mouseup", function(){
              if($(this).parents("form").hasClass("submitet")){
                  var equalto = $(this).attr("equalto");
                  val1 = $(this).parents("form").find("[equalto='" + equalto + "']").eq(0).val();
                  val2 = $(this).parents("form").find("[equalto='" + equalto + "']").eq(1).val();
                  if(val1!=val2 || val1=='' || val2==''){
                      $(this).parents("form").find("[equalto='" + equalto + "']").parent().addClass("error");
                  }
                  else{
                      $(this).parents("form").find("[equalto='" + equalto + "']").parent().removeClass("error");
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
        });
    };
})(jQuery);

(function($) {
	$.fn.isValid = function(){
	  var valid=true;
	  $(this).each(function(){
        oform = $(this);
        require(oform);
        checkMail(oform);
        checkNumber(oform);
        checkEqual(oform);

        if(oform.find(".error").length>0){
            oform.addClass("error");
            valid=false;
        }
        else{
            oform.removeClass("error");
            valid=true;
        }
        oform.addClass("submitet");
	  });
      return valid;
    };
})(jQuery);


 