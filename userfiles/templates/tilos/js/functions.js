

isobj = function(obj){
    if( obj == undefined){
      return false;
    }
    else{return true}
}

$.fn.multiWrap = function(each, wrapString){
    var results =[];
    var elements = $(this);
    if(elements.length>0){
        $.map(elements, function(i, n){
            if(n%each === 0 ){
                results.push(n);
            }
        });
        $.each(results , function(i,v){
            elements.slice(v, v+each).wrapAll(wrapString);
        });
    }
};





$(document).ready(function(){
    $("a[href='#']").attr("href", "javascript:void(0)");




    mw.isJSGenerated(".btn", function(){
        var html = $(this).html();
        $(this).html("<span class='b_left'>&nbsp;</span><span class='b_mid'><span>" + html + "</span></span><span class='b_right'>&nbsp;</span>");
    });

     mw.isJSGenerated(".btnH", function(){
       var html = $(this).html();
       $(this).html("<span class='b_left'>&nbsp;</span><span class='b_mid'><span>" + html + "</span></span><span class='b_right'>&nbsp;</span>");
    });

    mw.isJSGenerated(".btn2", function(){
      var html = $(this).text();
      $(this).html("<span class='b_left'>&nbsp;</span><span class='b_mid'><span>" + html + "</span></span><span class='b_right'>&nbsp;</span>");
    });








    $(".submit").click(function(){
       $(this).parents("form").submit();
    });


    slide.init({
      elem:"#photoslider"
    });


    $("#pic_gal .slide_engine li").multiWrap(3, '<ul></ul>');


    slide.init({
      elem:"#pic_gal",
      items:"ul"
    });


    $(".product_item").not(".product_item_slide").each(function(i){
      (i+1)%3==0?this.style.marginRight='0':''
    });




$("input[type='text'], textarea, input[type='password']").each(function(){
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
});




});


slide = {
  init:function(obj){
    var elem = $(obj.elem);

    var ctrl_left = elem.find(".slide_left:first");
    ctrl_left.hide();
    var ctrl_right = elem.find(".slide_right:first");
    var items = isobj(obj.items)?elem.find(obj.items):elem.find("li");
    var width = (items.length)*(items.outerWidth(true));
    if(width<=items.outerWidth(true)){
        ctrl_left.hide();
        ctrl_right.hide();
    }
    var item_width = items.outerWidth(true);
    var step = isobj(obj.step)?obj.step*item_width:item_width;
    var speed = isobj(obj.speed)?obj.speed:400;
    var engine = elem.find(".slide_engine:first");
    engine.css({
      left:0,
      width:width,
      position:"relative"
    });
    ctrl_left.click(function(){
      var curr = parseFloat(engine.css("left"));
      if(curr<0){
        ctrl_right.show()
         engine.not(":animated").animate({left:curr+step}, speed, function(){
            var curr = parseFloat(engine.css("left"));
            if(curr>=0){
              ctrl_left.hide()
            }
         });
      }
    });
    ctrl_right.click(function(){
      var curr = parseFloat(engine.css("left"));
      var max = width + curr - engine.parent().outerWidth()-item_width-1;
      if(max>0){
         ctrl_left.show();
         engine.not(":animated").animate({left:curr-step}, speed, function(){
             var curr = parseFloat(engine.css("left"));
             var max = width + curr - engine.parent().width()-items.outerWidth(true);
             if(max<=0){
                ctrl_right.hide()
             }
         });
      }
    });
  }
}

