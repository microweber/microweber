
mw.require("url.js");


mw.current_element_styles = {}

Registered_Sliders = ['margin', 'opacity', 'padding'];


mw.border_which = 'border';


var t = mwd.body.style;


mw.CSSPrefix = t.perspective!==undefined?"": t.MozPerspective!==undefined?"-moz-": t.WebkitPerspective!==undefined?"-webkit-": t.OPerspective!==undefined?"-o-":"-ms-";



(function($) {


canvasCTRL_draw = function(context, type, color, x, y, w, h){
         context.clearRect(x, y, w, h);
         context.fillStyle = color;
         context.beginPath();
         type=='rect' ? context.rect(x,y,w,h) : context.arc(x,y,3,15, Math.PI*2, true);
         context.closePath();
         context.fill();
}

canvasCTRL_rendValue = function(canvas, x, y, opt){
    var canvas = $(canvas);
    var zeroX = canvas.width()/2;
    var zeroY = canvas.height()/2;
    var r_left = opt.alwayPositive=='no' ? x-zeroX : x;
    var r_top = opt.alwayPositive=='no' ? y-zeroY : y;
    canvas.trigger("change",{
      top: y-zeroY,
      left: r_left
    });
}


canvasCTRL_rendXY = function(w,h,event,isX,isY, off){
    if(event!=""){
        var ml =  event.pageX;
        var mt =  event.pageY;
        return {
          x: isX ? ((ml-off.left>=5) && ((ml-off.left+5)<=w) ? ml-off.left : (ml-off.left<5) ? 5 : (ml-off.left+5)>w ? w-5 : w-5) : h/2,
          y: isY ? ((mt-off.top>=5) && ((mt-off.top+5)<=h) ? mt-off.top : (mt-off.top<5) ? 5 : (mt-off.top+5)>h ? h-5 : h-5) : h/2
        }
    }
}

canvasCTRL_defaults = {
  axis:'x,y',
  alwayPositive:'no'
}

$.fn.canvasCTRL = function(options){

  var opt = mw.is.obj(options) ? $.extend({}, canvasCTRL_defaults, options) : canvasCTRL_defaults;

  var isX =  opt.axis.contains('x');
  var isY =  opt.axis.contains('y');

  var el = this;
  var id = 'canvas_'+mw.random();
  var w = el.width();
  var h = el.height();
  el.html('<canvas tabindex="0" class="canvas-slider" focusable="true" id="'+id+'" width="'+w+'" height="'+h+'"></canvas>');
  var canvas = mwd.getElementById(id);

  var context = canvas.getContext("2d");
  canvasCTRL_draw(context, 'rect', 'transparent', 0 , 0, w, h);
  if(opt.alwayPositive=='no'){
     canvasCTRL_draw(context, 'arc', '#444444', w/2,h/2);
  }
  else{
    canvasCTRL_draw(context, 'arc', '#444444', 5, 5);
  }




  canvas.x=w/2;
  canvas.y=h/2;
  canvas.isDrag = false;
  canvas.onmousedown = function(){
    canvas.isDrag = true;
  }

  canvas.onmousemove = function(event){
    if(canvas.isDrag){
        var off = $(canvas).offset();

        var coords =  canvasCTRL_rendXY(w,h,event,isX,isY, off);

        var x = coords.x;
        var y = coords.y;

        canvasCTRL_draw(context, 'rect', 'transparent', 0 , 0, w, h);
        canvasCTRL_draw(context, 'arc', '#444444', x,y);
        canvasCTRL_rendValue(canvas, x, y, opt);
        canvas.x=x;
        canvas.y=y;
    }
    canvas.onkeydown = function(event){
      if(event.keyCode==38 && isY){//up
        var x = parseFloat(canvas.x);
        var y = parseFloat(canvas.y);
        if(y>5){
          canvas.y=y-1;
          canvasCTRL_draw(context, 'rect', 'transparent', 0 , 0, w, h);
          canvasCTRL_draw(context, 'arc', '#444444', x,y-1);
          canvasCTRL_rendValue(canvas, x, y-1, opt);
        }
      }
      else if(event.keyCode==40 && isY){//down
        var x = parseFloat(canvas.x);
        var y = parseFloat(canvas.y);
        if(y+5<h){
          canvas.y=y+1;
          canvasCTRL_draw(context, 'rect', 'transparent', 0 , 0, w, h);
          canvasCTRL_draw(context, 'arc', '#444444', x,y+1);
          canvasCTRL_rendValue(canvas, x, y+1, opt);
        }
      }
      if(event.keyCode==37 && isX ){//left
        var x = parseFloat(canvas.x);
        var y = parseFloat(canvas.y);
        if(x>5){
          canvas.x=x-1;
          canvasCTRL_draw(context, 'rect', 'transparent', 0 , 0, w, h);
          canvasCTRL_draw(context, 'arc', '#444444', x-1,y);
          canvasCTRL_rendValue(canvas, x-1, y, opt);
        }
      }
      else if(event.keyCode==39 && isX ){//right
        var x = parseFloat(canvas.x);
        var y = parseFloat(canvas.y);
        if(x+5<w){
          canvas.x=x+1;
          canvasCTRL_draw(context, 'rect', 'transparent', 0 , 0, w, h);
          canvasCTRL_draw(context, 'arc', '#444444', x+1,y);
          canvasCTRL_rendValue(canvas, x+1, y, opt);
        }
      }
      event.preventDefault();
    }
  }
  return $(canvas);
}

})(jQuery);


mw.css3fx = {
  perspective:function(el,a,b){
    el.style.WebkitTransform = "perspective( "+a+"px ) rotateY( "+b+"deg )";
    el.style.MozTransform = "perspective( "+a+"px ) rotateY( "+b+"deg )";
    el.style.OTransform = "perspective( "+a+"px ) rotateY( "+b+"deg )";
    el.style.transform = "perspective( "+a+"px ) rotateY( "+b+"deg )";
    $(el).addClass("mwfx");

  },

  set_obj:function(element, option, value){

    if(mw.is.defined(element.attributes["data-mwfx"])){

     var json = mw.css3fx.read(element);

     json[option] = value;

     var string = JSON.stringify(json);

     var string = string.replace(/{/g, "").replace(/}/g);
     var string = string.replace(/"/g, "XX");

     $(element).attr("data-mwfx", string);
    }
    else{
      $(element).attr("data-mwfx", "XX"+option+"XX:XX"+value+"XX");
    }
  },
  init_css:function(el){
    var el = el || ".mwfx";
    $(el).each(function(){
      var elem = this;
      var json = mw.css3fx.read(el);
      $.each(json, function(a,b){
         $(elem).css(mw.CSSPrefix+a, b);
         mw.log(mw.CSSPrefix+a + " : " + b)
      });
    });
  },
  read:function(el){
    var el = $(el);
    var attr = el.attr("data-mwfx");
    if(mw.is.defined(attr)){
      var attr = attr.replace(/XX/g, '"');
      var attr = attr.replace(/undefined/g, '');
      var json = $.parseJSON('{'+attr+'}');
      return json;
    }
    else{return false;}
  }
}

mw.config_element_styles=function(){
    var q = mw.current_element_styles;

    $.each(Registered_Sliders, function(a,b){
        var val = q[b];
        if(val == "") {var val = 0;}
        var val = parseFloat(val);


        if(b=='opacity'){
           $("#tb_design_holder ."+b+"-slider").slider("option", "value", val*100);
         }
         else{
           $("#tb_design_holder ."+b+"-slider").slider("option", "value", val);
         }
    });


   $(".square_map_item").removeClass("active");
   $(".square_map_item_default").addClass("active");

}


mw.alignem = function(align){
    var el = $(".element-current");
    switch(align){
      case "left":
        el.removeClass("right").removeClass("center").addClass("left");
        break;
      case "right":
        el.removeClass("left").removeClass("center").addClass("right");
        break;
      case "center":
        el.removeClass("right").removeClass("left").addClass("center");
        break;
      default:
        el.removeClass("right").removeClass("left").removeClass("center");
    }
}

mw.setbg = function(url){
  $(".element-current").css("backgroundImage", "url("+url+")");
}

mw.sliders_settings = {
  css:{
    slide:function(event,ui){
        var type = $(this).attr("data-type");
        var val = (ui.value);
        type=='opacity'?  val = val/100 :'';
        $(".element-current").css($(this).attr("data-type"), val);
    },
    min:0,
    max:100,
    value:0
  }
}

init_square_maps = function(){
  var items = $(".square_map .square_map_item");
  items.hover(function(){
     var val = $(this).html();
     $(this).parents(".square_map").find(".square_map_value").html(val);
  }, function(){
     var val = $(this).parents(".square_map").find(".active").html();
     $(this).parents(".square_map").find(".square_map_value").html(val);
  });
  items.mousedown(function(){
    var el = $(this);
    if(!el.hasClass("active")){
        el.parents(".square_map").find(".active").removeClass("active");
        el.addClass("active");
        el.parents(".mw_dropdown").setDropdownValue(el.attr("data-value"), true, true, el.html());
    }
  });

  $(".mw_dropdown_func_slider").change(function(){
    var val = $(this).getDropdownValue();
    var who = $(this).attr("data-for");
    $("#"+who).attr("data-type", val);
  });
}




$(document).ready(function(){

$(window).bind("onItemClick", function(e, el){
  $(".element-current").removeClass("element-current");
  $(el).addClass("element-current");
  mw.current_element_styles = window.getComputedStyle(el, null);
  $(".es_item").trigger("change");
});

$(window).bind("onImageClick", function(e, el){
  $(".element-current").removeClass("element-current");
  $(el).addClass("element-current");
  mw.current_element_styles = window.getComputedStyle(el, null);
  $(".es_item").trigger("change");
});


$(window).bind("onBodyClick", function(){
    $(".element-current").removeClass("element-current");
    $("#items_handle").css({
      top:"",
      left:""
    })
});


  $(".ed_slider").each(function(){
    var el = $(this);
    el.slider(mw.sliders_settings.css);
    var max = $(this).attr("data-max");
    var min = $(this).attr("data-min");

    el.slider("option", "max", max!=undefined?parseFloat(max):100);
    el.slider("option", "min", min!=undefined?parseFloat(min):0);
    console.log(el.slider("option", "min"))
  });


  init_square_maps();

  $("#fx_element").change(function(){
    var val = $(this).getDropdownValue();
    $("#element_effx .fx").hide();
    $("#fx_"+val).show();
  });

  $(".perspective-slider").slider({
    slide:function(event,ui){
      mw.css3fx.perspective($(".element-current")[0], $(".element-current").width(), ui.value);
    },
    stop:function(event,ui){
        mw.css3fx.set_obj($(".element-current")[0], 'transform', "perspective( "+$(".element-current").width()+"px ) rotateY( "+ui.value+"deg )");
    },
    min:-180,
    max:180,
    value:0
  });


  var shadow_pos  = $("#ed_shadow").canvasCTRL();

  shadow_pos.bind("change", function(event, val){
      if(mw.current_element_styles.boxShadow !="none"){
        var arr = mw.current_element_styles.boxShadow.split(' ');
        var len = arr.length;
        var s = parseFloat(arr[len-2]);
      }
      else{var s = 6}
      var color = $(".ed_shadow_color").dataset("color");
      $(".element-current").css("box-shadow", val.left+"px " + val.top + "px "+ s +"px #"+color);
  });

  var shadow_strength = $("#ed_shadow_strength").canvasCTRL({axis:'x', alwayPositive:'yes'});

  shadow_strength.bind("change", function(event, val){
      if( mw.current_element_styles.boxShadow !="none" ){
        var arr = mw.current_element_styles.boxShadow.split(' ');
        var len = arr.length;
        var x =  parseFloat(arr[len-4]);
        var y =  parseFloat(arr[len-3]);
        var color = $(".ed_shadow_color").dataset("color");
        $(".element-current").css("box-shadow", x+"px " + y + "px "+ (val.left-5)*2 +"px #" + color);
      }


  });


    mw.css3fx.init_css();

});


