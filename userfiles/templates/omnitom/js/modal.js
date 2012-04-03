(function($) {
	$.fn.modal = function(type,ModalWidth,ModalHeight) {
         if($("#xoverlay").length==0){
            $("body").append('<div id="xoverlay" onclick="Modal.xclose()"></div><div id="modalbox"><a href="javascript:;" class="xclose">close<span></span></a></div>');
            var modalcss = document.createElement('style');
            var css = ".xclose{color:white;cursor:pointer;display:block;font:bold 12px Arial;height:18px;position:absolute;right:-9px;text-align:right;text-indent:-9999px;top:-32px;width:76px;}.xclose span{cursor:pointer;height:18px;width:76px;}#controlls{background:#0F1013;bottom:0px;display:none;left:0px;padding:10px 0;position:absolute;width:100%;z-index:2;opacity:.8;filter:alpha(opacity=80)}#imgnext{color:white;cursor:pointer;float:right;font:15px Verdana,Arial;margin:0 10px;text-transform:uppercase;}#imgprev{color:white;cursor:pointer;float:left;font:15px Verdana,Arial;margin:0 10px;text-transform:uppercase;}#modalbox{background:white url(http://www.omnitom.com/userfiles/templates/omnitom/img/l.gif) no-repeat center center;border:solid 10px #14121B;display:none;height:50px;left:50%;margin-left:-25px;position:absolute;width:50px;z-index:21;}#xoverlay{background:#0E0F14;display:none;filter:alpha(opacity=0);height:100%;left:0px;opacity:0;position:fixed;-position:absolute;top:0px;-top:expression((0+(ignoreMe=document.documentElement.scrollTop?document.documentElement.scrollTop:document.body.scrollTop))+'px');width:100%;z-index:20;-height:expression(document.documentElement.clientHeight+'px');-width:expression(document.documentElement.clientWidth+'px');}";
                modalcss.type = 'text/css';
                if(modalcss.styleSheet){
                   modalcss.styleSheet.cssText=css;
                }
                else {
                   modalcss.appendChild(document.createTextNode(css));
                }
                document.getElementsByTagName("head")[0].appendChild(modalcss);
         };
            Modal = new Object();
            Modal.xclose = function(){
              $("#xoverlay").animate({opacity:0}, 'normal', function(){
                  $("#xoverlay").hide();
              });
              $("#modalbox").hide();
              $("#modalbox").html('<a href="javascript:;" class="close" onclick="Modal.xclose()">close<span></span></a>');
              $("#modalbox").attr("style", "");
            };
            Modal.xoverlay = function(){
                if($("#xoverlay").css('display')=='none'){
                   $("#xoverlay").show().animate({opacity:0.85}, 'normal');
                };
            };
            Modal.xclose = function(){
              $("#xoverlay").animate({opacity:0}, 'normal', function(){
                  $("#xoverlay").hide();
              });
              $("#modalbox").hide();
              $("#modalbox").html('<a href="javascript:;" class="xclose" onclick="Modal.xclose()">close<span></span></a>');
              $("#modalbox").attr("style", "");
            };
            Modal.box = function(html, width, height){
                Modal.xoverlay();
                 $("#modalbox").css({
                   "width":width,
                   "height":height,
                   "marginLeft":-width/2,
                   "top": $(window).scrollTop() + ($(window).height())/2-height/2
                 });
                 $("#modalbox").prepend(html);
                 $(".xclose").click(function(){
                   Modal.xclose();
                 });
                 $("#modalbox").show();
            };
            if(type=='gallery'||type=='single'){
              $(this).click(function(){
                    $(".LBActive").removeClass("LBActive");
                    $(this).addClass("LBActive");
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
                       Modal.box(data, ModalWidth, ModalHeight);
                    });
                return false;
              });
            }
	};
})(jQuery);

function CalculateImage(imageUrl){
     var lightboximg = new Image();
      Modal.xoverlay();
      lightboximg.style.position = 'absolute';
      lightboximg.style.left = '-9999px';
      document.body.appendChild(lightboximg);
      $("body img:last").addClass("lbpreload");
      var imgsrc = imageUrl;
      if($("#modalbox").css("display")=='none'){
            Modal.box(' ', '100', '100');
      }
      lightboximg.onload = function(){
          var Ewidth = this.clientWidth;
          var Eheight = this.clientHeight;
          window_height = $(window).height();
          if(Ewidth<window_height){
              $("#modalbox").animate({"width":Ewidth,"height":Eheight, "marginLeft":-Ewidth/2, "top": ($(window).scrollTop() + ($(window).height())/2-Eheight/2)}, function(){
                lightboximg.style.position = 'static';
                lightboximg.style.left = '0px';
                $(this).append(lightboximg);
                $("#controlls").show()
              });
          }
          else{
            lightboximg.height = window_height - 50;
            lightboximg.style.width = "auto";

            var Eheight = lightboximg.height;
            var Ewidth = lightboximg.offsetWidth;

            $("#modalbox").animate({"width":Ewidth,"height":Eheight, "marginLeft":-Ewidth/2, "top": ($(window).scrollTop() + ($(window).height())/2-Eheight/2)}, function(){
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
    if($(".LBActive").next("a").length>0){
        if($("#modalbox").not(":animated").length>0){
           $("#modalbox img").remove();
           var LBActive_old = $(".LBActive:first");
           LBActive_old.next().addClass("LBActive");
           LBActive_old.removeClass("LBActive");
           var LBActive = $(".LBActive:first");
           var nextUrl = LBActive.attr("href");
           CalculateImage(nextUrl);
           next_prev_visibility()
       }
    }
}

function imgprev(){
  if($(".LBActive").prev("a").length>0){
    if($("#modalbox").not(":animated").length>0){
         $("#modalbox img").remove();
         var LBActive_old = $(".LBActive:first");
         LBActive_old.prev().addClass("LBActive");
         LBActive_old.removeClass("LBActive");
         var LBActive = $(".LBActive:first");
         var prevUrl = LBActive.attr("href");
         CalculateImage(prevUrl);
         next_prev_visibility();
    }
  }
}

function next_prev_visibility(){
    $("#imgnext").css("visibility", "visible");
    $("#imgprev").css("visibility", "visible");
    if($(".LBActive").next("a").length<1){
         $("#imgnext").css("visibility", "hidden");
         $("#imgprev").css("visibility", "visible");
       }
    if($(".LBActive").prev("a").length<1){
        $("#imgprev").css("visibility", "hidden");
        $("#imgnext").css("visibility", "visible");
    }
}

$(function(){
  $(window).resize(function(){

   });
    $(document).keyup(function(event){
       if(event.keyCode==27){
            if($("#modalbox").css("display")=='block'){
               Modal.xclose();
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


function chat(theURL){

    var chat_left = ($(window).width()/2) - 400;

	window.open(theURL, '', 'fullscreen=no, scrollbars=yes, resizable=yes, statusbar=no, toolbar=no, menubar=no, height=600, width=800').moveTo(chat_left, 100);

}
