
mw.require("wysiwyg.js");


mw.external_tool = function(name){
  //return mw.settings.includes_url  +  "toolbar/editor_tools/"+name+"/index.php";


  return mw.settings.site_url  +  "editor_tools/" + name + "/";
}



mw.require("tools.js");



$(window).load(function(){






$("#module_category_selector").change(function(){
    var val = $(this).getDropdownValue();
    (val!=-1&&val!="-1")?mw.tools.toolbar_sorter(Modules_List, val):'';
});

$("#module_category_selector #dd_module_search").bind("keyup paste", function(){
    mw.tools.toolbar_searh(Modules_List, this.value);
});


});




mw.extras = {
  fullscreen:function(el){
      if (el.webkitRequestFullScreen) {
        el.webkitRequestFullScreen();
      } else if(el.mozRequestFullScreen){
        el.mozRequestFullScreen();
      }
  },
  get_filename:function(s) {
    var d = s.lastIndexOf('.');
    return s.substring(s.lastIndexOf('/') + 1, d < 0 ? s.length : d);
  }
}




mw.image_settings={
    html:function(){
        var id = 'image_'+mw.random();
        var html = ''
        + '<div onmouseleave="$(this).remove();" class="mw_image_settings" id="'+id+'">'
          +  '<span class="image_close">Close</span>'
          +  '<span class="image_change">Change</span>'
        + '</div>';
        return {html:html,id:id};
    },
    prepare:function(){
       var item = mw.image_settings.html();
       $(document.body).append(item.html);
       return item.id;
    },
    scale:function(el, id){
        var offset = $(el).offset();
        var width = $(el).outerWidth();
        var height = $(el).outerHeight();
        $("#" + id).css({
          left:offset.left,
          top:offset.top,
          width:width,
          height:height,
          display:'block'
        });
    },
    init:function(el){  return false;
       var id = mw.edit.image_settings.prepare();
       mw.image_settings.scale(el, id);

       mw.image_settings.del_init(id, el);
       mw.image_settings.change_init(id, el);
    },
    del_init:function(id, el){
       $("#"+id).find(".image_close").click(function(){
         var filename = mw.extras.get_filename(el.src);
         if(confirm("Are you sure you want to delete '"+filename+"'?")){
           $(el).slideUp(function(){$(this).remove();});
         }
       });
    },
    change_init:function(id, el){
      $("#"+id).find(".image_change").click(function(){
        var w = $(window).width()-100;
        var h = $(window).height()-100;
        var save_img_url = '/Microweber/save.php';
        //var frame = "<iframe width='"+(w-30)+"' height='"+(h-30)+"' src='http://pixlr.com/express/?wmode=transparent&locktarget=true&target="+save_img_url+"&image=" + el.src + "' scrolling='no' frameborder='0'></iframe>";
        var frame = ":)";
        mw.modal(frame, w, h);
      });
    },
    image_resize:function(selector){
      //chrome, opera, safari

      if($.browser.safari || $.browser.chrome || $.browser.opera || true){
        $(selector).each(function(){
          if(!$(this).parent().hasClass("image_resizer")){
              var w = $(this).width();
              var h = $(this).height();
              $(this).wrap("<span class='image_resizer' style='width:"+w+"px;height:"+h+"px'></span>");
          }
        });
        $(".image_resizer").resizable({
           resize: function(event, ui){
            var w = $(this).width();
            var h = $(this).height();
            $(this).find("img").attr("width",w).attr("height",h).width(w).height(h);
           }
        });
        $(".edit img").attr("contentEditable", false);
      }
    }
}




mw.image = {
    isResizing:false,
    currentResizing:null,
    resize:{
      create_resizer:function(){
        if(mw.image_resizer==undefined){
          var resizer = document.createElement('div');
          resizer.className = 'mw_image_resizer';
          document.body.appendChild(resizer);
          mw.image_resizer = resizer;
        }
      },
      prepare:function(){
        mw.image.resize.create_resizer();
        $(mw.image_resizer).resizable({
            handles: "all",
            minWidth: 20,
            minHeight: 20,
            start:function(){
              mw.image.isResizing = true;
            },
            stop:function(){
              mw.image.isResizing = false;
            },
            resize:function(){
              var offset = mw.image.currentResizing.offset();
              $(this).css({
                top: offset.top,
                left: offset.left
              })
            },
            aspectRatio: 16 / 9
        });
        $(mw.image_resizer).mouseleave(function(){
          if( !mw.image.isResizing ){
             $(this).removeClass("active");
          }
        });
      },
      init:function(selector){
        mw.image_resizer == undefined?mw.image.resize.prepare():'';
        $(selector, '.edit').each(function(){
          $(this).notclick().bind("click", function(){
             if( !mw.image.isResizing && !mw.isDrag && !mw.settings.resize_started){
             var el = $(this);
             var offset = el.offset();
             var r = $(mw.image_resizer);
             var width = el.width();
             var height = el.height();
             r.css({
                left:offset.left,
                top:offset.top,
                width:width,
                height:height
             });
             r.addClass("active");
             $(mw.image_resizer).resizable( "option", "alsoResize", el); }
             $(mw.image_resizer).resizable( "option", "aspectRatio", width/height);
             mw.image.currentResizing = el;
            })
          });
        }
      }
    }




$.expr[':'].noop = function(){
    return true;
};


(function( $ ){
  $.fn.notmouseenter = function() {
    return this.filter(function(){
      var el = $(el);
      var events = el.data("events");
      return (events==undefined || events.mouseover==undefined || events.mouseover[0].origType!='mouseenter');
    });
  };
})( jQuery );

(function( $ ){
  $.fn.notclick = function() {
    return this.filter(function(){
      var el = $(el);
      var events = el.data("events");
      return (events==undefined || events.click==undefined);
    });
  };
})( jQuery );


(function( $ ){
  $.fn.visible = function() {
    return this.css("visibility", "visible");
  };
})( jQuery );

(function( $ ){
  $.fn.invisible = function() {
    return this.css("visibility", "hidden");
  };
})( jQuery );


(function( $ ){
  $.fn.getDropdownValue = function() {
    return this.attr("data-value");
  };
})( jQuery );
(function( $ ){
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
})( jQuery );





editablePurify = function(el){
  var dirty = $(el).find("[_moz_dirty]").not("br");
  dirty.each(function(){
    var el = $(this);
    el.removeAttr("id");
    if(el.html()=="" || el.html()==" "){
      el.replaceWith('<br />');
    }
  });
}





$(document).ready(function(){

    windowOnScroll.stop();

    mw.wysiwyg.prepare();
    mw.wysiwyg.init();

});

mw.toolbar = {
  module_icons:function(){
    $(".mw_module_image").each(function(){
      var img = $(this.getElementsByTagName('img')[0]);
      var img_height = img.height();
      var img_margin = 0;
      if(img_height<32){
        var img_margin = ($(this).height()/2)-(img_height/2);
        img.css({
          marginTop: img_margin
        });
      }
      $(this).find(".mw_module_image_shadow").css({
         top:img_height-4,
         left:($(this).width()/2)-32,
         marginTop:img_margin>0?img_margin+2:img_margin
      }).visible();
    });
  }
}





$(window).load(function(){
    mw.toolbar.module_icons();

    $(".element").keyup(function(event){
        editablePurify(this);
    });

    $(".element").mouseup(function(event){
        mw.wysiwyg.check_selection();
    });
    $(".element").mousedown(function(event){
        $(".mw_editor_btn").removeClass("mw_editor_btn_active")
    });


    mw.disable_selection();



  mw.smallEditor = $("#mw_small_editor");
mw.bigEditor = $("#mw-text-editor");


$(".mw_dropdown_action_font_family").change(function(){
    var val = $(this).getDropdownValue();
     mw.wysiwyg.fontFamily(val);
});
$(".mw_dropdown_action_font_size").change(function(){
    var val = $(this).getDropdownValue();
     mw.wysiwyg.fontSize(val);
});
$(".mw_dropdown_action_format").change(function(){
    var val = $(this).getDropdownValue();
    mw.wysiwyg.format(val);
});



  $(".edit img").click(function(){
      mw.image_settings.init(this);
  });

  mw.image.resize.init(".element img");



    $("#live_edit_toolbar_holder").height($("#live_edit_toolbar").height());

    $(window).bind("scrollstop",function(){
      setTimeout(function(){
      if(mw.isDrag && $(".ui-draggable-dragging").css("position")=='relative'){
        var curr_el = $(".ui-draggable-dragging").css("position", "static");
        var offset = curr_el.offset();
        curr_el.css("position", "relative");
        var scroll_top = $(window).scrollTop();
        curr_el.css({
          top:mw.mouse.y-offset.top+(scroll_top)+30
        });
      }  }, 100);
    });
    $(document.body).mousedown(function(){
      if($(".editor_hover").length==0){
        $(mw.wysiwyg.external).empty().css("top", "-9999px");
        mw.wysiwyg.check_selection();
      }
      if($(".mw_dropdown.hover").length==0){
        $("div.mw_dropdown_fields").hide();
      }
    });


    $("#mw_small_editor").draggable({
        drag:function(){
          mw.SmallEditorIsDragging = true;
        },
        stop:function(){
          mw.SmallEditorIsDragging = false;
        }
    });



    $("#mw-text-editor").mousedown(function(){
      if($(".mw_editor_btn_hover").length==0){
        mw.mouseDownOnEditor = true;
        $(this).addClass("hover");
      }
    });
    $("#mw-text-editor").mouseup(function(){
        mw.mouseDownOnEditor = false;
        $(this).removeClass("hover");
    });
    $("#mw-text-editor").mouseleave(function(){
        if(mw.mouseDownOnEditor){
            $("#mw_small_editor").visible();
            $("#mw-text-editor").animate({opacity:0}, function(){
              $(this).invisible();
            });
            $("#mw-text-editor").removeClass("hover");
        }
    });
    $(document.body).mouseup(function(){
         mw.mouseDownOnEditor = false;
         mw.SmallEditorIsDragging = false;
    });





});


windowOnScroll = {
    scrollcatcher : 0,
    scrollcheck : 1,
    int : null,
    stop:function(){
      $(window).scroll(function(){
        windowOnScroll.scrollcatcher +=37;
        if(!windowOnScroll.int){
           windowOnScroll.int = setInterval(function(){
               if(windowOnScroll.scrollcheck != windowOnScroll.scrollcatcher){
                 windowOnScroll.scrollcheck = windowOnScroll.scrollcatcher;
               }
               else{
                 clearInterval(windowOnScroll.int);
                 windowOnScroll.int = null;
                 $(window).trigger("scrollstop");
               }
           }, 37);
        }
      });
    }
  }


mw.toggle_subpanel = function(){
  this.speed = 200;
  var el = $("#show_hide_sub_panel");
  if(el.hasClass("state-off")){
     el.removeClass("state-off");
     $("#show_hide_sub_panel_slider").animate({left:0}, this.speed);
     $("#show_hide_sub_panel_info").fadeOut(this.speed, function(){
       $(this).css({left:'auto'}).html('Hide').fadeIn(this.speed);
     });
     $(".mw_tab_active").slideDown(this.speed);
     $("#mw_toolbar_nav").slideDown(this.speed);
  }
  else{
    el.addClass("state-off");
    $("#show_hide_sub_panel_slider").animate({left:35}, this.speed);
    $("#show_hide_sub_panel_info").fadeOut(this.speed, function(){
      $(this).css({left:3}).html('Show').fadeIn(this.speed);
    });

    $(".mw_tab_active").slideUp(this.speed);
    $("#mw_toolbar_nav").slideUp(this.speed);
  }
}


$(window).resize(function(){
    mw.tools.module_slider.scale();
    mw.tools.toolbar_slider.ctrl_show_hide();
});