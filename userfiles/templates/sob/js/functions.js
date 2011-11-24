isie6 = !window.XMLHttpRequest;
jQuery.fn.exists = function(){return jQuery(this).length>0;}
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
  },
  static: function(){
    return this.each(function(){this.style.position = 'static'});
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
  imgurl+'btnrighth.jpg',
  imgurl+'top_loginh.jpg',
  imgurl+'top_joinh.jpg',
  imgurl+'header_login_field.jpg',
  imgurl+'header_submit.jpg',
  imgurl+'sb_tl.png',
  imgurl+'sb_tm.png',
  imgurl+'sb_tr.png',
  imgurl+'sb_bl.png',
  imgurl+'sb_bm.png',
  imgurl+'sb_br.png',
  imgurl+'sb_l.png',
  imgurl+'sb_r.png'

);

function rand(max){
  return Math.ceil(Math.random() * max) - 1;
}


function moovePopDotTo(which, new_state){
       if(new_state==1){
         $(".pd-dot").eq(which).animate({"left":"9px"}, function(){
             $(".pd-ctrl-wrapper").eq(which).attr("state", new_state);
        });
       }
       if(new_state==2){
         $(".pd-dot").eq(which).animate({"left":"23px"}, function(){
             $(".pd-ctrl-wrapper").eq(which).attr("state", new_state);
        });
       }
       if(new_state==3){
         $(".pd-dot").eq(which).animate({"left":"36px"}, function(){
             $(".pd-ctrl-wrapper").eq(which).attr("state", new_state);
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

        var current_state = parseFloat($("#home-slide-holder").attr("state"));
		var current_index = parseFloat($("#home-slide-holder").attr("index"));
        if(current_state==2){
          $("#home-slider-right").hide();
		  $("#home-slider-left").show();
        }
        $(".home-slide-animator").eq(current_index-1).animate({"left":(-current_state)*542}, function(){
            $("#home-slider").removeClass("activated");
            $("#home-slide-holder").attr("state", current_state+1);
        });

    }
}


function SlideLeft(){
    if($("#home-slider").hasClass("activated")){
       /* */
    }
    else{
        $("#home-slider").addClass("activated");
        $("#home-slider-left").show();

        var current_state = parseFloat($("#home-slide-holder").attr("state"));
		var current_index = parseFloat($("#home-slide-holder").attr("index"));
		var curr_pos = parseFloat($(".home-slide-animator").eq(current_index-1).css("left"));
        if(current_state==2){
          $("#home-slider-left").hide();
		  
        }
		else if(current_state==3){
			$("#home-slider-right").show();
		}
		
        $(".home-slide-animator").eq(current_index-1).animate({"left":curr_pos+542}, function(){
            $("#home-slider").removeClass("activated");
            $("#home-slide-holder").attr("state", current_state-1);
        });
    }
}



function popSlideLeft(which, currState, newState){
    if(currState>=1){
        moovePopDotTo(which, newState);
        $(".popular-slider").eq(which).animate({"left":-(newState-1)*653}, function(){
              $(".pd-ctrl-wrapper").eq(which).attr("state", newState);
        });
    }
}
function popSlideRight(which, currState, newState){
    if(currState<=2){
        moovePopDotTo(which, newState);
        $(".popular-slider").eq(which).animate({"left":(-newState+1)*653}, function(){
              $(".pd-ctrl-wrapper").eq(which).attr("state", newState);
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

function richImageWidthScale(){
	$(".richtext img").each(function(){
		var rwidth = $(this).width();
		if(rwidth>570){
			$(this).removeAttr("width");
			$(this).removeAttr("height");
			$(this).css({"width":"570px", "height":"auto"});
		}
	});		
}


function autoScrollFA(){
	if($("#home-featured-articles-wrapper").hasClass("hover")){}
	else{
		var length = $("#fa-ctrl li").length;
		var activeIndex;
		$("#fa-ctrl li").each(function(i){
			if ($(this).hasClass("active")){
				activeIndex = i;
				if((activeIndex+1)==length){
					$("#fa-ctrl li:first").click();
				}
				else{
					$("#fa-ctrl li").eq(activeIndex+1).click();
				}
				return false;
			}
		});
	}
}





setInterval("autoScrollFA()", 5000);

function scaleSidebarHeight(){
  $(".sidebar").css('height', 'auto');
	var sidebarHeight = $(".sidebar").outerHeight();
	var mainHeivht = ($(".main").length==1)?($(".main").outerHeight()):0;
	var mainInnerHeivht = ($(".main-inner").length==1)?($(".main-inner").outerHeight()):0;
    var contentHeight = mainHeivht+mainInnerHeivht;

	if($(".sidebar").length>0){
		$(".sidebar").css("borderLeft","1px dotted #D6D6D6");
		if(sidebarHeight<contentHeight){
			$(".sidebar").height(contentHeight);
		}
        else{
            if(mw.browser.msie()){
              if($.browser.version<9){
                $("#content").height($("#content").height()+10);
              }
            }
        }
	}
}

function equal(){
  var max = 0;
  $('.equal').each(function(){
    if($(this).height()>max){max=$(this).height()}
  });
  $('.equal').height(max);
}


function msieBorderRadiusImage(elem, radius, strokeColor, strokeWidth, imgURL){
  $(elem).each(function(){
      var radius = Math.min(parseInt(radius) / Math.min(this.offsetWidth, this.offsetHeight), 1);
      var html = $(this).html();
      var width = $(this).outerWidth();
      var height = $(this).outerHeight();
      //var rect = '<v:roundrect strokeColor="' + strokeColor' + " arcsize="' + radius + '" style="width:' + width + 'px;height:' + height + 'px; display:block"><v:fill origin="0,0" type="tile" src="' + imgurl + '" />' + html + '</v:roundrect>';
  });
}


$(document).ready(function(){






	$("img[src*='learn_from_the_best.jpg']").hide();
	scaleSidebarHeight();
						   
    $("a[href='#']").attr("href", "javascript:void(0)");
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


    $(".child-nav li").has("ul").addClass("child-has-ul");
    $(".child-nav li").has("ul").parent().addClass("more-width");


	richImageWidthScale();

    var firsth_rich_img_width = $(".richtext img:first").width();
    if(firsth_rich_img_width>525){
         $(".richtext img:first").width('525px');
    }

$("#home-featured-articles-wrapper").hover(function(){
	$(this).addClass("hover");

}, function(){
	$(this).removeClass("hover");
});



$(".users-search-nav li").hover(function(){
	$(this).addClass("hover");

}, function(){
	$(this).removeClass("hover");
});


$(".paging-content li").each(function(){
 if($(this).find("a").hasClass("active")){
    var index = $(".paging-content li").index(this);
    if(index>2){
      $(".paging-content li").eq(index-1).show();
      $(".paging-content li").eq(index-2).show();
      $(".paging-content li").eq(index).show();
      $(".paging-content li").eq(index+1).show();
      $(".paging-content li").eq(index+2).show();
    }
    else{
      $(".paging-content li:lt(5)").show();
    }
 }
});
$(".paging-content li:last-child a").each(function(){
    if($(this).hasClass("active")){
      $(this).parents(".paging").find(".raquo").hide();
      $(this).parents(".paging").find(".raquo2").hide();
    }
});
$(".paging-content li:first-child a").each(function(){
    if($(this).hasClass("active")){
      $(this).parents(".paging").find(".laquo").hide();
      $(this).parents(".paging").find(".laquo2").hide();
    }
});


var paging_active_url

$(".raquo").click(function(){
    window.location.href = $(this).parents(".paging").find("li").has(".active").next().find("a").attr("href");
    return false;
});
$(".raquo2").click(function(){
    window.location.href = $(this).parents(".paging").find(".paging-content a:last").attr("href");
    return false;
});
$(".laquo").click(function(){
    var prev_url;
    $(this).parent().next().find("li").each(function(){
        if($(this).find("a").hasClass("active")){
            prev_url = $(this).prev().find("a").attr("href");
        }
    });
     window.location.href = prev_url;
    /* prev does not work like like next - bug in jquery */
    return false;
});

$(".laquo2").click(function(){
    window.location.href = $(this).parents(".paging").find(".paging-content a:first").attr("href");
    return false;
});





$(".paging li").hover(function(){
	$(this).addClass("hover");

}, function(){
	$(this).removeClass("hover");
});


var preloadSelector = "a,span";
var preloadEvends = "mouseup";

$(preloadSelector).bind(preloadEvends, function(){
    preload(this);
});


 $("#preloader").ajaxStop(function(){
   $(this).hide();
 });



$("#navigation-bar li.parent").hover(function(){
  var dis = $(this);
  dis.addClass('parent-hover');

setTimeout(function(){
      if(dis.hasClass("parent-hover")){
          if(dis.find('.child-nav').length){
            $(".sub-nav-block").hide();
            dis.find(".sub-nav-block").fadeIn('fast');
          }
      }

}, 200);




}, function(){
   $(this).find(".sub-nav-block").fadeOut('fast');
   $(this).removeClass('parent-hover');
});


/*$("#header #navigation-bar em, #header #navigation-bar strong").click(function(){

    var item = $(this).parents("li").find("ul.child-nav:first");

    item.css('height', 'auto');

    var top = $(this).offset().top+$(this).height();
    var height = item.outerHeight();
    var window_height = $(window).height();
    var scroll_top = $(window).scrollTop();

    if(height>(window_height+scroll_top-top)){
        item.css({
          "overflowX":"hidden",
          "overflowY":"auto",
          "height":window_height+scroll_top-top-30
        })
    }
    else{
       item.css({
          "overflowX":"hidden",
          "overflowY":"hidden",
          "height":"auto"
        });
    }

}); */







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
          $("#home-slide-vertical-holder").css({"top":(-NewIndex+1)*145});
          $("#home-slider").removeClass("activated");
          $("#home-slide-holder").attr("index", NewIndex);
          $(".home-slide-animator").css("left", "0px");

       }
    }
    return false;
});


        /* hide slider*/
        $("#home-slider-controlls, #home-slider-left, #home-slider-right").mouseover(function(){
           $("#slidertip").hidden();
        });




    /* /slider*/


$("#at20mc").live("mouseover", function(){
	$("#home-featured-articles-wrapper").addClass("hover");
});

$("#at20mc").live("mouseout", function(){
	$("#home-featured-articles-wrapper").removeClass("hover");
});

$("#at15s").live("mouseover", function(){
	$("#home-featured-articles-wrapper").addClass("hover");
});

$("#at15s").live("mouseout", function(){
	$("#home-featured-articles-wrapper").removeClass("hover");
});



$("#top-login").click(function(){
	$("#login-or-register").hide();
	$("#header-login").show();
    msRoundedField()
});


    /* POP SLider  */

    $(".pd-ctrl-wrapper").attr("state", "1");
	$(".popular-discussions").each(function(i){
		$(this).attr("eq", i);
	});

    $(".pd-ctrl").click(function(){
	    var parent = $(this).parents(".popular-discussions:first");	
		var eq = parent.attr("eq");
       if($(this).hasClass("active")){

       }
       else{
         if(parent.find('.popular-slider').not(":animated").length>0){
               parent.find(".pd-ctrl").removeClass("active");
               $(this).addClass("active");
               var state = parseFloat(parent.find(".pd-ctrl-wrapper").attr("state"));
               var new_state = parseFloat($(this).text());
               if(new_state>state){
                  popSlideRight(eq, state, new_state);
               }
               else{
                  popSlideLeft(eq, state, new_state);
               }
         }

       }
    });

    $(".pd-right").click(function(){
	  var parent = $(this).parents(".popular-discussions:first");
	  var eq = parent.attr("eq");
      var curr_state = parseFloat(parent.find(".pd-ctrl-wrapper").attr("state"));
      if(curr_state<=2 && parent.find('.popular-slider').not(":animated").length>0){
        parent.find(".pd-ctrl").removeClass("active");
        parent.find(".pd-ctrl").eq(curr_state).addClass('active');
        popSlideRight(eq, curr_state, curr_state+1);
      }


    });
    $(".pd-left").click(function(){
		var parent = $(this).parents(".popular-discussions:first");	
		var eq = parent.attr("eq");
        var curr_state = parseFloat(parent.find(".pd-ctrl-wrapper").attr("state"));
        if(curr_state>1 && parent.find('.popular-slider').not(":animated").length>0){
          parent.find(".pd-ctrl").removeClass("active");
          parent.find(".pd-ctrl").eq(curr_state-2).addClass('active');
          popSlideLeft(eq, curr_state, curr_state-1);
        }
    });


    /* /POP SLider  */


   $(".btn").each(function(){

        var btn_html = $(this).html();
        $(this).html("<span class='btn-right'><samp class='btn-mid'>" + btn_html + "</samp></span>");

   });

   $("a[href*='http://feeds.mashable.com']").remove();




  $(".mw-tabs:first").show();
  $(".learn-nav a:first").addClass("active");
  $(".mw-tab-text:first").show();


  $(".learn-nav a").click(function(){
    $(".learn-nav a").removeClass("active");
    $(this).addClass("active");
    $(".mw-tabs").hide();
    $($(this).attr('href') + '-tab').show();
    $(".mw-tab-text").hide();
    $($(this).attr('href') + '-text').show();
  });






   $(".tabs-holder").tabs("html");

   $(".tabs-nav li:first-child").addClass("first");
   $(".tabs-nav li:first-child").addClass("active-first");
   $(".tabs-nav li:first-child").addClass("active");
   $(".tabs-nav li:last-child").addClass("last");





  var anchor = window.location.hash.replace("#", "");
  $(".tabs-nav a").click(function(){

      if($(this).parent().hasClass("active")){/**/}
      else{
         var tab_url = $(this).attr("href")+'-tab';
         $(this).parents(".tabs-nav").find("li").removeClass("active");
         $(this).parents(".tabs-nav").find("li").removeClass("active-first");
         $(this).parents(".tabs-nav").find("li").removeClass("active-last");
         $(this).parent().addClass("active");
         if($(this).parent().hasClass("first")){$(this).parent().addClass("active-first")}
         if($(this).parent().hasClass("last")){$(this).parent().addClass("active-last")}
         $(this).parents(".tabs-holder").find(".tab").hide();
         $(tab_url).show();
      }//return false;
  });

  $("a[href='#"+anchor+"']").click();
  $(window).bind("hashchange", function(){
      var hash = window.location.hash;
      if($("a[href='" + hash + "']").length>0 && $(hash + "-tab").length>0){
         $("a[href='" + hash + "']").click();

         //scrollto('#Learning-Center-Tabs');
      }
  });






$("#fa-ctrl li").click(function(){
    if($(this).attr("class")!="active"){
        $("#fa-ctrl li").removeClass("active");
        $(this).addClass("active");
        var step = parseFloat($(this).text())-1;
        $("#featured-articles-slider").stop();
        $("#featured-articles-slider").animate({"left":-step*600}, 'slow');
    }
});

$(".blue-tabs-nav a.btn").click(function(){
    $(this).parents("ul").find("a").removeClass("btnactive");
    $(this).addClass("btnactive");

    var href = $(this).attr("href");
    $(this).parents(".tabs-holder").find(".tab").hide();
    $(href+"-tab").show();

    //return false;

});


 

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
	 

	 $("#navigation-bar ul a.parent-link").each(function(){
			var aOwidth = $(this).outerWidth();
			if(aOwidth%2!=0){
				$(this).css("paddingLeft", "20px");		
			}
	 });
	 

   }

   $(".cross-tabs .tab li:odd").addClass("border-left");
   $(".cross-tabs .tab li").hover(function(){
     $(this).css("background", "#EDF1F3");
   }, function(){
     $(this).css("background", "transparent");
   });


  $(".circle").each(function(){
       $(this).find("li").each(function(i){
         $(this).addClass("circle-"+(i+1));
       });
  });

  //$(".circle li").draggable();
  
  
  
 
$(".recent-drop").each(function(){
    var DropActiveHTML = $(this).find("a.active:last").text();
    var DropActiveValue = $(this).find("a.active:last").text();
    $(this).find("span").html(DropActiveHTML);
});
$(".recent-drop").addClass("OBJDropDown");
$(".recent-drop").click(function(){
   var ul = $(this).find(".recent-drop-list-holder");
   $(this).toggleClass("isDown");
   var state = ul.css("height");
   if (state=='0px'){
     //ul.slideDown('fast');

	 $(this).find(".recent-drop-list-holder").css({"border":"solid 1px #e9e9e9", "borderTop":"none"});
	 $(this).find(".recent-drop-list-holder").css({"height":"200px"})
	 $(this).find(".recent-drop-list-holder").addClass("recent-drop-active")

   }
   else{
     //ul.slideUp('fast');
	 $(this).find(".recent-drop-list-holder").css({"height":"0px"});
     $(this).find(".recent-drop-list-holder").removeClass("recent-drop-active");
     $(this).find(".recent-drop-list-holder").css("border", "none");
   }
});
$(".recent-drop").hover(function(){
    $(this).removeClass("OBJDropDown")
}, function(){
    $(this).addClass("OBJDropDown")
});

$(".recent-drop ul li").hover(function(){$(this).addClass("hover")}, function(){$(this).removeClass("hover")});
$(".recent-drop ul li a").click(function(){
    var DropItemHTML = $(this).html();
    var DropItemValue = $(this).text();
    $(this).parents(".recent-drop").find("span").html(DropItemHTML);

});


$("body").click(function(){
	$(".OBJDropDown").removeClass("isDown");
    $(".OBJDropDown .recent-drop-list-holder").css({
		"height":"0px",
		"border":"none"
	});

    if($(".PublishDropDownHover").length==0){
      $("#publish-dropdown").remove()
    }
   
});



  
 equal();


$(window).load(function(){
 equal();


 var firsth_rich_img_width = $(".richtext img:first").width();
    if(firsth_rich_img_width>525){
         $(".richtext img:first").width('525px');
    }


	$(".submit-error-info").slideDown('slow')
	richImageWidthScale();
	scaleSidebarHeight();

});

   $(".submit").click(function(){
     $(this).parents("form").submit();
     return false;
   });
   $(".submitenter").click(function(){
     $(this).parents("form").find("input[type=submit]").click();
     return false;
   });
   
  $(".status:last").css("borderBottom", "1px dotted #C2C2C2"); 
   
  $(".add-comment-anchor").click(function(){
    $(this).parents(".users-status").find(".status-comment").show();
  });
   
  $(".add-comment-anchor, .add-comment textarea").mousedown(function(){
     //$(this).parents(".users-status").find(".status-comment").show();
     $(this).parents(".users-status").find(".add-comment textarea").height("50px");
     $(this).parents(".users-status").find(".add-comment .btn").show();
	 $(this).parents(".users-status").find(".commentForm").fadeIn();
	 
	 $(this).parents(".users-status").find(".commentFormSuccess").fadeOut();
	 

	 
	 
  });







  $(".cmm").click(function(){
     $(this).parents(".users-status").find(".status-comment").not(".add-comment").show();
   });
  $(".add-comment textarea").val("Write a comment...");
  $(".add-comment textarea").focus(function(){
    if($(this).val()=="Write a comment..."){
      $(this).val("")
    }
    $(this).parents(".users-status").find(".add-comment textarea").css({
      "color":"#000",
      "height":"50px"
    });
    $(this).parents(".users-status").find(".add-comment .btn").show();
  });
  $(".add-comment textarea").blur(function(){
    if($(this).val()==""){
      $(this).val("Write a comment...");
          $(this).parents(".users-status").find(".add-comment textarea").css({
            "color":"#B1B6B8",
            "height":"16px"
          });
          $(this).parents(".users-status").find(".add-comment .btn").hide();
    }

  });





  $(".user-posted-status").each(function(){
    var text = $(this).text();
    if(text.length>320){
      var less_text = text.slice(0,175);
      $(this).wrapInner("<span class='user-posted-status-more-text'></span>");
      $(this).append("<span class='user-posted-status-less-text'>"+less_text+" ... </span>");
    }
    else{
      $(this).parent().find(".more:first").remove();
    }
  });
  $(".user-posted-status").show();

  $(".users-status-content .more").click(function(){
    if($(this).hasClass("active")){
      $(this).removeClass("active");
      $(this).html("See More");
      $(this).parents(".users-status-content").find(".user-posted-status-more-text").hide();
      $(this).parents(".users-status-content").find(".user-posted-status-less-text").show();
    }
    else{
      $(this).addClass("active");
      $(this).html("See Less");
      $(this).parents(".users-status-content").find(".user-posted-status-more-text").show();
      $(this).parents(".users-status-content").find(".user-posted-status-less-text").hide();
    }
  });

  $(".edit-image").hover(function(){
     $(this).find("div").css("visibility", "visible");
  }, function(){
     $(this).find("div").css("visibility", "hidden");
  });


  $(".dashboard-update-status input.type-text").focus(function(e){
    $(this).addClass('focus');
    this.select();
    $(".update-status-btn").show();
    e.preventDefault();
  });
  $(".update-status-btn").click(function(){
      var update_var = $("#update-status .type-text").val();
      update_var = update_var.replace("<script", "&lt;script");
      update_var = update_var.replace("</script>", "&lt;/script&gt;");
      $("#update-status .type-text").val(update_var);
      $(".dashboard-update-status input.type-text").removeClass("focus");
      $(".update-status-btn").hide();
  });

  $(".select_redirect").change(function(){
    window.location.href = $(this).val();
  });


  $(".users-status:last, .notification:last").css({
    "borderBottom":"1px dotted #C2C2C2"
  });

  $(".blinklist li:odd").css("marginRight", 0);

  $(".contact-list").each(function(){
    $(this).find("li").each(function(i){
        if(i%2!=0){
          $(this).css("marginRight", 0);
        }
    });
  });

  $(".title-tip").each(function(){
      var title = $(this).attr("title");
      $(this).attr("tip", title);
      $(this).removeAttr("title");
  });
  $(".title-tip").hover(function(){
  		var thisX = $(this).offset().left;
  		var thisY = $(this).offset().top;
          var tip = $(this).attr("tip");
          var window_width = $(window).width();
          $("#title-tip-content").html(tip);
          var tip_width = $("#title-tip").outerWidth();
          if((thisX + tip_width)>=window_width){
            $("#title-tip").attr("class", "title-tip-left");
            $("#title-tip").css({
              "left":thisX-tip_width+20,
              "visibility":"visible"
            });
          }
          else{
            $("#title-tip").attr("class", "title-tip-right");
            $("#title-tip").css({
              "left":thisX,
              "visibility":"visible"
            });
          }
          if($(this).offset().top<30){
            $("#title-tip").addClass("title-tip-top");
            $("#title-tip").css({
              "top":thisY+25
            });
          }
          else{
            //$("#title-tip").attr("class", "title-tip-top");
            $("#title-tip").css({
              "top":thisY-25
            });
          }
     }, function(){
         $("#title-tip").css("visibility", "hidden");
         $("#title-tip").css("left", "0");
  });

  $(".Help").hover(function(){
    $(this).addClass('helpHover');
    var dis = $(this);
    var html = $(this).find("span").html();
    var top = $(this).offset().top;
    var left = $(this).offset().left;
    var width = $(this).outerWidth();
    $("#Helper").html(html);
    var height = $("#Helper").outerHeight();
      setTimeout(function(){
        if(dis.hasClass('helpHover')){
          $("#Helper").css({
            left:left + width +5,
            top:top - height/3,
            visibility:'visible'
          });
        }

      }, 250);


  }, function(){
     $(this).removeClass('helpHover');
      $("#Helper").css({visibility:'hidden'});
  });


      $(".notification, .box-ico-holder").hover(function(){
        $(this).find(".box-ico").each(function(){
            $(this).addClass("box-ico-hover");
            var oldPos = $(this).css("backgroundPosition");
            $(this).data("oldPos",oldPos);
            if($.browser.mozilla){

              var newPos = oldPos.replace("100%", "0");
              $(this).css("backgroundPosition", newPos);

            }
            else{
              $(this).css("backgroundPositionY", "0");
            }
        });

        $(this).find(".delete_notification").addClass("delete_notification_hover");

      }, function(){
        $(this).find(".box-ico").each(function(){
            $(this).removeClass("box-ico-hover");
            if($.browser.mozilla){$(this).css("backgroundPosition", $(this).data("oldPos"))}
            else{
              $(this).css("backgroundPositionY", "100%");
            }
          });
          $(this).find(".delete_notification").removeClass("delete_notification_hover");
      });




     $("#comment_body").focus(function(){
       $(this).height("90px");
     });
     $("#comment_body").blur(function(){
       if($(this).val()==""){
          $(this).height("33px");
       }
     });


    $(".profile-blue-tabs .post:last-child").css("border", "none");


  $("a.share-favourites").css('left')

  $(".share-more").click(function(){
     var item = $(this);
    if($(this).text()=='More'){
       item.prev().not(':animated').animate({'width':'65px'}, 'fast', function(){
            item.text('Less');
       });
    }
    else{
       item.prev().not(':animated').animate({'width':'0px'}, 'fast', function(){
            item.text('More');
       });
    }



      if($(this).parents(".share").outerWidth()<160){
         $(this).parents(".share").animate({"width":"170px"});
      }
      else{
        $(this).parents(".share").animate({"width":"105px"});
      }



  });


      $(".toggle-fragment").click(function(){
        var dis = $(this);
        var href = dis.attr('href');
        if(dis.hasClass('toggle-fragment-active')){
            dis.parent().find('.fragment-content').not(':animated').slideUp('fast', function(){
              $(this).parent().find('.toggle-fragment-active').removeClass('toggle-fragment-active');
            });
        }
        else{
          $(href+'-tab').not(':animated').slideDown('fast', function(){
            $('.toggle-fragment-active').removeClass('toggle-fragment-active');
             $(this).parent().find('.toggle-fragment').addClass('toggle-fragment-active');
          });
          /*$('.toggle-fragment').parent().find('.fragment-content').not(':animated').slideUp('fast', function(){


            //$('html,body').animate({scrollTop: dis.offset().top});
          }); */

        }
      });



  msRoundedField();

  $(window).ajaxComplete(function(){
       msRoundedField();
  });



  $("#header-login").hide();
  $("#header-login").visible();
  $("#header-login").static();


  var loc = window.location.href;

  if(loc.indexOf('192.') !=-1 || loc.indexOf('ooyes.net') !=-1){
    if(loc.indexOf('debug:')==-1){
        var debug = document.createElement('a');
        debug.href = loc + '/debug:1';
        debug.innerHTML = 'Debug';
        debug.id='debug';
        document.body.appendChild(debug);
    }

    //$("#side-add-areaobject, #side-add-area embed").remove()

  }



$(window).load(function(){
    var regchk = $.cookie("registerCheck");
    if(regchk=='true'){
        $('#ctandc').check();
    }
});


$("#ctandc").bind('click change', function(){
    if($(this).attr('checked')){
      $.cookie("registerCheck", 'true');
    }
    else{
      $.cookie("registerCheck", 'false');
    }
});

var chapters_length = $(".trainings-inner-list li.chapter-item").length;
var single_chapter_width = $("#first-chapter").outerWidth(true);


$(".trainings-inner-list").width(single_chapter_width*chapters_length);


$(".chapter-contents a:first").addClass("active");

$(".chapter-contents a").mouseup(function(){
    var index = $(".chapter-contents a").index(this);
    $(".trainings-inner-list").css({"left":(-index*single_chapter_width)});
    $(".chapter-contents a").removeClass("active");
    $(this).addClass("active");
    $(".next-chapter").show();
    $(".prev-chapter").show();
});
$(".chapter-contents a:first").click(function(){
     $(".prev-chapter").hide();
});
$(".chapter-contents a:last").click(function(){
     $(".next-chapter").hide();
});

$("embed").each(function(){
  if(this.wmode!='transparent'){
    $(this).attr('wmode', 'transparent');
  }
});

$("object").each(function(){

    if($(this).find("param[value='transparent']").length==0){
      var wmode = document.createElement('param');
      wmode.name='wmode';
      wmode.value='transparent';
      $(this).append(wmode);
    }



});

$(".chapter-contents a:first").addClass("active");


var chapter_max = -(single_chapter_width*chapters_length) + single_chapter_width;

$(".next-chapter").click(function(){

  var curr = parseFloat($(".trainings-inner-list").css('left'));
  if(curr>chapter_max){
    $(".trainings-inner-list").css({"left":(curr-single_chapter_width)});
    $(".prev-chapter").show();
    var active = $(".chapter-contents a.active");
    $(".chapter-contents a").removeClass("active");
    active.parent().next().find("a").addClass("active");

  }
  if(curr<(chapter_max+(2*single_chapter_width))){
        $(this).hide();
  }



});
$(".prev-chapter").click(function(){
  var curr = parseFloat($(".trainings-inner-list").css('left'));
  if(curr<0){
    $(".trainings-inner-list").css({"left":(curr+single_chapter_width)});
    $(".next-chapter").show();
    var active = $(".chapter-contents a.active");
    $(".chapter-contents a").removeClass("active");

    active.parent().prev().find("a").addClass("active");

  }
  if(curr>(0-(2*single_chapter_width))){
        $(this).hide();
  }
});



$(".article-gallery").each(function(){
  if($(this).find("a").length>1) {
     $(this).find("a").modal("gallery");

  }
  else{
    $(this).find("a").modal("single");
  }
});

var cat_active_length = $(".category-nav a.active").length;

if(cat_active_length>1){
     $(".category-nav a.active:last").addClass("IsActive");
     $(".category-nav a.active").removeClass("active");
     $(".category-nav a.IsActive").addClass("active");
}

$("div.master-help").hide();



$("#user-top-nav .Publish").click(function(){
  $("#publish-dropdown").remove();
  $(this).css("background", "#F5F6F8");

    var ul = document.createElement('ul');
    ul.id = 'publish-dropdown-top';
        $(ul).hover(function(){}, function(){
      $(this).remove();
      $(".Publish").removeAttr("style");
    });
    ul.innerHTML = publish_dropdown;
    var top = $(this).offset().top;
    var left = $(this).offset().left;
    var width = $(this).outerWidth();
    var height = $(this).outerHeight();
    ul.style.top = (top+height-2) + 'px';
    ul.style.left = (left-(88 - width/2)) + 'px';
    document.body.appendChild(ul);
  return false;
});



var sidePublisher = document.createElement('ul');
sidePublisher.innerHTML = publish_dropdown;
sidePublisher.id = 'publish-side';

$(".ds_addpost").append(sidePublisher);

$(".ds_addpost a:first").click(function(){

$("#publish-side").slideToggle();




  return false;
});

$(".Publish, .ds_addpost a").hover(function(){

}, function(){
  $(this).removeClass("PublishDropDownHover")
});



$("#quick_search_selector input").click(function(){
  $(this).parent().find("input").uncheck();
  $(this).check();
  var val = $(this).attr("name");
  $(this).parents("#quick_search_box").find(".quickForm").hide();
  $(this).parents("#quick_search_box").find("#"+val).show();
});

$("#quick_search_selector label").click(function(){
  $(this).parent().find("input").uncheck();
  $(this).prev().check();
  var val = $(this).prev().attr("name");
  $(this).parents("#quick_search_box").find(".quickForm").hide();
  $(this).parents("#quick_search_box").find("#"+val).show();

});


$("a.master-help").click(function(){
  var href = $(this).attr("href");
  var helper = $(href);
  var display = helper.css('display');
  var helpid = href.replace("#", "");

   mw.box.element({
      element:href,
      width:600,
      height:'auto',
      id:helpid
   });


 /* if(display=='none'){
        helper.fadeIn();
  }
  else{
     helper.fadeOut();
  } */


  return false;
});


 /*
$(".eimg").each(function(){
  var dis = $(this);
  var width = $(this).width();
  var height = $(this).height();
  var bg = this.getElementsByTagName('span')[0].style.backgroundImage;
  bg = bg.replace('url(', '');
  bg = bg.replace('url (', '');
  bg = bg.replace(')', '');
  bg = bg.replace('"', '');
  bg = bg.replace('"', '');

  var img = new Image();
  img.onload  = function(){

    var iwidth = $(this).outerWidth();
    var iheight = $(this).outerHeight();

    dis.find('span').append("<i style='background:white;font-size:11px;display:block;color:white;background:black'>Elem W: " + width  + "<br />Img W: " + iwidth + "<br />Img H: " + iheight +"</i>")

  }
  img.src = bg;
  img.style.position = 'absolute';
  img.style.left = '-99999px';
  img.style.top = '-99999px';
  document.body.appendChild(img)




});
*/


$(".order, .product-more").each(function(){
  var html = $(this).html();
  $(this).html("<span>" + html +"</span>");
});

mwtree.init(".category-nav:first", 2);
$(".popular-slider-holder div.post").multiWrap(5, '<div class="post-slide"></div>');


var current_content_type =  $("#postSet").val();
$("#addnav a").each(function(){
  if($(this).attr("rel")==current_content_type){
    $(this).addClass("active");
  }
});


$("#addnav a").mouseup(function(){
    $("#addnav a").removeClass("active");
    var rel = $(this).attr("rel");
    $("#postSet").val(rel);
    $(this).addClass('active');
    //$("#debug").html($("#postSet").val());
    return false;
});


});// end document ready;


function addMoreSites(){
  var length = $(".MyWebSite").length;
  if(length<5){
      var site = $(".MyWebSite:first").clone(true);
      $(site).addClass('MyWebSite-'+(length+1));
      $(site).find('label').html('Website '+ (length+1)+ ': ');
      $("#more-websites").append(site);
      $(".MyWebSite:last input").attr('name', 'custom_field_website_'+(length+1));
      $(".MyWebSite:last #moresites").remove();
      //msRoundedField();
  }

}



function msRoundedField(){

  if(mw.browser.msie()){

    $(".linput, .larea").each(function(){
      if($(this).hasClass('isRounded')){
          /**/
      }
      else{

        var top_elem = document.createElement('div');
        var bottom_elem = document.createElement('div');

        top_elem.style.width = $(this).outerWidth() + 'px';
        bottom_elem.style.width = $(this).outerWidth() + 'px';

        top_elem.className = 'lTop';
        bottom_elem.className = 'lBot';

        top_elem.innerHTML = '<div class="lTopLeft">&nbsp;</div><div class="lTopRight">&nbsp;</div><div class="lTopMid">&nbsp;</div>';
        bottom_elem.innerHTML = '<div class="lBotLeft">&nbsp;</div><div class="lBotRight">&nbsp;</div><div class="lBotMid">&nbsp;</div>';

        var ileft = document.createElement('div');
        ileft.className = 'input_left';
        //$(ileft).css('height', $(this).outerHeight() - 14 + 'px');

        var iright = document.createElement('div');
        iright.className = 'input_right';
        //$(iright).css('height', $(this).outerHeight() - 14 + 'px');


        $(this).append(top_elem);
        $(this).append(bottom_elem);
        $(this).append(ileft);
        $(this).append(iright);

        $(this).addClass('isRounded');


      }
    });


  }

}





function bookmark(url, title, elem) {
	if (window.sidebar) { // Mozilla Firefox Bookmark
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
        $(elem).attr('href', 'javascript:void(0)') ;
        mw.box.alert('Please use CTRL + D to bookmark this address.');
    }

}










function redirect(url){window.location.href=url}


function scrollto(elem){
  if($(elem).offset()){
     var scrollTo = $(elem).offset().top;
     $("html, body").animate({scrollTop: scrollTo}, 700);
     return false;
  }

}



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

preload = function (elem){
  var elem = $(elem);
  var elem_top = elem.offset().top;
  var elem_left = elem.offset().left;
  var elem_width = elem.outerWidth();
  $(document).ajaxStart(function(){
      $("#preloader").css({
        top:elem_top,
        left:elem_left+elem_width + 2,
        display:'block'
      });
  });

}





