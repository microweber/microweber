

mw.current_element_styles = {}

Registered_Sliders = ['margin', 'opacity'];

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
  var elements = $(".element");
  elements.mousedown(function(){
         elements.removeClass("element-current");
         $(this).addClass("element-current");
         mw.current_element_styles = window.getComputedStyle(this, null);
         $(".es_item").trigger("change");
  });


  $(".ed_slider").each(function(){
    var el = $(this);
    el.slider(mw.sliders_settings.css);
  });


  init_square_maps();

});