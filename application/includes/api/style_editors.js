
mw.require("url.js");
mw.require("css_parser.js");






mw.current_element_styles = {}

Registered_Sliders = ['margin', 'opacity', 'padding'];


mw.border_which = 'border';


var t = mwd.body.style;





  mw._JSPrefixes = ['Moz', 'Webkit', 'O', 'MS'];

  mw._CSSPrefixes = ['-moz-', '-webki-t', '-o-', '-ms-'];

  var _Prefixtest = document.body.style;

  mw.JSPrefix = function(property){
    if(_Prefixtest[property]!==undefined){
      return property;
    }
    else{
       var property = property.charAt(0).toUpperCase() + property.slice(1);
       for(var i=0;i<mw._JSPrefixes.length;i++){
         if(_Prefixtest[mw._JSPrefixes[i]+property] !==undefined){
            return mw._JSPrefixes[i]+property;
         }
       }
    }
  }



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
    event.stopPropagation();
    event.preventDefault();
  }

  canvas.onmousemove = function(event){
    if(canvas.isDrag){
      event.stopPropagation();
      event.preventDefault();
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




mw.css3fx = {
  perspective:function(el,a,b){
    if(el){
      el.style[mw.JSPrefix('transform')] = "perspective( "+a+"px ) rotateY( "+b+"deg )";
      $(el).addClass("mwfx");
    }
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
         $(elem).css(mw.JSPrefix(a), b);
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









mw.sliders_settings = function(el){
    var el = $(el);

    var type = el.dataset('type');
    var min = parseFloat(el.dataset('min'));
    var min = !isNaN(min)?min:0;
    var max = parseFloat(el.dataset('max'));
    var max = !isNaN(max)?max:100;
    var val = parseFloat(el.dataset('value'));
    var val = !isNaN(val)?val:0;



    return {
       slide:function(event,ui){
          var val = (ui.value);
          var to_set = type=='opacity'? val/100 :val;
          $(".element-current").css(type, to_set);
          $("input[name='"+this.id+"']").val(val);
       },
       change:function(event,ui){
          var val = (ui.value);
          var to_set = type=='opacity'? val/100 :val;
          $(".element-current").css(type, to_set);
          $("input[name='"+this.id+"']").val(val);
       },
       create: function(event, ui) {
          $("input[name='"+this.id+"']").val(val);
       },
       min:min,
       max:max,
       value:val
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
        el.parents(".mw_dropdown").setDropdownValue(el.attr("data-value"), true, true, false);
    }
  });

  $(".mw_dropdown_func_slider").change(function(){
    var val = $(this).getDropdownValue();
    var who = $(this).attr("data-for");
    $("#"+who).attr("data-type", val);
  });
}



mw.parseCSS = function(element){

    this.border = function(){

    }

    return this;
}


$(document).ready(function(){

$("#design_bnav").draggable({
  handle:"#design_bnav_handle",
  containment:'window',
  scroll:false,
  start:function(){
    $(".ts_main_ul .ts_action").invisible();
    $(".ts_main_ul .ts_action").css({"left":"100%", top:0});
  }
});

$(window).bind("onItemClick", function(e, el){
  $(".element-current").removeClass("element-current");
  $(el).addClass("element-current");
  mw.current_element = el;
  mw.current_element_styles = window.getComputedStyle(el, null);
  $(".es_item").trigger("change");
});

$(window).bind("onImageClick", function(e, el){
  $(".element-current").removeClass("element-current");
  $(el).addClass("element-current");
  mw.current_element = el;
  mw.current_element_styles = window.getComputedStyle(el, null);
  $(".es_item").trigger("change");
});


$(window).bind("onBodyClick", function(){
    $(".element-current").removeClass("element-current");
    mw.current_element = false;
    $("#items_handle").css({
      top:"",
      left:""
    })
});


  $(".ed_slider").each(function(){
    $(this).slider(mw.sliders_settings(this));
  });



  init_square_maps();

  $("#fx_element").change(function(){
    var val = $(this).getDropdownValue();
    $("#element_effx .fx").hide();
    $("#fx_"+val).show();
  });


  /*
  $(".perspective-slider").slider({
    slide:function(event,ui){
        mw.css3fx.perspective($(".element-current")[0], $(".element-current").width(), ui.value);
    },
    change:function(event,ui){
        mw.css3fx.perspective($(".element-current")[0], $(".element-current").width(), ui.value);
    },
    stop:function(event,ui){
        mw.css3fx.set_obj($(".element-current")[0], 'transform', "perspective( "+$(".element-current").width()+"px ) rotateY( "+ui.value+"deg )");
    },
    min:-180,
    max:180,
    value:0
  });      */


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


    $(".slider_val input").keyup(function(event){
      var el = $(this);
      var _el = this;
      var val = _el.value;
      var val = val.replace(/[^-\d]/,'');
      var val = val !=="" ? val : 0;
      var val = parseFloat(val);
      el.val(val);
      var name = _el.name;
      $("#"+name).slider("value", val);
    });


    $(".ts_border_position_selector a.border-style").click(function(){
      if(!$(this).hasClass("active")){
         $(".ts_border_position_selector a.border-style.active").removeClass("active");
         $(this).addClass("active");
         var which = $(this).dataset("val");
         mw.border_which = which;
         if(which=='none'){
           $('.element-current').css("border", "none");
         }
      }
    });

    $(".dd_border_selector").change(function(){
      $('.element-current').css(mw.border_which+'Style', $(this).getDropdownValue());
    });

});


