
mwd.body.className = mwd.body.className + " mw-live-edit";


mw.designTool = {
  position:function(rel){
    var rel = rel || mw.$("#design_bnav");
    var ww = $(window).width();
    var wh = $(window).height();

    var off = rel.offset();
    var w = rel.width();
    var h = rel.height();

    var sumWidth = off.left + w;
    var sumHeight = off.top + h;

    sumWidth > ww ? rel.css('left', ww-w-20) : '';
    sumHeight > wh ? rel.css('top', wh-h-20) : '';
  }
}




$(window).load(function(){


mw.$(".mw_dropdown_type_navigation a").each(function(){
  var el = $(this);
  var li = el.parent();
  el.attr("href", "javascript:;");
  var val = li.dataset("category-id");
  li.attr("value", val);
});

mw.$("#module_category_selector").change(function(){
    var val = $(this).getDropdownValue();

    if(val=='all'){
        mw.$(".list-modules li").show();
        return false;
    }
    (val!=-1&&val!="-1") ? mw.tools.toolbar_sorter(Modules_List_modules, val):'';
});
mw.$("#elements_category_selector").change(function(){
    var val = $(this).getDropdownValue();

    if(val=='all'){
        mw.$(".list-elements li").show();
        return false;
    }
    (val!=-1&&val!="-1") ? mw.tools.toolbar_sorter(Modules_List_elements, val):'';
});

mw.$("#dd_module_search, #dd_elements_search").bind("keyup paste", function(event){
       var val = this.value;


       var a = this.parentNode.querySelector(".dd_custom a")
       var li = a.parentNode;
       if(val!=""){
             $(li).show();
             //$(li).attr("value", val);
             a.innerHTML = val;
       }
       else{
          $(li).hide();
       }

       $(mw.tools.firstParentWithClass(this, 'mw_dropdown')).setDropdownValue(-1, false);


       var obj = this.id == 'dd_module_search'?Modules_List_modules:Modules_List_elements;
       mw.tools.toolbar_searh(obj, val);


       event.preventDefault();
       event.stopPropagation();
       return false;

});


mw.$("#module_category_selector .dd_custom a").mousedown(function(e){
   mw.tools.toolbar_searh(Modules_List_modules, $(this).html());
   mw.tools.firstParentWithClass(this, 'mw_dropdown').querySelector('.mw_dropdown_val').innerHTML = $(this).html();
   e.preventDefault();
});
mw.$("#elements_category_selector .dd_custom a").mousedown(function(e){
   mw.tools.toolbar_searh(Modules_List_elements, $(this).html());
   mw.tools.firstParentWithClass(this, 'mw_dropdown').querySelector('.mw_dropdown_val').innerHTML = $(this).html();
   e.preventDefault();
});




mw.$("#design_bnav, .mw_ex_tools").addClass(mw.cookie.ui("designtool"));


var design_pos = mw.cookie.ui("designtool_position");

if(design_pos!=""){
    var design_pos = design_pos.split("|");
    mw.$("#design_bnav").css({
      top:design_pos[0]+"px",
      left:design_pos[1]+"px"
    });
}

mw.designTool.position();


mw.$(".mw_ex_tools").click(function(){

  var rel = mw.$($(this).attr("href"));

  rel.toggleClass('active');

  $(this).toggleClass('active');

  mw.cookie.ui("designtool", rel.hasClass("active") ? "active" : "");

  mw.designTool.position(rel);

  return false;
});


mw.$(".ts_main_li").mouseenter(function(){

  var selected_el = mwd.querySelector('.element-current');
  var parentedit = mw.tools.firstParentWithClass(selected_el, 'edit');
  $(parentedit).addClass('changed');

  if(!mw.$("#design_bnav").hasClass('ui-draggable-dragging')){
  $(this).addClass("hovered");
  mw.$(".ts_main_ul .ts_action").invisible();
  mw.$(".ts_main_ul .ts_action").css({left:"100%", top:0});
  var toshow = $(this).find(".ts_action:first");
  toshow.visible();
  toshow.css("top", 0);
  var offset = toshow.offset();
  var width = toshow.outerWidth();
  var height = toshow.outerHeight();
  var window_w = $(window).width();
  var window_h = $(window).height();
  var scroll = $(window).scrollTop();

    toshow.css({
       left:((offset.left+width) < window_w) ? "100%" : -width ,
       top:(offset.top+height-scroll)<window_h ? 0 : -(offset.top+height-scroll-window_h)
    });

 }
});
mw.$(".ts_main_li").mouseleave(function(){
    $(this).removeClass("hovered");
})


mw.$(".ts_main_li .ts_action_item").mouseenter(function(){
  $(this).parent().find(".ts_action").invisible();
  $(this).parent().find(".ts_action").css("left", "100%");
  var toshow = $(this).find(".ts_action:first");
  var offset = toshow.offset();
  if(typeof offset === 'object' && offset !== null){

  var width = toshow.outerWidth();
  var window_w = $(window).width();
  if((offset.left+width) < window_w){
    toshow.css({
       left:"100%",
       visibility:'visible'
    });
  }
  else{
     toshow.css({
       left:-width,
       visibility:'visible'
    });
  }
  }
});

mw.$(".toolbar_bnav").hover(function(){
  $(this).addClass("toolbar_bnav_hover");
}, function(){
  $(this).removeClass("toolbar_bnav_hover");
});


});

mw.$(".ts_action_item").mouseenter(function(){
   var toshow = $(this).find(".ts_action:first");

   toshow.css({
       left:"100%",
       visibility:'visible'
    });
});














mw.image = {
    isResizing:false,
    currentResizing:null,
    resize:{
      create_resizer:function(){
        if(mw.image_resizer==undefined){
          var resizer = document.createElement('div');
          resizer.className = 'mw_image_resizer';
          resizer.innerHTML = '<span onclick="mw.wysiwyg.media(\'#editimage\');" class="image_change">Change</span>';
          document.body.appendChild(resizer);
          mw.image_resizer = resizer;
        }
      },
      prepare:function(){
        mw.image.resize.create_resizer();
        $(mw.image_resizer).resizable({
            handles: "all",
            minWidth: 60,
            minHeight: 60,
            start:function(){
              mw.image.isResizing = true;
                 $(mw.image_resizer).resizable("option", "maxWidth", mw.image.currentResizing.parent().width());
            },
            stop:function(){
              mw.image.isResizing = false;
              mw.drag.fix_placeholders();
            },
            resize:function(){
              var offset = mw.image.currentResizing.offset();
              $(this).css(offset);
            },
            aspectRatio: 16 / 9
        });
      },
      init:function(selector){
        mw.image_resizer == undefined?mw.image.resize.prepare():'';   /*
        mw.$(".element-image").each(function(){
          var img = this.getElementsByTagName('img')[0];
          this.style.width = $(img).width()+'px';
          this.style.height = $(img).height()+'px';
        });     */

        $(window).bind("onImageClick", function(e, el){

         if( !mw.image.isResizing && !mw.isDrag && !mw.settings.resize_started && el.tagName=='IMG'){
             var order = mw.tools.parentsOrder(el, ['edit', 'module']);

             if(!(order.module > -1 && order.edit > order.module) && order.edit>-1){

               var el = $(el);
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
               $(mw.image_resizer).resizable( "option", "alsoResize", el);
               $(mw.image_resizer).resizable( "option", "aspectRatio", width/height);
               mw.image.currentResizing = el;

         }
         }
        })

        }
      },
      linkIt:function(img_object){
        var img_object = img_object || document.querySelector("img.element-current");

        if(img_object.parentNode.tagName === 'A'){
           $(img_object.parentNode).replaceWith(img_object);
        }
        else{
            mw.tools.modal.frame({
              url:"rte_link_editor#image_link",
              title:"Add/Edit Link",
              name:"mw_rte_link",
              width:340,
              height:535
            });
        }
      },
      _isrotating:false,
      rotate:function(img_object, angle){

        if(!mw.image.Rotator){
           mw.image.Rotator = document.createElement('canvas');
           mw.image.Rotator.style.top = '-9999px';
           mw.image.Rotator.style.position = 'absolute';
           mw.image.RotatorContext = mw.image.Rotator.getContext('2d');
           document.body.appendChild(mw.image.Rotator);
        }


        if(!mw.image._isrotating){
          mw.image._isrotating = true;
        var img_object = img_object || document.querySelector("img.element-current");

        mw.image.preload(img_object.src, function(){


        if(!img_object.src.contains("base64")){
          var currDomain = mw.url.getDomain(window.location.href);

          var srcDomain = mw.url.getDomain(img_object.src);

          if(currDomain!==srcDomain){
               mw.tools.alert("This action is allowed for images on the same domain.");
               return false;
          }
        }


            var angle = angle || 90;
            var image = $(this);
            var w = image.width();
            var h = image.height();

            var contextWidth = w
            var contextHeight = h
            var x = 0;
            var y = 0;

             switch(angle){
                  case 90:
                      var contextWidth = h;
                      var contextHeight = w;
                      var y = -h;
                      break;
                  case 180:
                      var x = -w;
                      var y = -h;
                      break;
                  case 270:
                      var contextWidth = h;
                      var contextHeight = w;
                      var x = -w;
                      break;
                  default:
                      var contextWidth = h;
                      var contextHeight = w;
                      var y = -h;
             }

           mw.image.Rotator.setAttribute('width', contextWidth);
  		   mw.image.Rotator.setAttribute('height', contextHeight);
  		   mw.image.RotatorContext.rotate(angle * Math.PI / 180);
  		   mw.image.RotatorContext.drawImage(img_object, x, y);

           var data =  mw.image.Rotator.toDataURL("image/png");
           img_object.src = data;
           mw.image._isrotating = false;
        });
        }
      },
      _dragActivated : false,
      _dragcurrent : null,
      _dragparent : null,
      _dragcursorAt : {x:0,y:0},
      _dragTxt:function(e){
        if(mw.image._dragcurrent!==null){
          mw.image._dragcursorAt.x = e.pageX-mw.image._dragcurrent.offsetLeft;
          mw.image._dragcursorAt.y = e.pageY-mw.image._dragcurrent.offsetTop;
          var x = e.pageX - mw.image._dragparent.offsetLeft - mw.image._dragcurrent.startedX  - mw.image._dragcursorAt.x;
          var y = e.pageY - mw.image._dragparent.offsetTop - mw.image._dragcurrent.startedY  - mw.image._dragcursorAt.y;
          mw.image._dragcurrent.style.top = y + 'px';
          mw.image._dragcurrent.style.left = x + 'px';



        }
      },
      text_object:function(tag, text){
        var el = mwd.createElement(tag);
        el.className = "image_free_text";
        el.innerHTML = text;
        el.style.position = 'relative';
        el.style.display = 'inline-block';
        el.contenteditable = false;
        el.style.top = '0px';
        el.style.left = '40px';
        el.style.color = 'white';
        el.style.textShadow = '0 0 6px black';
        el.style.cursor = 'move';
        el.style.zIndex = '999';
        el.style.height = 'auto';
        el.ondblclick = function(e){
          e.preventDefault();
          mw.wysiwyg.select_all(this);
        }
        return el;
      },
      enterText:function(img_object){
          var img_object = img_object || document.querySelector("img.element-current");
          var image = $(img_object);
          if(!img_object.is_activated){
                img_object.is_activated = true;
                image.removeClass("element");
                image.wrap("<div class='element mw_image_txt'></div>");
                var obj = mw.image.text_object('span', "Lorem ipsum a asd a as asd");
                image.before(obj);
          }
      },
      preload:function(url, callback){
        var img = mwd.createElement('img');
        img.className = 'semi_hidden';
        img.src = url;
        img.onload = function(){
          callback.call(img);
          $(img).remove();
        }
        mwd.body.appendChild(img);
      },
      description:{
        add:function(text){
            var img = document.querySelector("img.element-current");
            img.title = text;
        },
        get:function(){
           return document.querySelector("img.element-current").title;
        },
        init:function(id){
            var area = $(id);
            area.hover(function(){
              area.addClass("desc_area_hover");
            }, function(){
              area.removeClass("desc_area_hover");
            });
            var curr = mw.image.description.get();
            if(!area.hasClass("inited")){
              area.addClass("inited");
              area.bind("keyup change paste", function(){
                var val = $(this).val();
                mw.image.description.add(val);
              });
            }
            area.val(curr);
            area.show();
        }
      }
    }








$.fn.notmouseenter = function() {
  return this.filter(function(){
    var el = $(el);
    var events = el.data("events");
    return (events==undefined || events.mouseover==undefined || events.mouseover[0].origType!='mouseenter');
  });
};

$.fn.notclick = function() {
  return this.filter(function(){
    var el = $(el);
    var events = el.data("events");
    return (events==undefined || events.click==undefined);
  });
};





$.expr[':'].isHidden = function(obj, index, meta, stack){
    return  mw.is.invisible(obj);
};
$.expr[':'].isVisible = function(obj, index, meta, stack){

    return window.getComputedStyle(obj, null).visibility === 'visible';
};











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

    set_pagetab_size();

    mw.on.hashParam("tab", function(){
      mw.tools.sidebar();
      var tab = this;
      if(tab==false){
        mw.url.windowHashParam('tab', 'modules');
        return false;
      }
      mw.$(".mw_toolbar_tab").removeClass("mw_tab_active");
      mw.$("#tab_"+tab).addClass("mw_tab_active");
      mw.$("#mw_tabs li").removeClass("active");
      mw.$("#mw_tabs li#t_"+tab).addClass("active");
      if(tab=='pages'){
        mw.$("html").addClass("mw_pages");
      }
      else{
        mw.$("html").removeClass("mw_pages");
      }
 });




});

mw.toolbar = {
  center_icons:function(){
    mw.$(".list-modules .mw_module_image img").each(function(){
      var Istyle = window.getComputedStyle(this, null);
      var img_height = parseFloat(Istyle.height);
      img_height < 32 ? $(this).css("marginTop", 16 - img_height/2) : '';
    });
  }
}






$(window).load(function(){

    mw.toolbar.center_icons();

    mw.$(".element").keyup(function(event){
        editablePurify(this);
    });

    mw.$(".element").mouseup(function(event){
        mw.wysiwyg.check_selection();
    });
    mw.$(".element").mousedown(function(event){
        mw.$(".mw_editor_btn").removeClass("mw_editor_btn_active");

    });


  //  mw.disable_selection();













  //mw.image.resize.init(".edit img");
  mw.image.resize.init(".element-image");



    mw.$("#live_edit_toolbar_holder").height(mw.$("#live_edit_toolbar").height());

    $(window).bind("scrollstop",function(){
      setTimeout(function(){
      if(mw.isDrag && mw.$(".ui-draggable-dragging").css("position")=='relative'){
        var curr_el = mw.$(".ui-draggable-dragging").css("position", "static");
        var offset = curr_el.offset();
        curr_el.css("position", "relative");
        var scroll_top = $(window).scrollTop();
        curr_el.css({
          top:mw.mouse.y-offset.top+(scroll_top)+30
        });
      }  }, 100);
    });
    $(document.body).mousedown(function(event){

      if(mw.$(".editor_hover").length==0){
        $(mw.wysiwyg.external).empty().css("top", "-9999px");
        mw.wysiwyg.check_selection();
        $(mwd.body).removeClass('hide_selection');
      }

    });


    mw.$("#mw_small_editor").draggable({
        drag:function(){
          mw.SmallEditorIsDragging = true;
        },
        stop:function(){
          mw.SmallEditorIsDragging = false;
        }
    });

    mw.$("#mw-text-editor").mousedown(function(){
      if(mw.$(".mw_editor_btn_hover").length==0){
        mw.mouseDownOnEditor = true;
        $(this).addClass("hover");
      }
    });
    mw.$("#mw-text-editor").mouseup(function(){
        mw.mouseDownOnEditor = false;
        $(this).removeClass("hover");
    });
    mw.$("#mw-text-editor").mouseleave(function(){
        if(mw.mouseDownOnEditor){
            mw.$("#mw_small_editor").visible();
            mw.$("#mw-text-editor").invisible();
            mw.$("#mw-text-editor").removeClass("hover");
        }
    });
    $(document.body).mouseup(function(event){
         mw.target.item = event.target;
         mw.target.tag = event.target.tagName.toLowerCase();
         mw.mouseDownOnEditor = false;
         mw.SmallEditorIsDragging = false;

        if( !mw.image.isResizing &&
             mw.target.tag!='img' &&
             mw.target.item.className!='image_change' && $(mw.image_resizer).hasClass("active")){
           $(mw.image_resizer).removeClass("active");


           //mw.$("#module_design_selector").setDropdownValue("#tb_el_style", true);

        }
    });





   if(mw.hash()==='' || mw.url.getHashParams(mw.hash()).tab===undefined){
     mw.url.windowHashParam("tab", "modules");
   }


   mw.tools.sidebar();

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
                 //$(window).trigger("scrollstop");
               }
           }, 37);
        }
      });
    }
  }


mw.toggle_subpanel = function(){
  this.speed = 200;
  var el = mw.$("#show_hide_sub_panel");
  if(el.hasClass("state-off")){
     el.removeClass("state-off");
     mw.$("#show_hide_sub_panel_slider").animate({left:0}, this.speed);
     mw.$("#show_hide_sub_panel_info").fadeOut(this.speed, function(){
       $(this).css({left:'auto'}).html('Hide').fadeIn(this.speed);
     });
     mw.$(".mw_tab_active").slideDown(this.speed);
     mw.$("#mw_toolbar_nav").slideDown(this.speed);
     $(mwd.body).animate({paddingTop:170});
  }
  else{
    el.addClass("state-off");
    mw.$("#show_hide_sub_panel_slider").animate({left:35}, this.speed);
    mw.$("#show_hide_sub_panel_info").fadeOut(this.speed, function(){
      $(this).css({left:3}).html('Show').fadeIn(this.speed);
    });

    mw.$(".mw_tab_active").slideUp(this.speed);
    mw.$("#mw_toolbar_nav").slideUp(this.speed);
    $(mwd.body).animate({paddingTop:0});
  }
}

set_pagetab_size = function(){
    mw.$("#mw_edit_pages").css({
       width:window.innerWidth,
       height:window.innerHeight-49
     });
}


$(window).resize(function(){
    mw.tools.module_slider.scale();
    mw.tools.toolbar_slider.ctrl_show_hide();
    set_pagetab_size();
    mw.designTool.position();
});




mw.preview = function(){
    var url = mw.url.removeHash(window.location.href);
    var url = mw.url.set_param('preview', true, url);

    window.open(url, '_blank');
    window.focus();

   /*
    mw.tools.modal.frame({
      url:url,
      width:$(window).width(),
      height:$(window).height()
    }); */
}