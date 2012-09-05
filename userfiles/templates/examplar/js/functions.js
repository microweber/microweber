/* Settings */

rotator_speed = 600;
rotator_interval = 5000;


/* /Settings */

var windowFocused = true; // google chrome window focus bug
window.onblur = function(){windowFocused = false;}
window.onfocus = function(){windowFocused = true;}


$(document).ready(function(){

oldie = (($.browser.msie) && ($.browser.version<9))?true:false;

//oldie = true;


$(".box").append("<i class='tl'>&nbsp;</i><i class='tr'>&nbsp;</i><i class='bl'>&nbsp;</i><i class='br'>&nbsp;</i>");

  $("a[href='#']").attr("href", "javascript:void(0)");

  $(".action-submit").click(function(){
    $(this).parents("form").submit();
    return false;
  });


  $(".shadow").prepend("<img class='shadow_img' src='"+img_url+"shadow.png' />");


/* Rotator */

$(".frame:first").show();

$(".frame").each(function(i){
    if(i==0){
      $("#SliderControlls").append("<span class='slider_ctrl slider_ctrl_active'>"+i+"</span>");
    }
    else{
      $("#SliderControlls").append("<span class='slider_ctrl'>"+i+"</span>");
    }
});


$("#HeadRotator").hover(function(){
  $(this).addClass("hovered");
}, function(){
  $(this).removeClass("hovered");
});

$(".slider_ctrl").click(function(){
  if(!$(this).hasClass("slider_ctrl_active")){
    $(".slider_ctrl").removeClass("slider_ctrl_active");
    $(this).addClass("slider_ctrl_active");
     var i = $(this).html();
     $(".frame:visible").fadeOut(rotator_speed);
     $(".frame").eq(i).fadeIn(rotator_speed);
  }


});  /* /Rotator */

var sw = $("#Scroll .shadow").eq(0).outerWidth(true);

$("#ScrollHolder").width(sw*$("#Scroll .shadow").length);



$("input[type='text'], textarea, input[type='password']").each(function(){
  var Default = $(this).attr("default");
  $(this).val(Default)
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
});

$(window).load(function(){
   autoSlide();
   slide.init({
        elem:"#Scroll",
        items:".shadow",
        step:3
    });



});


$("#List li:nth-child(3n+3)").css("marginRight", 0);

$(".gallery ul a").colorbox({
  slideshow:false,
  rel:'slide',
  previous:'Предишна',
  next:'Следваща',
  close:'Затвори',
  current: "Снимка {current} от {total}",
  width:800,
  height:$(window).height()-50
});






});


autoSlide = function(){

    setInterval(function(){

    if(windowFocused && $(".frame").length>1 && !$("#HeadRotator").hasClass("hovered")){

      if($(".frame:visible").next().length>0){
         $(".frame:visible").fadeOut(rotator_speed);
         $(".frame:visible").next().fadeIn(rotator_speed);
         $(".slider_ctrl_active").next().addClass("slider_ctrl_active");
         $(".slider_ctrl_active:first").removeClass("slider_ctrl_active");
      }
      else{
          $(".frame:visible").fadeOut(rotator_speed);
          $(".frame:first").fadeIn(rotator_speed);
          $(".slider_ctrl_active").removeClass("slider_ctrl_active");
          $(".slider_ctrl:first").addClass("slider_ctrl_active");
      }
    }


    }, rotator_interval)

}



























