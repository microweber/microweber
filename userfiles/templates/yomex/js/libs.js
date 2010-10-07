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





//check empty
function require(the_form){

    the_form.find(".required").each(function(){

          if($(this).attr("type")!="checkbox"){
              if($(this).val()=="" || $(this).val()==undefined){
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

function semirequire(the_form){

    the_form.find(".semi-required:visible").each(function(){

          if($(this).attr("type")!="checkbox"){
              if($(this).val()=="" || $(this).val()==undefined){
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

function checkEqualMail(the_form){
      var regexmail = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,4})+$/;
      var val1 = the_form.find(".required-email-equal:first").val();
      var val2 = the_form.find(".required-email-equal:last").val();
      if(val1 == val2 && regexmail.test(val1)==true){
        the_form.find(".required-email-equal:first").parent().removeClass("error");
        the_form.find(".required-email-equal:last").parent().removeClass("error");

      }
      else{
         the_form.find(".required-email-equal:first").parent().addClass("error");
         the_form.find(".required-email-equal:last").parent().addClass("error");
      }
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


(function($) {
	$.fn.validate = function(callback) {
        $(this).each(function(){
            $(this).submit(function(){

                  oform = $(this);
                  var valid=true;
                  require(oform);
                  checkMail(oform);
                  checkNumber(oform);
                  semirequire(oform);
                  checkEqualMail(oform);

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

            $(this).find(".semi-required").bind("keyup blur change mouseup", function(){
                if($(this).parents("form").hasClass("submitet") && $(this).is(":visible")){
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

            var x = $(this);
            $(this).find(".required-email-equal").bind("keyup blur", function(){
                  var regexmail = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,4})+$/;
                  var val1 = x.find(".required-email-equal:first").val();
                  var val2 = x.find(".required-email-equal:last").val();
                  if(val1 == val2 && regexmail.test(val1)==true){
                    x.find(".required-email-equal:first").parent().removeClass("error");
                    x.find(".required-email-equal:last").parent().removeClass("error");

                  }
                  else{
                     x.find(".required-email-equal:first").parent().addClass("error");
                     x.find(".required-email-equal:last").parent().addClass("error");
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













/* hashchange */

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


/* /hashchange */


    (function($) {
    	$.fn.seotabs = function(type) {
    	    $(window).bind("hashchange load", function(){
                var hash = window.location.hash;
                if(hash!=''){
                  if($(".seotabs a[href='" + hash + "']").length>0 && $(hash + "-seo").length>0){
                     $(".seotabs a").removeClass("active");
                     $(".xtab").addClass("abshidden");
                     $(".xtab").css("position", "absolute");
                     $(".seotabs a[href='" + hash + "']").addClass('active');
                     $(hash+"-seo").removeClass("abshidden");
                     $(hash+"-seo").css("position", "static");
                  }
                }
                else{
                  $(".seotabs a").removeClass("active");
                  $(".xtab").addClass("abshidden");
                  $(".seotabs ul a:first").addClass('active');
                  $(".xtab:first").removeClass("abshidden");
                }
    	    });
            if(type=='html'){
                $(this).each(function(){
                    $(this).addClass("seotabs");
                    $(this).find(".xtab").not(":first").addClass("abshidden");
                    $(this).find(".xtab").each(function(){
                        var id = $(this).attr("id");
                        $(this).attr("id", id + "-seo");
                    });
                    $(this).find("-nav a").click(function(){
                        if($(this).hasClass("active")){}
                        else{
                            $(this).parents("-nav").find("a").removeClass("active");
                            $(this).addClass("active");
                            $(this).parents(".seotabs").find(".xtab").addClass("abshidden");
                            var href = $(this).attr("href");
                            $(href + "-seo").show().removeClass("abshidden");
                        }
                    });
                });
            }
            else if(type=='ajax'){

            }
      	};
    })(jQuery);




  /*
 * jQuery Color Animations
 * Copyright 2007 John Resig
 * Released under the MIT and GPL licenses.
 */

(function(jQuery){

	// We override the animation for all of these color styles
	jQuery.each(['backgroundColor', 'borderBottomColor', 'borderLeftColor', 'borderRightColor', 'borderTopColor', 'color', 'outlineColor'], function(i,attr){
		jQuery.fx.step[attr] = function(fx){
			if ( fx.state == 0 ) {
				fx.start = getColor( fx.elem, attr );
				fx.end = getRGB( fx.end );
			}

			fx.elem.style[attr] = "rgb(" + [
				Math.max(Math.min( parseInt((fx.pos * (fx.end[0] - fx.start[0])) + fx.start[0]), 255), 0),
				Math.max(Math.min( parseInt((fx.pos * (fx.end[1] - fx.start[1])) + fx.start[1]), 255), 0),
				Math.max(Math.min( parseInt((fx.pos * (fx.end[2] - fx.start[2])) + fx.start[2]), 255), 0)
			].join(",") + ")";
		}
	});

	// Parse strings looking for color tuples [255,255,255]
	function getRGB(color) {
		var result;

		// Check if we're already dealing with an array of colors
		if ( color && color.constructor == Array && color.length == 3 )
			return color;

		// Look for rgb(num,num,num)
		if (result = /rgb\(\s*([0-9]{1,3})\s*,\s*([0-9]{1,3})\s*,\s*([0-9]{1,3})\s*\)/.exec(color))
			return [parseInt(result[1]), parseInt(result[2]), parseInt(result[3])];

		// Look for rgb(num%,num%,num%)
		if (result = /rgb\(\s*([0-9]+(?:\.[0-9]+)?)\%\s*,\s*([0-9]+(?:\.[0-9]+)?)\%\s*,\s*([0-9]+(?:\.[0-9]+)?)\%\s*\)/.exec(color))
			return [parseFloat(result[1])*2.55, parseFloat(result[2])*2.55, parseFloat(result[3])*2.55];

		// Look for #a0b1c2
		if (result = /#([a-fA-F0-9]{2})([a-fA-F0-9]{2})([a-fA-F0-9]{2})/.exec(color))
			return [parseInt(result[1],16), parseInt(result[2],16), parseInt(result[3],16)];

		// Look for #fff
		if (result = /#([a-fA-F0-9])([a-fA-F0-9])([a-fA-F0-9])/.exec(color))
			return [parseInt(result[1]+result[1],16), parseInt(result[2]+result[2],16), parseInt(result[3]+result[3],16)];

		// Otherwise, we're most likely dealing with a named color
		return colors[jQuery.trim(color).toLowerCase()];
	}

	function getColor(elem, attr) {
		var color;

		do {
			color = jQuery.curCSS(elem, attr);

			// Keep going until we find an element that has color, or we hit the body
			if ( color != '' && color != 'transparent' || jQuery.nodeName(elem, "body") )
				break;

			attr = "backgroundColor";
		} while ( elem = elem.parentNode );

		return getRGB(color);
	};

	var colors = {
		aqua:[0,255,255],
		azure:[240,255,255]

	};

})(jQuery);


(function($) {
	$.fn.modal = function(type,ModalWidth,ModalHeight, callback) {
         if($("#overlay").length==0){
            $("body").append('<div id="overlay"></div><div id="modalbox"><a href="javascript:;" class="close">Затвори<span></span></a></div>');
            var modalcss = document.createElement('style');
            var css = ".close{color:white;cursor:pointer;display:block;font:bold 12px Arial;height:18px;position:absolute;right:-9px;text-align:right;text-indent:-9999px;top:-32px;width:76px;}.close span{cursor:pointer;height:18px;width:76px;}#controlls{background:#0F1013;bottom:0px;display:none;left:0px;padding:10px 0;position:absolute;width:100%;z-index:2;opacity:.8;filter:alpha(opacity=80)}#imgnext{color:white;cursor:pointer;float:right;font:15px Verdana,Arial;margin:0 10px;text-transform:uppercase;}.eXposed{z-index:999}#imgprev{color:white;cursor:pointer;float:left;font:15px Verdana,Arial;margin:0 10px;text-transform:uppercase;}#modalbox{background:white;border:solid 10px #14121B;display:none;height:50px;left:50%;margin-left:-25px;position:absolute;width:50px;z-index:21;}#overlay{background:#0E0F14;display:none;filter:alpha(opacity=0);height:100%;left:0px;opacity:0;position:fixed;-position:absolute;top:0px;-top:expression((0+(ignoreMe=document.documentElement.scrollTop?document.documentElement.scrollTop:document.body.scrollTop))+'px');width:100%;z-index:20;-height:expression(document.documentElement.clientHeight+'px');-width:expression(document.documentElement.clientWidth+'px');}";
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
                     $("#overlay").show().animate({opacity:0.75}, 'fast');
                  }
               },
               close:function(){
                  $("#overlay").animate({opacity:0}, 'normal', function(){
                      $("#overlay").hide();
                  });
                  $("#modalbox").hide();
                  $("#modalbox").html('<a href="javascript:;" class="close" onclick="Modal.close()">Затвори<span></span></a>');
                  $("#modalbox").attr("style", "");
                  $(".ActiveImage").removeClass("ActiveImage");
                  $(".eXposed").removeClass("eXposed");
                  $("#ooYes").remove();
              },
              box: function(html, width, height, callback){
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
                 $("#modalbox").append('<a href="javascript:;" class="close">Затвори<span></span></a>');
                 $(".close").click(function(){
                   Modal.close();
                 });
                 $("#modalbox").show();
                 if(typeof callback == 'function'){
                   callback.call(this);
                 }
              }
            };

            if(type=='gallery'||type=='single'){
              $(this).click(function(){
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
                    Modal.box(html, html_width, html_height, callback);
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

            else if(type=='iframe-auto'){
                $(this).click(function(){
                    var href = $(this).attr('href');
                      var iframe = document.createElement('iframe');
                      iframe.setAttribute("src", href);
                      iframe.setAttribute("frameborder", "0");
                      iframe.setAttribute("width", "100%");
                      iframe.setAttribute("height", "100%");
                      Modal.overlay();
                      Modal.box(iframe, $(window).width()-100, $(window).height()-100);
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
          if(Eheight<window_height){
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
    if($(".ActiveImage").next("a").length>0){
        if($("#modalbox").not(":animated").length>0){
           $("#modalbox img").remove();
           var ActiveImage_old = $(".ActiveImage:first");
           ActiveImage_old.next().addClass("ActiveImage");
           ActiveImage_old.removeClass("ActiveImage");
           var ActiveImage = $(".ActiveImage:first");
           var nextUrl = ActiveImage.attr("href");
           CalculateImage(nextUrl);
           next_prev_visibility();
       }
    }
}


function imgprev(){
  if($(".ActiveImage").prev("a").length>0){
    if($("#modalbox").not(":animated").length>0){
         $("#modalbox img").remove();
         var ActiveImage_old = $(".ActiveImage:first");
         ActiveImage_old.prev().addClass("ActiveImage");
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
    if($(".ActiveImage").next("a").length<1){
         $("#imgnext").css("visibility", "hidden");
         $("#imgprev").css("visibility", "visible");
       }
    if($(".ActiveImage").prev("a").length<1){
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
















browserIs = {
  msie : function(){
     return (document.all && window.external) ? true : false;
  },
  msie6 : function(){
    return (!window.XMLHttpRequest)?true:false;
  },
  msie7 : function(){
    return (document.all && (!window.XDomainRequest) && window.XMLHttpRequest)?true:false;
  },
  msie8 : function(){
    return window.XDomainRequest?true:false;
  },
  opera : function(){
    return window.opera?true:false;
  },
  firefox : function(){
    return (window.globalStorage && window.postMessage)?true:false;
  },
  chrome: function(){
    return window.chrome?true:false;
  },
  safari:function(){
    return ((document.childNodes) && (!document.all) && (!navigator.taintEnabled) && (!navigator.accentColorName) && (!window.chrome))?true:false;
  },
  webkit:function(){
    return (typeof document.body.style.webkitBorderRadius != "undefined")?true:false;
  },
  khtml:function(){
    return (navigator.vendor == "KDE")?true:false;
  },
  konqueror:function(){
    return ((navigator.vendor == 'KDE') || (document.childNodes) && (!document.all) && (!navigator.taintEnabled))?true:false;
  }

}

$.fn.toggleSlide = function (s) {
    return $(this).each(function () {
        var d = $(this).css("display");
        if (d == "none" || d == '') $(this).slideDown(s);
        else $(this).slideUp(s);
    });
}
$.fn.toggleFade = function (s) {
    return $(this).each(function () {
        var d = $(this).css("display");
        if (d == "none" || d == '') $(this).fadeIn(s);
        else $(this).fadeOut(s);
    });
}



	$.fn.mceDisable = function(opacity) {
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




/* mw.form */


var mwtree = {
    createButtons:function(itemID){
        var btn = $(itemID);
        var span = document.createElement('span');
        span.className = 'treeControll';
        span.innerHTML = '&nbsp;';
        $(span).click(function(event){
          $(this).parents('li:first').find('ul:first').toggle();
          $(this).toggleClass('treeControllActive');
          event.preventDefault();
        });
        $(span).hover(function(){
          $(this).addClass("treeControllHover");
        }, function(){
          $(this).removeClass("treeControllHover")
        });
        btn.mouseover(function(){
          if($(this).hasClass('Activated')){

          }
          else{
            $(this).addClass('Activated');
            $(this).parent().find('ul:first').show();
            $(span).toggleClass('treeControllActive');
          }

        });
        btn.prepend(span);
        btn.addClass("hasChilds")
    },
    init:function(selector, Maxdepth){
        var tree = $(selector);
        tree.find('li:has(li)').find('a:first').each(function(){
          mwtree.createButtons(this);
        });
        tree.find('li').each(function(i){
           var depth = $(this).parents('li').length;
           if(Maxdepth!=undefined && Maxdepth!=""){
             if ((depth+1)>Maxdepth){
               $(this).parent().hide();
             }
           }
           if($(this).has('ul') && (depth+1)<Maxdepth){
             $(this).find('span:first').addClass('treeControllActive');
             $(this).find('a:first').addClass('Activated');
           }
           $(this).addClass('depth-'+(depth+1));
        });
        //find active
        tree.find("a.active").parents("ul").show();
        tree.find("a.active").parents("ul").prev("a").addClass("Activated").find("span").addClass("treeControllActive");

    }
 }











