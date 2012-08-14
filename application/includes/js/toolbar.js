
mw.external_tool = function(name){
  //return mw.settings.includes_url  +  "toolbar/editor_tools/"+name+"/index.php";


  return mw.settings.site_url  +  "editor_tools/" + name + "/";
}

mw.external_tool("some_tool");

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
    source:function(id){
      var id = id || "modal_"+mw.random();
      var html = ''
        + '<div class="mw_modal mw_modal_maximized" id="'+id+'">'
          + '<div class="mw_modal_toolbar">'
            + '<span class="mw_modal_title"></span>'
            + '<span class="mw_modal_close" onclick="mw.tools.modal.remove(\''+id+'\')">Close</span>'
            + '<span class="mw_modal_minimize" onclick="mw.tools.modal.minimax(\''+id+'\');">Minimize</span>'
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
    rte_tool:function(tool_name, title, name){
        var frame = "<iframe width='430' height='360' src='" + mw.external_tool(tool_name) + "' scrolling='auto' frameborder='0'></iframe>";
        var modal = mw.tools.modal.init({
          html:frame,
          width:430,
          height:365,
          callback:false,
          title:title,
          name:name
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
       var xdiez =hash.replace("#", "");
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

mw.random = function(){return Math.floor(Math.random()*(new Date().getTime()));}


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



mw.files = {
    drag_from_pc:function(){
        $(".element, .element>*").each(function(){
            var el = $(this);
            this.addEventListener('dragover', function(event){
                event.stopPropagation();
                event.preventDefault();
                event.dataTransfer.dropEffect = 'copy';
            }, false);
            this.addEventListener('drop', function(event){
                event.stopPropagation();
                event.preventDefault();
                var files = event.dataTransfer.files;
                $.each(files, function(){
                    var reader = new FileReader();
                    var file_data = mw.files.processer(reader, this);
                });
            }, false);
        });
    },
    processer : function(reader, file){
          var toreturn = {}
          if(file.type.contains("image")){
             toreturn.type = "image";
             reader.onload = function(e) {
               toreturn.result = e.target.result;
               return toreturn;
  		     }
             reader.readAsDataURL(file);
          }
          else if(file.type.contains("txt") ||
                  file.type.contains("rtf") ||
                  file.type.contains("html") ||
                  file.type.contains("html")){

            toreturn.type = "text";
            reader.onload = function(e) {
               toreturn.result = e.target.result;
               return toreturn;
  		    }
            reader.readAsText(file,"UTF-8");
          }
    },
    browser:function(obj){
        var u = document.createElement('input');
        u.type = 'file';
        u.className = 'semi_hidden';
        document.body.appendChild();
        $(u).click();
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



/* A Cool HTML5 WYSIWYG Editor */



mw.wysiwyg = {
    _external:function(){  //global element for handelig the iframe tools
      var external = document.createElement('div');
      external.className='wysiwyg_external';
      document.body.appendChild(external);
      return external;
    },
    execCommand:function(a,b,c){
      if(mw.wysiwyg.isThereEditableContent){
         $.browser.mozilla?document.designMode = 'on':'';  // for firefox
         document.execCommand(a,b,c);
         $.browser.mozilla?document.designMode = 'off':'';
      }
    },
    isThereEditableContent:false,
    selection:'',
    _do:function(what){
        mw.wysiwyg.execCommand(what);
    },
    save_selected_element:function(){
        $("#mw-text-editor").addClass("editor_hover");
    },
    deselect_selected_element:function(){
        $("#mw-text-editor").removeClass("editor_hover");
    },
    prepare:function(){

      mw.wysiwyg.external = mw.wysiwyg._external();
      mw.wysiwyg.checker = mw.wysiwyg._checker();
      $("#mw-text-editor").bind("mousedown mouseup click", function(event){event.preventDefault()});
      var items = $(".element").not(".module");
      items.bind("mouseup",function(){



        if(!mw.isDrag && $(".module.element-active").length==0){
          $(this).attr('contenteditable','true');
          $(this).find('.mw-sorthandle').attr('contenteditable','false');
          this.focus();
          mw.wysiwyg.isThereEditableContent=true;
          mw.drag.fix_onChange_editable_elements(this);
          $(this).unbind("change");
          $(this).bind("change", function(event){
            mw.drag.fix_placeholders(true , $(this));
          });
        }
        if($(".module.element-active").length>0){
          $(".module.element-active").parents(".element").attr("contenteditable", false);
          this.blur();
          mw.wysiwyg.isThereEditableContent=false;
        }
      });
      items.blur(function(){
           if($(".editor_hover").length==0){
              $(this).attr('contenteditable','false');
              mw.wysiwyg.isThereEditableContent=false;
           }
      });
      $(".mw_editor").hover(function(){$(this).addClass("editor_hover")}, function(){$(this).removeClass("editor_hover")});
    },
    init:function(){
      var mw_editor_btns = $(".mw_editor_btn");
      mw_editor_btns.mousedown(function(event){
          var command = this.dataset!=undefined?this.dataset.command:this.getAttribute('data-command');
          if(!command.contains('custom-')){
             mw.wysiwyg._do(command);
          }
          else{
            var name = command.replace('custom-', "");
            mw.wysiwyg[name]();
          }
          $(this).addClass("mw_editor_btn_mousedown");
          mw.wysiwyg.check_selection();
          event.preventDefault();
      });
      mw_editor_btns.mouseup(function(){
           $(this).removeClass("mw_editor_btn_mousedown");
      });
      mw_editor_btns.hover(function(){
        $(this).addClass("mw_editor_btn_hover");
      }, function(){
        $(this).removeClass("mw_editor_btn_hover");
      });
    },
    applier:function(tag, classname, style_object){
      var range = window.getSelection().getRangeAt(0);
      var selectionContents = range.extractContents();
      var el = document.createElement(tag);
      el.className = classname;
      style_object!=undefined?$(el).css(style_object):'';
      el.appendChild(selectionContents);
      range.insertNode(el);
      return el;
    },
    external_tool:function(el, url){
        var el = $(el).eq(0);
        var offset = el.offset();
        $(mw.wysiwyg.external).css({
          top: offset.top - $(window).scrollTop() + el.height(),
          left:offset.left
        });
        $(mw.wysiwyg.external).html("<iframe src='" + url + "' scrolling='no' frameborder='0' />");
    },
    createelement : function(){
       var el = mw.wysiwyg.applier('div', 'mw_applier element');
       mw.drag.init(el);
       mw.drag.fix_handles();
    },
    fontcolorpicker:function(){
        var el = ".mw_editor_font_color";
        mw.wysiwyg.external_tool(el, mw.external_tool('color_picker') + "#fontColor");
        $(mw.wysiwyg.external).find("iframe").width(360).height(180);
    },
    fontbgcolorpicker:function(){
        var el = ".mw_editor_font_background_color";
        mw.wysiwyg.external_tool(el, mw.external_tool('color_picker') + "#fontbg");
        $(mw.wysiwyg.external).find("iframe").width(360).height(180);
    },
    fontColor:function(color){
       if(mw.wysiwyg.isThereEditableContent){
         mw.wysiwyg.execCommand('forecolor', null, color);
       }
    },
    fontbg:function(color){
       if(mw.wysiwyg.isThereEditableContent){
         mw.wysiwyg.execCommand('backcolor', null, "#"+color);
       }
    },
    fontFamily:function(font_name){
       if(mw.wysiwyg.isThereEditableContent){
         mw.wysiwyg.execCommand('fontname', null, font_name);
       }
    },
    fontSize:function(px){
        var obj = {
          fontSize:px+'px'
        }
        //var el = mw.wysiwyg.applier('span', 'mw_applier', obj);
        mw.wysiwyg.execCommand('fontsize', null, px);
    },
    does_selection_has:function(range,tagname){
      try{
        return $(range.commonAncestorContainer).parents(tagname).length>0 || range.commonAncestorContainer.getElementsByTagName(tagname).length>0;
      }catch(err){return false}
    },
    get_format:function(range){
       try{
        return $(range.commonAncestorContainer).parents("p,h1,h2,h3,h4,h5,h6")[0].tagName.toLowerCase();
      }catch(err){return false}
    },
    get_list:function(range){
       try{
        return $(range.commonAncestorContainer).parents("ul,ol")[0].tagName.toLowerCase();
      }catch(err){return false}
    },
    get_size:function(range){
      try{
        var font_size = $(range.commonAncestorContainer).parents("font").attr("size");
        return font_size!=undefined?font_size:'-1';
      }catch(err){return false}
    },
    get_font_family:function(range){
       try{
        return $(range.commonAncestorContainer).parent().css("fontFamily");
       }catch(err){return false}
    },
    _checker:function(){
        var checker = document.createElement('div');
        checker.className='wysiwyg_checker';
        document.body.appendChild(checker);
        return checker;
    },
    get_alignment:function(range){
        try{
          return  $(range.commonAncestorContainer).parent().css("textAlign");
        }catch(err){return false}
    },
    check_selection:function(){
       var selection = window.getSelection();
       if(mw.wysiwyg.isThereEditableContent && selection.rangeCount>0){
           var range = selection.getRangeAt(0);
           var selection_clone = range.cloneContents();
           $(mw.wysiwyg.checker).empty().append(selection_clone);
           var checker_tags = $(mw.wysiwyg.checker).find("*");
           wys_is_bold = false;
           wys_is_italic = false;
           wys_is_underlined = false;
           if(checker_tags.length==0){
             wys_is_bold = mw.wysiwyg.does_selection_has(range, 'b') || mw.wysiwyg.does_selection_has(range, 'b');
             wys_is_italic = mw.wysiwyg.does_selection_has(range, 'i') || mw.wysiwyg.does_selection_has(range, 'em');
             wys_is_underlined = mw.wysiwyg.does_selection_has(range, 'u');
             wys_is_link = mw.wysiwyg.does_selection_has(range, 'a');
            }
            else{
               checker_tags.each(function(){
                  var tagname = this.tagName.toLowerCase();
                  if(tagname=='b' || tagname =='strong'){
                       wys_is_bold = true;
                  }
                  if(tagname=='i' || tagname =='em'){
                       wys_is_italic = true;
                  }
                  if(tagname=='u'){
                       wys_is_underlined = true;
                  }
                  if(tagname=='a'){
                       wys_is_link = true;
                  }
               });
           }
          wys_is_bold ? $(".mw_editor_bold").addClass("mw_editor_btn_active") : $(".mw_editor_bold").removeClass("mw_editor_btn_active");
          wys_is_italic ? $(".mw_editor_italic").addClass("mw_editor_btn_active") : $(".mw_editor_italic").removeClass("mw_editor_btn_active");
          wys_is_underlined ? $(".mw_editor_underline").addClass("mw_editor_btn_active") : $(".mw_editor_underline").removeClass("mw_editor_btn_active");
          wys_is_link ? $(".mw_editor_link").addClass("mw_editor_btn_active") : $(".mw_editor_link").removeClass("mw_editor_btn_active");

          var format = mw.wysiwyg.get_format(range);
          var font_size = mw.wysiwyg.get_size(range);
          var font_family = mw.wysiwyg.get_font_family(range);

          $(".mw_dropdown_action_format").setDropdownValue(format);
          $(".mw_dropdown_action_font_size").setDropdownValue(font_size);
          $(".mw_dropdown_action_font_family").setDropdownValue(font_family);

          var the_list = mw.wysiwyg.get_list(range);
          if(the_list=='ul'){
            $(".mw_editor_ul").addClass("mw_editor_btn_active");
            $(".mw_editor_ol").removeClass("mw_editor_btn_active");
          }
          else if(the_list=='ol'){
            $(".mw_editor_ol").addClass("mw_editor_btn_active");
            $(".mw_editor_ul").removeClass("mw_editor_btn_active");
          }
          else{
             $(".mw_editor_ol").removeClass("mw_editor_btn_active");
             $(".mw_editor_ul").removeClass("mw_editor_btn_active");
          }

          var align = mw.wysiwyg.get_alignment(range);
          $(".mw_editor_alignment").removeClass("mw_editor_btn_active");
          if(align.contains("right")) {$(".mw_editor_justifyright").addClass("mw_editor_btn_active");}
          else if(align.contains("center")) {$(".mw_editor_justifycenter").addClass("mw_editor_btn_active");}
          else if(align.contains("justify")) {$(".mw_editor_justifyfull").addClass("mw_editor_btn_active");}
          else {$(".mw_editor_justifyleft").addClass("mw_editor_btn_active");}

      }
    },
    popup:function(url, title){

    },
    link:function(){
        if($("#mw_rte_link").length>0){
           $("#mw_rte_link").remove();
        }
        else{
          var modal =  mw.tools.modal.rte_tool("rte_link_editor", "Add/Edit LInk", 'mw_rte_link');
        }
    },
    image:function(){
        if($("#mw_rte_image").length>0){
           $("#mw_rte_image").remove();
        }
        else{
          mw.wysiwyg.save_selection();
          var modal = mw.tools.modal.rte_tool("rte_image_editor", "Upload Picture", 'mw_rte_image');
        }
    },
    save_selection:function(){
        var selection = window.getSelection();
        var range =  selection.getRangeAt(0);
        mw.wysiwyg.selection = {
          sel:selection,
          range:range,
          element:$('[contenteditable="true"]').eq(0)
        }
    },
    restore_selection:function(){
        mw.wysiwyg.selection.element.attr("contenteditable", "true");
        mw.wysiwyg.selection.element.focus();
        mw.wysiwyg.selection.sel.removeAllRanges()
        mw.wysiwyg.selection.sel.addRange(mw.wysiwyg.selection.range);
    },
    format:function(command){
        mw.wysiwyg.execCommand('FormatBlock', false, '<' + command + '>');
    }
}





mw.disable_selection = function(element){
    var el = element || ".module";
    var el = $(el, ".edit").not(".unselectable");

    el.attr("unselectable", "on");
    el.addClass("unselectable");
    el.bind("selectstart", function(event){
      event.preventDefault();
      return false;
    });
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