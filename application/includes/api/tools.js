



(function() {
    if(typeof jQuery.browser === 'undefined'){
        var matched, browser;
        // Use of jQuery.browser is frowned upon.
        // More details: http://api.jquery.com/jQuery.browser
        // jQuery.uaMatch maintained for back-compat
        jQuery.uaMatch = function( ua ) {
        	ua = ua.toLowerCase();

        	var match = /(chrome)[ \/]([\w.]+)/.exec( ua ) ||
        		/(webkit)[ \/]([\w.]+)/.exec( ua ) ||
        		/(opera)(?:.*version|)[ \/]([\w.]+)/.exec( ua ) ||
        		/(msie) ([\w.]+)/.exec( ua ) ||
        		ua.indexOf("compatible") < 0 && /(mozilla)(?:.*? rv:([\w.]+)|)/.exec( ua ) ||
        		[];

        	return {
        		browser: match[ 1 ] || "",
        		version: match[ 2 ] || "0"
        	};
        };

        matched = jQuery.uaMatch( navigator.userAgent );
        browser = {};

        if ( matched.browser ) {
        	browser[ matched.browser ] = true;
        	browser.version = matched.version;
        }

        // Chrome is Webkit, but Webkit is also Safari.
        if ( browser.chrome ) {
        	browser.webkit = true;
        } else if ( browser.webkit ) {
        	browser.safari = true;
        }

        jQuery.browser = browser;
    }
})();


mw.controllers = {}


mw.simpletabs = function(root){
  var root = root || mwd;
  mw.$(".mw_simple_tabs_nav", root).each(function(){
    if(!$(this).hasClass('activated')){
        $(this).addClass('activated')
        if(!$(this).hasClass('by-hash')){
            var parent = $(mw.tools.firstParentWithClass(this, 'mw_simple_tabs'));
            parent.children(".tab").addClass("semi_hidden");
            parent.children(".tab").eq(0).removeClass("semi_hidden");
            $(this).find("a").eq(0).addClass("active");
            $(this).find("a").click(function(){
                mw.simpletab.set(this);
                return false;
            });
        }
        else{

        }
    }
  });
}

mw.simpletab = {
  set:function(el){
      if(!$(el).hasClass('active')){
        var ul = mw.tools.firstParentWithClass(el, 'mw_simple_tabs_nav');
        var master = mw.tools.firstParentWithClass(ul, 'mw_simple_tabs');
        $(ul.querySelector('.active')).removeClass('active');
        $(el).addClass('active');
        var index = mw.tools.index(el, ul);
        $(master).children('.tab').addClass('semi_hidden');
        $(master).children('.tab').eq(index).removeClass('semi_hidden');
      }
  }
}






mw.external_tool = function(url){
  return !url.contains("/") ? mw.settings.site_url  +  "editor_tools/" + url : url;
}



mw.tools = {

  modal:{
    settings:{
      width:600,
      height:500
    },
    source:function(id, template){
      var template = template || 'mw_modal_default';
      var id = id || "modal_"+mw.random();
      var html = ''
        + '<div class="mw_modal mw_modal_maximized '+template+'" id="'+id+'">'
          + '<div class="mw_modal_toolbar">'
            + '<span class="mw_modal_title"></span>'
            + '<span class="mw_modal_close" onclick="mw.tools.modal.remove(\''+id+'\')">Close</span>'
            //+ '<span class="mw_modal_minimize" onclick="mw.tools.modal.minimax(\''+id+'\');"></span>'
          + '</div>'
          + '<div class="mw_modal_container">'
          + '</div>'
          + '<div class="iframe_fix"></div>'
        + '</div>';
        return {html:html, id:id}
    },
    _init:function(html, width, height, callback, title, name, template, overlay, doc){
        if(typeof name==='string' && $("#"+name).length>0){
            return false;
        }


        var modal = mw.tools.modal.source(name, template);

        var doc = doc || document;

        $(doc.body).append(modal.html);
        var modal_object = $(doc.getElementById(modal.id));

        var container = modal_object.find(".mw_modal_container").eq(0);
        modal_object.width(width).height(height);

        var padding = parseFloat(container.css("paddingTop")) + parseFloat(container.css("paddingBottom"));
        var padding = container.offset().top - modal_object.offset().top;


        container.append(html).height(height-padding);

        modal_object.css({top:($(doc.defaultView).height()/2)-(height/2) - parseFloat(modal_object.css('paddingTop'))/2 ,left:($(doc.defaultView).width()/2)-(width/2)});
        if(typeof $.fn.draggable === 'function'){
            modal_object.show().draggable({
              handle:'.mw_modal_toolbar',
              containment:'window',
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
        }

        var modal_return = {main:modal_object, container:modal_object.find(".mw_modal_container")[0]}
        typeof callback==='function'?callback.call(modal_return):'';
        typeof title==='string'?$(modal_object).find(".mw_modal_title").html(title):'';
        typeof name==='string'?$(modal_object).attr("name", name):'';

        if(overlay==true)mw.tools.modal.overlay(modal.main);

        return modal_return;
    },
    get:function(selector){
      if(mw.$(selector).find(".mw_modal_container").length>0){
        return {
          main:mw.$(selector),
          container: mw.$(selector).find(".mw_modal_container")[0]
        }
      }
      else{return false;}
    },
    init:function(o){
      var o = $.extend({}, mw.tools.modal.settings, o);
      return  mw.tools.modal._init(o.html, o.width, o.height, o.callback, o.title, o.name, o.template, o.overlay, o.doc);
    },
    minimize:function(id, doc){

        var doc = doc || document;
        var modal = mw.$("#"+id);
        var window_h = $(doc.defaultView).height();
        var window_w = $(doc.defaultView).width();
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
        var span = "<span class='mw-ui-btn mw-ui-btn-small' style='line-height:21px;padding:0 10px;position:absolute;right:33px;top: -30px;' onclick='i=this.nextSibling.src;this.nextSibling.src=i;'>reload</span>";
        var frame = "<iframe name='frame-"+obj.name+"' id='frame-"+obj.name+"' style='overflow-x:hidden;overflow-y:auto;' class='mw-modal-frame'  width='"+obj.width+"' height='"+(obj.height-35)+"' src='" + mw.external_tool(obj.url) + "'  frameBorder='0' allowfullscreen></iframe>";
        var modal = mw.tools.modal.init({
          html:frame,
          width:obj.width,
          height:obj.height,
          title:obj.title,
          name:obj.name,
          overlay:obj.overlay,
          template:obj.template
        });
        if(!!modal){
            $(modal.main).addClass("mw_modal_type_iframe");
            $(modal.container).css("overflow", "hidden");
            modal.main[0].querySelector('iframe').contentWindow.thismodal = modal;
            $(modal.main).find("iframe").eq(0).height($(modal.main).find(".mw_modal_container").height());
            modal.main[0].querySelector('iframe').onload = function(){
                typeof obj.callback === 'function' ? obj.callback.call(modal, this) : '';
            }
        }

        return modal;
    },

    remove:function(id){
        if(typeof id == 'object') var id = $(id)[0].id;
        $(document.getElementById(id)).remove();
        //$("div.mw_overlay[rel='"+id+"']").remove();
        $("div.mw_overlay").remove();
    },
    resize:function(modal, w, h, center, doc){
      var doc = doc || document;
      var maxh = $(doc.defaultView).height() - 60;
      var maxw = $(doc.defaultView).width() - 60;


      var w = w<maxw?w:maxw;
      var h = h<maxh?h:maxh;

      var center = typeof center == 'undefined' ? true : center;
      var modal = $(modal);
      var container = modal.find(".mw_modal_container").eq(0);



      if(container.length === 0) { return false; }

      var frame = modal.find(".mw-modal-frame").eq(0);


      var padding = parseFloat(container.css("paddingTop")) + parseFloat(container.css("paddingBottom")) + container.offset().top - modal.offset().top;


      if(!!w){
        modal.width(w);
        container.width(w);
        frame.width(w);
      }
      if(!!h){
        modal.height(h);
        container.height(h-padding);
        frame.height(h-padding);
      }

      if(center == true){mw.tools.modal.center(modal)};
    },
    center:function(modal, only){
        var only = only || 'all';
        var modal = $(modal);
        var h = modal.height();
        var w = modal.width();
        if(only == 'all'){
          modal.css({top:($(window).height()/2)-(h/2),left:($(window).width()/2)-(w/2)});
        }
        else if(only == 'vertical'){
          modal.css({top:($(window).height()/2)-(h/2)});
        }
        else if(only == 'horizontal'){
          modal.css({left:($(window).width()/2)-(w/2)});
        }

    },
    overlay:function(for_who, is_over_modal, doc){
        var doc = doc || document;
        var overlay = doc.createElement('div');
        overlay.className = 'mw_overlay';
        var id = for_who ? $(for_who).attr("id") : 'none';
        $(overlay).attr("rel",id);
        doc.body.appendChild(overlay);
        if(is_over_modal!=undefined){

        }
        return overlay;
    }
  },
  alert:function(text){
    var html = ''
    + '<table class="mw_alert" width="100%" height="140" cellpadding="0" cellspacing="0">'
        + '<tr>'
        + '<td align="center" valign="middle"><div class="mw_alert_holder">'+text+'</div></td>'
        + '</tr>'
        + '<tr>'
        + '<td align="center" height="25"><span class="mw-cancel" onclick="mw.tools.modal.remove(\'mw_alert\');"><b>'+mw.msg.ok+'</b></span></td>'
        + '</tr>'
    + '</table>';
    return  mw.tools.modal.init({
      html:html,
      width:400,
      height:200,
      overlay:false,
      name:"mw_alert",
      template:"mw_modal_basic"
    });
  },
  dropdown:function(root){
    var root = root || mwd.body;

    mw.$(".mw_dropdown .other-action", root).hover(function(){
      $(this).addClass("other-action-hover");
    }, function(){
      $(this).removeClass("other-action-hover");
    });
    mw.$(".mw_dropdown", root).mouseup(function(event){
      if(!mw.tools.hasClass(event.target.className, 'mw_dropdown_fields') && !mw.tools.hasClass(event.target.className, 'dd_search')){
        $(this).toggleClass("active");
        $(".mw_dropdown").not(this).removeClass("active").find(".mw_dropdown_fields").hide();
        if($(this).find(".other-action-hover").length==0){
          var item =  $(this).find(".mw_dropdown_fields");

          if(item.is(":visible")){
              item.hide();
              item.focus();
          }
          else{
              item.show();
              if((item.offset().left + item.find("ul").width()) > $(window).width() ){
                item.css({left:'auto', right:0});
              }
              else{
                item.css({left:0});
              }
              if(event.target.type!='text'){
                 try{this.querySelector("input.dd_search").focus();}catch(e){}
              }
          }
        }
      }
    });
    mw.$(".mw_dropdown", root).hover(function(){
        $(this).addClass("hover");
    }, function(){
        $(this).removeClass("hover");
    });
    mw.$(".mw_dropdown a", root).mousedown(function(event){
      mw.tools.dd_sub_set(this);
      return false;
    });

    if(typeof __dd_activated === 'undefined'){
      __dd_activated = true;
      $(mwd.body).mousedown(function(e){
        if(mw.$('.mw_dropdown.hover').length==0){
           mw.$(".mw_dropdown").removeClass("active");
           mw.$(".mw_dropdown_fields").hide();
        }
      });
    }


    mw.$(".mw_dropdown", root).each(function(){
        if($(this).attr("tabindex") != undefined){

          var el = this;
          $(el).keydown(function(e){

            var w = e.keyCode;

            if(w == 38 || w== 39){
                e.preventDefault();
                e.stopPropagation();
                var val = $(el).getDropdownValue();
                var curr = mw.$("li[value='"+val+"']", el)[0];
                if(curr.previousElementSibling !== null){
                     $(el).setDropdownValue($(curr.previousElementSibling).attr("value"), true);
                }
                else{
                    $(el).setDropdownValue(mw.$("li:last-child", el).attr("value"), true);
                }

            }
            if(w==37 || w==40){
                e.preventDefault();
                e.stopPropagation();
                var val = $(el).getDropdownValue();
                var curr = mw.$("li[value='"+val+"']", el)[0];
                if(curr.nextElementSibling !== null){
                     $(el).setDropdownValue($(curr.nextElementSibling).attr("value"), true);
                }
                else{
                    $(el).setDropdownValue(mw.$("li:first-child", el).attr("value"), true);
                }

            }
          });
        }
    });

  },
  dd_sub_set:function(item){
      var html = $(item).html();
      var value = item.parentNode.getAttribute("value");
      $(mw.tools.firstParentWithClass(item, 'mw_dropdown')).setDropdownValue(value, true);
  },
  module_slider:{
    scale:function(){
      var window_width = $(window).width();
      mw.$(".modules_bar").each(function(){
           $(this).width(window_width-204);
           $(this).find(".modules_bar_slider").width(window_width-220);
      });
    },
    prepare:function(){
      mw.$(".modules_bar").each(function(){
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
    mw.$(".modules_bar").scrollLeft(0);
    for (var item in obj){
        var child_object = obj[item];
        var id = child_object.id;
        var categories = child_object.category.replace(/\s/gi,'').split(',');
        var item = $(document.getElementById(id));
        if(categories.indexOf(value_to_search)!==-1){
           item.show();
        }
        else{
            item.hide();
        }
    }
  },
  toolbar_searh : function(obj, value){
    var value = value.toLowerCase();
    mw.$(".modules_bar").scrollLeft(0);
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

  },
  classNamespaceDelete:function(el_obj, namespace, parent){
    var parent = parent || mwd;
    if(el_obj ==='all'){
      var all = parent.querySelectorAll('.edit *'), i=0, l=all.length;
      for( ;i<l; i++){mw.tools.classNamespaceDelete(all[i], namespace)}
      return;
    }
    var clas = el_obj.className!==undefined ? el_obj.className.split(" ") : '';
    for(var i=0; i<clas.length; i++){
      if(clas[i].indexOf(namespace)==0){
           clas[i] = 'MWDeleteNameSpace';
      }
    }
    el_obj.className = clas.join(" ").replace(/MWDeleteNameSpace/g, "").replace(/\s\s+/g, ' ');
  },
  tree:{
    toggle : function(el, event){
      $(el.parentNode).toggleClass('active');
       var master = mw.tools.firstParentWithClass(el,'mw-tree');
       mw.tools.tree.remember(master);
        if(event.type==='click'){
          event.stopPropagation();
          event.preventDefault();
          return false;
        }
    },
    open:function(el, event){
      $(el.parentNode).addClass('active');
      var master = mw.tools.firstParentWithClass(el,'mw-tree');
      mw.tools.tree.remember(master);
    },
    del : function(id){
        mw.tools.confirm(mw.msg.del, function(){
           $.post(mw.settings.site_url + "api/delete_content", {id:id}, function(data) {
              var todelete =  mw.$(".item_" + id);
               todelete.fadeOut(function(){
                   todelete.remove();
                   mw.reload_module('content/trash');
               });
  		    });
        })
    },
	del_category : function(id){
      mw.tools.confirm('Are you sure you want to delete this?', function(){
         $.post(mw.settings.site_url + "api/delete_category", {id:id}, function(data) {
            var todelete =  mw.$(".item_" + id);
             todelete.fadeOut(function(){
                 todelete.remove();
             });
		 });
      })
    },
    detectType:function(tree_object){
      if(tree_object!==null && typeof tree_object === 'object'){
        return tree_object.querySelector('li input[type="checkbox"], li input[type="radio"]') !==null ? 'selector' : 'controller';
      }
    },
    remember : function(tree){
      var type = mw.tools.tree.detectType(tree);
      if(type==='controller'){
          _remember = "";
          var lis = tree.querySelectorAll("li.active");
          var len = lis.length;
          $.each(lis, function(i){
            i++;
            if(!! this.attributes['data-item-id']){
              var id = this.attributes['data-item-id'].nodeValue;
              _remember = i<len ? _remember + id + "," : _remember + id;
            }

          });
          mw.cookie.ui("tree_"+tree.id, _remember);
      }
    },
    recall : function(tree){
      if(tree!==null){

      var ids = mw.cookie.ui("tree_"+tree.id);
      if(ids!==''){
        var ids = ids.split(",");
        $.each(ids, function(a,b){
          if(tree.querySelector('.item_'+b)){
             tree.querySelector('.item_'+b).className+=' active';
          }
        });
      }
      }
    },
    toggleit : function(el, event, pageid){
       event.stopPropagation();   /*
       if(el.attributes['data-page-id'] !== undefined){
          mw.url.windowHashParam('action', 'showposts:'+pageid);
       }
       else if(el.attributes['data-category-id'] !== undefined){
          mw.url.windowHashParam('action', 'showpostscat:'+pageid);
       }   */
       mw.tools.tree.toggle(el, event);
    },
    openit : function(el, event, pageid){
       event.stopPropagation();
       if(el.attributes['data-page-id'] !== undefined){
          mw.url.windowHashParam('action', 'showposts:'+pageid);
       }
       else if(el.attributes['data-category-id'] !== undefined){
          mw.url.windowHashParam('action', 'showpostscat:'+pageid);
       }
       mw.tools.tree.open(el, event);
    },
    closeAll : function(tree){
        $(tree.querySelectorAll('li')).removeClass('active').removeClass('active-bg');
        mw.tools.tree.remember(tree);
    },
    openAll : function(tree){
       $(tree.querySelectorAll('li')).addClass('active');
       mw.tools.tree.remember(tree);
    },
    checker:function(el){

		var is_checkbox = el.getElementsByTagName('input')[0];
		if(is_checkbox.type != 'checkbox'){
		return false;
		}

        var state = el.getElementsByTagName('input')[0].checked;
        if( state === true){
          mw.tools.foreachParents(el.parentNode, function(loop){
            this.tagName === 'LI' ? this.getElementsByTagName('input')[0].checked=true : '';
            this.tagName === 'DIV' ? mw.tools.stopLoop(loop) : '';
          });
        }
        else{
          var f = el.parentNode.getElementsByTagName('input'), i=0, len = f.length;
          for( ; i<len; i++){
            f[i].checked=false;
          }
        }
    },
    viewChecked:function(tree){
        var all = tree.querySelectorAll('li input'), i=0, len=all.length;
        for ( ; i<len; i++){
          var curr = all[i];
          curr.parentNode.parentNode.style.display =  !curr.checked ? 'none' : '';
        }
    }
  },
  hasClass:function(classname, whattosearch){   //for strings
    if(typeof classname === 'string'){
      return classname.split(' ').indexOf(whattosearch) > -1;
    }
    else{
      return false;
    }
  },
  addClass:function(el, cls){
   if( !mw.tools.hasClass(el.className, cls) ) el.className += (' ' + cls);
  },
  removeClass:function(el, cls){
   if( mw.tools.hasClass(el.className, cls) ) el.className = (el.className + ' ').replace(cls+' ', '').replace(/\s{2,}/g, ' ');
  },
  hasParentsWithClass:function(el, cls){
    var d = {};
    d.toreturn = false;
    mw.tools.foreachParents(el, function(loop){
        if(mw.tools.hasClass(this.className, cls)){
            d.toreturn = true;
            mw.tools.stopLoop(loop);
        }
    });
    return d.toreturn;
  },
  hasParentsWithTag:function(el, tag){
    var d = {};
    d.toreturn = false;
    mw.tools.foreachParents(el, function(loop){
        if(this.nodeName === tag){
            d.toreturn = true;
            mw.tools.stopLoop(loop);
        }
    });
    return d.toreturn;
  },
  loop:{/* Global index for MW loops */},
  stopLoop:function(loop){
    mw.tools.loop[loop] = false;
  },
  foreachParents:function(el, callback){
     if(typeof el === 'undefined') return false;
     if(el === null) return false;
     var index = mw.random();
     mw.tools.loop[index]=true;
     var _curr = el.parentNode;
     var count = -1;
     if(_curr !== null && _curr !== undefined){
       var _tag = _curr.tagName;
       while(_tag !== 'BODY'){
           count++;
           var caller =  callback.call( _curr, index, count);
           var _curr = _curr.parentNode;
           if( caller === false || _curr === null || _curr === undefined || !mw.tools.loop[index]){ delete mw.tools.loop[index]; break }
           var _tag = _curr.tagName;
       }
     }
  },
  foreachChildren:function(el, callback){
     if(typeof el === 'undefined') return false;
     if(el === null) return false;
     var index = mw.random();
     mw.tools.loop[index]=true;
     var _curr = el.firstChild;
     var count = -1;
     if(_curr !== null && _curr !== undefined){
       while(_curr.nextSibling !== null){
           count++;
           var caller =  callback.call( _curr, index, count);
           var _curr = _curr.nextSibling;
           if( caller === false || _curr === null || _curr === undefined || !mw.tools.loop[index]){ delete mw.tools.loop[index]; break }
           var _tag = _curr.tagName;
       }
     }
  },
  hasChildrenWithClass:function(node, cls){
    var g = {}
    g.final = false;
    mw.tools.foreachChildren(node,function(){
        if(mw.tools.hasClass(this.className, cls)){
          g.final = true;
        }
    });
    return g.final;
  },
  parentsOrder:function(node, arr){
    var only_first = [];
    var obj = {}, l = arr.length, i=0;
    for( ; i<l; i++) {obj[arr[i]] = -1;}
    mw.tools.foreachParents(node, function(loop, count){
        var cls = this.className;
        var i=0;
        for( ; i<l; i++) {
           if(mw.tools.hasClass(cls, arr[i]) && only_first.indexOf(arr[i])==-1){
                obj[arr[i]] = count;
                only_first.push(arr[i]);
           }
        }
    });
    return obj;
  },
  firstParentWithClass:function(el,cls){
      _has = false;
      mw.tools.foreachParents(el, function(loop){
         if(mw.tools.hasClass(this.className, cls)){
           _has = this;
           mw.tools.stopLoop(loop);
         }
      });
      return _has;
  },
  lastParentWithClass:function(el,cls){
      _has = false;
      mw.tools.foreachParents(el, function(loop){
         if(mw.tools.hasClass(this.className, cls)){
           _has = this;
         }
      });
      return _has;
  },
  firstParentWithTag:function(el,tag){
      var tag = typeof tag !== 'string'?tag:[tag];
      _has = false;
      mw.tools.foreachParents(el, function(loop){
         if(tag.indexOf(this.nodeName.toLowerCase()) !== -1){
           _has = this;
           mw.tools.stopLoop(loop);
         }
      });
      return _has;
  },
  toggle:function(who, toggler, callback){
    var who = mw.$(who);
    who.toggle();
    who.toggleClass('toggle-active');
    $(toggler).toggleClass('toggler-active');

    mw.is.func(callback) ? callback.call(who) : '';
  },
  memoryToggle:function(toggler){
    if(typeof _MemoryToggleContentID == 'undefined') return false;

    var id = toggler.id;
    var who = $(toggler).dataset('for');

    mw.tools.toggle(who, "#"+id);

    var page =  "page_" + _MemoryToggleContentID;

    var is_active = $(toggler).hasClass('toggler-active');
    if(_MemoryToggleContentID=='0') return false;
    var curr = mw.cookie.ui(page);
    if(curr==""){
        var obj = {}
        obj[id] = is_active;
        mw.cookie.ui(page, obj);
    }
    else{
        curr[id] = is_active;
        mw.cookie.ui(page, curr);
        mw.log(mw.cookie.ui(page))
    }

  },
  memoryToggleRecall:function(){
     if(typeof _MemoryToggleContentID == 'undefined') return false;
     var page =  "page_" + _MemoryToggleContentID;
     var curr = mw.cookie.ui(page);
     if(curr!=""){
        $.each(curr, function(a,b){
            if(b==true){
              var toggler = mw.$("#"+a);
              toggler.addClass('toggler-active');
              var who = toggler.dataset("for");
              $(who).show().addClass('toggle-active');
              var callback = toggler.dataset("callback");
              if(callback != ""){
                Wait(callback, function(){
                    window[callback]();
                });
              }
            }
        });
     }
  },
  confirm:function(question, callback){

    var conf = confirm(question);
    if(conf && typeof callback === 'function'){
      callback.call(window);
    }
    return conf;

  },
  el_switch:function(arr, type){
    if(type==='semi'){
      $(arr).each(function(){
        var el = $(this);
        if(el.hasClass("semi_hidden")){
          el.removeClass("semi_hidden");
        }
        else{el.addClass("semi_hidden");}
      });
    }
    else{
      $(arr).each(function(){
        var el = $(this);
        if(el.css('display')=='none'){
          el.show();
        }
        else{el.hide();}
      });
    }
  },
  sort:function(obj){
    var group = mw.tools.firstParentWithClass(obj.el, 'mw-table-sorting');
	// var parent_mod = mw.tools.firstParentWithClass(obj.el, 'module');

    // Tablicata
    var table = mwd.getElementById(obj.id);

	var parent_mod = mw.tools.firstParentWithClass(table, 'module');

    var others = group.querySelectorAll('li span'), i=0, len = others.length;
    for( ; i<len; i++ ){
        var curr = others[i];
        if(curr !== obj.el){
           //curr.setAttribute('data-state', 0);
           $(curr).removeClass('ASC').removeClass('DESC');
        }
    }
    obj.el.attributes['data-state'] === undefined ? obj.el.setAttribute('data-state', 0) : '';
    var state = obj.el.attributes['data-state'].nodeValue;
    var tosend = {}
    tosend.type = obj.el.attributes['data-sort-type'].nodeValue;
    if(state === '0'){
        tosend.state = 'ASC';
        obj.el.className = 'ASC';
        obj.el.setAttribute('data-state', 'ASC');
    }
    else if(state==='ASC'){
        tosend.state = 'DESC';
        obj.el.className = 'DESC';
        obj.el.setAttribute('data-state', 'DESC');
    }
    else if(state==='DESC'){
         tosend.state = 'ASC';
         obj.el.className = 'ASC';
         obj.el.setAttribute('data-state', 'ASC');
    }
    else{
       tosend.state = 'ASC';
       obj.el.className = 'ASC';
       obj.el.setAttribute('data-state', 'ASC');
    }

	if(parent_mod !== undefined){
		 parent_mod.setAttribute('data-order', tosend.type +' '+ tosend.state);
	     mw.reload_module(parent_mod);
	}
  },
  focus_on:function(el){
    if(!$(el).hasClass('mw-focus')){
      mw.$(".mw-focus").each(function(){
         this!==el ? $(this).removeClass('mw-focus') : '';
      });
      $(el).addClass('mw-focus');
    }
  },
  scrollTo:function(el, callback, parent){
    if( $(el).length === 0) { return false; }
    mw.$('html,body').animate({scrollTop:$(el).offset().top}, function(){
        typeof callback === 'function' ? callback.call(el) : '';
    });
  },
  accordion:function(el, callback){
    var speed = 200;
    var container = el.querySelector('.mw-accordion-content');
    if(container===null) return false;
    var is_hidden = mw.CSSParser(container).get.display() == 'none';

    if(!$(container).is(":animated")){
        if(is_hidden){
            $(container).slideDown(speed, function(){
              $(el).addClass('active');
              typeof callback === 'function' ? callback.call(el, 'visible') : '';
            });
        }
        else{
          $(container).slideUp(speed, function(){
              $(el).removeClass('active');
              typeof callback === 'function' ? callback.call(el, 'hidden') : '';
          });
        }
    }
  },
  index:function(el, parent, selector){
    var selector = selector || el.tagName.toLowerCase();
    var parent = parent || el.parentNode;
    var all = parent.querySelectorAll(selector), i=0, l=all.length;
    var all = mw.$(selector, parent), i=0, l=all.length;
    for ( ; i<l; i++){
        if( el===all[i] ) return i;
    }
  },
  simpleRotator:function(rotator){
    if(typeof rotator !== 'undefined'){
      if(!$(rotator).hasClass('activated')){
        $(rotator).addClass('activated')
        var all = mw.$('> *', rotator);
        var l = all.length;
        var w = 2 * (l * ($(all[0]).outerWidth(true)));
        $(all).addClass('mw-simple-rotator-item');
        $(rotator).width(w);
        rotator.go = function(where, callback){
            $(rotator).dataset('state', where);
            var item = $(rotator).children()[where];
            var item_left = $(item).offset().left;
            var rleft =  $(rotator).offset().left;
            $(rotator).animate({left:-(item_left-rleft)}, function(){
              if(typeof callback === 'function'){
                callback.call(rotator);
              }
            });
        }
        rotator.state = function(){return parseFloat($(rotator).dataset('state'))}
      }
    }
    return rotator;
  },
  sidebar:function(){
    if(mw.$("#mw_edit_page_left").length > 0){
      if(mw.$("#mw-admin-container").length > 0){
          $("#mw-admin-container").addClass('has_sidebar');
          $("#mw-admin-container").css('backgroundPosition',  '-'+(500-$("#mw_edit_page_left").width()) + 'px 0');
      }
      else if(mw.$("#mw_edit_pages").length > 0){
        $("#mw_edit_pages_content").addClass('has_sidebar');
        $("#mw_edit_pages_content").css('backgroundPosition',  '-'+(500-$("#mw_edit_page_left").width()) + 'px 0');
      }
    }
  },
  highlight:function(el, color, speed1, speed2){
    if(typeof el === 'undefined') return false;
    $(el).stop();
    var color = color || '#D8FFC4';
    var speed1 = speed1 || 777;
    var speed2 = speed2 || 777;
    var curr = window.getComputedStyle(el, null).backgroundColor;
    if(curr == 'transparent'){
      var curr = '#ffffff';
    }
    $(el).css('boxShadow', '0 0 10px #ccc');
    $(el).animate({ backgroundColor: color }, speed1, function(){
        $(el).animate({ backgroundColor: curr }, speed2, function(){
          $(el).css('backgroundColor', '');
          $(el).css('boxShadow', '');
        })
    });
  },
  highlightStop:function(el){
     $(el).stop();
     $(el).css('backgroundColor', '');
     $(el).css('boxShadow', '');
  },
  toCamelCase:function(str){
     return str.replace(/(\-[a-z])/g, function(a){return a.toUpperCase().replace('-','');})
  },
  multihover:function(){
    var l = arguments.length, i=1;
    var type = arguments[0].type;
    var check =  ( type ==='mouseover' ||  type ==='mouseenter');
    for( ; i<l; i++) {
      check  ?  $(arguments[i]).addClass('hovered')  :  $(arguments[i]).removeClass('hovered') ;
    }
  },
  search:function(string, selector, callback){
    var string = string.toLowerCase();

    if(typeof selector === 'object'){
       var items = selector;
    }
    else{
       var items = mwd.querySelectorAll(selector)
    }

    var i=0, l=items.length;
    for( ; i<l; i++){
      items[i].textContent.toLowerCase().contains(string) ? callback.call(items[i], true) : callback.call(items[i], false);
    }
  },
  tag:function(obj){
    var o = this;
    var itemsWrapper = obj.itemsWrapper;

    if (itemsWrapper == null) return false;
    var items = obj.itemsWrapper.querySelectorAll(obj.items);
    var tagMethod = obj.method || 'parse';

    var tagholder = $(obj.tagholder);
    var field = mw.$('input[type="text"]', tagholder[0]);

    var def =  field.dataset('default');
    o.createTag = function(el){
        var span_holder = mwd.createElement('span');
        var span_x = mwd.createElement('span');

        span_holder.className = 'mw-ui-btn mw-ui-btn-small';
        span_holder.id = 'id-'+el.value;
        span_holder.innerHTML = el.parentNode.textContent;

        var icon = mwd.createElement('i');
        icon.className = mw.tools.firstParentWithTag(el, 'li').className;

        $(span_holder).prepend(icon);

        span_holder.onclick = function(e){


            if(e.target.className != 'mw-ui-btnclose'){
                mw.tools.highlight(mw.$('item_'+el.value)[0],'green');

                var input = itemsWrapper.querySelector(".item_"+el.value + " input");

                if(input !== null){

                  mw.tools.foreachParents(input, function(loop){

                    if(mw.tools.hasClass(this.className, 'mw-ui-category-selector')){
                       mw.tools.stopLoop(loop);
                    }
                    if(this.tagName=='LI'){
                      $(this).addClass('active');
                    }
                  });



                  var label = itemsWrapper.querySelector(".item_"+el.value + " label");

                  setTimeout(function(){
                      label.scrollIntoView(false);
                      mw.tools.highlightStop(mw.$(".highlighted").removeClass("highlighted"));
                      mw.tools.highlight(label);
                      $(label).addClass("highlighted");
                   }, 55);
                }
            }
        }
        span_x.className = 'mw-ui-btnclose';
        span_x.onclick = function(){
            o.untag(this.parentNode, el);
        }
        span_holder.appendChild(span_x);
        return span_holder;
    }

    o.rend = function(method, el){ // parse and prepend
      var method = method || 'parse';
      if(method === 'parse' || el==='all'){
        var html = [];
        var checks = itemsWrapper.querySelectorAll('input[type="radio"], input[type="checkbox"]');
        $(checks).each(function(){
           if(this.checked == true){
              $(mw.tools.firstParentWithClass(this, 'mw-ui-check')).addClass("active");
              var tag = o.createTag(this);
              html.push(tag);
           }
           else{
              $(mw.tools.firstParentWithClass(this, 'mw-ui-check')).removeClass("active");
           }
        });
        $(tagholder).prepend(html);
      }
      else if(method === 'prepend'){
        var tag = o.createTag(el);
        if($('.mw-ui-btn', tagholder).length==0){
            tagholder.prepend(tag);
        }
        else{
            $('.mw-ui-btn:last', tagholder).after(tag);
        }
      }
    }

    o.untag = function(pill, input){
      $(pill).remove();
      if(!!input) { $(input)[0].checked = false; $(mw.tools.firstParentWithClass($(input)[0], 'mw-ui-check')).removeClass("active");}
      if(typeof obj.onUntag === 'function'){
           obj.onUntag.call(o);
      }
    }

    o.rend(tagMethod, 'all');

    tagholder.click(function(e){
      if(e.target.tagName != 'INPUT'){ field.focus(); }

        itemsWrapper.style.display = 'block';
        if(itemsWrapper.querySelector('input').binded != true){
            itemsWrapper.querySelector('input').binded = true;
            var checks = itemsWrapper.querySelectorAll('input[type="radio"], input[type="checkbox"]');
            $(checks).commuter(function(){
                if(tagMethod === 'prepend'){
                  o.rend(tagMethod, this);
                }
                else{
                  $('.mw-ui-btn', tagholder).remove();
                  o.rend(tagMethod);
                }
                if(typeof obj.onTag === 'function'){
                     obj.onTag.call(o);
                }
                field.val('');
            }, function(){
                 o.untag($("#id-"+this.value, tagholder));
            });

           tagholder.hover(function(){$(this).addClass('mw-tagger-hover')}, function(){$(this).removeClass('mw-tagger-hover')});
           $(itemsWrapper).hover(function(){$(this).addClass('mw-tagger-hover')}, function(){$(this).removeClass('mw-tagger-hover')});
           $(mwd.body).bindMultiple('mousedown', function(){
               if(mw.$(".mw-tagger-hover").length==0){
                   itemsWrapper.style.display = 'none';
                   if(mw.$('.mw-ui-btn', tagholder).length==0){
                      field.val(def);
                   }
                   else{
                      field.val('');
                   }

                   $(items).show();
               }
           });
        }
    });
    field.keyup(function(){
      var val = $(this).val();
      mw.tools.search(val, items, function(found){
        if(found){
            $(this).show();
        }
        else{
           $(this).hide();
        }
      });
    });
    field.focus(function(){
       this.value === def ? this.value = '' : '';
    });
    field.blur(function(){
       if(this.value === '' && mw.$('.mw-ui-btn', tagholder).length == 0){
         this.value = def;
       }
    });

    return this;

  },
  iframeLinksToParent:function(iframe){
    $(iframe).contents().find('a').each(function(){
       var href = this.href;
       if(href.contains(mw.settings.site_url)){
         this.target = '_parent';
       }
    });
  },
  fullscreen:function(el){
      if (el.webkitRequestFullScreen) {
        el.webkitRequestFullScreen();
      } else if(el.mozRequestFullScreen){
        el.mozRequestFullScreen();
      }
  },
  get_filename:function(s) {
    if(s.contains('.')){
      var d = s.lastIndexOf('.');
      return s.substring(s.lastIndexOf('/') + 1, d < 0 ? s.length : d);
    }
    else{
      return undefined;
    }
  },
  is_field:function(obj){
    var t = obj.tagName.toLowerCase();
    if(t=='input' || t=='textarea' || t=='select') return true;
    return false;
  },
  getAttrs : function(el){
    var attrs = el.attributes;
    var obj = {}
    for(var x in attrs){
      var dis = attrs[x];
      obj[dis.nodeName] = dis.nodeValue
    }
    return obj;
  },
  copyAttributes:function(from, to, except){
    var except = except || [];
    var attrs = mw.tools.getAttrs(from);
    if(mw.tools.is_field(from) && mw.tools.is_field(to)) to.value = from.value;
    for(var x in attrs){
       ( $.inArray(x, except) == -1 && x != 'undefined') ? to.setAttribute(x, attrs[x]): '';
    }
  },
  isEmptyObject:function(obj){
     for (var a in obj) {
        if(obj.hasOwnProperty(a)) return false;
     }
     return true;
  },
  objLenght:function(obj){
    var len = 0, x;
    if(obj.constructor === {}.constructor){
        for (var x in obj) {
            len++;
        }
    }
    return len;
  },
  scaleTo:function(selector, w, h){
    var w = w || 800;
    var h = h || 600;

    var is_percent = w.toString().contains("%") ? true:false;
    var item = mw.$(selector);
    if(item.hasClass('mw-scaleto') || w == 'close'){
       item.removeClass('mw-scaleto');
       item.removeAttr('style');
    }
    else{
      item.parent().height(item.height());
      item.addClass('mw-scaleto');
      if(is_percent){
          item.css({
            width:w,
            height:h,
            left:((100-parseFloat(w))/2)+"%",
            top:((100-parseFloat(h))/2)+"%"
          });
      }
      else{
          item.css({
            width:w,
            height:h,
            marginLeft:-w/2,
            marginTop:-h/2
          });
      }
    }
  },
  getFirstEqualFromTwoArrays:function(a,b){
    var ia=0, ib=0, la=a.length, lb=b.length;
    loop:
    for( ; ia<la; ia++){
      var curr = a[ia];
      for( ; ib<lb; ib++){
          if(b[ib]==curr){
            //break +;
            return curr;
          }
      }
    }
  },
  tabGroup : function(obj, master){
    var master = master || mwd.body;
    var active = obj.activeNav || "active";
    mw.$(obj.nav).click(function(){
      if(!$(this).hasClass(active)){
        var i = mw.tools.index(this, master, obj.nav);
        mw.$(obj.nav).removeClass(active);
        $(this).addClass(active);
        mw.$(obj.tabs).hide().eq(i).show();
        if(typeof obj.onclick == 'function'){
            obj.onclick.call(this, mw.$(obj.tabs).eq(i)[0], obj);
        }
      }
      else{
        if(obj.toggle == true){
            $(this).removeClass(active);
            mw.$(obj.tabs).hide();
            if(typeof obj.onclick == 'function'){
                var i = mw.tools.index(this, master, obj.nav);
                obj.onclick.call(this, mw.$(obj.tabs).eq(i)[0], obj);
            }
        }
      }
    });
  },
  has:function(el, what){
    return el.querySelector(what) !== null;
  },
  html_info:function(html){
    if(typeof mw._html_info === 'undefined'){
         mw._html_info = mwd.createElement('div');
         mw._html_info.id = 'mw-html-info';
         mwd.body.appendChild(mw._html_info);
    }
    $(mw._html_info).html(html);
    return mw._html_info;
  },
  image_info:function(a, callback){
    var img = mwd.createElement('img');
    img.className = 'semi_hidden';
    img.src = a.src;
    mwd.body.appendChild(img);

    img.onload = function(){
        callback.call({width:$(img).width(), height: $(img).height()});
        $(img).remove();
    }
  },
  refresh_image:function(node){
    node.src =  mw.url.set_param('refresh_image', mw.random(), node.src);
    return node;
  },
  getDiff : function(_new,_old){
        var diff = {}, x, y;
        for (x in _new){
            if(!x in _old || _old[x]!=_new[x]){
              diff[x] = _new[x];
            }
        }
        for (y in _old){
            if(typeof _new[y] === 'undefined'){
              diff[y] = "";
            }
        }
        return diff;
  },
  liveEdit:function(el, textonly, callback){
    if(el.getElementsByTagName('input').length===0){
      var textonly = textonly || true;
      var input = mwd.createElement('input');
      input.type = "text";
      input.className = "mw-ui-field";
      input.style.width = $(el).width()+'px';
      if(textonly===true){
         input.value = el.textContent;
         input.onblur = function(){
            $(el).text(input.value)
         }
      }
      else{
         input.value = el.innerHTML;
         input.onblur = function(){
            el.innerHTML = input.value;
         }
      }
      $(el).empty().append(input);
      $(input).focus();
      if(typeof callback === 'function'){
        $(input).change(function(){
            callback.call(this.value);
        });
      }
    }
  },
  objectExtend:function(str, value){
    var arr = str.split("."), l=arr.length, i = 1;
    var t = typeof window[arr[0]] === 'undefined' ? {} : window[arr[0]];
    for( ; i<l; i++){
        t = t[arr[i]] = {};
    }
    window[arr[0]] = t;
  },
  parseHtml: function(html){
    var d = document.implementation.createHTMLDocument("");
    d.body.innerHTML = html;
    return d;
  },
  isEmpty:function(node){
    return ( node.innerHTML.trim() ).length === 0;
  },
  isJSON:function(a){
    if(typeof a === 'object'){
      if(a.constructor === {}.constructor){
        return true;
      }
      else{
        return false;
      }
    }
    else if(typeof a === 'string'){
      try {
          JSON.parse(a);
      }
      catch (e) {
          return false;
      }
      return true;
    }
    else{
      return false;
    }
  },
  toJSON:function(whatever){
    if(typeof whatever === 'object' && mw.tools.isJSON(whatever)){
      return whatever;
    }
    if(typeof whatever === 'string'){
      try {
          var r = JSON.parse(whatever);
      }
      catch (e) {
          var r = {"0":whatever};
      }
      return r;
    }
    if(typeof whatever === 'object' && whatever.constructor === [].constructor){
        var obj = {}, i=0, l=whatever.length;
        for( ; i<l; i++){
          obj[i] = whatever[i];
        }
        return obj;
    }
  },
  iframe_editor:function(area, params){
    var params = params || {};
    var params = json2url(params);
    var area = mw.$(area);
    var frame = mwd.createElement('iframe');
    frame.src = mw.external_tool('wysiwyg?'+params);
    frame.className = 'mw-iframe-editor';
        frame.scrolling = 'no';
        var name =  'mweditor'+mw.random();
        frame.id = name;
        frame.name = name;
        frame.style.backgroundColor = "transparent";
        frame.setAttribute('frameborder', 0);
        frame.setAttribute('allowtransparency', 'true');
        area.hide().after(frame);
    $(frame).load(function(){
        frame.contentWindow.thisframe = frame;
        var cont = $(frame).contents().find("#mw-iframe-editor-area");
        cont[0].contentEditable = true;
        if(area[0].tagName === 'TEXTAREA'){
          cont.html(area[0].value);
        }
        else{
          cont.html(area.html())
        }
    });
    $(frame).bind('change', function(e, val){
      if(area[0].tagName === 'TEXTAREA'){
       area.val(val);
      }
      else{
        area.html(val);
      }
       if(area.hasClass("mw_option_field")){
         area.trigger("change");
       }
    });
    return frame;
  },
  disable : function(el, text, global){
    var text = text || 'Loading...';
    var global = global || false;
    var _el = $(el);
    if(!_el.hasClass("disabled")){
      _el.addClass('disabled');
      if(_el[0].tagName != 'INPUT'){
        _el.dataset("text", _el.html());
        _el.html(text);
      }
      else{
        _el.dataset("text", _el.val());
        _el.val(text);
      }
     if(global) $(mwd.body).addClass("loading");
    }
    return el;
  },
  enable:function(el){
    var _el = $(el);
    var text = _el.dataset("text");
    _el.removeClass("disabled");

    if(_el[0].tagName != 'INPUT'){
        _el.html(text);
      }
      else{
        _el.val(text);
      }
    $(mwd.body).removeClass("loading");
    return el;
  },
  loading:function(el, state){
    var state = typeof state === 'undefined' ? true : state;
    if(state){
      $(el).addClass("mw-loading");
    }
    else{
      $(el).removeClass("mw-loading");
    }
  }
}




Alert = mw.tools.alert;





Wait('$', function(){

  $.fn.getDropdownValue = function() {
    return this.dataset("value");
  };


  $.fn.setDropdownValue = function(val, triggerChange, isCustom, customValueToDisplay) {
     var isCustom = isCustom || false;
     var triggerChange = triggerChange || false;
     var isValidOption = false;
     var el = this;
     if(isCustom){
        var isValidOption = true;
        el.dataset("value", val);
        triggerChange?el.trigger("change"):'';
        if(customValueToDisplay!=false){
           el.find(".mw_dropdown_val").html(customValueToDisplay);
        }
     }
     else{
       el.find("li").each(function(){
           if(this.getAttribute('value')==val){
                el.dataset("value", val);
                var isValidOption = true;
                el.find(".mw_dropdown_val").html(this.getElementsByTagName('a')[0].innerHTML);
                triggerChange?el.trigger("change"):'';
                return false;
           }
       });
     }
     this.dataset("value", val);
  };


mw.datassetSupport = typeof mwd.documentElement.dataset !== 'undefined';

$.fn.dataset = function(dataset, val){
  var el = this[0];
  if(el === undefined) return false;
  var _dataset = !dataset.contains('-') ? dataset : mw.tools.toCamelCase(dataset);
  if(!val){
     var dataset = mw.datassetSupport ? el.dataset[_dataset] : $(el).attr("data-"+dataset);
     return dataset !== undefined ? dataset : "";
  }
  else{
    mw.datassetSupport ? el.dataset[_dataset] = val :  $(el).attr("data-"+dataset, val);
    return $(el);
  }
}


$.fn.commuter = function(a,b) {
  if(a===undefined){return false}
  var b = b || function(){};
  return this.each(function(){
    if((this.type==='checkbox'  || this.type==='radio') && !$(this).hasClass("cmactivated") ){
      $(this).addClass("cmactivated");
      $(this).bind("change", function(){
        this.checked === true ? a.call(this) : b.call(this);
      });
    }
  });
};

});


mw.cookie = {
  get:function(name){
      var cookies=document.cookie.split(";"), i=0, l = cookies.length;
      for ( ; i<l; i++){
        var x=cookies[i].substr(0,cookies[i].indexOf("="));
        var y=cookies[i].substr(cookies[i].indexOf("=")+1);
        var x=x.replace(/^\s+|\s+$/g,"");
        if (x==name){
          return unescape(y);
        }
      }
  },
  set:function( name, value, expires, path, domain, secure ){
    var now = new Date();
    now.setTime( now.getTime() );
    if ( expires ){
        var expires = expires * 1000 * 60 * 60 * 24;
    }
    var expires_date = new Date( now.getTime() + (expires) );
    document.cookie = name + "=" +escape( value ) + ( ( expires ) ? ";expires=" + expires_date.toGMTString() : "" ) + ( ( path ) ? ";path=" + path : ";path=/" ) +  ( ( domain ) ? ";domain=" + domain : "" ) +  ( ( secure ) ? ";secure" : "" );
  },
  ui:function(a,b){
    var mwui = mw.cookie.get("mwui");
    var mwui = (!mwui || mwui=='') ? {} : $.parseJSON(mwui);
    if(typeof a === 'undefined'){return mwui}
    if(typeof b === 'undefined'){return mwui[a]!==undefined?mwui[a]:""}
    else{
        mwui[a] = b;
        var tostring = JSON.stringify(mwui);
        mw.cookie.set("mwui", tostring, false, "/");
        if(typeof mw.cookie.uievents[a] !== 'undefined'){
          var funcs = mw.cookie.uievents[a], l=funcs.length, i=0;
          for(; i<l; i++){
              mw.cookie.uievents[a][i].call(b);
          }
        }
    }
  },
  uievents:{},
  onchange:function(name, func){
    if(typeof mw.cookie.uievents[name] === 'undefined'){
         mw.cookie.uievents[name] = [func];
    }
    else{
        mw.cookie.uievents[name].push(func);
    }
  }
}

mw.recommend = {
  get:function(){
    var cookie = mw.cookie.get("recommend");
    if(!cookie){return {}}
    else{
      return $.parseJSON(cookie);
    }
  },
  increase:function(item_name){
    var json  =  mw.recommend.get();
    var curr =  parseFloat(json[item_name]);
    if(isNaN(curr)){
       json[item_name] = 1;
    }
    else{
        json[item_name] += 1;
    }
    var tostring = JSON.stringify(json);
    mw.cookie.set("recommend", tostring, false, "/");
  }
}

String.prototype._exec = function(a,b,c){

  var a = a || "";
  var b = b || "";
  var c = c || "";
  if(!this.contains(".")){
    return window[this](a,b,c);
  }
  else{
    var arr = this.split(".");
    var temp = window[arr[0]];

    var len = arr.length-1, i=1;
    for( ; i<=len; i++){
        var temp = temp[arr[i]];
    }
    return (typeof temp === 'function') ? temp(a,b,c) : temp;
  }
}


String.prototype.toCamelCase = function() {
    return  mw.tools.toCamelCase(this);
};

mw.exec = function(str, a,b,c){
    return str._exec(a,b,c);
}







/*

$(document).ready(function(){
  $(".modules-list.list-elements img").each(function(){
    var file = mw.extras.get_filename(this.src);
    $(this).after("<div><textarea onfocus='this.select()' style='padding:5px;background:#B8CDE2;white-space:nowrap;box-shadow:inset 0 0 2px #000;height:14px;border:0;width:120px;font:11px Arial;resize:none'>"+file+"</textarea></div>");
  });

  $(".mw_module_image, .mw_module_hold").css({
    minWidth:0,
    maxWidth:'none',
    width:'auto'
  })
});

*/


$.fn.datas = function(){
    var attrs = this[0].attributes;
    var toreturn = {}
    for(var item in attrs){
        var attr = attrs[item];
        if(attr.nodeName!==undefined){
            if(attr.nodeName.contains("data-")){
                toreturn[attr.nodeName] = attr.nodeValue;
            }
        }
    }
    return toreturn;
}

mw.switcher = {
  _switch:function(el, callback){
    if($(el).hasClass("mw-switcher-off")){
       mw.switcher.on(el);
       var checked = el.getElementsByTagName('input')[0];
    }
    else{
       mw.switcher.off(el);
       var checked = el.getElementsByTagName('input')[1];
    }
    $(checked).trigger('change', checked);
    if(typeof callback==='function'){
      callback.call(checked);
    }
  },
  on:function(el){
    if(el){
     var _el = $(el);
     _el.removeClass('mw-switcher-off');
     _el.addClass('mw-switcher-on');
     el.getElementsByTagName('input')[0].checked = true;
    }
  },
  off:function(el){
     if(el){
     var _el = $(el);
     _el.addClass('mw-switcher-off');
     _el.removeClass('mw-switcher-on');
     el.getElementsByTagName('input')[1].checked = true;
     }
  }
}




 mw.check = {
   all:function(selector){
    mw.$(selector).find("input[type='checkbox']").each(function(){
       this.checked = true;
    });
   },
   none:function(selector){
      mw.$(selector).find("input[type='checkbox']").each(function(){
       this.checked = false;
    });
   },
   collectChecked:function(parent){
     var arr = [];
     var all = parent.querySelectorAll('input[type="checkbox"]'), i=0, l=all.length;
     for( ; i<l; i++){
        var el = all[i];
        el.checked ? arr.push(el.value) : '';
     }
     return arr;
   }
 }




mw.walker = function(context, callback){   //todo
  var context = mw.is.obj(context) ? context : mwd.body;
  var callback = mw.is.func(context) ? context :  callback;
  var walker = document.createTreeWalker(context, NodeFilter.SHOW_ELEMENT, null, false);
  while (walker.nextNode()){
    callback.call(walker.currentNode);
  }
}
Array.prototype.remove = function(what){
  var i=0, l=this.length;
  for( ; i<l; i++){
    this[i] === what ? this.splice(i, 1) : '';
  }
}







__mwextend = function(el){
      if(el.attributes['data-extended']===undefined){
          el.setAttribute('data-extended', true);
          el.getModal = function(){
              var modal = mw.tools.firstParentWithClass(el, 'mw_modal');
              if(!!modal){
                  return  {
                       main:modal,
                       container:modal.querySelector(".mw_modal_container")
                  }
              }
              else {return false};
          }
          el.attr = function(name, value){
            if(value===undefined){
              return el.attributes[name] !== undefined ? el.attributes[name].nodeValue : undefined;
            }
            else{
              el.setAttribute(name, value);
              return el;
            }
          }
          el.addClass = function(cls){
            return mw.tools.addClass(el, cls)
          }
          el.removeClass = function(cls){
            return mw.tools.removeClass(el, cls)
          }
      }
    return el;
}


mw.extend = function(el){
    return __mwextend(el);
}

$(window).load(function(){
  mw.loaded = true;
  mwd.body.className+=' loaded';
  mw.tools.removeClass(mwd.body, 'loading');


  mw.$('div.mw-ui-field').click(function(e){
    if(e.target.type!= 'text'){
       try{this.querySelector('input[type="text"]').focus();}
       catch(e){}
    }
 });




});
















mw._dump = function(obj){
  var obj = obj || mw;
  var html = '<ol class="mw-dump-list">'
  $.each(obj, function(a,b){
    if(typeof b==='function'){
      var c = ''+b+'';
      var c = c.split(')')[0];
      var c = '<i>' + c + ')</i>';
    }
    else if(typeof b==='object'){
       var c = '<a href="javascript:;" onclick="mw.tools.modal.init({html: \'<h2>mw.'+a+'</h2>\' + mw._dump(mw.'+a+')});"> + Object</a>';
    }
    else{
      var c = b.toString()
    }
    html=html+'<li>' + a + ' : ' + c + '</li>';
  });
  html=html+ '</ol>';
  return html;
}


mw.dump = function(){

    mw.tools.modal.init({
      html: mw._dump(),
      width:800
    });
}

mw.notification = {

   msg:function(data, timeout, alert){
        var timeout = timeout || 1000;
        var alert = alert || false;
        if(data != undefined){
            if(data.success != undefined ){
               if(!alert){
                 mw.notification.success(data.success, timeout);
               }
               else{
                 Alert (data.success);
               }

            }
            if(data.error != undefined ){
               mw.notification.error(data.error, timeout);
            }
            if(data.warning != undefined ){
               mw.notification.warning(data.warning, timeout);
            }
         }
    },

    build:function(type, text){
        var div = mwd.createElement('div');
        div.className = 'mw-notification mw-'+type;
        div.innerHTML = '<div>'+text+'</div>'
        return div;
    },
    append:function(type, text, timeout){
        var timeout = timeout || 1000;

        var div = mw.notification.build(type, text);
        if(typeof mw.notification._holder === 'undefined'){
           mw.notification._holder = mwd.createElement('div');
           mw.notification._holder.id = 'mw-notifications-holder';
           mwd.body.appendChild(mw.notification._holder);
        }
        mw.notification._holder.appendChild(div);
        var w = $(div).outerWidth();
        $(div).css("marginLeft", -(w/2));
        setTimeout(function(){
           div.style.opacity = 0;
           setTimeout(function(){
             $(div).remove();
           }, 1000);
        }, timeout);
    },
    success:function(text, timeout){
      var timeout = timeout || 1000;
      mw.notification.append('success', text, timeout);
    },
    error:function(text, timeout){
      var timeout = timeout || 1000;
      mw.notification.append('error', text, timeout);
    },
    warning:function(text, timeout){
      var timeout = timeout || 1000;
      mw.notification.append('warning', text, timeout);
    }
}






  $.fn.visible = function() {
    return this.css("visibility", "visible");
  };
  $.fn.visibilityDefault = function() {
    return this.css("visibility", "");
  };

  $.fn.invisible = function() {
    return this.css("visibility", "hidden");
  };


  mw.which = function(str, arr_obj, func){
     if(arr_obj instanceof Array){
       var l = arr_obj.length, i = 0;
       for( ; i<l; i++){
         if(arr_obj[i] === str){
            func.call(arr_obj[i]);
            return arr_obj[i];
         }
       }
     }
     else{
       for(var i in arr_obj){
         if(i===str){
            func.call(arr_obj[i]);
            return arr_obj[i];
         }
       }
     }
  }












mw.traverse = function(root, h){
  var els = root.querySelectorAll('.edit .element, .edit .module');

  $(els).each(function(){
        _dis = this;
        var el = mwd.createElement('span');
        el.className = 'layer';
        $(el).data("for", this);
        $(el).click(function(){
            $(".element-current").removeClass("element-current");
            $($(el).data("for")).addClass("element-current");
           $(_dis).remove()

        });

        var str = _dis.textContent.slice(0,25);
        el.innerHTML = $(this).hasClass("module")?'Module':'Element';
        el.innerHTML += ' - <small>'+str+'...</span>';
        h.appendChild(el);



  });
}








mw.isDragItem = mw.isBlockLevel = function(obj){
  var items = /^(blockquote|center|dir|fieldset|form|h[1-6]|hr|menu|ul|ol|dl|p|pre|table|div)$/i;
  return items.test(obj.nodeName);
}



mw.help = function(a){

    return mw.tools.modal.frame({
        url:"//microweber.com/help/"+a+".php"
    });
}



mw._JSPrefixes = ['Moz', 'Webkit', 'O', 'ms'];

_Prefixtest = false;



  mw.JSPrefix = function(property){
    ! _Prefixtest ? _Prefixtest = mwd.body.style : '';
    if(_Prefixtest[property]!==undefined){
      return property;
    }
    else{
       var property = property.charAt(0).toUpperCase() + property.slice(1),
           len = mw._JSPrefixes.length,
           i = 0;
       for( ; i<len ;i++){
         if(_Prefixtest[mw._JSPrefixes[i]+property] !== undefined){
            return mw._JSPrefixes[i]+property;
         }
       }
    }
  }





if (typeof document.hidden !== "undefined") {
	_mwdochidden = "hidden";
} else if (typeof document.mozHidden !== "undefined") {
	_mwdochidden = "mozHidden";
} else if (typeof document.msHidden !== "undefined") {
	_mwdochidden = "msHidden";
} else if (typeof document.webkitHidden !== "undefined") {
	_mwdochidden = "webkitHidden";
}


document.isHidden = function(){
  if(typeof _mwdochidden !== 'undefined'){
    return  document[_mwdochidden];
  }
  else{
    return !document.hasFocus();
  }
}




mw.storage = {
        init:function(){
          if(!('localstorage' in window)) return false;
          var mw = localStorage.getItem("mw");
          var mw = mw === null ? (localStorage.setItem("mw", "{}")) : mw;
          this.change("INIT");
          return mw;
        },
        set:function(key, val){
            if(!('localstorage' in window)) return false;
            var curr = JSON.parse(localStorage.getItem("mw"));
            curr[key] = val;
            var a = localStorage.setItem("mw", JSON.stringify(curr))
            mw.storage.change("CALL", key, val);
            return a;
        },
        get:function(key){
           if(!('localstorage' in window)) return false;
            var curr = JSON.parse(localStorage.getItem("mw"));
            return curr[key];
        },
        _keys : {},
        change:function(key, callback, other){
          if(!('localstorage' in window)) return false;
          if(key ==='INIT' ){
              window.addEventListener('storage', function(e){
                  if(e.key==='mw'){
                     var _new = JSON.parse(e.newValue);
                     var _old = JSON.parse(e.oldValue);
                     var diff = mw.tools.getDiff(_new, _old);
                     for(var t in diff){
                       if(t in mw.storage._keys){
                         var i = 0, l = mw.storage._keys[t].length;
                         for( ; i<l; i++){
                             mw.storage._keys[t][i].call(diff[t]);
                         }
                       }
                     }
                  }
              }, false);
          }
          else if(key==='CALL'){
            if(!document.isHidden() && typeof mw.storage._keys[callback] !== 'undefined'){
               var i = 0, l = mw.storage._keys[callback].length;
               for( ; i<l; i++){
                   mw.storage._keys[callback][i].call(other);
               }
            }
          }
          else{
              if(key in mw.storage._keys){
                  mw.storage._keys[key].push(callback);
              }
              else{
                  mw.storage._keys[key] = [callback];
              }
          }
        }
    }






    ///  TESTS
      mw.storage.init();
      mw.storage.change("reload_module", function(){
          if( this!= ''){
              mw.reload_module(this.toString());
          }
      });




mw.requestToReload = function(id){
   mw.storage.set("reload_module", '');
   mw.storage.set("reload_module", id);
}

rcss = function(){
  mw.$("link").each(function(){
    var href = this.href;

    this.href =  mw.url.set_param('v', mw.random(), href);
  });
}

setVisible = function(e){
    if(e.type == 'focus'){
      $(mw.tools.firstParentWithClass(e.target, 'mw-dropdown-content')).visible()
    }
    else if(e.type == 'blur'){
     $(mw.tools.firstParentWithClass(e.target, 'mw-dropdown-content')).visibilityDefault()
    }
}


mw.beforeleave_html = ""
    + "<div class='mw-before-leave-container'>"
      + "<p>Leave page by choosing an option</p>"
      + "<span class='confirm-btn-red'>"+mw.msg.before_leave+"</span>"
      + "<span class='confirm-btn-green' >"+mw.msg.save_and_continue+"</span>"
      + "<div class='vSpace'></div>"
      + "<span class='mw-cancel' onclick='mw.tools.modal.remove(\"modal_beforeleave\")'>"+mw.msg.cancel+"</span>"
    + "</div>";

mw.beforeleave = function(url){
    if(mw.askusertostay){
        if(mwd.getElementById('modal_beforeleave') === null){
            var modal = mw.tools.modal.init({
               html:mw.beforeleave_html,
               name:'modal_beforeleave',
               width:430,
               height:230,
               template:'mw_modal_basic'
            });

            var save = modal.container.querySelector('.confirm-btn-green');
            var go = modal.container.querySelector('.confirm-btn-red');

            $(save).click(function(){
              mw.drag.save(mwd.getElementById('main-save-btn'), function(){
                mw.askusertostay = false;
                window.location.href = url;
              });
            });
            $(go).click(function(){
              mw.askusertostay = false;
              window.location.href = url;
            });
        }

        return false;
    }
}


mw.postMsg = function(w, obj){
  w.postMessage(JSON.stringify(obj), window.location.href);
}


mw.contact = {
    report:function(url){
      mw.tools.modal.frame({
            url:url,
            overlay:true,
            template:'mw_modal_basic',
            width:500,
            height:410,
            callback:function(){

                mw.postMsg(this.container.getElementsByTagName('iframe')[0].contentWindow, {user:mw.settings.user});
            }
      })
    }
}

crawl = false;
crawlc = [];

$(document).ready(function(){
  if(crawl){
    Alert("Crawling !");
  $(mwd.links).each(function(){
    if(top.crawlc.indexOf(this.href) === -1){
      top.crawlc.push(this.href);
       var frame = mwd.createElement('iframe');
       frame.className = 'semi_hidden';
        frame.src = this.href;
        mwd.body.appendChild(frame);
    }
    else{return false;}
  });
   }
});



  mw.ui = mw.tools;

  mw.ui.btn = {
    radionav : function(nav, btn_selector){

        if( mw.tools.hasClass(nav.className, 'activated') ) { return false; }
        var btn_selector = btn_selector || ".mw-ui-btn";
        var all = nav.querySelectorAll(btn_selector), i = 0, l = all.length, el;
        for( ; i<l; i++){
            var el = all[i];
            el.onclick = function(){
              if(!mw.tools.hasClass(this.className, 'active')){
                var active = nav.querySelector(btn_selector + ".active");
                if( active !== null) { mw.tools.removeClass(active, 'active'); }
                this.className += ' active';
              }
            }
        }
    },
    checkboxnav : function(nav){
      if( mw.tools.hasClass(nav.className, 'activated') ) { return false; }
      var all = nav.querySelectorAll(".mw-ui-btn"), i = 0, l = all.length;
        for( ; i<l; i++){
           var el = all[i];
            el.onclick = function(){
              if(!mw.tools.hasClass(this.className, 'active')){
                this.className += ' active';
              }
              else{
                mw.tools.removeClass(this, 'active');
              }
            }
        }
    }
  }

  mw.inline = {
    bar:function(id){
      if(typeof id === 'undefined') { return false; }
      if(mw.$("#"+id).length === 0){
        var bar = mwd.createElement('div');
        bar.id = id;
        bar.contentEditable = false;
        bar.className = 'mw-inline-bar';
        mwd.body.appendChild(bar);

        return bar;
      }
      else {
        return mw.$("#"+id)[0];
      }
    },
    tableControl:false,
    tableController:function(el, e){
       if(typeof e !== 'undefined'){ e.stopPropagation(); }
       if(mw.inline.tableControl === false){
            mw.inline.tableControl = mw.inline.bar('mw-inline-tableControl');

            mw.inline.tableControl.innerHTML = ''
              +'<div class="mw-defaults mw-ui-btn-nav mw-ui-dropdown-button-controller">'
              +'<div class="mw-defaults mw-ui-dropdown">'
                  +'<a style="margin: 0 -1px 0 0;" class="mw-ui-btn mw-ui-btn-medium" href="javascript:;"><span>Insert</span></a>'
                  +'<div style="width: 155px;" class="mw-dropdown-content">'
                    +'<ul class="mw-dropdown-list">'
                        +'<li><a href="javascript:;" onclick="mw.inline.tableManager.insertRow(\'above\', mw.inline.activeCell);">Row Above</a></li>'
                        +'<li><a href="javascript:;" onclick="mw.inline.tableManager.insertRow(\'under\', mw.inline.activeCell);">Row Under</a></li>'
                        +'<li><a href="javascript:;" onclick="mw.inline.tableManager.insertColumn(\'left\', mw.inline.activeCell)">Column on left</a></li>'
                        +'<li><a href="javascript:;" onclick="mw.inline.tableManager.insertColumn(\'right\', mw.inline.activeCell)">Column on right</a></li>'
                    +'</ul>'
                  +'</div>'
              +'</div>'
              +'<div class="mw-defaults mw-ui-dropdown">'
                  +'<a style="margin: 0 -1px 0 0;" class="mw-ui-btn mw-ui-btn-medium" href="javascript:;"><span>Style</span></a>'
                  +'<div style="width: 155px;" class="mw-dropdown-content">'
                    +'<ul class="mw-dropdown-list">'
                        +'<li><a href="javascript:;" onclick="mw.inline.tableManager.setStyle(\'mw-wysiwyg-table\', mw.inline.activeCell);">Bordered</a></li>'
                        +'<li><a href="javascript:;" onclick="mw.inline.tableManager.setStyle(\'mw-wysiwyg-table-zebra\', mw.inline.activeCell);">Bordered Zebra</a></li>'
                        +'<li><a href="javascript:;" onclick="mw.inline.tableManager.setStyle(\'mw-wysiwyg-table-simple\', mw.inline.activeCell);">Simple</a></li>'
                        +'<li><a href="javascript:;" onclick="mw.inline.tableManager.setStyle(\'mw-wysiwyg-table-simple-zebra\', mw.inline.activeCell);">Simple Zebra</a></li>'
                    +'</ul>'
                  +'</div>'
              +'</div>'
              +'<div class="mw-defaults mw-ui-dropdown">'
                  +'<a style="margin-left: 0;" class="mw-ui-btn mw-ui-btn-medium" href="javascript:;"><span>Delete</span></a>'
                  +'<div style="width: 155px;" class="mw-dropdown-content">'
                    +'<ul class="mw-dropdown-list">'
                        +'<li><a href="javascript:;" onclick="mw.inline.tableManager.deleteRow(mw.inline.activeCell);">Row</a></li>'
                        +'<li><a href="javascript:;" onclick="mw.inline.tableManager.deleteColumn(mw.inline.activeCell);">Column</a></li>'
                    +'</ul>'
                  +'</div>'
                  +'</div>'
              +'</div>';
         }
         var off = $(el).offset();
        $(mw.inline.tableControl).css({
          top : off.top - 45,
          left: off.left,
          display:'block'
        });

    },
    activeCell:null,
    setActiveCell:function(el, event){
        //event.preventDefault();
        //event.stopPropagation();
        if(!mw.tools.hasClass(el.className, 'tc-activecell')){
           mw.$(".tc-activecell").removeClass('tc-activecell');
           $(el).addClass('tc-activecell');
           mw.inline.activeCell = el;
        }
    },
    tableManager: {
        insertColumn: function(dir, cell){
            var cell = $(cell)[0];
            if(cell === null) {return false;}
            var dir = dir || 'right';
            var rows = $(cell.parentNode.parentNode).children('tr');
            var i = 0, l = rows.length, index = mw.tools.index(cell);
            for( ; i < l; i++){
              var row = rows[i];
              var cell = $(row).children('td')[index];
              if(dir == 'left' || dir == 'both'){
                  $(cell).before("<td onclick='mw.inline.setActiveCell(this, event);'>&nbsp;</td>");
              }
              if(dir == 'right' || dir == 'both'){
                  $(cell).after("<td onclick='mw.inline.setActiveCell(this, event);'>&nbsp;</td>");
              }
            }
        },
        insertRow: function(dir, cell){
           var cell = $(cell)[0];
           if(cell === null) { return false; }
           var dir = dir || 'under';
           var parent = cell.parentNode, cells = $(parent).children('td'), i = 0, l = cells.length, html = '' ;
           for( ; i<l; i++){
                html += '<td onclick="mw.inline.setActiveCell(this, event);">&nbsp;</td>';
           }
           var html = '<tr>' + html + '</tr>';
           if(dir == 'under' || dir == 'both'){
             $(parent).after(html)
           }
           if(dir == 'above' || dir == 'both'){
             $(parent).before(html)
           }
        },
        deleteRow:function(cell){
            $(cell.parentNode).remove();
        },
        deleteColumn:function(cell){
            var index = mw.tools.index(cell), body = cell.parentNode.parentNode, rows = $(body).children('tr'), l = rows.length, i = 0;
            for( ; i<l; i++){
                var row = rows[i];
                $(row.getElementsByTagName('td')[index]).remove();
            }
        },
        setStyle:function(cls, cell){
           var table = mw.tools.firstParentWithTag(cell, 'table');
           mw.tools.classNamespaceDelete(table, 'mw-wysiwyg-table');
           $(table).addClass(cls);
        }
    }
  }


  mw.dynamicCSS = {
    previewOne:function(selector, value){
      if(mwd.getElementById('user_css') === null){
        var style = mwd.createElement('style');
        style.type = 'text/css';
        style.id = 'user_css';
        mwd.head.appendChild(style);
      }
      else{
        var style = mwd.getElementById('user_css');
      }
      var css = selector + '{' + value + "}";
      style.appendChild(document.createTextNode(css));
    },
    manageObject:function(main_obj, selector_obj){

    }
  }

























