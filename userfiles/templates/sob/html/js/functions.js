isie6 = !window.XMLHttpRequest;

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
$.imgpreload(
  imgurl+'btnlefth.jpg',
  imgurl+'btnrighth.jpg'
);

function moovePopDotTo(new_state){
       if(new_state==1){
         $("#pd-dot").animate({"left":"9px"}, function(){
             $("#pd-ctrl").attr("state", new_state);
        });
       }
       if(new_state==2){
         $("#pd-dot").animate({"left":"23px"}, function(){
             $("#pd-ctrl").attr("state", new_state);
        });
       }
       if(new_state==3){
         $("#pd-dot").animate({"left":"36px"}, function(){
             $("#pd-ctrl").attr("state", new_state);
        });
       }

}

function SlideRight(){
    if($("#home-slider").hasClass("activated")){
       /* */
    }
    else{
        $("#home-slider").addClass("activated");
        $("#home-slider-left").show();
        var slide_url = $("#home-slider-controlls li.active a").attr("href");
        var current_state = parseFloat($("#home-slide-holder").attr("state"));
        if(current_state==2){
          $("#home-slider-right").hide();
        }
        $.get(slide_url + "/state-" + (current_state+1), function(data){
            $("#home-slide-animator").append(data);
            $("#home-slide-animator").animate({"left":"-542px"}, function(){
                $("#home-slide-animator ul:first").remove();
                $("#home-slide-animator").css("left", "0px");
                $("#home-slider").removeClass("activated");
                $("#home-slide-holder").attr("state", current_state+1);
            });
            $("#home-slider-content ul a").hover(function(){
                      sliderTip(this);
                  },function(){
                      sliderUntip();
            });
        });
    }
}

function SlideLeft(){
    if($("#home-slider").hasClass("activated")){
       /* */
    }
    else{
        $("#home-slider").addClass("activated");
        $("#home-slider-right").show();
        var slide_url = $("#home-slider-controlls li.active a").attr("href");
        var current_state = parseFloat($("#home-slide-holder").attr("state"));
        if(current_state==2){
          $("#home-slider-left").hide();
        }
        $.get(slide_url + "/state-" + (current_state-1), function(data){
            $("#home-slide-animator").prepend(data);
            $("#home-slide-animator").css({"left":"-542px"});
            $("#home-slide-animator").animate({"left":"0px"}, function(){
                $("#home-slide-animator ul:last").remove();
                $("#home-slide-animator").css("left", "0px");
                $("#home-slider").removeClass("activated");
                $("#home-slide-holder").attr("state", current_state-1);
            });
            $("#home-slider-content ul a").hover(function(){
                      sliderTip(this);
                  },function(){
                      sliderUntip();
            });
        });
    }
}

var popSlideURL = "http://workspace.ooyes.net/soob/slider/pop/";

function popSlideLeft(currState, newState){
    if(currState>=1){
         $.get(popSlideURL + "state_" +newState+'.txt', function(data){
             $("#popular-slider").prepend(data);
             $("#popular-slider").css({"left":"-653px"});
             moovePopDotTo(newState);
             $("#popular-slider").animate({"left":"0px"}, function(){
                    $("#popular-slider ul:last").remove();
                    $("#pd-ctrl").attr("state", newState);
             });
         });
    }

}
function popSlideRight(currState, newState){

    if(currState<=2){
         $.get(popSlideURL + "state_" +newState+'.txt', function(data){
             $("#popular-slider").append(data);
             moovePopDotTo(newState);
             $("#popular-slider").animate({"left":"-653px"}, function(){
                   $("#popular-slider ul:first").remove();
                   $("#popular-slider").css({"left":"0px"});
                   $("#pd-ctrl").attr("state", newState);
             });
         });
    }
}


function sliderTip(elem){
    $("#helpelem").stop();
    $("#slidertip").empty();
    var offset_left = $(elem).offset().left;
    var offset_top = $(elem).offset().top;
    var elem_html = $(elem).find("span").html();
    $("#slidertip").html(elem_html);
    var slider_tip_half_width = $("#slidertip").outerWidth()/2;
    $("#slidertip").css({
      "top":offset_top + 52,
      "left":offset_left - slider_tip_half_width + 34,
      "visibility":"visible"
    });

}

function sliderUntip(){
    $("#helpelem").animate({"left":"0px"}, 10, function(){
        if($("#slidertip").hasClass("hover")){/**/}
        else{
           $("#slidertip").hidden();
        }
    });
}


$(document).ready(function(){
    $("a[href='#']").attr("href", "javascript:void(0)");
    $("*").each(function(){
      if($(this).css("clear")=='both'){
        $(this).before("<span class='c'>&nbsp;</span>");
      }
    });
    $("#header-login .submit-field").hover(function(){
      $(this).css("backgroundPosition", "0 -23px");
    }, function(){
      $(this).css("backgroundPosition", "0 0");
    });

    $("#navigation-bar ul a span").each(function(){
       var nav_link_text = $(this).html();
       $(this).after("<samp>" + nav_link_text +"</samp>");
    });
    $("#home-slider-controlls li").hover(function(){
        $(this).addClass("hover");
    }, function(){
        $(this).removeClass("hover");
    });

    $("#slidertip").hover(function(){
      $(this).addClass("hover");
    }, function(){
      $(this).removeClass("hover");
    });


    /* slider*/


    $("#home-slider-content ul a").hover(function(){
        sliderTip(this);
    },function(){
        sliderUntip();
    });

    $("#slidertip").mouseout(function(){
      $(this).hidden();
    });

    $("#home-slide-holder").attr("state", "1");
    $("#home-slide-holder").attr("index", "1");

    $("#home-slider-controlls a").click(function(){
        if($(this).parent().hasClass("active")){
           /**/
        }
        else{
          if($("#home-slider").hasClass("activated")){
             /**/
          }
          else{
              $("#home-slider").addClass("activated");
              $("#home-slider-right").show();
              $("#home-slider-left").hide();
              NewIndex = parseFloat($("#home-slider-controlls a").index(this)+1);
              OldIndex = parseFloat($("#home-slide-holder").attr("index"));
              $("#home-slider-controlls li").removeClass("active");
              $(this).parent().addClass("active");
              $("#home-slide-holder").attr("state", "1");
              var active_href = $(this).attr("href");
              $.get(active_href + "/state-1.txt", function(data){
                  if(NewIndex > OldIndex){
                      $("#home-slide-animator").append(data);
                      $("#home-slide-animator").animate({"left":"-542px"}, function(){
                          $("#home-slide-animator ul:first").remove();
                          $("#home-slide-animator").css("left", "0px");
                          $("#home-slider").removeClass("activated");
                          $("#home-slide-holder").attr("index", NewIndex);
                      });
                  }
                  else{
                      $("#home-slide-animator").prepend(data);
                      $("#home-slide-animator").css({"left":"-542px"});
                      $("#home-slide-animator").animate({"left":"0px"}, function(){
                          $("#home-slide-animator ul:last").remove();
                          $("#home-slide-animator").css("left", "0px");
                          $("#home-slider").removeClass("activated");
                          $("#home-slide-holder").attr("index", NewIndex);
                      });
                  }
                  $("#home-slider-content ul a").hover(function(){
                      sliderTip(this);
                  },function(){
                      sliderUntip();
                  });

              });
           }
        }
        return false;
    });


        /* hide slider*/
        $("#home-slider-controlls, #home-slider-left, #home-slider-right").mouseover(function(){
           $("#slidertip").hidden();
        });




    /* /slider*/



    /* POP SLider  */

    $("#pd-ctrl").attr("state", "1");

    $(".pd-ctrl").click(function(){
       if($(this).hasClass("active")){

       }
       else{
         if($('#popular-slider').not(":animated").length>0){
               $(".pd-ctrl").removeClass("active");
               $(this).addClass("active");
               var state = parseFloat($("#pd-ctrl").attr("state"));
               var new_state = parseFloat($(this).text());
               if(new_state>state){
                  popSlideRight(state, new_state);
               }
               else{
                  popSlideLeft(state, new_state);
               }
         }

       }
    });

    $("#pd-right").click(function(){
      var curr_state = parseFloat($("#pd-ctrl").attr("state"));
      if(curr_state<=2 && $('#popular-slider').not(":animated").length>0){
        $(".pd-ctrl").removeClass("active");
        $(".pd-ctrl").eq(curr_state).addClass('active');
        popSlideRight(curr_state, curr_state+1);
      }


    });
    $("#pd-left").click(function(){
        var curr_state = parseFloat($("#pd-ctrl").attr("state"));
        if(curr_state>=1 && $('#popular-slider').not(":animated").length>0){
          $(".pd-ctrl").removeClass("active");
          $(".pd-ctrl").eq(curr_state-2).addClass('active');
          popSlideLeft(curr_state, curr_state-1);
        }
    });


    /* /POP SLider  */

   $(".btn").each(function(){
      var btntext = $(this).text();
      $(this).html("<span>"+btntext+"</span>");
   });

   $(".tabs-holder").tabs("html");
   $(".tabs-nav li:first-child").addClass("first");
   $(".tabs-nav li:last-child").addClass("last");


$("#fa-ctrl li").click(function(){
    if($(this).attr("class")!="active"){
        $("#fa-ctrl li").removeClass("active");
        $(this).addClass("active");
        var step = parseFloat($(this).text())-1;
        $("#featured-articles-slider").stop();
        $("#featured-articles-slider").animate({"left":-step*600}, 'slow');
    }
});



   $(".home-featured-article .addthis_button_compact").each(function(){
        var shareurl = $(this).parents(".home-featured-article").find("a:first").attr("href");
        var sharetitle = $(this).parents(".home-featured-article").find("h3").text();
       $(this).attr("addthis:url", shareurl);
       $(this).attr("addthis:title", sharetitle);
   });


    var anchor = getAnchor();
    if(anchor=='shake'){
      shake();
    }


   if($.browser.msie){
     $(".side-tabs .tab li:last-child").css("borderBottom", "none");
     $(".pop-side li:last-child").css("borderBottom", "none");
   }
   if(isie6){
     $("#home-featured-articles-wrapper li").hover(function(){
       $("#home-featured-articles-wrapper li").removeClass("hover");
       $(this).addClass("hover");
     }, function(){
       $(this).removeClass("hover");
     });
   }



});// end document ready


bName = navigator.appName;
bVer = parseInt(navigator.appVersion);
        if (bName == "Netscape" && bVer == 3) ver = "n3";
        else if (bName == "Netscape" && bVer == 2) ver = "n2";
        else if (bName == "Netscape" && bVer >= 4) ver = "n4";
        else if (bName == "Microsoft Internet Explorer" && bVer == 2) ver = "e3";
        else if (bName == "Microsoft Internet Explorer" && bVer > 2) ver = "e4";
if (navigator.appVersion.indexOf("Mac") != -1) ver+="m";
function shake() {
        if (ver == "n4" || ver == "n4m" || ver == "e4" || ver == "e4m") {
                for (i = 10; i > 0; i--) {
                for (z = 10; z > 0; z--) {
                        self.moveBy(0,i);
                        self.moveBy(i,0);
                        self.moveBy(0,-i);
                        self.moveBy(-i,0);
                } }
        } void(0);
}

function scrollto(elem){
   var scrollTo = $(elem).offset().top;
   $("html, body").animate({scrollTop: scrollTo}, 700);
}











