
function generate(html, number){
  for(var i=0;i<(number-1);i++){
    document.write(html);
  }
}

var windowLoaded;




$(document).ready(function(){


$("a[href='#']").attr("href", "javascript:void(0)");


$(".btn").each(function(){
  var html = $(this).html();
  $(this).html("<span>&nbsp;</span><strong>&nbsp;</strong><samp>" + html +"</samp>");
});

	$("ul#dyna li a").tooltip({
		tip: '#dynatip',
		offset: [11, 0],
		effect: 'slide',
		position: 'bottom center'
	});

$(".preloader").hide();
 $(".preloader").ajaxStart(function(){
       $(this).show();
    });
    $(".preloader").ajaxStop(function(){
     $(".preloader").hide();
    });

/*	$(window).load(function(){
		$(".preloader").hide();
	});

   */

    /*$(".nav a#t2").click(function(){
        if($("#choose-layout .layouts-list a.active").length==0){
          return false;
        }
    });

    $(".nav a#t3").click(function(){
        if($("#choose-layout .layouts-list a.active").length==0){
          return false;
        }
    });
    $(".nav a#t4").click(function(){
        if($("#choose-layout .layouts-list a.active").length==0){
          return false;
        }
    });  */

    $(".nav").hashtabs(".tab");

    /*tabs = $(".nav").tabs("#form-manager .tab", { history: true });
    tabs.onClick = function(){
       $(".layouts-list li a.active").click();
    } */

    $(".layouts-list").each(function(){
      var lilength = $(this).find('li').length;
      var breakIndex;
      if(lilength>3){
        breakIndex = Math.ceil(lilength/2);

          $(this).find('li').eq(breakIndex).before("<br />");

        $(this).find('li').eq(breakIndex-1).addClass('breakItem')

      }
    });



 $(".scroll-left").click(function(){
        var list = $(this).parent().find("ul:first");
        var list_left = parseFloat(list.css('left'));
        if(list_left<0){
         list.not(":animated").animate({"left":list_left+804})
        }
    });
    $(".scroll-right").click(function(){
        var break_left = $(this).parent().find(".breakItem").offset().left;
        var slider_left = $(this).parent().find(".scroller-content").offset().left;
        var break_left = break_left-slider_left;
        if(break_left>804){
          var list = $(this).parent().find("ul:first");
          var list_left = parseFloat(list.css('left'));
          list.not(":animated").animate({"left":list_left-804})
        }
    });

    $(".layouts-list li a").live('click', function(){
      $(this).parents(".layouts-list").find("a").removeClass("active");
      $(this).addClass("active");
    });

    $('input[type=text], input[type=password], textarea').focus(function(){
      $(this).addClass('focus')
    });
    $('input[type=text], input[type=password], textarea').blur(function(){$(this).removeClass('focus')});


    $(".campaign-header").hover(function(){
        $(this).addClass('campaign-header-hover');
    }, function(){
        $(this).removeClass('campaign-header-hover');
    });

    $(".campaign-table tr").hover(function(){

        $(this).addClass('hover');


    }, function(){
      $(this).removeClass('hover');
    });

    $(".campaign-table .type-checkbox:checked").each(function(){
        $(this).parents('tr').addClass('active');
    });

    $(".campaign-table input.type-checkbox").click(function(){
        if($(this).is(':checked')){
          $(this).parents('tr').addClass('active');
        }
        else{
          $(this).parents('tr').removeClass('active');
        }
    });


    $(".campaign-table-inner tr:last-child td").css("borderBottom", "0");









});