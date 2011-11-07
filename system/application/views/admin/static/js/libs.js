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

*/
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
    this.parentNode.removeChild(this);
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


$.fn.extend({
  onStopWriting:function(callback){
      var setTime = setTimeout(function(){},1);
      var keydown_val='';
      $(this).keydown(function(){
        keydown_val = $(this).val();
      });
      $(this).keyup(function(){
         var el = this;
         var val = el.value;
         if(setTime){clearTimeout(setTime)}
         setTime = setTimeout(function(){
           if(keydown_val!=val){
              callback.call(el);
           }
         }, 600);
       });
      return this;
	},
    onCheck:function(callback){
      var c_elem = '';
      $(this).bind("click change", function(){
        c_elem = this;
         if(this.checked == true && $(this).attr("type")=='checkbox'){
           callback.call(c_elem);
         }
      });
      return c_elem;
    },
    onUncheck:function(callback){
      var c_elem = '';
      $(this).bind("click change", function(){
        c_elem = this;
         if(this.checked == false && $(this).attr("type")=='checkbox'){
           callback.call(c_elem);
         }
      });
      return c_elem;
    },
    hasEmbed:function(){
      if($(this).find("object").length>0 || $(this).find("embed").length>0 || $(this).find("iframe").length>0){
        return true;
      }
      else{return false}
    }

});


$.dataFind = function(data, findwhat){
       var div = document.createElement('div');
       div.innerHTML = data;
       div.className = 'xhidden';
       document.body.appendChild(div);
       setTimeout(function(){$(div).destroy()}, 5);
       return $(div).find(findwhat)
    }


$.expr[':'].hasval = function(obj){
  var $this = $(obj);
  return ($this.val() != '' && $this.val() != undefined);
};
$.expr[':'].hasnoval = function(obj){
  var $this = $(obj);
  return ($this.val() == '' || $this.val() == undefined);
};

function mw_console(){
    jeapp = "<div class='radius' id='mw_console'><textarea onblur='this.value==\"\"?this.value=\"Microweber Console\":\"\"' onfocus='this.value==\"Microweber Console\"?this.value=\"\":\"\"' id='jearea'>Microweber Console</textarea><br/><br/><input style='float:left;margin-left:10px;' type='button' value='Execute' id='jebtn' /></div><div id='jescr'></div>";
    $("body").append(jeapp);
    $("#jebtn").click(function(){
      var JEval = $("#jearea").val();
      $("#jescr").html("<script>function MW_console(){" + JEval +"}</script>");
          MW_console();
    });
}
$(window).load(function(){
     mw_console()
});


mw.ready = function(elem, callback){


}
jQuery.cookie = function(name, value, options) {
    if (typeof value != 'undefined') {
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
        var path = options.path ? '; path=' + (options.path) : '';
        var domain = options.domain ? '; domain=' + (options.domain) : '';
        var secure = options.secure ? '; secure' : '';
        document.cookie = [name, '=', encodeURIComponent(value), expires, path, domain, secure].join('');
    } else {
        var cookieValue = null;
        if (document.cookie && document.cookie != '') {
            var cookies = document.cookie.split(';');
            for (var i = 0; i < cookies.length; i++) {
                var cookie = jQuery.trim(cookies[i]);
                if (cookie.substring(0, name.length + 1) == (name + '=')) {
                    cookieValue = decodeURIComponent(cookie.substring(name.length + 1));
                    break;
                }
            }
        }
        return cookieValue;
    }
};


$.fn.dataCollect = function() {
        var formData = '';
        var fields = 'input, select, textarea';
        var not = 'input[type="submit"], input[type="image"]';
        var length = $(this).find(fields).not(not).length;
        $(this).find(fields).not(not).each(function(i){
          var name = $(this).attr("name");
          var val = $(this).val();
          if(i<length){
            formData = formData + name + ':' + '"' + val + '",';
          }
          else{
            formData = formData + name + ':' + '"' + val + '"';
          }
        });
       return eval('({' + formData + '})');
    };




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







  urlSettings = {
    hashDelimitter:"&", //delimitter between each property
    hashStartWith:"/"  //how to begin after '#'
  }



  document.getHashProperty = function(prop, url){
    var prop = escape(prop);
    var hash = url==undefined?window.location.hash:url;
    var hash = hash.replace("#" + urlSettings.hashStartWith, "");
    var hashLength = hash.length;
    var prop = prop + "=";
    var propBegin = hash.search(prop);
    var propLength = prop.length;
    var valueBegin = propBegin + propLength;
    var valueEnd = 0;
    if(hash.indexOf(prop) != -1){
      for(var i = valueBegin; i<=hashLength; i++){
        if(hash.charAt(i)==urlSettings.hashDelimitter || i==hash.length){
          var valueEnd = i;
          break;
        }
      }
    }
    else{return false}
    var result = hash.substring(valueBegin, valueEnd);
    return result;
  }

    $(window).bind("load hashchange", function(){
     if(document.getHashProperty("tab") !=-1){
       var hash = document.getHashProperty("tab");
       $("a[href='" + window.location.hash + "']").parents("ul").find("a").removeClass("active");
       $("a[href='" + window.location.hash + "']").addClass("active");
       $("#" + hash).parent().find(".tab").hide();
       $("#" + hash).show();
     }
     if(window.location.hash==""){
      //$(".shop_nav a:first").addClass("active");
     // $("#orders_tabs .tab").hide();
     // $("#orders_tabs .tab:first").show()
     }
  });





 



