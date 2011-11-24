
function autoFade(){
  var curr = $("#home-image li.active");
  if($("#home-image").hasClass("hover")){/**/}
  else{
      if(curr.next("li").length<1){
         curr.removeClass("active");
         $("#home-image li:first").addClass("active");
         curr.fadeOut("slow");
         $("#home-image li:first").fadeIn("slow");
      }
      else{
        curr.removeClass("active");
        curr.fadeOut("slow");
        curr.next().fadeIn("slow");
        curr.next().addClass("active");
      }
  }
}

function FslideLeft(){
   var active = $("#sliderAction ul.active");
   if(active.prev("ul").length<1){
     /*$(this).removeClass("active");
     $(this).fadeOut("slow");
     $("#sliderAction ul:first").fadeIn("slow");
     $("#sliderAction ul:first").addClass("active"); */
   }
   else{
     active.removeClass("active");
     active.fadeOut("slow");
     active.prev().addClass("active");
     active.prev().fadeIn("slow");
   }
}

function FslideRight(){
   var active = $("#sliderAction ul.active");
   if(active.next("ul").length<1){
     /*$(this).removeClass("active");
     $(this).fadeOut("slow");
     $("#sliderAction ul:first").fadeIn("slow");
     $("#sliderAction ul:first").addClass("active"); */
   }
   else{
     active.removeClass("active");
     active.fadeOut("slow");
     active.next().addClass("active");
     active.next().fadeIn("slow");
   }
}

function ooYes(){
  var anchor = '' /*getAnchor()*/;
  if(anchor=='ooyes'){
    Modal.overlay();
    var yes = document.createElement('div');
    yes.style.width = "546px";
    yes.style.height = "220px";
    yes.style.position='fixed';
    yes.style.top='50%';
    yes.style.left='50%';
    yes.style.marginLeft='-273px';
    yes.style.marginTop='-110px';
    yes.style.background='url(http://workspace.ooyes.net/ooyes.png) no-repeat';
    yes.style.zIndex="99";
    yes.id="ooYes";
    setTimeout(function(){
      document.body.appendChild(yes);
    }, 400);
  }
}


$(document).ready(function(){
    setInterval("autoFade()", 5000);

    $("#nav li a").addClass("parent");
    $("#nav li li a").removeClass("parent");
    $("#nav a.parent").append("<span>&nbsp;</span>");

    $("#nav li").addClass("parent-item");
    $("#nav li li").removeClass("parent-item");
    $("#nav li li").addClass("child-item");
    $("#nav li li li").removeClass("child-item");
    $("#nav li li li").addClass("second-child-item");

    $("#nav li.parent-item").hover(function(){
      $(this).stop();
      $(this).find("ul:first").show();
    }, function(){
      $(this).stop();
      $(this).animate({"top":"0px"},400, function(){
          $(this).find("ul:first").hide();
      });
      $
    });

    $("#nav ul ul li:first-child a").css("borderTopWidth", "0px");
    $("#nav ul ul li:last-child a").css("borderBottomWidth", "0px");

    $("#home-image li:first").show().addClass("active");
    $("#home-image").hover(function(){
      $(this).addClass("hover");
    }, function(){
      $(this).removeClass("hover");
    });

  $("#sliderAction ul:first").show().addClass("active");

  $("#sliderAction ul li:last-child").css("marginRight","0px");

  $("#sliderAction li a strong, .price").each(function(){
    var html = $(this).html();
    $(this).html("<em>&nbsp;</em><b>" + html + "</b>");
  });
  $(".offers ul li:last-child").css("marginRight","0px");



  $(".submit").click(function(){
    $(this).parents("form").find("input[type=submit]").click();
    return false;
  });

  $("#web-design-company").hover(function(){
    $(this).stop();
    $(this).animate({"color":"#999999"});
  }, function(){
    $(this).stop();
    $(this).animate({"color":"#ffffff"});
  });

  //$("#footernav li:first-child a").css("paddingLeft","0px");

  $(window).load(function(){
     if(!window.XMLHttpRequest){
       $(".pad").each(function(){
         var Pwidth = $(this).width();
         $(this).css("width", Pwidth-20);
       });

       $("#fsliderright").animate({"right":"-11px"})
     }

     ooYes();
  });
  
  
  
  $(".3rd-elem li:nth-child(3n)").css("marginRight", "0");

  $(".slider-item:first").addClass('slider-item-active');

  var slider_length = $("#Slider .slider-item").length;
  $(".pagining p").html("Страница: 1 от "+slider_length);

  $(".pagining .right").click(function(){
    if($('#Slider .slider-item-active').next().length!=0){
       $('#Slider .slider-item-active').not(':animated').fadeOut('slow', function(){
          $('#Slider .slider-item-active').removeClass('slider-item-active');
       });
       $('#Slider .slider-item-active').next().not(':animated').fadeIn('slow', function(){
          $(this).addClass('slider-item-active');
          var current;
          $('#Slider .slider-item').each(function(i){
            if($(this).hasClass('slider-item-active')){
              current = i+1;
            }
          });
          $(".pagining p").html("Страница: "+  current + " от " + slider_length);
       });
    }
  });
  $(".pagining .left").click(function(){
    if($('#Slider .slider-item-active').prev().length!=0){
       $('#Slider .slider-item-active').not(':animated').fadeOut('slow', function(){
          $('#Slider .slider-item-active').removeClass('slider-item-active');
       });
       $('#Slider .slider-item-active').prev().not(':animated').fadeIn('slow', function(){
          $(this).addClass('slider-item-active');
          var current;
          $('#Slider .slider-item').each(function(i){
            if($(this).hasClass('slider-item-active')){
              current = i+1;
            }
          });
          $(".pagining p").html("Страница: "+  current + " от " + slider_length);
       });
    }
  });
  $('.gall-thumb a:first').addClass('active');
  $(".gall-big a").modal('single');
  $('.gall-thumb a').click(function(){
    if($(this).hasClass('active')){

    }
    else{
       $('.gall-thumb a').removeClass('active');
        $(this).addClass('active');
        var img = new Image();
        var hreflang = $(this).attr('hreflang');
        img.onload = function(){
            var link = document.createElement('a');
            link.href =hreflang;
            link.appendChild(img);
            $(".gall-big").empty();
            $(".gall-big").append(link);
            $(".gall-big a").modal('single');
        }
        img.src = $(this).attr('href');
    }
    return false;
  });

  $(".gall-thumb:first").addClass('gall-thumb-active');

    $(".image-nav .right-arrow").click(function(){
        if($(".gall-thumb-active").next('.gall-thumb').length!=0){
          $(".gall-thumb-active").not(':animated').fadeOut('slow', function(){
             $(this).removeClass('gall-thumb-active');
          });
          $(".gall-thumb-active").next().not(':animated').fadeIn('slow', function(){
             $(this).addClass('gall-thumb-active');
          });
        }
        return false;
    });
    $(".image-nav .left-arrow").click(function(){
        if($(".gall-thumb-active").prev('.gall-thumb').length!=0){
          $(".gall-thumb-active").not(':animated').fadeOut('slow', function(){
             $(this).removeClass('gall-thumb-active');
          });
          $(".gall-thumb-active").prev().not(':animated').fadeIn('slow', function(){
             $(this).addClass('gall-thumb-active');
          });
        }
        return false;
    });


    $(".offers-tabs").seotabs('html');
    $(".tabbed").seotabs('html');



$(".features").each(function(){
	
	var max = 0;
	$(this).find("ul").each(function(){
		var height = $(this).height();
		if(height>max){
			max = height	
		}
	});
	$(this).find("ul").height(max);

});







});// end doc ready

lang = 'bg';

$(function($){
    $.datepicker.regional['bg'] = {
        closeText: 'затвори',
        prevText: '&#x3c;назад',
        nextText: 'напред&#x3e;',
		nextBigText: '&#x3e;&#x3e;',
        currentText: 'днес',
        monthNames: ['Януари','Февруари','Март','Април','Май','Юни',
        'Юли','Август','Септември','Октомври','Ноември','Декември'],
        monthNamesShort: ['Яну','Фев','Мар','Апр','Май','Юни',
        'Юли','Авг','Сеп','Окт','Нов','Дек'],
        dayNames: ['Неделя','Понеделник','Вторник','Сряда','Четвъртък','Петък','Събота'],
        dayNamesShort: ['Нед','Пон','Вто','Сря','Чет','Пет','Съб'],
        dayNamesMin: ['Не','По','Вт','Ср','Че','Пе','Съ'],
		weekHeader: 'Wk',
        dateFormat: 'dd.mm.yy',
		firstDay: 1,
        isRTL: false,
		showMonthAfterYear: false,
		yearSuffix: ''};
        
    if(lang=='bg'){
       $.datepicker.setDefaults($.datepicker.regional['bg']);
    }

});



$(document).ready(function () {
	$('input.one').datepicker();
	$('input.two').datepicker();
});










