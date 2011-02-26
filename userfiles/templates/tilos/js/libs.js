// VALIDATE


//Change log: added .require-equal(must add an attribute: equalto="some_word")

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




(function($) {
	$.fn.modal = function(type,ModalWidth,ModalHeight) {
         if($("#overlay").length==0){
            $("body").append('<div id="overlay"></div><div id="modalbox"><a href="javascript:;" class="close">Close<span></span></a></div>');
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
                     $("#overlay").show().animate({opacity:0.85}, 'fast');
                  }
               },
               close:function(){
                  $("#overlay").animate({opacity:0}, 'normal', function(){
                      $("#overlay").hide();
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