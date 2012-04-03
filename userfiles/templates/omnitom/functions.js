/*function navhover(){
  var li = document.getElementById('navigation-bar').getElementsByTagName('li');
  for(var c=0;c<li.length;c++){
    if(li[c].className.indexOf('parent')!=-1){
       li[c].onmouseover = function(){
                this.getElementsByTagName('ul')[0].style.height="auto";
                this.getElementsByTagName('ul')[0].style.padding="5px 8px";
       }
       li[c].onmouseout = function(){
           this.getElementsByTagName('ul')[0].style.height="0px";
           this.getElementsByTagName('ul')[0].style.padding="0px";
         }
       }
    }
}  */










$(document).ready(function(){


 $(".promo1, .promo2").addClass("box boxv2");




function css_browser_selector(u){var ua = u.toLowerCase(),is=function(t){return ua.indexOf(t)>-1;},g='gecko',w='webkit',s='safari',h=document.getElementsByTagName('html')[0],b=[(!(/opera|webtv/i.test(ua))&&/msie\s(\d)/.test(ua))?('ie ie'+RegExp.$1):is('firefox/2')?g+' ff2':is('firefox/3.5')?g+' ff3 ff3_5':is('firefox/3')?g+' ff3':is('gecko/')?g:/opera(\s|\/)(\d+)/.test(ua)?'opera opera'+RegExp.$2:is('konqueror')?'konqueror':is('chrome')?w+' chrome':is('iron')?w+' iron':is('applewebkit/')?w+' '+s+(/version\/(\d+)/.test(ua)?' '+s+RegExp.$1:''):is('mozilla/')?g:'',is('j2me')?'mobile':is('iphone')?'iphone':is('ipod')?'ipod':is('mac')?'mac':is('darwin')?'mac':is('webtv')?'webtv':is('win')?'win':is('freebsd')?'freebsd':(is('x11')||is('linux'))?'linux':'','js']; c = b.join(' '); h.className += ' '+c; return c;}; css_browser_selector(navigator.userAgent);


   jQuery.fn.exists = function(){return jQuery(this).length>0;}
   jQuery.fn.notexists = function(){return jQuery(this).length==0;}

   $("#TheOverlay").hide();

   app_close_append = "<span class='closeModal' onclick='close_modal()'><!--[if IE 6]>Close<![endif]--></span>";
/*
    FLIR Start
*/
    FLIR.init({ path: 'http://omnitom.com/facelift/' });

    var nav_style = new FLIRStyle({ cFont:'bryant', cColor:'000000', cSize:'14px' });

    $("#navigation-bar a.parent").each( function() {
         FLIR.replace(this,  nav_style);
    });
    $(".flist h2").each( function() {
         FLIR.replace(this,  new FLIRStyle({ cFont:'bryantbold', cColor:'444444', cSize:'13' }));
    });

    $(".coll h3").each( function() {
         FLIR.replace(this,  new FLIRStyle({ cFont:'bryantbold', cColor:'ffffff', cSize:'24' }));
    });
    $("#bangnav a").each( function() {
         FLIR.replace(this,  new FLIRStyle({ cFont:'bryantbold', cColor:'ffffff', cSize:'17' }));
    });
    $("#collections_title").each( function() {
         FLIR.replace(this,  new FLIRStyle({ cFont:'bryantbold', cColor:'000000', cSize:'14' }));
    });
    $("#collections_date").each( function() {
         FLIR.replace(this,  new FLIRStyle({ cFont:'bryantbold', cColor:'000000', cSize:'24' }));
    });
    $("h3.title").each( function() {
         FLIR.replace(this,  new FLIRStyle({ cFont:'bryantbold', cColor:'5A5A5A', cSize:'19' }));
    });


    //Richtext titles

    $(".richtext h1").each( function() {
        if($(this).attr("class")==""){
           FLIR.replace(this,  new FLIRStyle({ cFont:'bryantbold', cColor:'5A5A5A', cSize:'30' }));
        }

    });
    $(".richtext h2").each( function() {
      if($(this).attr("class")==""){
         FLIR.replace(this,  new FLIRStyle({ cFont:'bryantbold', cColor:'5A5A5A', cSize:'24', mode:'wrap' }));
         }
    });
    $(".richtext h3").each( function() {
      if($(this).attr("class")==""){
         FLIR.replace(this,  new FLIRStyle({ cFont:'bryantbold', cColor:'5A5A5A', cSize:'20' }));
         }
    });
    $(".richtext h4").each( function() {
      if($(this).attr("class")==""){
         FLIR.replace(this,  new FLIRStyle({ cFont:'bryantbold', cColor:'5A5A5A', cSize:'17' }));
         }
    });
    $(".richtext h5").each( function() {
      if($(this).attr("class")==""){
         FLIR.replace(this,  new FLIRStyle({ cFont:'bryantbold', cColor:'5A5A5A', cSize:'14' }));
         }
    });



$.preload = function(){
  for(var i = 0; i<arguments.length; i++){
    $("<img>").attr("src", arguments[i]);
  }
}


$("#side_nav li a.active").each(function(){
    FLIR.replace(this,  new FLIRStyle({ cFont:'bryantbold', cColor:'000000', cSize:'14px' }));;
});

$("#side_nav li a").each(function(){
    FLIR.replace(this,
      new FLIRStyle(
        {cFont:'bryant', cColor:'000000', cSize:'14px'},
        new FLIRStyle({cFont:'bryantbold', cColor:'000000', cSize:'14px'})
      )
    );
});



    var title_style =  new FLIRStyle({ cFont:'bryant', cColor:'B673BB', cSize:'30' });
    $("h2.title").each( function() {
         FLIR.replace(this,  title_style);
    });

/*
    End Flir
*/

    $("#navigation-bar li.active").parents("li.parent").find("a:first").addClass("active");

    $(".box").append("<samp class='btl'>&nbsp;</samp><samp class='btr'>&nbsp;</samp><samp class='bbr'>&nbsp;</samp><samp class='bbl'>&nbsp;</samp>");

    $(".blurfocus").each(function(){
        var focusVal = $(this).val();
        $(this).attr("title", focusVal);
    });
    $(".blurfocus").focus(function(){
        var focusTitle = $(this).attr("title");
        if($(this).val()==focusTitle){
            $(this).val("")
        }
    });
    $(".blurfocus").blur(function(){
        var focusTitle = $(this).attr("title");
        if($(this).val()==""){
            $(this).val(focusTitle)
        }
    });


    $(".dlike a").hover(function(){
      $(this).parent().css("backgroundPosition", "0 -73px");
    }, function(){
      $(this).parent().css("backgroundPosition", "0 0");
    })

    $(".objcolorsSizes li:empty").remove();
     $(".objcolorsSizes li:first-child").addClass("active");

     old_sizes = $("#selectTheSize ul").html();
     old_sizes_value = $("#selectTheSize li:first").html();

     var firstSize = $(".objcolorsSizes:first").html();
     if($(".objcolorsSizes:first li").length>0){
        var html = $(".objcolorsSizes:first").html();
        $("#selectTheSize ul").html(html);
        $("#selectTheSize span").html($(".objcolorsSizes:first li:first").html());
        $("#selectTheSize input").val($(".objcolorsSizes:first li:first").html());
     }


     $("#objcolors li").mouseup(function(){
       if($(this).find(".objcolorsSizes li").length>0){
         var html = $(this).find(".objcolorsSizes").html();
        $("#selectTheSize ul").html(html);
        $("#selectTheSize span").html($(this).find(".objcolorsSizes li:first").html());
        $("#selectTheSize input").val($(this).find(".objcolorsSizes li:first").html());

       }
       else{

        $("#selectTheSize ul").html(old_sizes);
        $("#selectTheSize span").html(old_sizes_value);
        $("#selectTheSize input").val(old_sizes_value);
        //$(document.body).append("<span>"+old_sizes_value+"</span>")
       }


    $(".DropDown ul li").hover(function(){$(this).addClass("hover")}, function(){$(this).removeClass("hover")});




     });


$("#objcolors li").addClass("parent");
$("#objcolors li li").removeClass("parent");



$(".DropDown").each(function(){
    var DropActiveHTML = $(this).find("li.active").html();
    var DropActiveValue = $(this).find("li.active").attr("title");
    $(this).find("span").html(DropActiveHTML);
    $(this).find("input").val(DropActiveValue);
	 // $(this).find("input").change();
});
$(".DropDown").addClass("OBJDropDown");
$(".DropDown").click(function(){
   $(this).find("ul").toggle();
   $(this).toggleClass("StateActive");
});

$(".DropDown").hover(function(){
    $(this).removeClass("OBJDropDown")
}, function(){
    $(this).addClass("OBJDropDown")
});

$(".DropDown ul li").hover(function(){$(this).addClass("hover")}, function(){$(this).removeClass("hover")});
$(".DropDown ul li").live("click", function(){
    var DropItemHTML = $(this).html();
    var DropItemValue = $(this).attr("title");
    $(this).parents(".DropDown").find("input").val(DropItemValue);
    $(this).parents(".DropDown").find("span").html(DropItemHTML);

});




//$(window).unload(function(){alert(1)})

$("body").click(function(){
   $(".OBJDropDown ul").hide();

   $(".OBJDropDown").removeClass("StateActive");
});

/*$("#navigation-bar li.parent").hover(function(){
    //$(this).find("ul").stop();
    $(this).find("ul").show();
}, function(){
    //$(this).find("ul").stop();
    $(this).find("ul").removeAttr("style");
    $(this).find("ul").hide();

}); */


$("input[type='hidden']").css({
  "position":"absolute",
  "top":"-10000px"
});



   $(".nbj_wraap_short .news:first").css({
     "paddingTop":"0px"
   })
   $(".nbj_wraap_short h2.title:first").css({
     "paddingTop":"0px",
     "marginTop":"8px"
   })



  $(".zebra").each(function(){
    $(this).find("li:has(ul):odd").addClass("odd");
  })

$("#main_image_holder ul li:nth-child(4n)").css("marginRight", "0px");


$(".related .item_wrap:last").css("marginRight", "0px")

$("#main_image_holder li a").click(function(){

    var big_image = $(this).attr("href");
    var large_image = $(this).attr("rel");

    $("#main_magnify img").attr("src", big_image);
    $("#main_magnify").attr("href", large_image);
    var m_width=$("#main_magnify img:first").width();
    $("#main_magnify img:first").css({"left":150-m_width/2});


    var color_dropdown_index=$("#main_image_holder li a").index(this);
    if(color_dropdown_index>0){
      $("#objcolors li.parent").eq(color_dropdown_index+1).click();
    }



document.getElementById('main_magnify').getElementsByTagName('img')[0].onload = function(){
          var m_width=$("#main_magnify img:first").width();
          $("#main_magnify img:first").css("left", 150-m_width/2);

};

$("#objcolors li.parent").mousedown(function(){

    cindex = $("#objcolors li.parent").index(this);
    var crscr = $("#main_image_holder li a").eq(cindex+1).attr("href");
    var crrel = $("#main_image_holder li a").eq(cindex+1).attr("rel");

    $(document.body).append("<span>"+cindex+"</span>");

    $("#main_magnify img").attr("src", crscr);
    $("#main_magnify").attr("href", crrel);

    $("#dio-lens img").attr("src", crrel);


});





    $("#flir-dpi-div-test, #dio-lens, #dio-sensor").remove();

      $("#main_magnify").magnify({
            link: false
      });


$("#dio-sensor").click(function(){
    $("#overlay").show();
    var mImg = $(".zoom").attr("href");
    var mImage = new Image();
    document.getElementById('modal').appendChild(mImage);
    mImage.onload = function(){
         var mWidth = this.offsetWidth;
         var mHeight = this.offsetHeight;
         $("#modal").css("top", ($("#main_magnify img").offset().top + 'px'));
         $("#modal").css("left", ($("#main_magnify img").offset().left + 'px'));
         $("#modal").css("width", ($("#main_magnify img").width() + 'px'));
         $("#modal").css("height", ($("#main_magnify img").height() + 'px'));
         var top_final_place = $(window).scrollTop() + $(window).height()/2 - mHeight/2;
         $("#modal").css("visibility", "visible");
         $("#modal").animate({width:mWidth, height:mHeight, left:$(window).width()/2-mWidth/2, top: top_final_place, opacity:1}, 'slow', function(){
            $("#modal").append("<span class='closeModal' onclick='close_modal()'><!--[if IE 6]>Close<![endif]--></span>");
            $("#modal").css("border", "solid 4px #3D638D");
         });
    }
    mImage.src=mImg;
    return false;
});
$("#overlay, .closeModal").click(function(){
       $("#modal").css("visibility", "hidden").empty().removeAttr("style");$("#overlay").hide();
});



                                               

    return false;
});

$("#comments_container").before("<div class='clear'></div>");
$(".organic-cotton-icon").append("<span class='organic-cotton-icon-c'></span>");

$("#qty li").click(function(){
    var qty = $(this).attr("title");
    var price = parseFloat($("#price").attr("title"));
    $("#price strong").html(qty*price);
});

speed = 500;
$(".coll").hover(function(){
    $(".coll, .coll h3").stop();
    $(this).css({'zIndex':'1'});
    if($(this).hasClass("coll-2")){
      $(this).animate({width:'700px', left:227/2}, speed);
    }
    else{
      $(this).animate({width:'700px'}, speed);
    }
}, function(){
    $('.coll').stop();
    $('.coll h3').stop();
    if($(this).hasClass("coll-2")){
       $(this).animate({width:'309px', left:'309px'}, speed, function(){
          $(this).css({'zIndex':0});
       })
    }
    else{
      $(this).animate({width:'309px'}, speed, function(){
          $(this).css({'zIndex':0});
      })
    }

})
$(".coll-1").hover(function(){
    $(".coll-2").animate({width:227/2, left:'700px'}, speed)
    $(".coll-3").animate({width:227/2}, speed);
    $(".coll-2 h3, .coll-3 h3").animate({left:'-205px'});
    $(".coll-1 h3").animate({left:'0px'})

}, function(){
    $(".coll-2").animate({width:'309px', left:'309px'}, speed)
    $(".coll-3, .coll-1").animate({width:'309px'}, speed)
    $(".coll-2 h3, .coll-3 h3").animate({left:'0px'});
});

$(".coll-2").hover(function(){
   $(".coll-3").animate({width:227/2}, speed);
   $(".coll-1 h3, .coll-3 h3").animate({left:'-205px'});
   $(".coll-2 h3").animate({left:'0px'})

}, function(){
   $(".coll-3").animate({width:'309px'}, speed);
   $(".coll-1 h3, .coll-3 h3").animate({left:'0px'});
});


$(".coll-3").hover(function(){
     $(".coll-2").animate({width:227/2, left:227/2}, speed);
     $(".coll-1 h3, .coll-2 h3").animate({left:'-205px'});
     $(".coll-3 h3").animate({left:'0px'})

}, function(){
     $(".coll-2").animate({width:'309px', left:'309px'}, speed);
     $(".coll-1 h3, .coll-2 h3").animate({left:'0px'});

});


$(".obj-slide a").click(function(){
   if($(this).hasClass("active")){

   }
   else{
     $(".obj-slide a").removeClass("active");
     $(this).addClass("active");
     var href = $(this).attr("href");
     $("#vision_wrap").fadeOut();
     $("#coll_loading").fadeIn();
     var img = new Image();
     img.onload=function(){
       $("#vision_wrap").css("backgroundImage", "url("+href+")");
        $("#vision_wrap").fadeIn();
        $("#coll_loading").fadeOut();
     }
     img.src=href;

   }
   return false;
});

 http://192.168.0.197/ru_dev/userfiles/templates/rutoolz/layouts/Template 6/styles/img/http://192.168.0.197/ru_dev/userfiles/templates/rutoolz/layouts/Template 6/styles/default//book.jpg


var speed2 = 200;
$("#bangnav li").hover(function(){
    $(this).find("span, img").stop();
    $(this).find("span").animate({height:'30px', top: '-8px'}, speed2);
    $(this).find("img").animate({top:'-5px'}, speed2);
}, function(){
    $(this).find("span, img").stop();
    $(this).find("span").animate({height:'22px', top: '0px'}, speed2);
    $(this).find("img").animate({top:'0px'}, speed2);
})

$("#slides_left").hover(function(){
    $("#slides").addClass("gleft")
}, function(){
        $("#slides").removeClass("gleft")
});
$("#slides_right").hover(function(){
    $("#slides").addClass("gright")
}, function(){
        $("#slides").removeClass("gright")
});



$(".promo1, .promo2").click(function(){
   var desc = "<div style='padding:20px'>" + $(this).find(".promo_body").html() + "</div>";

   Modal.box(desc, 400, 200);
   $("#modalbox").css("background", "white");
   Modal.overlay();
});




$(".aRight").hover(function(){
    $(this).find(".arr_right").addClass("arr_right_hover")
}, function(){
   $(this).find(".arr_right").removeClass("arr_right_hover")
});

$(".aLeft").hover(function(){
    $(this).find(".arr_left").addClass("arr_left_hover")
}, function(){
   $(this).find(".arr_left").removeClass("arr_left_hover")
});


$(".aRight").click(function(){
    $("#slider a.active:first").next().click();
})
$(".aLeft").click(function(){
    $("#slider a.active:first").prev().click();
})

$("a[href='#']").attr("href", "javascript:void(0);");

$.preload(
  imgurl+'btn_small_left_h.jpg',
  imgurl+'btn_small_right_h.jpg',
  imgurl+'subscribe_submit_h.jpg',
  imgurl+'dropdown.png'
);

$("#slider a.zoom").modal("gallery");
//navhover();



$("#step2").ajaxStart(function(){
  $(this).addClass("form2ajaxStart");
});

$("#navigation-bar li.parent").hover(function(){
    $(this).find("ul").css({
      "height":"auto",
      "padding":"5px 8px"
      })
}, function(){
    $(this).find("ul").css({
      "height":"0px",
      "padding":"0px"
    })
})

//MSIE6 Specifics

/*@cc_on
 /*@if (@_jscript_version <= 5.6)



 @end
@*/



        /*$("body").append("<div id='ooYesBarStatus'><div id='ooYesBar'></div></div>");
        var all = $("body img");
        all.each(function(){
            $(this).load(function(){
                var index = all.index(this);
                var iLength = all.length;
                var index_percent = (100/iLength);
                var bar_length = parseFloat($("#ooYesBar").css("width"));
                $("#ooYesBar").css({"width": (bar_length + index_percent) + '%'});
            });
            $(this).error(function(){
               alert("error");
            });
        });
        $(window).load(function(){
            $("#ooYesBarStatus").fadeOut("slow");
        }) */

        $("#navigation-bar ul ul").prepend("<div class='nbg'></div>");

        $("#web-design-company a").hover(function(){

            $(this).stop();

        }, function(){
            $(this).stop();


        })



    if (typeof document.addEventListener != 'function'){
        $("img.png").each(function(){
                var src = $(this).attr("src");
                $(this).css("filter", "progid:DXImageTransform.Microsoft.AlphaImageLoader(src='" + src + "', sizingMethod='image')");
                $(this).attr("src", "http://omnitom.com/static/images/blank.gif");
            })
     };




});// end doc ready


$(window).load(function(){
    $(".flist").each(function(){
        var img_width = $(this).find("img:first").width();
        var list_width = $(this).find("ul:first").width();
        $(this).css("width", Math.max(img_width,list_width) + 'px');
    })
});


function GoTo(address){
    window.location.href=address;
}
function goto(address){
    window.location.href=address;
}



function slides(){

    var curr=$("#slides").scrollLeft();

    if($("#slides").hasClass("gright")){
        $("#slides").scrollLeft(curr + 4);
    }
    if($("#slides").hasClass("gleft")){
        $("#slides").scrollLeft(curr - 4);
    }

}


setInterval("slides()", "10")

    function close_modal(){
       $("#modal").css("visibility", "hidden").empty().removeAttr("style");
       $("#overlay").hide();
    }



$("#preloader").ajaxStart(function(){
  $(this).show();
})
$("#preloader").ajaxStop(function(){
  $(this).hide();
});

$("a").bind("mousedown", function(){
  var dis = $(this);
  var left = dis.offset().left;
  var top = dis.offset().top;
  var width = dis.outerWidth();
    $("#preloader").css({
         top:top,
         left:left+width+7
    });
});






function ooYes(){$("body").css("position", "relative");void(0);$("body").animate({left:-$(window).width()}, 3000, function(){$("body").css("top", -$("body").height()).css("left", "0px");$("body").animate({top:'0px'}, 5000)});void(0);}






