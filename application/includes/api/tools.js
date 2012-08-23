mw.simpletabs = function(context){
    var context = context || document.body;
    $(".mw_simple_tabs_nav", context).each(function(){
      var parent = $(this).parents(".mw_simple_tabs").eq(0);
      parent.find(".tab").addClass("semi_hidden");
      parent.find(".tab").eq(0).removeClass("semi_hidden");
      $(this).find("a").eq(0).addClass("active");
      $(this).find("a").click(function(){
          var parent = $(this).parents(".mw_simple_tabs_nav").eq(0);
          var parent_master =  $(this).parents(".mw_simple_tabs").eq(0);
          parent.find("a").removeClass("active");
          $(this).addClass("active");
          parent_master.find(".tab").addClass("semi_hidden");
          var index = parent.find("a").index(this);
          parent_master.find(".tab").eq(index).removeClass("semi_hidden");
          return false;
      });
    });
}

mw.external_tool = function(url){
  return !url.contains("/") ? mw.settings.site_url  +  "editor_tools/" + url + "/" : url;
}

mw.tools = {
  preloader:function(init, element){
    if(init=='stop'){$("#preloader").hide()}
    else{
      var el = $("#element");
      var offset = el.offset();
      var w = el.width();
      var h = el.height();

    }
  },
  modal:{
    settings:{
      width:600,
      height:500
    },
    source:function(id){
      var id = id || "modal_"+mw.random();
      var html = ''
        + '<div class="mw_modal mw_modal_maximized" id="'+id+'">'
          + '<div class="mw_modal_toolbar">'
            + '<span class="mw_modal_title"></span>'
            + '<span class="mw_modal_close" onclick="mw.tools.modal.remove(\''+id+'\')">Close</span>'
            + '<span class="mw_modal_minimize" onclick="mw.tools.modal.minimax(\''+id+'\');"></span>'
          + '</div>'
          + '<div class="mw_modal_container">'
          + '</div>'
          + '<div class="iframe_fix"></div>'
        + '</div>';
        return {html:html, id:id}
    },
    _init:function(html, width, height, callback, title, name){
        if(typeof name==='string' && $("#"+name).length>0){
            return false;
        }
        var modal = mw.tools.modal.source(name);

        $(document.body).append(modal.html);
        var modal_object = $(document.getElementById(modal.id));

        var container = modal_object.find(".mw_modal_container").eq(0);
        modal_object.width(width).height(height);
        var padding = parseFloat(container.css("paddingTop")) + parseFloat(container.css("paddingBottom"));
        container.append(html).height(height-padding);
        modal_object.css({top:($(window).height()/2)-(height/2),left:($(window).width()/2)-(width/2)});
        modal_object.show().draggable({
          handle:'.mw_modal_toolbar',
          containment:'body',
          iframeFix: false,
          start:function(){
            $(this).find(".iframe_fix").show();
            if($(".mw_modal").length>1){
              mw_m_max = parseFloat($(this).css("zIndex"));
              $(".mw_modal").not(this).each(function(){
                   var z = parseFloat($(this).css("zIndex"));
                   mw_m_max = z>=mw_m_max?z+1:mw_m_max;
              });
              $(this).css("zIndex", mw_m_max);
            }
          },
          stop:function(){
             $(this).find(".iframe_fix").hide();
          }
        });
        var modal_return = {main:modal_object, container:modal_object.find(".mw_modal_container")[0]}
        typeof callback==='function'?callback.call(modal_return):'';
        typeof title==='string'?$(modal_object).find(".mw_modal_title").html(title):'';
        typeof name==='string'?$(modal_object).attr("name", name):'';
        return modal_return;
    },
    init:function(o){
      var o = $.extend({}, mw.tools.modal.settings, o);
      return  mw.tools.modal._init(o.html, o.width, o.height, o.callback, o.title, o.name);
    },
    minimize:function(id){
        var modal = $("#"+id);
        var window_h = $(window).height();
        var window_w = $(window).width();
        var modal_width = modal.width();
        var old_position = {
          width:modal.css("width"),
          height:modal.css("height"),
          left:modal.css("left"),
          top:modal.css("top")
        }
        modal.data("old_position", old_position);
        var margin =  24*($(".is_minimized").length);
        modal.addClass("is_minimized");
        modal.animate({
            top:window_h-24-margin,
            left:window_w-modal_width,
            height:24
        });
        modal.draggable("option", "disabled", true);
    },
    maximize:function(id){
       var modal = $("#"+id);
       modal.removeClass("is_minimized");
       modal.animate(modal.data("old_position"));
       modal.draggable("option", "disabled", false);
    },
    minimax:function(id){
      //check the state of the modal and toggle it;
      if($("#"+id).hasClass("is_minimized")){
         mw.tools.modal.maximize(id);
      }
      else{
         mw.tools.modal.minimize(id);
      }
    },
    settings_window:function(callback){
        var modal = mw.modal("");
        return modal;
    },
    frame:function(obj){
        var obj = $.extend({}, mw.tools.modal.settings, obj);
        var frame = "<iframe width='"+obj.width+"' height='"+(obj.height-35)+"' src='" + mw.external_tool(obj.url) + "' scrolling='auto' frameborder='0'></iframe>";
        var modal = mw.tools.modal.init({
          html:frame,
          width:obj.width,
          height:obj.height,
          callback:obj.callback,
          title:obj.title,
          name:obj.name
        });
        $(modal.main).addClass("mw_modal_type_iframe");
        mw.tools.modal.overlay(modal.main);
        return modal;
    },
    remove:function(id){
        $(document.getElementById(id)).remove();
        $("div.mw_overlay[rel='"+id+"']").remove();
    },
    overlay:function(for_who, is_over_modal){
        var overlay = document.createElement('div');
        overlay.className = 'mw_overlay';
        var id = for_who ? $(for_who).attr("id") : 'none';
        $(overlay).attr("rel",id);
        document.body.appendChild(overlay);
        if(is_over_modal!=undefined){

        }
    },
  },
  dropdown:function(callback){
    $(".mw_dropdown .other-action").hover(function(){
      $(this).addClass("other-action-hover");
    }, function(){
      $(this).removeClass("other-action-hover");
    });
    $(".mw_dropdown").click(function(){
      $(".mw_dropdown").not(this).find(".mw_dropdown_fields").hide();
      if($(this).find(".other-action-hover").length==0){
        var item =  $(this).find(".mw_dropdown_fields");
        if(item.is(":visible")){
            item.hide();
            item.focus();
        }
        else{
            item.show();
            item.find(".dd_search").focus();
        }
      }
    });
    $(".mw_dropdown").hover(function(){
        $(this).addClass("hover");
    }, function(){
        $(this).removeClass("hover");
    });
    $(".mw_dropdown a").click(function(){
      $(this).parents(".mw_dropdown_fields").hide();
      var html = $(this).html();
      var value = this.parentNode.getAttribute("value");
      $(this).parents(".mw_dropdown").setDropdownValue(value, true);
      return false;
    });
  },
  module_slider:{
    scale:function(){
      var window_width = $(window).width();
      $(".modules_bar").each(function(){
           $(this).width(window_width-204);
           $(this).find(".modules_bar_slider").width(window_width-220);
      });
    },
    prepare:function(){
      $(".modules_bar").each(function(){
          var module_item_width = 0;
          $(this).find("li").each(function(){
            module_item_width += $(this).outerWidth(true);
          });
          $(this).find("ul").width(module_item_width);
      });
    },
    init:function(){
        mw.tools.module_slider.prepare();
        mw.tools.module_slider.scale();
    }
  },
  toolbar_tabs:{
    get_active:function(){
        var hash = window.location.hash;
        if(hash==''){
          return '#tab_modules';
        }
        else{
            return hash.replace(/mw_/g, '');
        }
    },
    change:function(){
       var hash = mw.tools.toolbar_tabs.get_active();
       $("#mw_tabs li").removeClass("active");
       var xdiez = hash.replace("#", "");
       $("#mw_tabs a[href*='"+xdiez+"']").parent().addClass("active");
       $(".mw_tab_active").removeClass("mw_tab_active");
       $(hash).addClass("mw_tab_active");
    },
    init:function(){
        $(window).bind('hashchange', function(){
            mw.tools.toolbar_tabs.change();
        });
        mw.tools.toolbar_tabs.change();
    }
  },
  toolbar_slider:{
    slide_left:function(item){
       var item = $(item);
        mw.tools.toolbar_slider.ctrl_show_hide();
        var left = item.parent().find(".modules_bar").scrollLeft();
       item.parent().find(".modules_bar").stop().animate({scrollLeft:left-120}, function(){
            mw.tools.toolbar_slider.ctrl_show_hide();
        });
    },
    ctrl_show_hide:function(){
      $(".modules_bar").each(function(){
          var el = $(this);
          var parent = el.parent();
          if(el.scrollLeft()==0){
            parent.find(".modules_bar_slide_left").hide();
          }
          else{
            parent.find(".modules_bar_slide_left").show();
          }
          var max = el.width() + el.scrollLeft();
          if(max==this.scrollWidth){
             parent.find(".modules_bar_slide_right").hide();
          }
          else{
             parent.find(".modules_bar_slide_right").show();
          }
      });

    },
    ctrl_states:function(){
       $(".modules_bar_slide_right,.modules_bar_slide_left").mousedown(function(){
         $(this).addClass("active");
       });
       $(".modules_bar_slide_right,.modules_bar_slide_left").bind("mouseup mouseout",function(){
         $(this).removeClass("active");
       });
    },
    slide_right:function(item){
      var item = $(item);
       mw.tools.toolbar_slider.ctrl_show_hide();
       var left = item.parent().find(".modules_bar").scrollLeft();
       item.parent().find(".modules_bar").stop().animate({scrollLeft:left+120}, function(){
             mw.tools.toolbar_slider.ctrl_show_hide();
       });
    },
    init:function(){
        $(".modules_bar").scrollLeft(0);
        mw.tools.toolbar_slider.ctrl_show_hide();
        $(".modules_bar_slide_left").click(function(){
            mw.tools.toolbar_slider.slide_left(this);
        }).disableSelection();
        $(".modules_bar_slide_right").click(function(){
            mw.tools.toolbar_slider.slide_right(this);
        }).disableSelection();
        mw.tools.toolbar_slider.ctrl_states();
    }
  },
  toolbar_sorter : function(obj, value_to_search){
    for (var item in obj){
        var child_object = obj[item];
        var id = child_object.id;
        var categories = child_object.category.replace(/\s/gi,'').split(',');
        var item = $(document.getElementById(id));
        if(categories.indexOf(value_to_search)!=-1){
           item.show();
        }
        else{
            item.hide();
        }
    }
  },
  toolbar_searh : function(obj, value){
    var value = value.toLowerCase();
    for (var item in obj){
        var child_object = obj[item];
        var id = child_object.id;
        var title = child_object.title.toLowerCase();
        var item = $(document.getElementById(id))
        if (title.contains(value)){
           item.show();
        }
        else{
          item.hide();
        }
    }
  }
}





Wait($, function(){
  $.fn.getDropdownValue = function() {
    return this.attr("data-value");
  };
  $.fn.setDropdownValue = function(val, triggerChange) {
     var isValidOption = false;
     var el = this;
     el.find("li").each(function(){
         if(this.getAttribute('value')==val){
              el.attr("data-value", val);
              var isValidOption = true;
              el.find(".mw_dropdown_val").html(this.getElementsByTagName('a')[0].innerHTML);
              triggerChange?el.trigger("change"):'';
              return false;
         }
     });
     this.attr("data-value", val);
  };
});









