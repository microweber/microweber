
$(document).ready(function(){
   mw.css3fx.init_css();
});


mw.onLive(function(){


mw.current_element_styles = {}

Registered_Sliders = ['margin', 'opacity', 'padding'];

mw.border_which = 'border';


canvasCTRL_draw = function(context, type, color, x, y, w, h){
         context.clearRect(x, y, w, h);
         context.fillStyle = color;
         context.beginPath();
         type=='rect' ? context.rect(x,y,w,h) : context.arc(x,y,3,15, Math.PI*2, true);
         context.closePath();
         context.fill();
         mw.wysiwyg.change(mwd.querySelector(".element-current"));
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

  var opt = typeof options === 'object' ? $.extend({}, canvasCTRL_defaults, options) : canvasCTRL_defaults;

  var isX =  opt.axis.contains('x');
  var isY =  opt.axis.contains('y');

  var el = this;
  var id = 'canvas_'+mw.random();
  var w = el.width();
  var h = el.height();
  el.html('<canvas tabindex="0" class="canvas-slider" focusable="true" id="'+id+'" width="'+w+'" height="'+h+'"></canvas>');
  var canvas = mwd.getElementById(id);
  if( canvas === null) return false;
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
  canvas.onmousedown = function(event){
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


width_slider_onstart = function(){
	var el =  mwd.getElementById('ed_auto_width');
 	if(el != null){
		el.checked=false;
	}
}


validateJSON4StaticElements = function(obj){
  if(!mw.tools.isEmptyObject(obj) && mwd.querySelector('[href*="live_edit.css"]') !== null){
      var sheet = mwd.querySelector('[href*="live_edit.css"]').sheet;
      var rules = sheet.cssRules, l = rules.length, i = 0;
      for ( ; i<l ; i++){
        var selector = rules[i].selectorText;
        if(!!obj[selector]){
             var css = rules[i].cssText.split(selector)[1].split('{')[1].split('}')[0];
             obj[selector].css = css + obj[selector].css;
        }
      }
  }
  return obj;
}

generateJSON4StaticElements = function(){
  var all = mwd.querySelectorAll("[staticdesign]"), l=all.length,i=0,obj={};
  if(l>0){
    for( ; i<l; i++){
        var el = all[i];
        var selector = mw.tools.generateSelectorForNode(el);
        var css = el.getAttribute("style");

		if(selector != undefined && (selector =='body' || selector =='BODY')){
			if(css != undefined){
				var css=css.replace("padding-top","mw-pad-top");
			}
		}
        if(el!==null){
          obj[selector] = {
             selector:selector,
             css:css
          }
        }
    }
  }
  return validateJSON4StaticElements(obj);
}

saveStaticElementsStyles = function(callback, error){
    var obj = generateJSON4StaticElements();
    if(!mw.tools.isEmptyObject(obj)){
      $.post(mw.settings.api_url + "current_template_save_custom_css", obj, function(data){
            if(typeof callback === 'function'){
              callback.call();
            }
      }).fail(function(){
           if(typeof error === 'function'){
              error.call();
            }
      });
    }
    else{
       if(typeof callback === 'function'){
          callback.call();
       }
    }
}


mw.sliders_settings = function(el){
    var el = $(el);
	
	if(typeof jQuery.fn.dataset !== 'function'){
		return;	
	}

    var step = parseFloat(el.dataset('step'));
    var step = !isNaN(step)?step:1;
    var min = parseFloat(el.dataset('min'));
    var min = !isNaN(min)?min:0;
    var max = parseFloat(el.dataset('max'));
    var max = !isNaN(max)?max:100;
    var val = parseFloat(el.dataset('value'));
    var val = !isNaN(val)?val:0;

    var onstart = el.dataset("onstart");
    var custom = el.dataset("custom");


    return {
       slide:function(event,ui){
          var val = (ui.value);
          var type = $(this).dataset('type');
          var to_set = type=='opacity'? val/100 :val;
          if(custom==''){
            mw.$(".element-current").css(type, to_set);
          }
          else{
            custom._exec(ui.value);
          }
          mw.$("input[name='"+this.id+"']").val(val);
       },
       stop:function(){
          mw.wysiwyg.change(mwd.querySelector(".element-current"));
       },
       change:function(event,ui){
         if(event.originalEvent!==undefined){
            var val = (ui.value);
            var type = $(this).dataset('type');
            var to_set = type=='opacity'? val/100 :val;
           mw.$("input[name='"+this.id+"']").val(val);
            if(custom==''){
                mw.$(".element-current").css(type, to_set);
            }
            else{
              custom._exec(ui.value);
            }
         }
       },
       create: function(event, ui) {
          mw.$("input[name='"+this.id+"']").val(val);
       },
       start:function(event, ui){
         if(onstart!==''){
           onstart._exec(ui.value);
         }
       },
       min:min,
       max:max,
       value:val,
       step:step
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
        el.parents(".mw-dropdown").setDropdownValue(el.attr("data-value"), true, true, false);
    }
  });

  mw.$(".mw-dropdown_func_slider").change(function(){
    var val = $(this).getDropdownValue();
    var who = $(this).attr("data-for");
    mw.$("#"+who).attr("data-type", val);
  });
}



mw.setCurrentStyles = function(el){



  var parser = mw.CSSParser(el);

  mw.$("#width_slider").slider("value", parser.get.width().tonumber());


  var bg = parser.get.background();
  mw.$("#ts_bg_repeat").setDropdownValue(bg.repeat);
  mw.$("#ts_bg_position").setDropdownValue(bg.position);

  if(bg.color!=='transparent'){
     mw.$("#ts_element_bgcolor span").css("background", bg.color);
  }
  else{
     mw.$("#ts_element_bgcolor span").css("background", '');
  }
  if(bg.image!=='none'){
     var url =  bg.image.replace(/url\(|\)|"|'/g, "");
     mw.$("#ed_bg_image_status").html("<img src='"+url+"' />");
  }
  else{
     mw.$("#ed_bg_image_status").html("");
  }


}


$(document).ready(function(){


if( typeof $.fn.draggable === 'function'){

    mw.$("#design_bnav").draggable({
      handle:"#design_bnav_handle",
      containment:'window',
      scroll:false,
      start:function(){
        mw.$(".ts_main_ul .ts_action").invisible();
        mw.$(".ts_main_ul .ts_action").css({"left":"100%", top:0});
      },
      stop:function(event, ui){
        mw.cookie.ui("designtool_position", ui.position.top+"|"+ui.position.left)
      }
    });


}

$(window).bind("onItemClick onImageClick onElementClick", function(e, el){

if($(".ts_action:isVisible").length==0){

  mw.$(".element-current").removeClass("element-current");
  $(el).addClass("element-current");
  mw.current_element = el;
  mw.current_element_styles = window.getComputedStyle(el, null);

  mw.$(".es_item").trigger("change");

  if(e.type=='onImageClick'){
    mw.$(".mw-designtype-element").hide();
    mw.$(".mw-designtype-image").show();
  }
  else if(e.type=='onItemClick'){
    mw.$(".mw-designtype-element").show();
    mw.$(".mw-designtype-image").hide();
  }
  else if(e.type=='onElementClick'){
    mw.$(".mw-designtype-element").show();
    mw.$(".mw-designtype-image").hide();
  }


  mw.setCurrentStyles(el);

  width_slider_onstart();

 }
});




$(mwd.body).bind("click", function(e){
  if(!mw.tools.hasClass(e.target.className, 'edit') && !mw.tools.hasParentsWithClass(e.target, 'edit') ){
  if(!mw.tools.hasClass(e.target.className, 'mw-defaults') && !mw.tools.hasParentsWithClass(e.target, 'mw-defaults') ){

    if(mw.$(".ts_action:isVisible").length==0){
      mw.$(".element-current").removeClass("element-current");
      mw.$(e.target).addClass("element-current");
      mw.current_element =e.target;
      mw.$("#items_handle").css({
        top:"",
        left:""
      });
      mw.$(".mw-designtype-element").show();
      mw.$(".mw-designtype-image").hide();
      mw.setCurrentStyles(e.target);
       $(e.target).attr('staticdesign', 'true');
       mw.wysiwyg.change(mw.current_element)
    }
  }
  }

});


  mw.$(".ed_slider").each(function(){
    $(this).slider(mw.sliders_settings(this));
  });



  init_square_maps();

  mw.$("#fx_element").change(function(){
    var val = $(this).getDropdownValue();
    mw.$("#element_effx .fx").hide();
    mw.$("#fx_"+val).show();
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
  });


  */





  mw.onLive(function(){

  var shadow_pos  = $("#ed_shadow").canvasCTRL();

if(typeof(shadow_pos.bind) != 'function'){
	return false;
}

  shadow_pos.bind("change", function(event, val){
      if(mw.current_element_styles.boxShadow !="none"){
		  
		 
		
		 if(typeof( mw.current_element_styles.boxShadow) === 'undefined'){
			return false;
		}  
		  
        var arr = mw.current_element_styles.boxShadow.split(' ');
		
		
		
		
        var len = arr.length;
        var s = parseFloat(arr[len-2]);
      }
      else{var s = 6}
      var color = mw.$(".ed_shadow_color").dataset("color");
      mw.$(".element-current").css("box-shadow", val.left+"px " + val.top + "px "+ s +"px #"+color);
  });




  var shadow_strength = mw.$("#ed_shadow_strength").canvasCTRL({axis:'x', alwayPositive:'yes'});

  shadow_strength.bind("change", function(event, val){
      if( mw.current_element_styles.boxShadow !="none" ){
        var arr = mw.current_element_styles.boxShadow.split(' ');
        var len = arr.length;
        var x =  parseFloat(arr[len-4]);
        var y =  parseFloat(arr[len-3]);
        var color = $(".ed_shadow_color").dataset("color");
        mw.$(".element-current").css("box-shadow", x+"px " + y + "px "+ (val.left-5)*2 +"px #" + color);
      }


  });


    mw.css3fx.init_css();



    mw.$(".slider_val input").keyup(function(event){
        var el = $(this);
        var _el = this;
        var val = _el.value;
        var val = val.replace(/[^-\d]/,'');
        var val = val !=="" ? val : 0;
        var val = parseFloat(val);
        el.val(val);
        var name = _el.name;
        mw.$("#"+name).slider("value", val);

    });


    mw.$(".ts_border_position_selector a.border-style").click(function(){
      if(!$(this).hasClass("active")){
         mw.$(".ts_border_position_selector a.border-style.active").removeClass("active");
         $(this).addClass("active");
         var which = $(this).dataset("val");
         mw.border_which = which;
         if(which=='none'){
           mw.$('.element-current').css("border", "none");
         }
      }
    });

    mw.$(".dd_border_selector").bind("change", function(e){

      mw.$('.element-current').css(mw.border_which+'Style', $(this).getDropdownValue());
      mw.wysiwyg.change(mwd.querySelector(".element-current"));
    });

    mw.$(".dd_borderwidth_Selector").bind("change", function(e){
      mw.$('.element-current').css(mw.border_which+'Width', $(this).getDropdownValue());
      mw.wysiwyg.change(mwd.querySelector(".element-current"));
    });


    mw.$("#ts_bg_repeat").bind("change", function(){
       mw.$('.element-current').css('backgroundRepeat', $(this).getDropdownValue());
       mw.wysiwyg.change(mwd.querySelector(".element-current"));
    });
    mw.$("#ts_bg_position").bind("change", function(e){
       var val = $(this).getDropdownValue();
       mw.$('.element-current').css('backgroundPosition', val);
       mw.wysiwyg.change(mwd.querySelector(".element-current"));
    });


    mw.$("#ed_auto_width").commuter(function(){
         $(".element-current").width('auto');
    }, function(){
         $(".element-current").width(mw.$("#width_slider").slider("value"));
    });
     mw.$("#ed_auto_height").commuter(function(){
         mw.$(".element-current").height('auto');
    }, function(){
         mw.$(".element-current").height(mw.$("#height_slider").slider("value"));
    });

    mw.$("#design_bnav").hover(function(){
       $(mwd.body).addClass('design_hover')
    }, function(){
       $(mwd.body).removeClass('design_hover')
    });




    });

});



});




