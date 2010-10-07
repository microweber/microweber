

var slider = {
  getCurr : function(elem){
    var dis = $(elem);
    var curr = parseFloat(dis.css("left"));
    return curr;
  },
  next:function(elem){
    var curr = slider.getCurr(elem);
    var dis = $(elem);
    var step = dis.find('li:first').outerWidth(true);
    var max = -(dis.outerWidth(true) - (3*step));
    if(!dis.hasClass('animated') && curr>max){
       dis.addClass('animated');
       dis.animate({left:curr-step}, function(){
         dis.removeClass('animated');
       });
    }
  },
  prev:function(elem){
    var curr = slider.getCurr(elem);
    var dis = $(elem);
    var step = dis.find('li:first').outerWidth(true);
    if(!dis.hasClass('animated') && curr<0){
       dis.addClass('animated');
       dis.animate({left:curr+step}, function(){
         dis.removeClass('animated');
       });
      }
    }
}




$(document).ready(function(){


$("a[href='#']").attr("href", "javascript:void(0);")




$(".slider-holder ul").each(function(){
     var length = $(this).find("li").length;
    var single_width = $(this).find("li:first").outerWidth(true);
    var slider_width = length*single_width;
    $(this).width(slider_width);
});



var box_top = document.createElement('div');
box_top.className = 'box-top';
box_top.innerHTML = '&nbsp;';

var box_bot = document.createElement('div');
box_bot.className = 'box-bot';
box_bot.innerHTML = '&nbsp;';

$(".box").append(box_bot);
$(".box").prepend(box_top);


$("#top-login").click(function(){
  $("#login").toggle();

});


$(".box:first").find(".scroll-up").hide();
$(".box:first").find(".scroll-down").css("right", "14px");
$(".box:last").find(".scroll-down").hide();


$(".scroll-down").click(function(){
    var parent = $(this).parent();
    var next = $(".box").index(parent) + 1;
    var top = $(".box").eq(next).offset().top-14;
    $("html").animate({scrollTop:top});
});
$(".scroll-up").click(function(){
    var parent = $(this).parent();
    var next = $(".box").index(parent) - 1;
    var top = $(".box").eq(next).offset().top-14;
    $("html").animate({scrollTop:top});
});


$(".title-tip").each(function(){
  var title = this.title;
  $(this).attr("tip", title);
  $(this).removeAttr("title");
});
$(".title-tip").hover(function(e){

var dis = $(this);

dis.addClass('title-tip-hover')


  if(dis.hasClass('title-tip-hover')){
    $("#ttm-content").html(dis.attr("tip"));

      var elem = {
        top:dis.offset().top,
        left:dis.offset().left,
      }
      var cursor = {
        top:e.pageY,
        left:e.pageX
      }
      $("#title-tip").css({
        top:cursor.top-5,
        left:cursor.left+20,
        visibility:'visible'
      })
  }



}, function(){
  $(this).removeClass('title-tip-hover')
  $("#title-tip").css({
    visibility:'hidden'
  })
})


$(".browser-scroll").each(function(){
    var length = $(this).find("a").length;
    var width = $(this).find("a:first").outerWidth(true);
    $(this).width(length*width);
});




    $(".browser-scroll-right").click(function(){
      var parent = $(this).parent();
      var slider = parent.find(".browser-scroll");
      var step = 3*(parent.find("a:first").outerWidth(true));
      var max = -(parseFloat(parent.find(".browser-scroll").outerWidth())-step);
      var curr = parseFloat(slider.css("left"));
      if(curr>max){
        slider.not(":animated").animate({left:curr-step})
      }
    });
    $(".browser-scroll-left").click(function(){
      var parent = $(this).parent();
      var slider = parent.find(".browser-scroll");
      var step = 3*(parent.find("a:first").outerWidth(true));
      var curr = parseFloat(slider.css("left"));
      if(curr<0){
        slider.not(":animated").animate({left:curr+step})
      }
    });


    $(".browser-scroll a").click(function(){
        var href = $(this).attr("href");

        $(this).parents(".box-content").find(".template-preview").css("backgroundImage", "url(" + href + ")");


        return false;
    });


    $(".browser-scroll").each(function(){
      var len = $(this).find("a").length;
      if(len<4){
        $(this).parents(".template-skins-browse").find(".browser-scroll-left").hide();
        $(this).parents(".template-skins-browse").find(".browser-scroll-right").hide();
      }
    });





}); // end doc ready



var scrollTo = function(id){
  var to = $(id).offset().top;
  $(document.body).animate({scrollTop:to});
}









