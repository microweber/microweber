/*!
 * OOYES.NET API v1.0
 * http://ooyes.net/
 *
 * Copyright 2010, Mass Media Group
 * Dual licensed under the MIT or GPL Version 2 licenses.
 *
 * Date: 22.02.2010
 */

(function($) {
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
})(jQuery);

(function($) {
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
})(jQuery);

(function($) {
	$.fn.mceDisable = function() {
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
          o.style.background = 'url(' + imgurl + 'blank.gif)';
          d.append(o);
        });
	};
})(jQuery);
(function($) {
	$.fn.mceEnable = function() {
        $(this).each(function(){
          var d = $(this);
          var id = d.attr('id');
          $("#" + id + '-overlay').remove();
        });
	};
})(jQuery);






 (function($) {
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
})(jQuery);

 (function($) {//'remove' function sometimes makes the fields non editable in IE
    $.fn.destroy = function(){
      $(this).each(function(){
        this.parentNode.removeChild(this);
      });
    };
})(jQuery);




/*
 * Cookie plugin
 */



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




 //Modal Box

 hasOverlay = false;

mw = {};

mw.getobj = function(opt, whatToFind){
  var value;
  if(typeof opt == 'object'){
    $.each(opt, function(i, n) {
        if(i==whatToFind){
            value = n;
        }
    });
  }
  if(value != undefined){
    return value;
  }
  else {
     return false;
  }
}

mw.box = {
  create:function(options){
    var id = mw.getobj(options, 'id');
    if(id && $('#mwbox-'+id).length<1){
        var width = mw.getobj(options, 'width')?parseFloat(mw.getobj(options, 'width')):400;
        var height = mw.getobj(options, 'height')?parseFloat(mw.getobj(options, 'height')):400;
        if(isNaN(height)){
          var height = 'auto';
        }
        var html = mw.getobj(options, 'html');
        var content = document.createElement('div');
        content.style.position='absolute';
        content.id = id?'mwbox-' + id:'mwbox-'+(Math.ceil(Math.random() * 999999) - 1);
        var skin = mw.getobj(options, 'skin')?mw.getobj(options, 'skin'):'mw-modal';
        content.className = 'mwbox-container '+skin;
        if(skin!='mw-notification'){
            content.style.left = $(window).width()/2-width/2 + 'px';
            content.style.zIndex = '20';
            if(height !='auto'){
              var top = $(window).scrollTop() + ($(window).height())/2-height/2 - 26;
            }
            else{
              var top = $(window).scrollTop() + 50;
            }

            content.style.top = top+'px';
        }
        else if(skin=='mw-notification'){
           content.className = 'mwbox-container mw-modal '+skin;
        }
        content.style.width = width + 52 + 'px';
        inner_content_width = width - 20;
        var boxString = '<div class="mwbox-top"><div class="mwbox-topleft">&nbsp;</div><div class="mwbox-topright">&nbsp;</div><div class="mwbox-topmid">&nbsp;</div></div><div class="mwbox-left"><div class="mwbox-right"><div class="mwbox-main-content"><div class="mwbox-content" style="height:' + height + 'px;width:' + inner_content_width + 'px;">' + html + '</div></div></div></div><div class="mwbox-bottom"><div class="mwbox-bottomleft">&nbsp;</div><div class="mwbox-bottomright">&nbsp;</div><div class="mwbox-bottommid">&nbsp;</div></div><span class="mwboxClose mwclose"></span>';
        content.innerHTML = boxString;
        content.onmousedown = function(){
            var maxZ = 0;
            $(".mwbox-container").each(function(){
                  var Z = parseFloat($(this).css("zIndex"));
                  if(Z>maxZ){maxZ=Z}
            });
            $(this).css("zIndex", maxZ+1);
        };
        $(content).draggable({ handle: ".mwbox-top", containment: 'body'});
        return content;

    }
  },
  html:function(options){
    var id = mw.getobj(options, 'id');
    var id = id?id:(Math.ceil(Math.random() * 999999) - 1);
    options.id = id;
    var createBox = mw.box.create(options);
    $("body").append(createBox);
  },
  ajax:function(options, params){
    var url = mw.getobj(options, 'url');
    var id = mw.getobj(options, 'id');
    var id = id?id:(Math.ceil(Math.random() * 999999) - 1);
    var options = options;
    $.post(url, params, function(data){
        options.html = data;
        options.id = id;
        return mw.box.html(options);
    });
  },
  element:function(options){
    var id = mw.getobj(options, 'id');
    var id = id?id:(Math.ceil(Math.random() * 999999) - 1);
    if($('#mwbox-'+id).length<1){
        var element = mw.getobj(options, 'element');
        var width = mw.getobj(options, 'width');
        var height = mw.getobj(options, 'height');
        var width = width?width:$(element).outerWidth()+20;
        var height = height?height:$(element).outerHeight()+20;
        var element = $(element).eq(0).clone(true);
        $(element).show();
        var newBox = mw.box.html({html:'',width:width,height:height,id:id});
        $('.mwbox-content:last').append(element);
        $('.mwbox-content:last').find(':first').show();
    }
  },
  notification:function(options){
    if($('.notification-holder').length<1){
        var notification_holder = document.createElement('div');
        notification_holder.className='notification-holder';
        var html = mw.getobj(options, 'html');
        var id = id?id:(Math.ceil(Math.random() * 999999) - 1);
        var nofication = mw.box.create({html:html, skin:'mw-notification', width:250, height:50, id:id});
        $(notification_holder).append(nofication);
        document.body.appendChild(notification_holder)
        setTimeout(function(){
          $(nofication).remove();
          $(".mw-notification").each(function(){
             var css_top = parseFloat($(this).css('top'));
             $(this).not(':animated').animate({"top":css_top-100}, 300);
          });
        }, 4000);
    }
    else{
        var length = $(".mw-notification").length;
        var id = id?id:(Math.ceil(Math.random() * 999999) - 1);
        var html = mw.getobj(options, 'html');
        var nofication = mw.box.create({html:html, skin:'mw-notification', width:250, height:50, id:id});
        nofication.style.top = length*100+'px';
        $('.notification-holder').append(nofication);
        setTimeout(function(){
          $(nofication).remove();
          $(".mw-notification").each(function(){
             var css_top = parseFloat($(this).css('top'));
             $(this).not(':animated').animate({"top":css_top-100}, 300);
          });
        }, 4000);
    }

  },
  overlay:function(color){
    if(!hasOverlay){
        hasOverlay = true;
        var overlay = document.createElement("div");
        overlay.className="mw-overlay";
		overlay.style.height = $("body").height()+'px';
        if($(".mwbox-container").length>0){
            $(".mwbox-container:first").before(overlay);
    	}
    	else{
    	    document.body.appendChild(overlay);

    	}
        color==undefined?overlay.style.backgroundColor='transparent':overlay.style.backgroundColor=color;

        if(color!=undefined){
          $("object, embed").hidden();
        }
        $(overlay).animate({"opacity":"0.75"}, 200);
    }
  },
  remove:function(id){
    if(id==undefined || id ==''){
        $(".mw-modal").remove();
        hasOverlay = false;
        $(".mw-overlay").animate({"opacity":0}, 200, function(){$(this).remove()});
    }
    else{
        $("#mwbox-"+id).remove();
        hasOverlay = false;
        $(".mw-overlay").animate({"opacity":0}, 200, function(){$(this).remove()});
    }
    $("object, embed").visible();
  },
  alert:function(html, id){
        var ok = '<div class="c"></div><a href="javascript:;" class="btnAlert mwclose">OK</a>';
        mw.box.html({html:'<table style="height:130px;width:361px"><tr><td align="center" valign="middle">' + html + ok + '</td></tr></table>', width:380, height:165,id:id!=undefined?id:(Math.ceil(Math.random() * 999999) - 1)});
  }
}


mw.NewWidtget = function(){
    var WidgetID = (Math.ceil(Math.random() * 999999999)) - $("#widget-table .widget-area").length;
    var newWidget = $("#create-new-widget tr").clone(true);
    $(newWidget).find('textarea').attr('name', 'custom_field_sidebar_widget_'+WidgetID);
    $("#widget-table-body").append(newWidget);
}

  $(document).ready(function(){
    $(document).keydown(function(event){
       if(event.keyCode==27){
         if($(".mwbox-container:last").attr("id")!='jcrop-container'){
            $(".mwbox-container:last").remove();
            hasOverlay = false;
            $(".mw-overlay").animate({"opacity":0}, 200, function(){$(this).remove()});
               $("object, embed").visible();
         }


       }
    });
    $(".mwclose").live('click', function(){
            hasOverlay = false;
             $(this).parents(".mwbox-container").remove();
             $(".mw-overlay").animate({"opacity":0}, 200, function(){$(this).remove()});
             $("object, embed").visible();
    });
  });
   //End Of Modal Box




//Tabs

function getAnchor(){
    var window_location = window.location.hash;
    if(window_location.indexOf("#")!=-1){
       var anchor_string = window_location.replace("#", "");
       return anchor_string;
    }
  }



  $(document).ready(function(){
      $(".tab").hide();
      $(".tabs-holder").each(function(){
        $(this).find(".tab:first").show();
      });
      $(".tab-nav li:first-child").addClass("active");
      $(".ajaxtab").each(function(){
           var first_ajax_tab = $(this).parent().find(".tab-nav li:first-child a").attr("href");
           $(this).load(first_ajax_tab);
      });
  });

  //Slider

(function($){
        $.fn.slider = function(step) {
            if(!document.getElementById('sliderStyle')){
              var slidercss = document.createElement('style');
              slidercss.id='sliderStyle';
              var cssstring = ".slider-content li{display:inline;list-style:none;}.slider-content ul{white-space:nowrap}";
                  slidercss.type = 'text/css';
                  if(slidercss.styleSheet){
                     slidercss.styleSheet.cssText=cssstring;
                  }
                  else {
                     slidercss.appendChild(document.createTextNode(cssstring));
                  }
                  document.getElementsByTagName("head")[0].appendChild(slidercss);
            }
           var slideLeft = document.createElement('span');
           slideLeft.className = 'slideLeft';
           var slideRight = document.createElement('span');
           slideRight.className = 'slideRight';
           $(this).append(slideLeft);
           $(this).append(slideRight);
           $(this).addClass("TheSlider");
           var sliderWrapper = document.createElement('div');
           sliderWrapper.className = 'slider-content';
           sliderWrapper.style.overflow = 'hidden';
           var list = $(this).find("ul:first");
           list.wrap(sliderWrapper);
            if(step==undefined){
              step=150;
            };
          var $this = $(this);
          $this.find(".slideLeft").click(function(){
            if($(this).attr("id")!='scroll-left-active'){
                var scrollHandler = $(this).parent().find(".slider-content:first");
                var currLeft = scrollHandler.scrollLeft();
                var btn = $(this);
                btn.attr("id", "scroll-left-active");
                scrollHandler.animate({scrollLeft:currLeft - step}, 400, function(){
                   btn.attr("id", "");
                });
             };
          });
          $this.find(".slideRight").click(function(){
            if($(this).attr("id")!='scroll-left-active'){
                var scrollHandler = $(this).parent().find(".slider-content:first");
                var currLeft = scrollHandler.scrollLeft();
                var btn = $(this);
                btn.attr("id", "scroll-left-active");
                scrollHandler.animate({scrollLeft:currLeft + step}, 400, function(){
                   btn.attr("id", "");
                });
             };
          });
        };
     })(jQuery);


     $(document).ready(function(){
        $(".stabs-nav li:first-child a").addClass("active");
        $(".stabs").each(function(){
          $(this).find(".stab:first").addClass('stab-show');
        });

        $(".stabs-nav a").click(function(){
            var parent = $(this).parents(".stabs");
            parent.find(".stabs-nav a").removeClass("active");
            $(this).addClass("active");
            parent.find(".stab").removeClass('stab-show');
            var aIndex =  parent.find(".stabs-nav a").index(this);
            parent.find(".stab").each(function(i){
              if(i==aIndex){
                $(this).addClass('stab-show');
              }
            });
            return false;
        });
     });
     /*
        <div class="stabs">
            <ul class="stabs-nav">
                <li><a href="#"></a></li>
            </ul>
            <div class="stab"></div>
        </div>
     */


/*Modal*/
(function($) {
	$.fn.modal = function(type,ModalWidth,ModalHeight) {
         if($("#overlay").length==0){
            $("body").append('<div id="overlay"></div><div id="modalbox"><a href="javascript:;" class="close">Close<span></span></a></div>');
            var modalcss = document.createElement('style');
            var css = ".close{color:white;cursor:pointer;display:block;font:bold 12px Arial;height:18px;position:absolute;right:-9px;text-align:right;text-indent:-9999px;top:-32px;width:76px;}.close span{cursor:pointer;height:18px;width:76px;}#controlls{background:#000;bottom:0px;display:none;left:0px;padding:10px 0;position:absolute;width:100%;z-index:2;opacity:.8;filter:alpha(opacity=80)}#imgnext{color:white;cursor:pointer;float:right;font:15px Verdana,Arial;margin:0 10px;text-transform:uppercase;}.eXposed{z-index:999}#imgprev{color:white;cursor:pointer;float:left;font:15px Verdana,Arial;margin:0 10px;text-transform:uppercase;}#modalbox{background:white;border:solid 10px #136EB4;display:none;height:50px;left:50%;margin-left:-25px;position:absolute;width:50px;z-index:21;}#overlay{background:#0E0F14;display:none;filter:alpha(opacity=0);height:100%;left:0px;opacity:0;position:fixed;-position:absolute;top:0px;-top:expression((0+(ignoreMe=document.documentElement.scrollTop?document.documentElement.scrollTop:document.body.scrollTop))+'px');width:100%;z-index:20;-height:expression(document.documentElement.clientHeight+'px');-width:expression(document.documentElement.clientWidth+'px');}";
                modalcss.type = 'text/css';
                if(modalcss.styleSheet){
                   modalcss.styleSheet.cssText=css;
                }
                else {
                   modalcss.appendChild(document.createTextNode(css));
                }
                document.getElementsByTagName("head")[0].appendChild(modalcss);
         };
            Modal = {
               overlay:function(){
                  if($("#overlay").css('display')=='none'){
                     $("#overlay").show().animate({opacity:0.85}, 'fast');
                  }
               },
               close:function(){
                  $("#overlay").animate({opacity:0}, 'normal', function(){
                      $("#overlay").hide();
                      $("object, embed").visible();
                  });
                  $("#modalbox").hide();
                  $("#modalbox").html('<a href="javascript:;" class="close" onclick="Modal.close()">Close<span></span></a>');
                  $("#modalbox").attr("style", "");
                  $(".ActiveImage").removeClass("ActiveImage");
                  $(".eXposed").removeClass("eXposed");
              },
              box: function(html, width, height){
                 if(window.console){
                   if(html==undefined){console.warn("Modal - 'html' parameter is not is not defined");}
                   if(width==undefined){console.warn("Modal - 'width' parameter is not is not defined");}
                   if(height==undefined){console.warn("Modal - 'height' parameter is not is not defined");}
                 };
                 $("#modalbox").css({
                   "width":width,
                   "height":height,
                   "marginLeft":-width/2,
                   "top": $(window).scrollTop() + ($(window).height())/2-height/2
                 });
                 $("#modalbox").empty();
                 $("#modalbox").append(html);
                 $("#modalbox").append('<a href="javascript:;" class="close">Close<span></span></a>');
                 $(".close").click(function(){
                   Modal.close();
                 });
                 $("#modalbox").show();
              }
            };

            if(type=='gallery'||type=='single'){
              if(type=='gallery'){
                $(this).addClass("Modal-Gallery");
                $(this).each(function(i){
                  $(this).attr("GalIndex", (i+1));
                });
              }
              $(this).click(function(){
                $("object, embed").hidden();
                    $(".ActiveImage").removeClass("ActiveImage");
                    $(this).addClass("ActiveImage");
                    var href = $(this).attr('href');
                    CalculateImage(href);
                    var controlls = document.createElement('div');
                    controlls.id='controlls';
                    controlls.innerHTML = "<a onclick='imgprev()' id='imgprev'>Previous</a><a onclick='imgnext()' id='imgnext'>Next</a>";
                    if(type=='gallery'){
                       $("#modalbox").append(controlls);
                       next_prev_visibility();
                    }
              return false;
              });
            }
            else if(type=='html'){
              $(this).click(function(){
                    var href = $(this).attr('href');
                    var elem_html = $(href);
                    elem_html.show();
                      html_width = elem_html.width();
                      html_height = elem_html.height();
                      var html = elem_html.clone(true);
                    elem_html.hide();
                    html.show();
                    Modal.overlay();
                    Modal.box(html, html_width, html_height);
              return false;
              });
            }
            else if(type=='expose'){
              $(this).click(function(){
                $(this).addClass("eXposed");
                var position = $(this).css("position");
                if(position=='static'){
                  $(this).css("position", "relative");
                }
                Modal.overlay();
                return false;
              });
            }
            else if(type=='flash'){
                $(this).attr("width", ModalWidth);
                $(this).attr("height", ModalHeight);
                $(this).click(function(){
                      var href = $(this).attr('href');
                      var embed = document.createElement('embed');
                      embed.setAttribute("type", "application/x-shockwave-flash");
                      embed.setAttribute("src", href);
                      embed.setAttribute("width", "100%");
                      embed.setAttribute("height", "100%");
                      embed.setAttribute("wmode", "transparent");
                      var ModalWidth = parseFloat($(this).attr("width"));
                      var ModalHeight = parseFloat($(this).attr("height"));
                      Modal.overlay();
                      Modal.box(embed, ModalWidth, ModalHeight);
                return false;
              });
            }
            else if(type=='iframe'){
                $(this).attr("width", ModalWidth);
                $(this).attr("height", ModalHeight);
                $(this).click(function(){
                    var href = $(this).attr('href');
                      var iframe = document.createElement('iframe');
                      iframe.setAttribute("src", href);
                      iframe.setAttribute("frameborder", "0");
                      iframe.setAttribute("width", "100%");
                      iframe.setAttribute("height", "100%");
                      var ModalWidth = parseFloat($(this).attr("width"));
                      var ModalHeight = parseFloat($(this).attr("height"));
                      Modal.overlay();
                      Modal.box(iframe, ModalWidth, ModalHeight);
                return false;
              });
            }
            else if(type=='ajax'){
                $(this).attr("width", ModalWidth);
                $(this).attr("height", ModalHeight);
                $(this).click(function(){
                    var ModalWidth = parseFloat($(this).attr("width"));
                    var ModalHeight = parseFloat($(this).attr("height"));
                    var href = $(this).attr('href');
                    Modal.overlay();
                    $.get(href, function(data){
                      Modal.box(data, ModalWidth, ModalHeight);
                    });
                return false;
              });
            }
	};
})(jQuery);

function CalculateImage(imageUrl){
     var lightboximg = new Image();
      Modal.overlay();
      lightboximg.style.position = 'absolute';
      lightboximg.style.left = '-9999px';
      document.body.appendChild(lightboximg);
      $("body img:last").addClass("lbpreload");
      var imgsrc = imageUrl;
      if($("#modalbox").css("display")=='none'){
            Modal.overlay();
            Modal.box(' ', '100', '100');
      }
      lightboximg.onload = function(){
          var Ewidth = this.clientWidth;
          var Eheight = this.clientHeight;
          window_height = $(window).height();
          if(Eheight<(window_height-20)){
              $("#modalbox").stop();
              $("#modalbox").animate({
                    "width":Ewidth,
                    "height":Eheight,
                    "marginLeft":-Ewidth/2,
                    "top": ($(window).scrollTop() + ($(window).height())/2-Eheight/2)},
              function(){
                lightboximg.style.position = 'static';
                lightboximg.style.left = '0px';
                $(this).append(lightboximg);
                $("#controlls").show();
              });
          }
          else{
            lightboximg.height = window_height - 150;
            lightboximg.style.width = "auto";
            var Eheight = lightboximg.clientHeight;
            var Ewidth = lightboximg.clientWidth;
            $("#modalbox").stop();
            $("#modalbox").animate({
                "width":Ewidth,
                "height":Eheight,
                "marginLeft":-Ewidth/2,
                "top": ($(window).scrollTop() + ($(window).height())/2-Eheight/2)},
            function(){
                lightboximg.style.position = 'static';
                lightboximg.style.left = '0px';
                $(this).append(lightboximg);
                $("#controlls").show();
            });
          }
          $(".lbpreload").remove();
      }
      lightboximg.src = imgsrc; // ie
}

function imgnext(){
  var nextIndex = (parseFloat($(".ActiveImage").attr("GalIndex"))+1);
  var next = $("a[GalIndex='" + nextIndex + "']");
    if(next.length>0){
        if($("#modalbox").not(":animated").length>0){
           $("#modalbox img").remove();
           var ActiveImage_old = $(".ActiveImage:first");
           next.addClass("ActiveImage");
           ActiveImage_old.removeClass("ActiveImage");
           var ActiveImage = $(".ActiveImage:first");
           var nextUrl = ActiveImage.attr("href");
           CalculateImage(nextUrl);
           next_prev_visibility();
       }
    }
}


function imgprev(){

  var nextIndex = (parseFloat($(".ActiveImage").attr("GalIndex"))-1);
  var next = $("a[GalIndex='" + nextIndex + "']");
  if(next.length>0){
    if($("#modalbox").not(":animated").length>0){
         $("#modalbox img").remove();
         var ActiveImage_old = $(".ActiveImage:first");
         next.addClass("ActiveImage");
         ActiveImage_old.removeClass("ActiveImage");
         var ActiveImage = $(".ActiveImage:first");
         var prevUrl = ActiveImage.attr("href");
         CalculateImage(prevUrl);
         next_prev_visibility();
    }
  }
}

function next_prev_visibility(){
    $("#imgnext").css("visibility", "visible");
    $("#imgprev").css("visibility", "visible");

  var prevIndex = (parseFloat($(".ActiveImage").attr("GalIndex"))-1);
  var prev = $("a[GalIndex='" + prevIndex + "']");


var nextIndex = (parseFloat($(".ActiveImage").attr("GalIndex"))+1);
  var next = $("a[GalIndex='" + nextIndex + "']");
    if(next.length<1){
         $("#imgnext").css("visibility", "hidden");
         $("#imgprev").css("visibility", "visible");
       }
    if(prev.length<1){

        $("#imgprev").css("visibility", "hidden");
        $("#imgnext").css("visibility", "visible");
    }
}

$(function(){
  $(window).resize(function(){
         if($("#modalbox").css("display")=='block'){
            var CurrentHeight = $('#modalbox').height();
            $("#modalbox").stop();
            $("#modalbox").animate({
              "top": ($(window).scrollTop() + ($(window).height())/2-CurrentHeight/2)
            });
         }
   });
    $(document).keyup(function(event){
       if(event.keyCode==27){
            if($("#modalbox").css("display")=='block' || $("#overlay").css("display")=='block'){
               Modal.close();
            }
       }
       if(event.keyCode==37){
         if($("#modalbox").css("display")=='block'){
               imgprev();
         }
       }
       if(event.keyCode==39){
         if($("#modalbox").css("display")=='block'){
               imgnext();
         }
       }
     });
});
$(document).ready(function(){
    $(document).modal();
    $("#overlay").click(function(){Modal.close()});
});
/*/Modal*/



var mceButtons ={
    buttons1:"bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,formatselect,fontsizeselect,|,pastetext,pasteword,|,bullist,numlist,|,link,unlink,image,media",
    //buttons1:"save,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,formatselect,fontselect,fontsizeselect",
    buttons2:"",
    //buttons2:"cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,undo,redo,|,link,unlink,anchor,image,cleanup,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",
    buttons3:""
    //buttons3:"tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,|,ltr,rtl,|,fullscreen,syntaxhl",
}
var mcePlugins = "autoresize,safari,pagebreak,style,advhr,advimage,advlink,iespell,inlinepopups,paste,media,contextmenu,directionality,noneditable,visualchars,nonbreaking,template,preelementfix";
//var mcePlugins = "autoresize,safari,pagebreak,style,table,save,advhr,advimage,advlink,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,preelementfix";


validElements = ""
+"a[accesskey|charset|class|coords|dir<ltr?rtl|href|hreflang|id|lang|name"
  +"|onblur|onclick|ondblclick|onfocus|onkeydown|onkeypress|onkeyup"
  +"|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|rel|rev"
  +"|shape<circle?default?poly?rect|style|tabindex|title|target|type],"
+"abbr[class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown|onkeypress"
  +"|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style"
  +"|title],"
+"acronym[class|dir<ltr?rtl|id|id|lang|onclick|ondblclick|onkeydown|onkeypress"
  +"|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style"
  +"|title],"
+"address[class|align|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown"
  +"|onkeypress|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover"
  +"|onmouseup|style|title],"
+"applet[align<bottom?left?middle?right?top|alt|archive|class|code|codebase"
  +"|height|hspace|id|name|object|style|title|vspace|width],"
+"area[accesskey|alt|class|coords|dir<ltr?rtl|href|id|lang|nohref<nohref"
  +"|onblur|onclick|ondblclick|onfocus|onkeydown|onkeypress|onkeyup"
  +"|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup"
  +"|shape<circle?default?poly?rect|style|tabindex|title|target],"
+"base[href|target],"
+"basefont[color|face|id|size],"
+"bdo[class|dir<ltr?rtl|id|lang|style|title],"
+"big[class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown|onkeypress"
  +"|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style"
  +"|title],"
+"blockquote[cite|class|dir<ltr?rtl|id|lang|onclick|ondblclick"
  +"|onkeydown|onkeypress|onkeyup|onmousedown|onmousemove|onmouseout"
  +"|onmouseover|onmouseup|style|title],"
+"body[alink|background|bgcolor|class|dir<ltr?rtl|id|lang|link|onclick"
  +"|ondblclick|onkeydown|onkeypress|onkeyup|onload|onmousedown|onmousemove"
  +"|onmouseout|onmouseover|onmouseup|onunload|style|title|text|vlink],"
+"br[class|clear<all?left?none?right|id|style|title],"
+"button[accesskey|class|dir<ltr?rtl|disabled<disabled|id|lang|name|onblur"
  +"|onclick|ondblclick|onfocus|onkeydown|onkeypress|onkeyup|onmousedown"
  +"|onmousemove|onmouseout|onmouseover|onmouseup|style|tabindex|title|type"
  +"|value],"
+"caption[align<bottom?left?right?top|class|dir<ltr?rtl|id|lang|onclick"
  +"|ondblclick|onkeydown|onkeypress|onkeyup|onmousedown|onmousemove"
  +"|onmouseout|onmouseover|onmouseup|style|title],"
+"center[class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown|onkeypress"
  +"|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style"
  +"|title],"
+"cite[class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown|onkeypress"
  +"|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style"
  +"|title],"
+"code[class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown|onkeypress"
  +"|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style"
  +"|title],"
+"col[align<center?char?justify?left?right|char|charoff|class|dir<ltr?rtl|id"
  +"|lang|onclick|ondblclick|onkeydown|onkeypress|onkeyup|onmousedown"
  +"|onmousemove|onmouseout|onmouseover|onmouseup|span|style|title"
  +"|valign<baseline?bottom?middle?top|width],"
+"colgroup[align<center?char?justify?left?right|char|charoff|class|dir<ltr?rtl"
  +"|id|lang|onclick|ondblclick|onkeydown|onkeypress|onkeyup|onmousedown"
  +"|onmousemove|onmouseout|onmouseover|onmouseup|span|style|title"
  +"|valign<baseline?bottom?middle?top|width],"
+"dd[class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown|onkeypress|onkeyup"
  +"|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style|title],"
+"del[cite|class|datetime|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown"
  +"|onkeypress|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover"
  +"|onmouseup|style|title],"
+"dfn[class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown|onkeypress"
  +"|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style"
  +"|title],"
+"dir[class|compact<compact|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown"
  +"|onkeypress|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover"
  +"|onmouseup|style|title],"
+"div[align<center?justify?left?right|class|dir<ltr?rtl|id|lang|onclick"
  +"|ondblclick|onkeydown|onkeypress|onkeyup|onmousedown|onmousemove"
  +"|onmouseout|onmouseover|onmouseup|style|title],"
+"dl[class|compact<compact|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown"
  +"|onkeypress|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover"
  +"|onmouseup|style|title],"
+"dt[class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown|onkeypress|onkeyup"
  +"|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style|title],"
+"em/i[class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown|onkeypress"
  +"|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style"
  +"|title],"
+"fieldset[class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown|onkeypress"
  +"|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style"
  +"|title],"
+"font[class|color|dir<ltr?rtl|face|id|lang|size|style|title],"
+"form[accept|accept-charset|action|class|dir<ltr?rtl|enctype|id|lang"
  +"|method<get?post|name|onclick|ondblclick|onkeydown|onkeypress|onkeyup"
  +"|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|onreset|onsubmit"
  +"|style|title|target],"
+"frame[class|frameborder|id|longdesc|marginheight|marginwidth|name"
  +"|noresize<noresize|scrolling<auto?no?yes|src|style|title],"
+"frameset[class|cols|id|onload|onunload|rows|style|title],"
+"h1[align<center?justify?left?right|class|dir<ltr?rtl|id|lang|onclick"
  +"|ondblclick|onkeydown|onkeypress|onkeyup|onmousedown|onmousemove"
  +"|onmouseout|onmouseover|onmouseup|style|title],"
+"h2[align<center?justify?left?right|class|dir<ltr?rtl|id|lang|onclick"
  +"|ondblclick|onkeydown|onkeypress|onkeyup|onmousedown|onmousemove"
  +"|onmouseout|onmouseover|onmouseup|style|title],"
+"h3[align<center?justify?left?right|class|dir<ltr?rtl|id|lang|onclick"
  +"|ondblclick|onkeydown|onkeypress|onkeyup|onmousedown|onmousemove"
  +"|onmouseout|onmouseover|onmouseup|style|title],"
+"h4[align<center?justify?left?right|class|dir<ltr?rtl|id|lang|onclick"
  +"|ondblclick|onkeydown|onkeypress|onkeyup|onmousedown|onmousemove"
  +"|onmouseout|onmouseover|onmouseup|style|title],"
+"h5[align<center?justify?left?right|class|dir<ltr?rtl|id|lang|onclick"
  +"|ondblclick|onkeydown|onkeypress|onkeyup|onmousedown|onmousemove"
  +"|onmouseout|onmouseover|onmouseup|style|title],"
+"h6[align<center?justify?left?right|class|dir<ltr?rtl|id|lang|onclick"
  +"|ondblclick|onkeydown|onkeypress|onkeyup|onmousedown|onmousemove"
  +"|onmouseout|onmouseover|onmouseup|style|title],"
+"head[dir<ltr?rtl|lang|profile],"
+"hr[align<center?left?right|class|dir<ltr?rtl|id|lang|noshade<noshade|onclick"
  +"|ondblclick|onkeydown|onkeypress|onkeyup|onmousedown|onmousemove"
  +"|onmouseout|onmouseover|onmouseup|size|style|title|width],"
+"html[dir<ltr?rtl|lang|version],"
+"iframe[align<bottom?left?middle?right?top|class|frameborder|height|id"
  +"|longdesc|marginheight|marginwidth|name|scrolling<auto?no?yes|src|style"
  +"|title|width],"
+"img[align<bottom?left?middle?right?top|alt|border|class|dir<ltr?rtl|height"
  +"|hspace|id|ismap<ismap|lang|longdesc|name|onclick|ondblclick|onkeydown"
  +"|onkeypress|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover"
  +"|onmouseup|src|style|title|usemap|vspace|width],"
+"input[accept|accesskey|align<bottom?left?middle?right?top|alt"
  +"|checked<checked|class|dir<ltr?rtl|disabled<disabled|id|ismap<ismap|lang"
  +"|maxlength|name|onblur|onclick|ondblclick|onfocus|onkeydown|onkeypress"
  +"|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|onselect"
  +"|readonly<readonly|size|src|style|tabindex|title"
  +"|type<button?checkbox?file?hidden?image?password?radio?reset?submit?text"
  +"|usemap|value],"
+"ins[cite|class|datetime|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown"
  +"|onkeypress|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover"
  +"|onmouseup|style|title],"
+"isindex[class|dir<ltr?rtl|id|lang|prompt|style|title],"
+"kbd[class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown|onkeypress"
  +"|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style"
  +"|title],"
+"label[accesskey|class|dir<ltr?rtl|for|id|lang|onblur|onclick|ondblclick"
  +"|onfocus|onkeydown|onkeypress|onkeyup|onmousedown|onmousemove|onmouseout"
  +"|onmouseover|onmouseup|style|title],"
+"legend[align<bottom?left?right?top|accesskey|class|dir<ltr?rtl|id|lang"
  +"|onclick|ondblclick|onkeydown|onkeypress|onkeyup|onmousedown|onmousemove"
  +"|onmouseout|onmouseover|onmouseup|style|title],"
+"li[class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown|onkeypress|onkeyup"
  +"|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style|title|type"
  +"|value],"
+"link[charset|class|dir<ltr?rtl|href|hreflang|id|lang|media|onclick"
  +"|ondblclick|onkeydown|onkeypress|onkeyup|onmousedown|onmousemove"
  +"|onmouseout|onmouseover|onmouseup|rel|rev|style|title|target|type],"
+"map[class|dir<ltr?rtl|id|lang|name|onclick|ondblclick|onkeydown|onkeypress"
  +"|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style"
  +"|title],"
+"menu[class|compact<compact|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown"
  +"|onkeypress|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover"
  +"|onmouseup|style|title],"
+"meta[content|dir<ltr?rtl|http-equiv|lang|name|scheme],"
+"noframes[class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown|onkeypress"
  +"|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style"
  +"|title],"
+"noscript[class|dir<ltr?rtl|id|lang|style|title],"
+"object[align<bottom?left?middle?right?top|archive|border|class|classid"
  +"|codebase|codetype|data|declare|dir<ltr?rtl|height|hspace|id|lang|name"
  +"|onclick|ondblclick|onkeydown|onkeypress|onkeyup|onmousedown|onmousemove"
  +"|onmouseout|onmouseover|onmouseup|standby|style|tabindex|title|type|usemap"
  +"|vspace|width],"
+"ol[class|compact<compact|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown"
  +"|onkeypress|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover"
  +"|onmouseup|start|style|title|type],"
+"optgroup[class|dir<ltr?rtl|disabled<disabled|id|label|lang|onclick"
  +"|ondblclick|onkeydown|onkeypress|onkeyup|onmousedown|onmousemove"
  +"|onmouseout|onmouseover|onmouseup|style|title],"
+"option[class|dir<ltr?rtl|disabled<disabled|id|label|lang|onclick|ondblclick"
  +"|onkeydown|onkeypress|onkeyup|onmousedown|onmousemove|onmouseout"
  +"|onmouseover|onmouseup|selected<selected|style|title|value],"
+"p[align<center?justify?left?right|class|dir<ltr?rtl|id|lang|onclick"
  +"|ondblclick|onkeydown|onkeypress|onkeyup|onmousedown|onmousemove"
  +"|onmouseout|onmouseover|onmouseup|style|title],"
+"param[id|name|type|value|valuetype<DATA?OBJECT?REF],"
+"pre/listing/plaintext/xmp[align|class|dir<ltr?rtl|id|lang|onclick|ondblclick"
  +"|onkeydown|onkeypress|onkeyup|onmousedown|onmousemove|onmouseout"
  +"|onmouseover|onmouseup|style|title|width],"
+"q[cite|class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown|onkeypress"
  +"|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style"
  +"|title],"
+"s[class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown|onkeypress|onkeyup"
  +"|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style|title],"
+"samp[class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown|onkeypress"
  +"|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style"
  +"|title],"
+"script[charset|defer|language|src|type],"
+"select[class|dir<ltr?rtl|disabled<disabled|id|lang|multiple<multiple|name"
  +"|onblur|onchange|onclick|ondblclick|onfocus|onkeydown|onkeypress|onkeyup"
  +"|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|size|style"
  +"|tabindex|title],"
+"small[class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown|onkeypress"
  +"|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style"
  +"|title],"
+"span[align<center?justify?left?right|class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown"
  +"|onkeypress|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover"
  +"|onmouseup|style|title],"
+"strike[class|class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown"
  +"|onkeypress|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover"
  +"|onmouseup|style|title],"
+"strong/b[class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown|onkeypress"
  +"|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style"
  +"|title],"
+"style[dir<ltr?rtl|lang|media|title|type],"
+"sub[class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown|onkeypress"
  +"|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style"
  +"|title],"
+"sup[class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown|onkeypress"
  +"|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style"
  +"|title],"
+"table[align<center?left?right|bgcolor|border|cellpadding|cellspacing|class"
  +"|dir<ltr?rtl|frame|height|id|lang|onclick|ondblclick|onkeydown|onkeypress"
  +"|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|rules"
  +"|style|summary|title|width],"
+"tbody[align<center?char?justify?left?right|char|class|charoff|dir<ltr?rtl|id"
  +"|lang|onclick|ondblclick|onkeydown|onkeypress|onkeyup|onmousedown"
  +"|onmousemove|onmouseout|onmouseover|onmouseup|style|title"
  +"|valign<baseline?bottom?middle?top],"
+"td[abbr|align<center?char?justify?left?right|axis|bgcolor|char|charoff|class"
  +"|colspan|dir<ltr?rtl|headers|height|id|lang|nowrap<nowrap|onclick"
  +"|ondblclick|onkeydown|onkeypress|onkeyup|onmousedown|onmousemove"
  +"|onmouseout|onmouseover|onmouseup|rowspan|scope<col?colgroup?row?rowgroup"
  +"|style|title|valign<baseline?bottom?middle?top|width],"
+"textarea[accesskey|class|cols|dir<ltr?rtl|disabled<disabled|id|lang|name"
  +"|onblur|onclick|ondblclick|onfocus|onkeydown|onkeypress|onkeyup"
  +"|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|onselect"
  +"|readonly<readonly|rows|style|tabindex|title],"
+"tfoot[align<center?char?justify?left?right|char|charoff|class|dir<ltr?rtl|id"
  +"|lang|onclick|ondblclick|onkeydown|onkeypress|onkeyup|onmousedown"
  +"|onmousemove|onmouseout|onmouseover|onmouseup|style|title"
  +"|valign<baseline?bottom?middle?top],"
+"th[abbr|align<center?char?justify?left?right|axis|bgcolor|char|charoff|class"
  +"|colspan|dir<ltr?rtl|headers|height|id|lang|nowrap<nowrap|onclick"
  +"|ondblclick|onkeydown|onkeypress|onkeyup|onmousedown|onmousemove"
  +"|onmouseout|onmouseover|onmouseup|rowspan|scope<col?colgroup?row?rowgroup"
  +"|style|title|valign<baseline?bottom?middle?top|width],"
+"thead[align<center?char?justify?left?right|char|charoff|class|dir<ltr?rtl|id"
  +"|lang|onclick|ondblclick|onkeydown|onkeypress|onkeyup|onmousedown"
  +"|onmousemove|onmouseout|onmouseover|onmouseup|style|title"
  +"|valign<baseline?bottom?middle?top],"
+"title[dir<ltr?rtl|lang],"
+"tr[abbr|align<center?char?justify?left?right|bgcolor|char|charoff|class"
  +"|rowspan|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown|onkeypress"
  +"|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style"
  +"|title|valign<baseline?bottom?middle?top],"
+"tt[class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown|onkeypress|onkeyup"
  +"|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style|title],"
+"u[class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown|onkeypress|onkeyup"
  +"|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style|title],"
+"ul[class|compact<compact|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown"
  +"|onkeypress|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover"
  +"|onmouseup|style|title|type],"
+"var[class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown|onkeypress"
  +"|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style"
  +"|title]";



