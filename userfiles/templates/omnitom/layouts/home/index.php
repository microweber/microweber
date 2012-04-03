<?php

/*

type: layout

name: Home layout

description: Home site layout









*/



?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v='urn:schemas-microsoft-com:vml'>
<head>

<meta name="googlebot" content="index,follow" />

<meta name="robots" content="index,follow" />

<meta http-equiv="imagetoolbar" content="no" />

<meta name="rating" content="G0ENERAL" />

<meta name="MSSmartTagsPreventParsing" content="TRUE" />

<link rel="start" href="<?php print site_url(); ?>" />

<link rel="home" type="text/html" href="<?php print site_url(); ?>"  />

<link rel="index" type="text/html" href="<?php print site_url(); ?>" />

<meta name="generator" content="Microweber" />

<title>{content_meta_title}</title>

<meta name="keywords" content="{content_meta_keywords}" />

<meta name="description" content="{content_meta_description}" />

<meta http-equiv="imagetoolbar" content="no" />
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
<style type="text/css">
    html, body{
      overflow: hidden;
      min-height: 540px;
    }

     #sidenav img{
       overflow: hidden;
       height: 11px !important;
       max-height: 11px !important;
     }

     .small_window, .small_window body{

       overflow: auto;
     }
     .small_window #container{

       overflow: hidden;
     }


</style>
<script type="text/javascript">
var imgurl = "<?php print site_url('img/intro'); ?>";

</script>

<link rel="stylesheet" type="text/css" href="intro2.css" />

<script type="text/javascript" src="http://omnitom.com/facelift/flir.js"></script>






<script type="text/javascript" src="http://google.com/jsapi"></script>
<script type="text/javascript">google.load("jquery", "1.4.2");</script>

<script type="text/javascript">

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


(function($) {
	$.fn.validate = function(callback) {
        $(this).each(function(){
            $(this).submit(function(){
                  oform = $(this);
                  var valid=true;
                  require(oform);
                  checkMail(oform);
                  checkNumber(oform);

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





function bookmark(url, title, elem) {
	if (window.sidebar && window.sidebar.addPanel) { // Mozilla Firefox Bookmark

        $(elem).attr('href', 'javascript:void(0)') ;
		window.sidebar.addPanel(title, url,"");
	}
    else if( window.external ) { // IE Favorite
        $(elem).attr('href', 'javascript:void(0)') ;
		window.external.AddFavorite( url, title);
    }
	else if(window.opera && window.print) {
          $(elem).attr("rel", "sidebar");
          return true;
	   }
    else {
        $(elem).attr('href', 'javascript:void(0)');
        Modal.box('<h3 style="text-align:center;padding:20px;">Please use CTRL + D to bookmark this address.</h3>', 400, 120);
    }

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
                  $("#friendform").hide();
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



    function circles_fix(){
        if($(window).width()<1250){
          $(".circle").css("top", "102px");
        }
        else{
            $(".circle").css("top", "25px");
        }
    }

    $(document).ready(function(){
      $("#ht_wrapper").css("marginLeft", -($("#tree").width() + $("#hoe").width())/2);

        $("#tree_injector").css({
            "width":$(window).width(),
            "height":$(window).height()

        });

       circles_fix()

      /*$(window).load(function(){
           $("#tree").animate({opacity:1}, 1200, function(){
               $("#hoe").animate({opacity:1}, 1200, function(){
                  $("body").addClass("tree_done");
                  getFlash();
                  $("#tree_injector").fadeOut(1200);

               })
           })
      }) */



     $(window).load(function(){circles_fix()})
     $(window).resize(function(){circles_fix()})

      var dropdownbg = new Image();
      dropdownbg.src='http://omnitom.com/img/intro/dropdown.png';

      var popAnimation;
      var popSpeed = 300;


      $(".circle").hover(function(){
        $(this).addClass("CIRCLEhover");
      }, function(){
        $(this).removeClass("CIRCLEhover");
      });


      $(document.body).click(function(){
        if($(".CIRCLEhover").length==0){
            $(".pop1").remove();
            $(".pop2").remove();
            $(".pop3").remove();
            $(".circle").not(this).removeClass("backward");
        }
      });

      $(".circle").click(function(){



      if(!$(this).hasClass("backward") && $(this).attr("id")=="pop-1"){
          $.post("<?php print site_url('ajax_oracle.php'); ?>", function(data){
            var x = document.createElement('div');
            x.innerHTML = data;
            quote = $(x).find("em").html();
            author = $(x).find("span").html();



            //$("#sidenav").html(quote);



            setTimeout(function(){


              $(".popglobal-content .Pquote").html("<em>" + quote + "</em>");

              $(".popglobal-content .Pauthor").html(author);
              $("#message").val(quote + ' - ' + author);

            }, 2*popSpeed);

          });
        }

        var left = $(this).offset().left;
        var top = $(this).offset().top;
        var popid = $(this).attr("id");
        var html = $(this).find(".pop-text").html();



        if(popAnimation != 'started'){
          if(!$(this).hasClass("backward")){
            popAnimation = 'started';

            $(".pop1").remove();
            $(".pop2").remove();
            $(".pop3").remove();
            $(".circle").not(this).removeClass("backward");

            var e = document.createElement('div');
            e.className = 'pop1';
            e.style.left = left + 'px';
            e.style.top = (top + 130) + 'px';
            e.lang = popid + '-1';
            document.body.appendChild(e);
             setTimeout(function(){
                var f = document.createElement('div');
                f.className = 'pop2';
                f.style.left = (left - 60) + 'px';
                f.style.top = (top + 160) + 'px';
                f.lang = popid + '-2';
                document.body.appendChild(f);
            }, popSpeed);

            setTimeout(function(){
                var g = document.createElement('div');
                g.className = 'pop3';
                g.style.left = (left - 300) + 'px';
                g.style.top = (top + 180) + 'px';
                g.lang = popid + '-3';
                g.innerHTML = "<div class='popglobal-content'>" + html + "</div>";

                $(g).hover(function(){
                  $(this).addClass("CIRCLEhover");
                }, function(){
                  $(this).removeClass("CIRCLEhover");
                });

                document.body.appendChild(g);
            }, 2*popSpeed);


            popAnimation = 'stopped';
            $(this).addClass("backward");
          }
          else{
            $(this).removeClass("backward");
            $("div[lang='" + popid + "-3']").remove();

            setTimeout(function(){
              $("div[lang='" + popid + "-2']").remove();
            }, popSpeed);

            setTimeout(function(){
              $("div[lang='" + popid + "-1']").remove();
            }, 2*popSpeed);
          }
        }



      });







    })



  </script>



<script type="text/javascript">

function mainImage(){

   window_width = $(window).width();
   window_height = $(window).height();
   var image_height =  $("#mainImage").height();
   var image_width = $("#mainImage").width();

   $("#mainImage").css({"width":window_width});







   if(image_height>window_height){
      $("#mainImage").css({top:-(image_height-window_height)/2});
      $("#mainImage").css("height", 'auto');
   }
   if(image_width>window_width){
          $("#mainImage").css({left:-(image_width-window_width)/2});
          $("#mainImage").css("width", 'auto');
   }
   if(window_width>image_width){
         $("#mainImage").css("width", window_width);
         $("#mainImage").css("height", 'auto');
          $("#mainImage").css({left:0});
   }

   if(window_height>image_height){
         $("#mainImage").css("height", window_height);
         $("#mainImage").css("width", 'auto');
         $("#mainImage").css({top:0});
   }
}


    $(document).ready(function(){

$(document).keydown(function(event){
  var key = event.keyCode;
  /*if(key==33 || key==34 || key==37 || key==38 || key ==39 || key ==40){
    return false;
  } */

});



    setInterval(function(){
    mainImage();
    if($("html").hasClass("small_window")){
       $("#homeg").width($(document.body).width());
       $("#homeg").height($("#mainImage").height() + parseFloat($("#mainImage").css("top")));
    }
    else{

      $("#homeg").width($(document.body).width());
      $("#homeg").height($(document.body).height());

    }

    }, 50)

    FLIR.init({ path: 'http://omnitom.com/facelift/' });
    //var nav_style = new FLIRStyle({ cFont:'bryant', cColor:'000000', cSize:'14px' });
    var nav_style = new FLIRStyle({ cFont:'bryantbold', cColor:'312D2D', cSize:'12px' });


      //$(".circle").draggable();
      $("#nav li").addClass("parent");
      $("#nav li a").addClass("parentA");
      $("#nav li li").removeClass("parent");
      $("#nav li li a").removeClass("parentA");
      function normalize(){
         window_height = $(window).height();
         window_width = $(window).width();




         $("#introImages").css({"width":window_width, "height":window_height});
         //$("#flashIntro").css({"width":window_width, "height":window_height});
         //alert(window_height)


         if(window_height<560){
           $("html").addClass("small_window");
         }
         else{
           $("html").removeClass("small_window");
         }

      }
      normalize();
      $(window).load(function(){normalize();})
      $(window).resize(function(){normalize()});


    $("#nav .parentA").each( function() {
         FLIR.replace(this,  nav_style);
    });

    $("#sidenav a").each(function(){
         FLIR.replace(this,  nav_style);
    });


      //nav

      $("#nav li.parent").hover(function(){
            $("#nav li.parent").find("div.sub").hide();
           $(this).find("div.sub").show();
           $(this).css("height", "300px");
      }, function(){
          $(this).find("div.sub").hide();
          $("#nav li.parent").css("height", "auto");
      })

      $("#nav").hover(function(){}, function(){
           $(this).find("div.sub").hide();
           $("#nav li.parent").css("height", "auto");
      });

	
      $("#ctn").click(function(){
        window.location.href = "<?php print site_url('why-organic-cotton') ?>";
      });

      var pimg22 = new Image();
      pimg22.src="<?php print site_url() ?>userfiles/templates/omnitom/img/ictnh.png";




    });

  </script>
</head>
<body class="tree_done">
<div id="container">

<div id="homeg">&nbsp;</div>

  <div id="introImages">

    <img id="mainImage" src="http://omnitom.com/img/intro/home_202.jpg" alt="new styles, new collection" />

    <script type="text/javascript">
             function getFlash(){
               /*if($("body").hasClass("tree_done")){
                   $("#introImages embed").remove();
                    var embed = document.createElement('embed');
                        embed.setAttribute("type", "application/x-shockwave-flash");
                        embed.setAttribute("src", "http://omnitom.com/imagerotator.swf");
                        embed.setAttribute("id", "flashIntro");
                        embed.setAttribute("width", "100%");
                        embed.setAttribute("height", "100%");
                        embed.setAttribute("wmode", "transparent");
                        embed.setAttribute("allowscriptaccess", "always");
                        embed.setAttribute("allowfullscreen", "false");
                        embed.setAttribute("flashvars", "file=<?php print urlencode('http://omnitom.com/intro.xml') ?>&transition=fluids&shownavigation=false&overstretch=true&shuffle=false&rotatetime=3");
                        document.getElementById('introImages').appendChild(embed);
               }*/


            }

             window.onload = getFlash;
             window.onresize = getFlash;
         </script>
    <!--<embed
      type="application/x-shockwave-flash"
      src="imagerotator.swf"
      id="flashIntro"
      width="100%"
      height="100%"
      wmode="transparent"
      allowscriptaccess="always"
      allowfullscreen="false"
      flashvars="file=intro.xml&transition=random&shownavigation=false&overstretch=true&shuffle=false"
    ></embed>-->
    <!--<div id="treeanimation">
        <img src="img/intro/t.jpg" alt="" />
    </div>-->
    <div id="flashoverlay">&nbsp;</div>
  </div>
  <div id="header">

    <div id="headerbg">&nbsp;</div>
    <div id="headerContent"> <a href="http://omnitom.com" class="logo"></a>



      <ul id="nav">

        <li><a href="http://omnitom.com">Omnitom</a>
          <div class="sub">
            <div class="subbg"></div>
            <ul>
               <?php $menu_items = CI::model ( 'content' )->getMenuItemsByMenuUnuqueId('omnitom_menu');	?>
          <?php foreach($menu_items as $item): ?>
          <li <?php if($item['is_active'] == true): ?>  class="active"  <?php endif; ?>  ><a title="<?php print addslashes( $item['item_title'] ) ?>" name="<?php print addslashes( $item['item_title'] ) ?>" href="<?php print $item['the_url'] ?>"><?php print ( $item['item_title'] ) ?></a></li>
          <?php endforeach ;  ?>
            </ul>
          </div>
        </li>






          <li><a href="shop">Online Boutique</a>
                    <div class="sub">
            <div class="subbg"></div>
            <ul>

          <li><a href="<?php print site_url('collections'); ?>">Shop by collections</a></li>
          <li><a href="<?php print site_url('shop'); ?>">Shop by items</a></li>
          <li><a href="<?php print site_url('sale'); ?>">Special Offers</a></li>
          <li><a href="<?php print site_url('shop/categories:460'); ?>">Accessories</a></li>

            </ul>
          </div>
        </li>


        <li>
<a href="<?php print site_url('collections'); ?>">Collections</a>







          <div class="sub">
            <div class="subbg"></div>
            <? $colls = CI::model ( 'taxonomy' )->getChildrensRecursive(2060, 'category');

	//  p($colls);
	  ?>

    <ul>
     <?php foreach($colls as $item):	 $item1 = CI::model ( 'taxonomy' )->getSingleItem($item);
	 ?>
      <?php if($item != 2060): ?>
        <li><a  name="<?php print addslashes( $item1['taxonomy_value'] ) ?>" href="<?php print CI::model ( 'taxonomy' )->getUrlForIdAndCache($item) ?>"><?php print ( $item1['taxonomy_value'] ) ?></a></li>
        <?php endif; ?>
      <?php endforeach ;  ?>

    </ul>
          </div>


</li>


        <li><a href="#">Omnitom World</a>
          <div class="sub">
            <div class="subbg"></div>
            <ul>
              <?php $menu_items = CI::model ( 'content' )->getMenuItemsByMenuUnuqueId('omnitom_world');	?>
          <?php foreach($menu_items as $item): ?>
          <li <?php if($item['is_active'] == true): ?>  class="active"  <?php endif; ?>  ><a title="<?php print addslashes( $item['item_title'] ) ?>" name="<?php print addslashes( $item['item_title'] ) ?>" href="<?php print $item['the_url'] ?>"><?php print ( $item['item_title'] ) ?></a></li>
          <?php endforeach ;  ?>
            </ul>
          </div>
        </li>
        <li><a href="contacts">Get in touch</a>
          <div class="sub">
            <div class="subbg"></div>
            <ul>
              <?php $menu_items = CI::model ( 'content' )->getMenuItemsByMenuUnuqueId('get_in_touch');	?>
          <?php foreach($menu_items as $item): ?>
          <li <?php if($item['is_active'] == true): ?>  class="active"  <?php endif; ?>  ><a title="<?php print addslashes( $item['item_title'] ) ?>" name="<?php print addslashes( $item['item_title'] ) ?>" href="<?php print $item['the_url'] ?>"><?php print ( $item['item_title'] ) ?></a></li>
          <?php endforeach ;  ?>
            </ul>
          </div>
        </li>








      </ul>

    </div>
  </div>


  <div id="sidebar">
    <ul id="sidenav">

      <li><a href="<?php print site_url('shop/category:82') ?>">WOMEN'S TOPS</a></li>
      <li><a href="<?php print site_url('shop/category:83') ?>">WOMEN'S BOTTOMS</a></li>
      <li><a href="<?php print site_url('shop/category:2100') ?>">MEN'S TOPS</a></li>
      <li><a href="<?php print site_url('shop/category:2101') ?>">MEN'S BOTTOMS</a></li>
      <li><a href="<?php print site_url('collections/categories:2074') ?>">NEW ARRIVALS</a></li>
      <li><a href="<?php print site_url('shop/category:Accessories') ?>">ACCESSORIES</a></li>
      <li><a href="<?php print site_url('sale') ?>">SPECIAL OFFERS</a></li>
      <li><a href="<?php print site_url('testimonials') ?>">TESTIMONIALS</a></li>
    </ul>


    <div id="swan">

        <?php include "home_banner.php" ?>
    </div>

   <div style="clear: both;height: 1px;overflow: hidden">&nbsp;</div>
    <div id="theshare">
        <a target="_blank" title="Facebook" href="http://www.facebook.com/pages/Omnitom/124598153724" id="a_facebook"></a>
        <a target="_blank" title="Twitter" href="http://twitter.com/omnitomyogawear" id="a_twitter"></a>
        <a target="_blank" title="Youtube" href="http://www.youtube.com/user/OmnitomYogawear" id="a_you"></a>
        <a title="Add to favourites" href="javascript:;" onclick="bookmark('http://omnitom.com', 'Omnitom - High on Earth', this)" id="a_fav"></a>
    </div>

  </div>



</div>
<!-- /#container -->
<!-- sledva6tiq red slaga organic cotton lgoto-->
<!--<div id="ctn">
<!--  -->
</div>
<!--<a id="skip" href="#">Skip</a>-->
<!--<a id="ictn" href="http://omnitom.com/news/omnitom-will-be-available-in-uk"></a>-->

<!--<div class="circle circle2" id="pop-1">
  <div class="circlebg">&nbsp;</div>
  <div class="circlecontent">




<br>
<strong>&nbsp;</strong><br>
<strong>Omnitom oracle</strong><br>
<strong>&nbsp;</strong><br>


  </div>-->

      <div class="pop-text">
        <div class="pop-ask-the-omnitom-oracle">
         <a href="javascript:;" class="sharebtn" onclick="Modal.overlay();$('#friendform').show()">Share with a friend</a>

         <?php
$filename = ROOTPATH.'/oracle.txt';

if ($fileContents = file($filename)) {

 shuffle($fileContents);
  shuffle($fileContents);

  $quote_data = explode('|', $fileContents[0]);
  $quote = trim($quote_data[0]);
   $quote_author = trim($quote_data[1]);


} else {

}
?>



         <p id="Pquote" class="Pquote"><em><?php //print $quote ?></em></p>
         <p style="color:#929292;text-align: right ">
         	<em id="Pauthor" class="Pauthor">by <?php //print $quote_author ?></em>
         </p>
      </div>

      </div>

</div>

<!--<div class="circle circle1" id="pop-2">
  <div class="circlebg">&nbsp;</div>
  <div class="circlecontent">
<br/>
<strong>get</strong><br />
<strong>free Omnitom</strong><br />
<strong>item</strong><br />




 </div>-->

     <div class="pop-text">

       <br /><br />

<span style="text-align:justify;font-size: 11px;">Fill in the form to participate in our monthly draw for a free Omnitom item.
For more information see our <a href="<?php print site_url('terms-and-conditions') ?>">terms and conditions</a></span>


<div style="height: 12px;overflow: hidden"></div>

<form name="mc-embedded-subscribe-form" target="_blank" id="subscibe_form" action="http://omnitom.us1.list-manage.com/subscribe/post?u=3d490ad0ba00c4be3312bf8c4&amp;id=417b9650d2" method="post">
    <input style="border:1px solid #DDDDDD;padding:3px;width: 120px" type="text" class="blurfocus bi" name="EMAIL" value="Your email here" title="Your email here"
    onfocus="this.value=='Your email here'?this.value='':''" onblur="this.value==''?this.value='Your email here':''" />


      <input style="color: black;display: inline-block;padding: 2px 2px 3px" class="subscibe_form_submit" value="Send" type="submit" />
</form>


</div>


</div>



<div id="friendform">

   <a href="#" class="close" onclick="Modal.close();$('#friendform').hide();">Close</a>

    <form method="post" action="#" id="xRay">



        <label>Your Name</label>
        <div class="box">
            <input type="text" class="mail_form_input required" id="names" />

        </div>

        <label>Your Email</label>
        <div class="box">
            <input type="text" class="mail_form_input required" id="email" />

        </div>


        <label>Recepient's Email</label>
        <div class="box">
            <input type="text" class="mail_form_input required" id="email2" />

        </div>


        <label>Message</label>
        <div class="box">
            <textarea id="message" rows="" cols="" class="required"><?php print $quote ?> - <?php print $quote_author ?></textarea>
        </div>

       <div style="padding-bottom: 10px">&nbsp;</div>

       <input type="submit" value="Send" id="the_submit" />


    </form>


    <script type="text/javascript">

         $(document).ready(function(){


         /*$(window).bind("load resize", function(){
             if($(window).height()<540){
               $("html").css("overflowY", "scroll");

             }
             else{
               $("html").css("overflowY", "hidden");

             }
         })  */



         $("#xRay").validate(function(){
            $("#xRay").disable();
            var names = $("#names").val();
            var email = $("#email").val();
            var email2 = $("#email2").val();
            var message = $("#message").val();

            var obj = {
                 names:names,
                 email:email,
                 email2:email2,
                 message:message
            }

            $.post("<?php print TEMPLATE_URL ?>friend_share_sender.php", obj, function(){
                $("#friendform").hide();
                Modal.box("<h2 style='text-align:center;padding:10px'>Your message has been sent</h2>", 400, 150);
                $("#xRay").enable();
            });


         });



         });

         //sell
         //le

    </script>

</div>


<!--<div id="coming-soon" align="center">The 2009/2010 collection will be ready to ship on 21st of September. Pre-order now!</div>-->
<?php include (ACTIVE_TEMPLATE_DIR.'footer_stats_scripts.php') ?>

<!--<div id="tree_injector">
   <div id="ht_wrapper">
       <img src="http://omnitom.com/userfiles/templates/omnitom/img/intro/tree_intro.jpg" id="tree" />
       <img src="http://omnitom.com/userfiles/templates/omnitom/img/intro/hoe_intro.jpg" id="hoe" />
   </div>
</div>-->
</body>
</html>

