/*!
 * OOYES.NET API v1.0
 * http://ooyes.net/
 *
 * Copyright 2010, Mass Media Group
 * Dual licensed under the MIT or GPL Version 2 licenses.
 *
 * Date: 22.02.2010
 */


 //Modal Box

(function($) {
	$.fn.modal = function(type,ModalWidth,ModalHeight) {
         if($("#overlay").length==0){
            $("body").append('<div id="overlay"></div><div id="modalbox"><a href="javascript:;" class="close">Close<span></span></a></div>');
            var modalcss = document.createElement('style');
            var css = ".close{color:white;cursor:pointer;display:block;font:bold 12px Arial;height:18px;position:absolute;right:-9px;text-align:right;text-indent:-9999px;top:-32px;width:76px;}.close span{cursor:pointer;height:18px;width:76px;}#controlls{background:#0F1013;bottom:0px;display:none;left:0px;padding:10px 0;position:absolute;width:100%;z-index:2;opacity:.8;filter:alpha(opacity=80)}#imgnext{color:white;cursor:pointer;float:right;font:15px Verdana,Arial;margin:0 10px;text-transform:uppercase;}#imgprev{color:white;cursor:pointer;float:left;font:15px Verdana,Arial;margin:0 10px;text-transform:uppercase;}#modalbox{background:white;border:solid 10px #14121B;display:none;height:50px;left:50%;margin-left:-25px;position:absolute;width:50px;z-index:21;}#overlay{background:#0E0F14;display:none;filter:alpha(opacity=0);height:100%;left:0px;opacity:0;position:fixed;-position:absolute;top:0px;-top:expression((0+(ignoreMe=document.documentElement.scrollTop?document.documentElement.scrollTop:document.body.scrollTop))+'px');width:100%;z-index:20;-height:expression(document.documentElement.clientHeight+'px');-width:expression(document.documentElement.clientWidth+'px');}";
                modalcss.type = 'text/css';
                if(modalcss.styleSheet){
                   modalcss.styleSheet.cssText=css;
                }
                else {
                   modalcss.appendChild(document.createTextNode(css));
                }
                document.getElementsByTagName("head")[0].appendChild(modalcss);
         };
            Modal = {};
            Modal.overlay = function(){
                if($("#overlay").css('display')=='none'){
                   $("#overlay").show().animate({opacity:0.85}, 'fast');
                }
            };
            Modal.close = function(){
              $("#overlay").animate({opacity:0}, 'normal', function(){
                  $("#overlay").hide();
              });
              $("#modalbox").hide();
              $("#modalbox").html('<a href="javascript:;" class="close" onclick="Modal.close()">Close<span></span></a>');
              $("#modalbox").attr("style", "");
              $(".ActiveImage").removeClass("ActiveImage");
            };
            Modal.box = function(html, width, height){
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
                    Modal.box(html, html_width, html_height);
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
                    $.get(href, function(data){
                      Modal.overlay();
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
           next_prev_visibility()
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
    $(window).modal();
});


//Tabs

function getAnchor(){
    var window_location = window.location.href;
    if(window_location.indexOf("#")!=-1){
       var start_anchor = window_location.indexOf("#")+1;
       var end_anchor = window_location.length;
       var anchor_string = window_location.substring(start_anchor,end_anchor);
       return anchor_string;
    }
  }
(function($) {
	$.fn.tabs = function(type) {
	    if(typeof TabsActivated=="undefined"){
             var TabsActivated=true;
             var anchor = getAnchor();
             if(type=='html'){
                $(this).find(".tabs-nav a").click(function(){
                    if($(this).parent().hasClass("active")){/**/}
                    else{
                       var tab_url = $(this).attr("href");
                       $(this).parents(".tabs-nav").find("li").removeClass("active");
                       $(this).parents(".tabs-nav").find("li").removeClass("active-first");
                       $(this).parents(".tabs-nav").find("li").removeClass("active-last");
                       $(this).parent().addClass("active");
                       if($(this).parent().hasClass("first")){$(this).parent().addClass("active-first")}
                       if($(this).parent().hasClass("last")){$(this).parent().addClass("active-last")}
                       $(this).parents(".tabs-holder").find(".tab").hide();
                       $(tab_url).show();
                    }return false;
                });
             }
             else if(type=='ajax'){
                  $(this).find(".tabs-nav a").click(function(){
                    if($(this).parent().hasClass("active")){/**/}
                    else{
                       var tab_url = $(this).attr("href");
                       $(this).parents(".tabs-nav").find("li").removeClass("active");
                       $(this).parents(".tabs-nav").find("li").removeClass("active-first");
                       $(this).parents(".tabs-nav").find("li").removeClass("active-last");
                       $(this).parent().addClass("active");
                       if($(this).parent().hasClass("first")){$(this).parent().addClass("active-first")}
                       if($(this).parent().hasClass("last")){$(this).parent().addClass("active-last")}
                       $(this).parents(".tabs-holder").find(".ajaxtab").load(tab_url);
                    }return false;
                 });
             }
             $("a[href='#"+anchor+"']").click();
	    }
  	};
  })(jQuery);
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

