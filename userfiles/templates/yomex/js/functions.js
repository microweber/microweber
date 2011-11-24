
function newRow(elem){
    var e = $('#' + elem);
    var clone = e.find("tr:last").clone(true);
   clone.find("td:first").html(e.find("tbody tr").length + 1 + '.');
   clone.find("input").val("");
   clone.find("td:last").html('<a href="javascript:;" onclick="deleteRow(this)"><u>Изтрий</u></a>');
   e.find("tbody").append(clone);
}

function deleteRow(elem){
  var e = $(elem);
  var tbody = e.parents("tbody");
  if(confirm("Сигурни ли сте че искате да премахнете информацията за придружителя?")){

     e.parents("tr").animate({opacity:'0'}, 'slow', function(){
         $(this).remove();
         tbody.find("tr td:first-child").each(function(i){
           $(this).html((i+1) + ".")
         });
     })

  }

}


categories_search = categories_search;

function getStyle(oElm, strCssRule){
	var strValue = "";
	if(document.defaultView && document.defaultView.getComputedStyle){
		strValue = document.defaultView.getComputedStyle(oElm, "").getPropertyValue(strCssRule);
	}
	else if(oElm.currentStyle){
		strCssRule = strCssRule.replace(/\-(\w)/g, function (strMatch, p1){
			return p1.toUpperCase();
		});
		strValue = oElm.currentStyle[strCssRule];
	}
	return strValue;
}


function createCSS(string){
    var style = document.createElement('style');
    var css = string;
    style.type = 'text/css';
    if(style.styleSheet){
        style.styleSheet.cssText=css;
    }
    else {
        style.appendChild(document.createTextNode(css));
    }
    document.getElementsByTagName("head")[0].appendChild(style);
}


function scaleRichImages(){
  if(browserIs.msie6()){
     $(".richtext img").each(function(){
         if($(this).width()>690){
           $(this).width('690px');
         }
     });
  }
}


function autoFade(){
  var curr = $("#home-image li.active");
  if($("#home-image").hasClass("hover")){/**/}
  else{
      if(curr.next("li").length<1){
         curr.removeClass("active");
         $("#home-image li:first").addClass("active");
         curr.fadeOut(2000);
         $("#home-image li:first").fadeIn(2000);
      }
      else{
        curr.removeClass("active");
        curr.fadeOut(2000);
        curr.next().fadeIn(2000);
        curr.next().addClass("active");
      }
  }
}



function FslideLeft(){
    var step = $("#sliderAction ul.notXfader li:first").outerWidth(true);
    var list = $("#sliderAction ul.notXfader");
    var fader = $("#sliderAction ul.Xfader");
    var curr = parseFloat(list.css("left"));
    if(/*curr<0 && */!$(document.body).hasClass('sliding')){
       $(document.body).addClass('sliding');

         //infinity
         var first_li = $("#sliderAction ul li:last");
         var clone = $("#sliderAction ul li:last").clone(true);
         clone.prependTo("#sliderAction ul");
         $("#sliderAction ul li:last-child").remove();
         $("#sliderAction ul").css("left",curr-step);
         var curr = parseFloat(list.css("left"));
         // End infinity


       fader.css("left", curr+step);
       list.animate({opacity:'hide'}, 700);
       fader.fadeIn(700, function(){
         list.css("left", curr+step);
         fader.removeClass('Xfader').addClass("notXfader");
         list.addClass('Xfader').removeClass("notXfader");
         $(document.body).removeClass('sliding');
       })
    }
}

function FslideRight(){
    var step = $("#sliderAction ul.notXfader li:first").outerWidth(true);
    var list = $("#sliderAction ul.notXfader");
    var fader = $("#sliderAction ul.Xfader");
    var max = -(list.outerWidth() - 3*step);
    var curr = parseFloat(list.css("left"));
    if(curr>max && !$(document.body).hasClass('sliding')){
       $(document.body).addClass('sliding');
       fader.css("left", curr-step);
       list.animate({opacity:'hide'}, 700);
       fader.fadeIn(700, function(){
         list.css("left", curr-step);
         fader.removeClass('Xfader').addClass("notXfader");
         list.addClass('Xfader').removeClass("notXfader");

         //infinity
         var first_li = $("#sliderAction ul li:first");
         var clone = $("#sliderAction ul li:first").clone(true);
         clone.appendTo("#sliderAction ul");
         $("#sliderAction ul li:first-child").remove();
         $("#sliderAction ul").css("left","0");
         // End infinity

         $(document.body).removeClass('sliding');
       })
    }
}




$(document).ready(function(){

$(".hright .gtitle:first").attr("id", "gtitle-first");
$(".hright .box:first").attr("id", "box-first");


darkColor = $("#gtitle-first").css("background-color");
lightColor = $("#box-first").css("background-color");

$(".inline-price-content").css("color", darkColor);
if(window.location.href.indexOf('hotelski-rezervacii') != -1){
   $(".inline-price-content").css("color", 'white');
}


   $("#sidebar-search").replaceWith("Removed with javascript")

    $("a[href='#']").attr("href", "javascript:void(0);");

    setInterval("autoFade()", 10000);



   // $("#nav li:nth-child(2)").find('ul').remove();

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

    $(".nav-pink ul:first").append('<li class="child-item"><a href="http://yomexbg.com/hotels-online-reserve">Хотели в чужбина</a></li>');

    $("#home-image li:first").show().addClass("active");
    $("#home-image").hover(function(){
      $(this).addClass("hover");
    }, function(){
      $(this).removeClass("hover");
    });

var slider_length = $("#sliderAction ul li").length;
var single_width = $("#sliderAction ul li:first").outerWidth(true);

$("#sliderAction ul").width(slider_length*single_width);

  //$("#sliderAction ul li:last-child").css("marginRight","0px");




  $("#sliderAction li a strong, .price").each(function(){
    var html = $(this).html();
    $(this).html("<em>&nbsp;</em><b>" + html + "</b>");
  });


  $("#sliderAction ul").clone(true).appendTo("#sliderAction").hide().addClass("Xfader").removeClass("notXfader");








  var autoslideright;
  var autoslideleft;

  $("#fsliderright").mousedown(function(){

    autoslideright = setInterval(function(){
       FslideRight();
    }, 800);
  });
  $("#fsliderright").mouseup(function(){
    FslideRight();
    clearInterval(autoslideright)
  });

  $("#fsliderleft").mousedown(function(){
    autoslideleft = setInterval(function(){
       FslideLeft();
    }, 800);
  });
  $("#fsliderleft").mouseup(function(){
    FslideLeft();
    clearInterval(autoslideleft)
  });




  $(".submit").click(function(){
    $(this).parents("form").find("input[type=submit]").click();
    return false;
  });

  $("#web-design-company").hover(function(){
    $(this).stop();
    $("#griph").stop();
    $(this).animate({"color":"#999999"});
    $("#griph").animate({'opacity':'1'});
  }, function(){
    $(this).stop();
    $("#griph").stop();
    $("#griph").animate({'opacity':'0'});
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
     scaleRichImages();

  });

 $("#sliderAction li").hover(function(){
   $(this).addClass("hover");
 }, function(){
   $(this).removeClass("hover");
 });
  $("#sliderAction li").click(function(){
    window.location.href = $(this).find("a:first").attr("href");
  });



  mwtree.init(".sec-nav:first", 2);


/*$("#Slider").each(function(){
        var rowDiv = document.createElement("div");
        $(rowDiv).addClass("slider-item");
        for(i=0; i< $(this).find("> .list-offer").length ; i+= 4){
                $(rowDiv).append( $(this).find("> .list-offer").slice(i, i+4).clone() );
                $(this).append(rowDiv);
                rowDiv = document.createElement("div");
                $(rowDiv).addClass("slider-item");
        }
        $(this).find("> .list-offer").remove();//Remove all the immediate boxgrid child elements.
}); */




var results =[];
var elements = $('.list-offer');
$.map( elements, function(i, n){
        if( n%4 === 0 ){
                results.push(n);
        }
});
$.each( results , function(i,v){
        elements.slice(v, v+4).wrapAll('<div class="slider-item"></div>');
});



var results =[];
var elements = $('.gall-other span');
$.map( elements, function(i, n){
        if( n%4 === 0 ){
                results.push(n);
        }
});
$.each( results , function(i,v){
        elements.slice(v, v+4).wrapAll('<div class="gall-thumb"></div>');
});

  $(".list-offers a.back").click(function(){
    history.back();
    return false;
  });



$(".share, #sendmail").click(function(){

    Modal.box('', 400, 440);
    Modal.overlay();
    var clone = $('#recommend').clone(true);

    $(clone).validate(function(){
        $(clone).disable();
        var r_address = window.location.href;
        var names = $(clone).find("#r_name").val();
        var email = $(clone).find("#r_sender_email").val();
        var email2 = $(clone).find("#r_receiver_email").val();
        var message = $(clone).find("#r_message").val();

        var options = {
            r_address:r_address,
            names:names,
            email:email,
            email2:email2,
            message:message

        }

        $.post(template_url+'send_share.php', options, function(data){
          //alert(data)
            Modal.box('<h2 class="ajax_success_message">Съобщението е изпратено успешно.</h2>', 500, 150);
        });
    });


    $("#modalbox").append(clone);
    return false;

});


$("a[rel='empty']").parent().remove();
$("a[hreflang='empty']").parent().remove();


$(".reserve").click(function(){

    Modal.box('', 400, 400);
    Modal.overlay();
    var clone = $('#reserve_form').clone(true);
    $(clone).validate(function(){

      $(clone).disable();

      var reserve_title = $("#reserve_title").val();
      var reserve_url = $("#reserve_url").val();
      var reserve_name = $("#reserve_name").val();
      var reserve_phone = $("#reserve_phone").val();
      var reserve_mail = $("#reserve_mail").val();
      var reserve_message = $("#reserve_message").val();

      var options = {
          reserve_title:reserve_title,
          reserve_url:reserve_url,
          reserve_title:reserve_title,
          reserve_name:reserve_name,
          reserve_phone:reserve_phone,
          reserve_mail:reserve_mail,
          reserve_message:reserve_message
      }

      $.post(template_url+'send_reservation.php', options, function(){
          Modal.box('<h2 class="ajax_success_message">Вашата резервация е изпратена успешно. <br />Ще се свържем с вас за потвърждение.</h2>', 500, 150);
      });


    });
    $("#modalbox").append(clone);
    return false;

});


 $(".sec-nav a").each(function(){
   if($(this).find("strong").length==0){
     $(this).wrapInner("<strong></strong>");
   }
 });

 $(".faqlink").click(function(){
   $(this).next().toggleSlide('fast');
   return false;
 });

 //$(".chapter-link").css("background", darkColor);

  $(".chapter-link").click(function(){
   $(this).next(".chapter-content").toggleSlide('fast');
   return false;
 });




   $(".print").click(function(){
        var window_content = $(".content").html();
        var d = document.createElement('div');
        d.innerHTML = window_content;
        //$(d).find()
        var window_center = $(window).width()/2-300 + 'px';
        var printPopup=window.open("","myWin","menubar,scrollbars,left="+ window_center+",top=90px,height=400px,width=600px");
        printPopup.document.write('<style>.options, .hidden, .tab-nav{display:none}</style>'+window_content);
        printPopup.print();

        return false;
    });






  $(".3rd-elem li:nth-child(3n)").css("marginRight", "0");
  $(".3rd-elem li").each(function(i){
    if(i%3==0){
      $(this).css("clear", "both");
    }
  });

  $(".slider-item:first").addClass('slider-item-active');

  var slider_length = $("#Slider .slider-item").length;
  $(".pagining p").html("Страница: 1 от "+slider_length);

  $(".pagining").not(".nojs").find(".right").click(function(){
    if($('#Slider .slider-item-active').next(".slider-item").length!=0){
       $('#Slider .slider-item-active').not(':animated').fadeOut('slow', function(){
          $('#Slider .slider-item-active').removeClass('slider-item-active');
       });
       $('#Slider .slider-item-active').next(".slider-item").not(':animated').fadeIn('slow', function(){
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
  $(".pagining").not(".nojs").find(".left").click(function(){

    if($('#Slider .slider-item-active').prev(".slider-item").length!=0){
       $('#Slider .slider-item-active').not(':animated').fadeOut('slow', function(){
          $('#Slider .slider-item-active').removeClass('slider-item-active');
       });
       $('#Slider .slider-item-active').prev(".slider-item").not(':animated').fadeIn('slow', function(){
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

 if($(".pnojs a.active").next("a").length==0){
   $(".pagining.nojs .right").hide();
 }
 if($(".pnojs a.active").prev("a").length==0){
   $(".pagining.nojs .left").hide();
 }



  $(".pagining.nojs .right").click(function(){
    window.location.href = $(this).parent().find("a.active").next("a").attr("href");
  });
  $(".pagining.nojs .left").click(function(){
    window.location.href = $(this).parent().find("a.active").prev("a").attr("href");
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
        img.title = $(this).find("img").attr("title");
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




/* Dropdown */








$(".dropdown").each(function(){
  var links_length = $(this).find('li a').length;
  links_length>12?$(this).find('.dropdownList:first').css('height', '250px').css('overflowY','auto').css('overflowX', 'hidden'):'';

  if($(this).find(".dropdownList a.active").length>0){
    var html = $(this).find(".dropdownList a.active").html();
    $(this).find(".dropdownValue").html(html)
  }

});

$(".dropdown").hover(function(){
  $(this).addClass('dropdownHover');
}, function(){
  $(this).removeClass('dropdownHover');
});

$(".dropdown").click(function(){
  $('.dropdownList').not('.dropdownHover .dropdownList').not('.dropdownList .dropdownList').hide();
  $(this).find('.dropdownList:first').toggle();
});

$(document.body).mousedown(function(){
    if(!$('.dropdownHover').length){
        $('.dropdownList').not('.dropdownList .dropdownList').hide();
    }
});



$(".dropdownList a.active").each(function(){
    var val = $(this).attr("rev");
    var dfor = $(this).attr("type");
    var oldval = $("#" + dfor).val();



    if(oldval==undefined){
        oldval='';
    }

    //alert(val);
    //alert(dfor);
   // alert(oldval);

    //$("#" + dfor).val(oldval + val + ',');

    $("input[type='hidden']").each(function(){
      if($(this).attr("id")==dfor){

        $(this).val(val);
        //$(this).val(oldval + val);
        //$(this).val(oldval + val + ',');
      }
    });


});

$("#DestiNacia ul:first").prepend('<li><a type="selected_categories" hreflang="full" rel="destination" rev="">Дестинация</a></li>');
$("#kategoria_ID ul:first").prepend('<li><a type="selected_categories" hreflang="full" href="" rel="destination" rev="">Категория</a></li>');



$(".dropdownList a").click(function(){

    var val = $(this).attr('rev');
    var rel = $(this).attr('rel');
    var text = $(this).text();

    var type = $(this).attr("type");

    $(this).parents(".dropdownList").find("a").removeClass("active");
    $(this).addClass("active");




    var str = ''
    $("#topsearch-form a").each(function(){
      if($(this).attr('type')==type && $(this).hasClass("active")){
        str = str + $(this).attr('rev') + ',';
      }
    });


    $(this).parents('.dropdown').find('.dropdownValue').text(text);
    $('.dropdownList').not('.dropdownList .dropdownList').hide();


   //$("#" + type).val(str);

   str = str.slice(0, (str.length-1));


   document.getElementById(type).value = str;






    return false;

});



$("#topsearch-form").submit(function(){
  if($("#searchtext").val() == 'Търсене'){
     $("#searchtext").val('');
  }
});

$("#header-search").submit(function(){
  if($("input[name='searchsite']").val() == 'Търсене'){
     $("input[name='searchsite']").val('');
  }
});


/* /Dropdown */





/*darkColor = getStyle(document.getElementById('gtitle-first'), 'background-color');
lightColor = getStyle(document.getElementById('box-first'), 'background-color');*/



//$("a.reserve").css("color", darkColor);

 //$(".reserve").css("backgroundColor", lightColor)
/*
$(".Yomex h1").css("background", mainColor);
$(".Yomex h2").css("background", mainColor);
$(".Yomex h3").css("background", mainColor);
$(".Yomex h4").css("background", mainColor);
$(".Yomex h5").css("background", mainColor);
*/

 $(".Yomex h4").click(function(){
   if($(this).hasClass("closed")){
    $(this).parent().css("height", 'auto');
    $(this).removeClass('closed');
   }
   else{
    var height = $(this).outerHeight(true);
    $(this).parent().css("height", height);
    $(this).addClass('closed');
   }

 });


 scaleRichImages();







 $(".ajax-box").modal("ajax", 550, 600);

 $("form.validate").validate();


$("a[href='#']").attr("href", "javascript:void(0)");


$(".cat-slider ul").each(function(){
  var length = $(this).find("li").length;
  var width = $(this).find("li:first").outerWidth(true);
  $(this).width(length*width);
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

    $(".panes.richtext table").attr("border", 0);
    $(".panes.richtext table").attr("cellpadding", 0);
    $(".panes.richtext table").attr("cellspacing", 0);

    //$(".panes.richtext table").removeAttr("style");
    $(".panes.richtext table").removeAttr("border");
    $(".panes.richtext table").removeAttr("onmouseover");
    $(".panes.richtext table").removeAttr("onmouseout");



    $(".panes.richtext td")
        //.removeAttr("style")
        .removeAttr("border")
        .removeAttr("onmouseover")
        .removeAttr("onmouseout");


    $(".panes.richtext tr")
        .removeAttr("border")
        .removeAttr("onmouseover")
        .removeAttr("onmouseout");


    //$(".panes.richtext th").removeAttr("style");
    $(".panes.richtext th").removeAttr("border");
    $(".panes.richtext th").removeAttr("onmouseover");
    $(".panes.richtext th").removeAttr("onmouseout");




    $(".stars").each(function(){

      if($(this).find(".star-num").length>0){
          var length=parseFloat($(this).find(".star-num").html());
      }  else {
            var length = $(this).find(".star").length;

      }
      if($(this).parents(".gtitle").length>0){
       $(this).html('<samp> - ' + length + '</samp>&nbsp;<span class="star">&nbsp;</span>');
      }
      else{
       $(this).html('<samp>' + length + '</samp>&nbsp;<span class="star">&nbsp;</span>');
      }

    });


    $(".sotw").click(function(){
      $(this).parent().find("ul:first").slideToggle();
      return false;
    });



    /*$(".panes.richtext table p").each(function(){
            $(this).replaceWith($(this).html());
    });


    $(".panes.richtext table").css({
      textAlign:'center'
    });


    $(".panes.richtext table tr td:first-child").css({
        textAlign:'left'
    });


    $(".panes.richtext table td[align='left']").css({
        textAlign:'left'
    });
    $(".panes.richtext table td[align='center']").css({
        textAlign:'center'
    });
    $(".panes.richtext table td[align='right']").css({
        textAlign:'right'
    }); */




});

















