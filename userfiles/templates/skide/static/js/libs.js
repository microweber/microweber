(function($,window,undefined){
  '$:nomunge'; // Used by YUI compressor.
  var fake_onhashchange,
    jq_event_special = $.event.special,
    str_location = 'location',
    str_hashchange = 'hashchange',
    str_href = 'href',
    browser = $.browser,
    mode = document.documentMode,
    is_old_ie = browser.msie && ( mode === undefined || mode < 8 ),
    supports_onhashchange = 'on' + str_hashchange in window && !is_old_ie;

  function get_fragment( url ) {
    url = url || window[ str_location ][ str_href ];
    return url.replace( /^[^#]*#?(.*)$/, '$1' );
  };

  $[ str_hashchange + 'Delay' ] = 100;

  jq_event_special[ str_hashchange ] = $.extend( jq_event_special[ str_hashchange ], {
    setup: function() {
      if ( supports_onhashchange ) { return false; }
      $( fake_onhashchange.start );
    },

    teardown: function() {
      if ( supports_onhashchange ) { return false; }
      $( fake_onhashchange.stop );
    }

  });
  fake_onhashchange = (function(){
    var self = {},
      timeout_id,
      iframe,
      set_history,
      get_history;

    function init(){
      set_history = get_history = function(val){ return val; };

      if ( is_old_ie ) {
        iframe = $('<iframe src="javascript:0"/>').hide().insertAfter( 'body' )[0].contentWindow;

        get_history = function() {
          return get_fragment( iframe.document[ str_location ][ str_href ] );
        };
        set_history = function( hash, history_hash ) {
          if ( hash !== history_hash ) {
            var doc = iframe.document;
            doc.open().close();
            doc[ str_location ].hash = '#' + hash;
          }
        };
        set_history( get_fragment() );
      }
    };
    self.start = function() {
      if ( timeout_id ) { return; }
      var last_hash = get_fragment();
      set_history || init();
      (function loopy(){
        var hash = get_fragment(),
          history_hash = get_history( last_hash );
        if ( hash !== last_hash ) {
          set_history( last_hash = hash, history_hash );
          $(window).trigger( str_hashchange );
        } else if ( history_hash !== last_hash ) {
          window[ str_location ][ str_href ] = window[ str_location ][ str_href ].replace( /#.*/, '' ) + '#' + history_hash;
        }
        timeout_id = setTimeout( loopy, $[ str_hashchange + 'Delay' ] );
      })();
    };
    self.stop = function() {
      if ( !iframe ) {
        timeout_id && clearTimeout( timeout_id );
        timeout_id = 0;
      }
    };
    return self;
  })();
})(jQuery,this);

/*
    $(element).toggleSlide()
    $(element).toggleFade()
    $(element).enable()
    $(element).disable()
    $(element).check()
    $(element).uncheck()
    $(element).visible()
    $(element).hidden()
    $(element).xDisable(opacity)        //puts an overlay over the element
    $(element).xEnable()
    $(element).multiWrap()              //Example: $(element).multiWrap(2, '<div></div>') --> wraps each 2 elements into a div
    $(element).destroy()

    $.imgpreload(
      'img1.jpg',
      'img2.jpg'
    )
    $(element).bgpreload();

*/

$.fn.tagName = function() {
    return this.each(function() {
        return this.tagName;
    });
}



$.fn.toggleSlide = function (s, callback) {
    return $(this).each(function () {
        var d = $(this).css("display");
        if (d == "none" || d == '') $(this).slideDown(s);
        else $(this).slideUp(s, function(){
          if(typeof callback == 'function' && callback != undefined){
            callback.call(this);
          }
        });
    });
}
$.fn.toggleFade = function (s, callback) {
    return $(this).each(function () {
        var d = $(this).css("display");
        if (d == "none" || d == '') $(this).fadeIn(s);
        else $(this).fadeOut(s, function(){
          if(typeof callback == 'function' && callback != undefined){
            callback.call(this);
          }
        });
    });
}


$.fn.disable = function() {
      $(this).each(function(){
        var d = $(this);
        if(d.find('input').length>0 || d.find('select').length>0 || d.find('textarea').length>0){
          d.find('input').attr("disabled", true);
          d.find('select').attr("disabled", true);
          d.find('textarea').attr("disabled", true);
        }
        else{
          d.attr("disabled", true);
        }
      });
};



$.fn.enable = function() {
    $(this).each(function(){
      var d = $(this);
      if(d.find('input').length>0 || d.find('select').length>0 || d.find('textarea').length>0){
        d.find('input').removeAttr("disabled");
        d.find('select').removeAttr("disabled");
        d.find('textarea').removeAttr("disabled");
      }
      else{
        d.removeAttr("disabled");
      }
    });
};


jQuery.fn.extend({
  check: function(){
    return this.each(function(){this.checked = true;});
  },
  uncheck: function(){
    return this.each(function(){this.checked = false;});
  },
  hidden: function(){
    return this.each(function(){this.style.visibility = 'hidden'});
  },
  visible: function(){
    return this.each(function(){this.style.visibility = 'visible'});
  }
});

$.fn.xDisable = function(opacity) {
      $(this).each(function(){
        var d = $(this);
        var w = d.outerWidth();
        var h = d.outerHeight();
        var o = document.createElement('div');
        o.id = d.attr('id') + '-overlay';
        o.style.width = w+'px';
        o.style.height = h+'px';
        o.style.position = 'absolute';
        o.style.zIndex = '20';
        o.style.top = 0;
        o.style.left = 0;
        o.style.background = 'white';
        if(opacity!=undefined){
          o.style.opacity = opacity;
          o.style.filter = 'alpha(opacity=' + (opacity*100) + ')';
        }
        else{
          o.style.opacity = 0.1;
          o.style.filter = 'alpha(opacity=10)';
        }
        d.append(o);
      });
};

$.fn.xEnable = function() {
    $(this).each(function(){
      var d = $(this);
      var id = d.attr('id');
      $("#" + id + '-overlay').remove();
    });
};


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


//'remove' function sometimes makes the fields non editable in IE
$.fn.destroy = function(){
  $(this).each(function(){
    try{
      this.parentNode.removeChild(this);
    }
    catch(err){}
  });
};

(function($) {
  var cache = [];
  $.imgpreload = function() {
    var args_len = arguments.length;
    for (var i = args_len; i--;) {
      var cacheImage = document.createElement('img');
      cacheImage.src = arguments[i];
      cache.push(cacheImage);
    }
  }
})(jQuery);

$.fn.bgpreload = function(){
  $(this).each(function(){
      var bg = $(this).css("backgroundImage");
      var bg = bg.replace('url', '');
      var bg = bg.replace('"', '');
      var bg = bg.replace('"', '');
      var bg = bg.replace('(', '');
      var bg = bg.replace(')', '');
      $.imgpreload(bg);
  });
};

function scrollto(elem){
  $('html, body').animate({
    scrollTop: $(elem).offset().top
  }, 'slow');
}

// VALIDATE

//check empty
function require(the_form){
    the_form.find(".required").each(function(){
          if($(this).attr("type")!="checkbox"){
              if($(this).val()=="" || $(this).val()=='Write a comment...'){
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
	$.fn.validate = function(callback) {
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

                  if(valid==true && callback!=undefined && typeof callback == 'function'){
                      callback.call(this);
                      return false;
                  }
                  else{
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


jQuery.cookie = function(name, value, options) {
    if (typeof value != 'undefined') { // name and value given, set cookie
        options = options || {};
        if (value === null) {
            value = '';
            options.expires = -1;
        }
        var expires = '';
        if (options.expires && (typeof options.expires == 'number' || options.expires.toUTCString)) {
            var date;
            if (typeof options.expires == 'number') {
                date = new Date();
                date.setTime(date.getTime() + (options.expires * 24 * 60 * 60 * 1000));
            } else {
                date = options.expires;
            }
            expires = '; expires=' + date.toUTCString(); // use expires attribute, max-age is not supported by IE
        }
        // CAUTION: Needed to parenthesize options.path and options.domain
        // in the following expressions, otherwise they evaluate to undefined
        // in the packed version for some reason...
        var path = options.path ? '; path=' + (options.path) : '';
        var domain = options.domain ? '; domain=' + (options.domain) : '';
        var secure = options.secure ? '; secure' : '';
        document.cookie = [name, '=', encodeURIComponent(value), expires, path, domain, secure].join('');
    } else { // only name given, get cookie
        var cookieValue = null;
        if (document.cookie && document.cookie != '') {
            var cookies = document.cookie.split(';');
            for (var i = 0; i < cookies.length; i++) {
                var cookie = jQuery.trim(cookies[i]);
                // Does this cookie string begin with the name we want?
                if (cookie.substring(0, name.length + 1) == (name + '=')) {
                    cookieValue = decodeURIComponent(cookie.substring(name.length + 1));
                    break;
                }
            }
        }
        return cookieValue;
    }
};




















































