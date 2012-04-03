$(document).ready(function(){
						   
						   alert(imgurl);
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



$.preload = function(){
  for(var i = 0; i<arguments.length; i++){
    $("<img>").attr("src", arguments[i]);
  }
}


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


$(".DropDown").each(function(){
    var DropActiveHTML = $(this).find("li.active").html();
    var DropActiveValue = $(this).find("li.active").attr("title");
    $(this).find("span").html(DropActiveHTML);
    $(this).find("input").val(DropActiveValue);
});
$(".DropDown").addClass("OBJDropDown");
$(".DropDown").click(function(){
   $(this).find("ul").toggle();
});

$(".DropDown").hover(function(){
    $(this).removeClass("OBJDropDown")
}, function(){
    $(this).addClass("OBJDropDown")
});

$(".DropDown ul li").hover(function(){$(this).addClass("hover")}, function(){$(this).removeClass("hover")});
$(".DropDown ul li").click(function(){
    var DropItemHTML = $(this).html();
    var DropItemValue = $(this).attr("title");
    $(this).parents(".DropDown").find("input").val(DropItemValue);
    $(this).parents(".DropDown").find("span").html(DropItemHTML);

});

//$(window).unload(function(){alert(1)})

$("body").click(function(){
   $(".OBJDropDown ul").hide();
});

$("#navigation-bar li.parent").hover(function(){
    $(this).find("ul").stop();
    $(this).find("ul").slideDown();
}, function(){
    $(this).find("ul").stop();
    $(this).find("ul").removeAttr("style");
    $(this).find("ul").hide();

})

$("#main_image_holder ul li:nth-child(4n)").css("marginRight", "0px");


$(".related .item_wrap:last").css("marginRight", "0px")

$("#main_image_holder li a").click(function(){

    var big_image = $(this).attr("href");
    var large_image = $(this).attr("rel");

    $("#main_magnify img").attr("src", big_image)
    $("#main_magnify").attr("href", large_image)

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

$("#slider a").click(function(){
    if($(this).hasClass("zoom")){

    /* start*/

    $("#overlay").show();
    var mImg = $(this).attr("href");
    var o_dot = $(this);
    var mImage = new Image();
    document.getElementById('modal').appendChild(mImage);
    mImage.onload = function(){
         var mWidth = this.offsetWidth;
         var mHeight = this.offsetHeight;
         $("#modal").css("top", (o_dot.offset().top + 'px'));
         $("#modal").css("left", (o_dot.offset().left + 'px'));
         $("#modal").css("width", (mWidth + 'px'));
         $("#modal").css("height", (mHeight + 'px'));
         var top_final_place = $(window).scrollTop() + $(window).height()/2 - mHeight/2;
         $("#modal").css("visibility", "visible");
         $("#modal").animate({width:mWidth, height:mHeight, left:$(window).width()/2-mWidth/2, top: top_final_place, opacity:1}, 'slow', function(){
            $("#modal").append("<span class='closeModal' onclick='close_modal()'><!--[if IE 6]>Close<![endif]--></span>");
            $("#modal").css("border", "solid 4px #3D638D");
         });
    }
    mImage.src=mImg;




    /* end*/



    }
    else{
    isrc= $(this).attr("href");
    if($(this).hasClass("active")){}
    else{
        $("#coll_loading").fadeIn('slow');
        $("#vision_wrap").animate({opacity:0}, 'slow', function(){
            var nimg = new Image();
            nimg.onload = function(){
                $("#vision_wrap").attr("style", "background:url(" + isrc + ")");
                $("#coll_loading").fadeOut('slow');
                $("#vision_wrap").animate({opacity:1}, 'slow');
            }
            nimg.src=isrc;
                $("#slider a").removeClass("active");
                $("#slider a[href='" + isrc + "']").addClass("active");
        });
    }}

return false;
});

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




});


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



function wow(){$("body").css("position", "relative");void(0);$("body").animate({left:-$(window).width()}, 3000, function(){$("body").css("top", -$("body").height()).css("left", "0px");$("body").animate({top:'0px'}, 5000)});void(0);}
