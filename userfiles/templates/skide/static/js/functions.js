









$(document).ready(function(){

var oldbrowser = "<div style='border:1px solid #F7941D; background:#FEEFDA; text-align:center; clear:both; height:75px; position:absolute;top:0;left:0' id='oldb'> <div style='position:absolute; right:3px; top:3px; font-family:courier new; font-weight:bold;'><a href='#' onclick='javascript:this.parentNode.parentNode.style.display=\"none\"; return false;'><img src='http://www.ie6nomore.com/files/theme/ie6nomore-cornerx.jpg' style='border:none;' alt='Close this notice'/></a></div> <div style='width:640px; margin:0 auto; text-align:left; padding:0; overflow:hidden; color:black;'> <div style='width:75px; float:left;'><img src='http://www.ie6nomore.com/files/theme/ie6nomore-warning.jpg' alt='Warning!'/></div> <div style='width:275px; float:left; font-family:Arial, sans-serif;'> <div style='font-size:14px; font-weight:bold; margin-top:12px;'>You are using an outdated browser</div> <div style='font-size:12px; margin-top:6px; line-height:12px;'>For a better experience using this site, please upgrade to a modern web browser.</div> </div> <div style='width:75px; float:left;'><a href='http://www.firefox.com' target='_blank'><img src='http://www.ie6nomore.com/files/theme/ie6nomore-firefox.jpg' style='border:none;' alt='Get Firefox 3.5'/></a></div> <div style='width:75px; float:left;'><a href='http://www.browserforthebetter.com/download.html' target='_blank'><img src='http://www.ie6nomore.com/files/theme/ie6nomore-ie8.jpg' style='border:none;' alt='Get Internet Explorer 8'/></a></div> <div style='width:73px; float:left;'><a href='http://www.apple.com/safari/download/' target='_blank'><img src='http://www.ie6nomore.com/files/theme/ie6nomore-safari.jpg' style='border:none;' alt='Get Safari 4'/></a></div> <div style='float:left;'><a href='http://www.google.com/chrome' target='_blank'><img src='http://www.ie6nomore.com/files/theme/ie6nomore-chrome.jpg' style='border:none;' alt='Get Google Chrome'/></a></div> </div> </div>";

var h = $(document.getElementsByTagName('html'));
if(h.hasClass("ff3") || h.hasClass("ie6")){
    $("body").append(oldbrowser);
    $("#oldb").width()
}






mw.ready("a", function(){
     $("a[href='#']").attr("href", "javascript:void(0);");

});

mw.ready(".paging", function(){
  mw.paging.init();
});

mw.ready("#wall .post_item", function(){
    /*if($(this).find("textarea").length>0){
      $(this).find("img").hide();
    }*/
});

mw.ready("#wall .post_item .embed_img", function(){
    $(".embed_img").click(function(){
        var area = $(this).parent().find("textarea");
        if(area.length>0){
         val = area.val();
         area.replaceWith("<div class='c'></div><div class='post_emb'>" + val + "</div>");
        }

    });

});







    $("a").click(function(){
      if($(this).attr("disabled")=='true'){
        return false;
      }
      else{return true}
    });

    $("#kids_login").click(function(){
       /* modal.init({
          width:400,
          height:300,
          html:document.getElementById('header_login_form')
        });              */



       $("#header_login_form").show();


        modal.overlay();





    });


    $(".share_this").live("click", function(){
      var href = $(this).attr("href");
       modal.init({
          width:400,
          height:300,
          html:$(href)
        });
        $(".modal .xhidden").removeClass("xhidden");
        modal.overlay();
        return false;
    });


    fader.init("#home_randomizer", 4000);



    $("#user_image").hover(function(){
      $(this).find("a").css("display", "block");
    }, function(){
      $(this).find("a").css("display", "none");
    });


    $(".submit").live("click", function(){
       $(this).parents("form").submit();
        return false;
    });

    $(".comment_area textarea").live("focus", function(){
      if(this.value=='Write a comment...'){
        this.value='';
      }
      this.style.height="45px";
      this.style.color="#3B3B3B";
      this.style.width="355px";
      $(this).parents("form").find(".user_photo").show();
    });
    $(".comment_area textarea").live("blur", function(){
        if(this.value==''){
            this.value='Write a comment...';
            this.style.height="20px";
            this.style.color="#A9A9A9";
            this.style.width="415px";
            $(this).parents("form").find(".user_photo").hide();
        }
    });




    $(".top_games").each(function(){
     $(this).find(".game_item:last").css({marginRight:0})
    });

    $(".user_friends_list_horizontal li:last-child").css("marginRight", 0);

    $(".bluebox").prepend('<div class="bluebox_top"><span></span><samp></samp></div>');
    $(".bluebox").append('<div class="bluebox_bot"><span></span><samp></samp></div>');


fields()

    $("#select_friends").click(function(){
      var top = $(this).offset().top;
      var left = $(this).offset().left;
      var width = $(this).outerWidth();
      var height = $(this).outerHeight();
      $.post(template_url + "ajax/addFriends.popup.php", function(data){
          modal.init({
            customPosition:{
              left:left+width-340,
              top:top+height
            },
            html:data,
            width:340,
            height:'auto'
          });
      });

    });


    $(".post_list:first").css("borderTop", "1px solid #C3C3C3");
    $(".similar_articles li:nth-child(4n)").css("marginRight","0");
    $(".whatis_tabs .tabnav li:last-child").css("marginRight", 0);

    $(".blueboxcontent .inbox_msg:last-child").css("marginBottom", "0");

    $(".post_comment .reply_btn").click(function(){
      scrollto("#reply_form");
    });

$("a, input").live("mousedown", function(){
  var dis = $(this);
  if(dis.hasClass("inbox_msg")){
      $("#preloader").css({
           top:-9999,
           left:-9999
      });
  }
  else{
    var left = dis.offset().left;
    var top = dis.offset().top;
    var width = dis.outerWidth();
      $("#preloader").css({
           top:top,
           left:left+width+7
      });
  }

});

$("#preloader").ajaxStart(function(){
  $(this).show();
})
$("#preloader").ajaxStop(function(){
  $(this).hide();
  $(this).css("left", "-9999px");

  fields()
});
$(".send_a_message").click(function(){
    var href = $(this).attr("href");
    $.post(href, function(data){
          modal.init({
            html:data,
            width:420,
            height:350
          });
    });

    return false;
});

 /*
$(".video_list_item .img").click(function(){
    var href = $(this).attr("href");
    $.post(href, function(data){
        var html = $(data).find(".bluebox_content").html();
        var width = parseFloat($(data).find(".bluebox_content object").attr("width"));
        var height =parseFloat($(data).find(".bluebox_content object").attr("height"));
        modal.init({
            html:html,
            width:width + 60,
            height:height + 10
          });
    });

    return false;
});
*/

$(".add_new_toy").click(function(){
    var href = $(this).attr("href");
    $.post(href, function(data){
      var theform = $(data).find("#add_toy_content").html();
       modal.init({
            html:theform,
            width:400,
            height:'auto',
            customPosition:{
              left:'center',
              top:70
            }
       });
       modal.overlay();
    });
    return false;
});

$(".field").click(function(){
   $(this).find("input, textarea").focus();
});

$(document.body).mousemove(function(e){
  $("#tip").css({
     left:e.pageX+17,
     top:e.pageY+15
  });

});

mw.tip("#usernav a");



mw.ready("input[type='text'], textarea, input[type='password']", function(){
        var Default = $(this).attr("default");

        if(Default!=undefined && Default !=''){
          var curr_val = $(this).val();
          if(curr_val==''){
              $(this).val(Default);
          }
          $(this).blur(function(){
             var val = $(this).val();
             var Default = $(this).attr('default');
             if(val==''){
                $(this).val(Default);
              }
          });
          $(this).focus(function(){
              var val = $(this).val();
              var Default = $(this).attr('default');
              if(val==Default){
                $(this).val('');
              }
          });
        }
})



$(".gsearch").click(function(){
    var val = $(".sidebar_search .content_search").val();
    content_list(val);
});




    $.imgpreload(
      imgurl + 'modal_tl.png',
      imgurl + 'modal_tr.png',
      imgurl + 'modal_tm.png',
      imgurl + 'modal_bm.png',
      imgurl + 'modal_br.png',
      imgurl + 'modal_bl.png',
      imgurl + 'modal_lm.png',
      imgurl + 'modal_rm.png',
      imgurl + 'modalbg.jpg'
    )




    $("#wall").css("minHeight", $('#sidebar').height())


    $(".image_thumb").click(function(){
       var href = $(this).attr("href");
       $("#toy_main_image img").attr("src", href);
       return false;
    });

    $("#sidebar ul.games li:even").css("clear", "both");

    $(".whatis_tabs_act li, #wall .whatis_tabs li").click(function(){
       $(this).parent().find("li").removeClass("active");
       $(this).addClass("active");
    });







    $(window).load(function(){

        //$("#user_sidebar").height($("#content").height());

    if($(".expire").length>0 && $.cookie("account_cookie")!='stop'){
       $(".xpire").slideDown(1000);

       $(".xpclose").click(function(){
           $(".xpire").slideUp(1000);
           $.cookie("account_cookie", "stop", {expires:(1/24)});
       })
    }



    $.imgpreload(
      imgurl + 'curatin_right.jpg',
      imgurl + 'curatin_left.jpg'
    )


    });

    $(window).keyup(function(event){
      if(event.keyCode==27){
            if($(".modal").length>0 || $("#overlay").css("display")=='block'){
               modal.close();
            }
       }
    });
    $(window).resize(function(){
        modal.center();
    });

});

function popup(theURL, popwidth, popheight){
    if(popwidth==undefined){
      popwidth = 400;
    }
    if(popheight==undefined){
      popheight = 300;
    }
    var chat_left = ($(window).width()/2) - (popwidth/2);

	window.open(theURL, '', 'fullscreen=no, scrollbars=no, resizable=no, statusbar=no, toolbar=no, menubar=no, height=' + popheight + ', width=' + popwidth).moveTo(chat_left, 100);

}







