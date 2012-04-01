function css_browser_selector(u){var ua = u.toLowerCase(),bis=function(t){return ua.indexOf(t)>-1;},g='gecko',w='webkit',s='safari',h=document.getElementsByTagName('html')[0],b=[(!(/opera|webtv/i.test(ua))&&/msie\s(\d)/.test(ua))?('ie ie'+RegExp.$1):bis('firefox/2')?g+' ff2':bis('firefox/3')?g+' ff3':bis('gecko/')?g:/opera(\s|\/)(\d+)/.test(ua)?'opera opera'+RegExp.$2:bis('konqueror')?'konqueror':bis('chrome')?w+' chrome':bis('applewebkit/')?w+' '+s+(/version\/(\d+)/.test(ua)?' '+s+RegExp.$1:''):bis('mozilla/')?g:'',bis('j2me')?'mobile':bis('iphone')?'iphone':bis('ipod')?'ipod':bis('mac')?'mac':bis('darwin')?'mac':bis('webtv')?'webtv':bis('win')?'win':bis('freebsd')?'freebsd':(bis('x11')||bis('linux'))?'linux':'','js']; c = b.join(' '); h.className += ' '+c; return c;}; css_browser_selector(navigator.userAgent);

    $.fn.exists = function(){
    	return $(this).length>0;
    }


function flex(){
  /**/
}







//$("form.save_disabled");

$(document).ready(function(){
     is_ie=/*@cc_on!@*/!1;

     $(".btn").not("input").each(function(){
       var btn_html = $(this).html();
       $(this).html("<span>" + btn_html + "</span>")
     });

$("input[type='submit'], input[type='button ']").each(function(e){
  $(this).removeClass(".btn");
  var item = $(this);
  if($(this).hasClass("custom")){
    //do nothing
  }
  else{
      $(this).replaceWith("<span class='btn' id='btn_" + e + "'></span>");
      $("#btn_" + e).append(item);
  }

})


$("#colors_area").val("");


     if (typeof document.addEventListener != 'function'){
        $("img[src$='.png']").each(function(){
            if($(this).hasClass("custom")){}
            else{
                var src = $(this).attr("src");
                $(this).css("filter", "progid:DXImageTransform.Microsoft.AlphaImageLoader(src='" + src + "')");
                $(this).attr("src", imgurl + "blank.gif");
            }

        });
     }


    $("#navigation-bar ul li").hover(function(){
        $(this).addClass("hover");
    }, function(){
        $(this).removeClass("hover");
    });





   var searchEngineStatus = $.cookie("searchengine");
   if(searchEngineStatus=='visible'){
       $('#searchengine').css("height", "260px");
   }




	var subHeaderLeft = ($("#navigation-bar li.active").offset().left) - ($("#wrapper").offset().left);
    $("#subheader ul").css("marginLeft", subHeaderLeft);

    $("#subheader ul li:last-child").css("background", "none");


    $(window).load(function(){
        $("#loadingOverlay").hide();
        flex();
    });

    $(window).resize(function(){
        flex();
        if(is_ie){
          $("#c31").hide();/*p od ie6 sidebara iz4ezva ina4e */
          $("#c31").show();
        }

    });


    $("#navigation-bar a").click(function(){
        //$("#loadingOverlay").fadeIn();
    });

    if(is_ie){
     $("input[type='text']").addClass("type-text");
     $("input[type='password']").addClass("type-password");
    }



    $("#content_url").focus(function(){
       $(this).addClass("content_url_focus");
       $(".penico").hide();
       $("#content_url_demo_holder").css("marginTop", "6px");

    })
    $("#content_url").blur(function(){

      $(this).removeClass("content_url_focus");

      $(".penico").show();

      $("#content_url_demo_holder").css("marginTop", "3px");




    });


    /*$("ul#gallery_module_sortable_pics li").live("hover", function(){
         $(this).find(".pic_link_holder").show();
    }, function(){
        $(this).find(".pic_link_holder").hide();

    })  */


    $(".penico").mousedown(function(){
          $("#content_url").focus();
    });


    $(".objbigSave, .objSave").click(function(){

          $(this).parents("form").submit();
    });





$("input.icolor").each(function(){
  var Icolor = $(this).val();
  if($(this).attr("checked")==true){
      if($("#colors_area").val()==""){
          $("#colors_area").val(Icolor);
          //alert(Icolor+ " -  " + $("#colors_area").val())
      }
      else{
         $("#colors_area").val($("#colors_area").val() + ", " + Icolor);
      }
  }
})


$("input.icolor").mouseup(function(){
  if($(this).attr("checked")==false){
      var Icolor = $(this).val();
      var Carea = $("#colors_area");
      var Carea_val = Carea.val();

      var search_color = Carea_val.search(Icolor);
      if(search_color==-1){
        if(Carea_val==""){
         $("#colors_area").val(Icolor)
        }
        else{$("#colors_area").val(Carea_val + ", " + Icolor) }

      }
  }
  else{
      var Icolor = $(this).val();
      var Carea = $("#colors_area");
      var Carea_val = Carea.val();

      var search_color = Carea_val.search(Icolor);
      if(search_color!=-1){
         if(Carea_val.indexOf(Icolor)==0){
          var new_val = Carea_val.replace(Icolor+", ","");
          $("#colors_area").val(new_val);
          var Carea_val = $("#colors_area").val();
          var new_val1 = Carea_val.replace(Icolor,"");
          $("#colors_area").val(new_val1);
         }
         else{
           var new_val_coma = Carea_val.replace(", "+Icolor,"");
           $("#colors_area").val(new_val_coma);
         }


      }
  }



});



$("#content_body_filename").focus(function(){
  $(this).addClass("content_body_filename_focus");
});
$("#content_body_filename").blur(function(){
  $(this).removeClass("content_body_filename_focus");
});


    $("form").submit(function(){
      if($(this).hasClass("save_disabled")){
        return false;
      }
    });

  $("table.checkbox tr:odd").addClass("even")


});

//setInterval('("iframe").remove()', 100);


function navico(){
  $("li.is_page").each(function(){
     $(this).find("a:first").attr("class", "is_page");
  });
  $("li.is_home").each(function(){
     $(this).find("a:first").attr("class", "is_home");
  });
  $("li.is_category").each(function(){
     $(this).find("a:first").attr("class", "is_category");
  });
  $("li.is_module").each(function(){
     $(this).find("a:first").attr("class", "is_module");
  });
}
setInterval('navico()', 100);












