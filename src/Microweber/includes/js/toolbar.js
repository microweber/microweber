
mwd.body.className = mwd.body.className + " mw-live-edit";

 
mw.designTool = {
  position:function(rel){
    var rel = rel || mw.$("#design_bnav");

	if(rel.length == 0){
		return false;
		
	}
	
	
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
  mw.session.checkPause = true;
  var selected_el = mwd.querySelector('.element-current');
  var parentedit = mw.tools.firstParentWithClass(selected_el, 'edit');
  $(parentedit).addClass('changed');

  if(!mw.$("#design_bnav").hasClass('ui-draggable-dragging')){
  $(this).addClass("hovered");
  mw.$(".ts_main_ul .ts_action").invisible();
  mw.$(".ts_main_ul .ts_action").css({left:"100%", top:0});
  var toshow = $(this).find(".ts_action:first");
  if(toshow.length === 0) return false;
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
    mw.session.checkPause = false;
});


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















PagesFrameSRCSet = false;


$(document).ready(function(){



    mw.wysiwyg.prepare();
    mw.wysiwyg.init();



    mw.on.hashParam("tab", function(){
      mw.tools.sidebar();
      mw.$("#mw_tabs_small .mw-dropdown-list").hide();
      setTimeout(function(){
        mw.$("#mw_tabs_small .mw-dropdown-list").show();
      }, 222);
      var tab = this;
      if(tab==false){
        mw.url.windowHashParam('tab', 'modules');
        mw.$("#mw_small_menu_text").html('Modules');
        return false;
      }
      mw.$("#mw_small_menu_text").html(this);
      mw.$(".mw_toolbar_tab").removeClass("mw_tab_active");
      mw.$("#tab_"+tab).addClass("mw_tab_active");
      mw.$("#mw_tabs li").removeClass("active");
      mw.$("#mw_tabs li#t_"+tab).addClass("active");
 });







 mw.$(".edit a, #mw-toolbar-right a").click(function(){
  var el = this;

  if(!el.isContentEditable){
      if(el.onclick === null){
        if(!(el.href.indexOf("javascript:") === 0 || el.href == '#' || $(el).attr("href").indexOf('#') == 0 || typeof el.attributes['href'] == 'undefined')){

           return mw.beforeleave(this.href);
      }
      }

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



 mw.$("#history_dd").hover(function(){
      $(this).addClass("hover");
    }, function(){
       $(this).removeClass("hover");
    });



    mw.toolbar.center_icons();


  //mw.image.resize.init(".edit img");
  mw.image.resize.init(".element-image");



    //mw.$("#live_edit_toolbar_holder").height(mw.$("#live_edit_toolbar").height());

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

        $(mwd.body).removeClass('hide_selection');
      }

      if(!mw.$("#history_dd").hasClass("hover")){
        $("#historycontainer").hide()
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
            mw.$("#mw_small_editor").visible().draggable({ disabled: false });
            mw.$("#mw-text-editor").invisible() ;
            mw.$("#mw-text-editor").removeClass("hover");
            if(mwd.getElementById('mw_toolbar_nav').style.display == 'none'){
              mwd.body.style.paddingTop = "";
            }
            else{
                mwd.body.style.paddingTop = (mw.toolbar.max - mw.$("#mw-text-editor").height()) + 'px';
            }

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




if(typeof mw.hasDraft === 'object'){
   var html = ""
   + "<div class='hasdraft'>"
      + "<p>Load last Draft?</p>"
      + "<span class='mw-ui-btn mw-ui-btn-small mw-ui-btn-green' onclick='mw.history.load(\""+mw.hasDraft.draft+"\")'>Yes</span>"
      + "<span class='mw-ui-btn mw-ui-btn-small mw-ui-btn-red' onclick='$(this.parentNode).remove();'>No</span>"
   +"</div>";

   mw.$("#mw_tabs_small").after(html);

   setTimeout(function(){
         mw.$(".hasdraft").addClass("active");
   }, 10000);

 }

});





mw.toggle_subpanel = function(){
  var _speed = 200;
  var el = mw.$("#show_hide_sub_panel");
  if(el.hasClass("state-off")){
     el.removeClass("state-off");
     mw.$("#show_hide_sub_panel_slider").animate({left:0}, _speed);
     mw.$("#show_hide_sub_panel_info").fadeOut(_speed, function(){
       $(this).css({left:'auto'}).html(mw.msg.less).fadeIn(_speed);
     });
     mw.$(".mw_tab_active").slideDown(_speed);
     mw.$("#mw_toolbar_nav").slideDown(_speed, function(){
        mw.$("#mw-toolbar-right").css("top", 6);
        mw.$("#show_hide_sub_panel").css("top", 16);
     });

     if(mwd.getElementById('mw-text-editor').style.visibility == 'hidden'){
        $(mwd.body).animate({paddingTop:mw.toolbar.max - mw.$("#mw-text-editor").height()});
     }
     else{
        $(mwd.body).animate({paddingTop:mw.toolbar.max});
     }



     mw.$("#mw-toolbar-right").show();
     mw.$("#editor_save").hide();
  }
  else{
    el.addClass("state-off");
    mw.$("#show_hide_sub_panel_slider").animate({left:35}, _speed);
    mw.$("#show_hide_sub_panel_info").fadeOut(_speed, function(){
      $(this).css({left:3}).html(mw.msg.more).fadeIn(_speed);
    });

    mw.$(".mw_tab_active").slideUp(_speed);
    mw.$("#mw_toolbar_nav").slideUp(_speed, function(){
       mw.$("#mw-toolbar-right").css("top", 0);
       mw.$("#show_hide_sub_panel").css("top", 10);
       mw.$("#editor_save").fadeIn();
    });




    if(mwd.getElementById('mw-text-editor').style.visibility == 'hidden'){
       $(mwd.body).animate({paddingTop:0});
    }
    else{
      $(mwd.body).animate({paddingTop:mw.$("#mw-text-editor").height()});
    }


    mw.$("#mw-toolbar-right").hide();

  }
}




$(window).resize(function(){
    mw.tools.module_slider.scale();
    mw.tools.toolbar_slider.ctrl_show_hide();
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

mw.iphonePreview = function(){
    var url = mw.url.removeHash(window.location.href);
    var url = mw.url.set_param('preview', true, url);


    mw.tools.modal.frame({
      url:url,
      width:382,
      height:802,
      width:320, //originalnoto
      height:568,//originalnoto
      height:592,
      template:'modal-iphone'
    });

    mw.tools.modal.overlay();
}


