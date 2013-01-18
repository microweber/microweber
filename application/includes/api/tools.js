

mw.controllers = {}


mw.simpletabs = function(root){
  var root = root || mwd;
  mw.$(".mw_simple_tabs_nav", root).each(function(){
    if(!$(this).hasClass('activated')){
        $(this).addClass('activated')
        if(!$(this).hasClass('by-hash')){
            var parent = $(this).parents(".mw_simple_tabs").eq(0);
            parent.find(".tab").addClass("semi_hidden");
            parent.find(".tab").eq(0).removeClass("semi_hidden");
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
      $(master.querySelectorAll('.tab')).addClass('semi_hidden');
      $(master.querySelectorAll('.tab')[index]).removeClass('semi_hidden');
    }
  }
}






mw.external_tool = function(url){
  return !url.contains("/") ? mw.settings.site_url  +  "editor_tools/" + url : url;
}



mw.tools = {
  preloader:function(init, element){
    if(!mw._preloader)mw._preloader=mwd.getElementById('mwpreloader');
    if(element){
        var el = $(element);
        var off = el.offset();
        var w = el.outerWidth();
        var h = el.outerHeight();
        $(mw._preloader).css({
         left:off.left+(w/2)-8,
         top:off.top+(h/2)-8
       })
    }
    else{
       $(mw._preloader).css({
         left:"",
         top:""
       })
    }
    if(init=='stop'){$(mw._preloader).invisible()}
    else if(init=='start'){$(mw._preloader).visible()}

  },
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
    _init:function(html, width, height, callback, title, name, template){
        if(typeof name==='string' && $("#"+name).length>0){
            return false;
        }

        var modal = mw.tools.modal.source(name, template);

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
      return  mw.tools.modal._init(o.html, o.width, o.height, o.callback, o.title, o.name, o.template);
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
          name:obj.name,
          template:obj.template
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
        return overlay;
    },
  },
  alert:function(text){
    var html = ''
    + '<table class="mw_alert" width="100%" height="140" cellpadding="0" cellspacing="0">'
        + '<tr>'
        + '<td align="center" valign="middle"><div class="mw_alert_holder">'+text+'</div></td>'
        + '</tr>'
        + '<tr>'
        + '<td align="center" height="25"><span class="mw-ui-btn-action" onclick="mw.tools.modal.remove(\'mw_alert\');"><b>OK</b></span></td>'
        + '</tr>'
    + '</table>';
    return  mw.tools.modal._init(html, 400, 200, "", "", "mw_alert");
  },
  dropdown:function(root){
    var root = root || mwd.body;
    mw.$(".mw_dropdown .other-action", root).hover(function(){
      $(this).addClass("other-action-hover");
    }, function(){
      $(this).removeClass("other-action-hover");
    });
    mw.$(".mw_dropdown", root).mouseup(function(event){
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
            item.find(".dd_search").focus();
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
  },
  dd_sub_set:function(item){
      var html = $(item).html();
      var value = item.parentNode.getAttribute("value");
      $(item).parents(".mw_dropdown").setDropdownValue(value, true);
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
  classNamespaceDelete:function(el_obj, namespace){
    if(el_obj ==='all'){
      var all = mwd.querySelectorAll('.edit *'), i=0, l=all.length;
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
        mw.tools.confirm('Are you sure you want to delete this?', function(){
           $.post(mw.settings.site_url + "api/delete_content", {id:id}, function(data) {
              var todelete =  mw.$(".item_" + id);
               todelete.fadeOut(function(){
                   todelete.remove();
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
            var id = this.attributes['data-item-id'].nodeValue;
            _remember = i<len ? _remember + id + "," : _remember + id;
          });
          mw.cookie.ui("tree_"+tree.id, _remember);
      }
    },
    recall : function(tree){
      if(tree!==null){
      var ids = mw.cookie.ui("tree_"+tree.id);
      if(ids!==''){
        var ids = ids.split(",");
        mw.log(ids)
        $.each(ids, function(a,b){
          if(tree.querySelector('.item_'+b)){
             tree.querySelector('.item_'+b).className+=' active';
          }
        });
      }
      }
    },
    toggleit : function(el, event, pageid){
       event.stopPropagation();
       if(el.attributes['data-page-id'] !== undefined){
          mw.url.windowHashParam('action', 'showposts:'+pageid);
       }
       else if(el.attributes['data-category-id'] !== undefined){
          mw.url.windowHashParam('action', 'showpostscat:'+pageid);
       }
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
    return (' ' + classname + ' ').indexOf(' ' + whattosearch + ' ') > -1;
  },
  addClass:function(el, cls){
   if( !mw.tools.hasClass(el.className, cls) ) el.className += (' ' + cls);
  },
  removeClass:function(el, cls){
   if( mw.tools.hasClass(el.className, cls) ) el.className = (el.className + ' ').replace(cls+' ', '').replace(/\s{2,}/g, ' ');
  },
  hasParentsWithClass:function(el, cls){
    var d = this;
    d.toreturn = false;
    mw.tools.foreachParents(el, function(loop){
        if(mw.tools.hasClass(this.className, cls)){
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
     if(el === null) return false;
     var index = mw.random();
     mw.tools.loop[index]=true;
     var _curr = el.parentNode;
     if(_curr !== null && _curr !== undefined){
       var _tag = _curr.tagName;
       while(_tag !== 'BODY'){
           var caller =  callback.call( _curr, index);
           var _curr = _curr.parentNode;
           if( caller === false || _curr === null || _curr === undefined || !mw.tools.loop[index]){ delete mw.tools.loop[index]; break }
           var _tag = _curr.tagName;
       }
     }
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
  toggle:function(who, toggler, callback){
    var who = mw.$(who).eq(0);
    who.toggle();
    who.toggleClass('toggle-active');
    $(toggler).toggleClass('toggler-active');
    mw.is.func(callback) ? callback.call(who) : '';
  },
  confirm:function(question, callback){
    if(confirm(question)){
      callback.call(window);
    }
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
  scrollTo:function(el, callback){
    mw.$('html,body').animate({scrollTop:$(el).offset().top}, function(){
        typeof callback === 'function' ? callback.call(el) : '';
    });
  },
  accordion:function(el, callback){
    var speed = 200;
    var container = el.querySelector('.mw-o-box-accordion-content');
    if($(el).hasClass('mw-accordion-active')){
        $(container).slideDown(speed, function(){
          $(el).removeClass('mw-accordion-active');
          typeof callback === 'function' ? callback.call(el, 'visible') : '';
        });
    }
    else{
      $(container).slideUp(speed, function(){
          $(el).addClass('mw-accordion-active');
          typeof callback === 'function' ? callback.call(el, 'hidden') : '';
      });
    }
  },
  index:function(el, parent, selector){
    var selector = selector || el.tagName.toLowerCase();
    var parent = parent || el.parentNode;
    var all = parent.querySelectorAll(selector), i=0, l=all.length;
    for ( ; i<l; i++){
        if( el===all[i] ) return i;
    }
  },
  simpleRotator:function(rotator){
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
  highlight:function(el, color){
    var color = color || '#D8FFC4';
    var speed = 777;
    var curr = window.getComputedStyle(el, null).backgroundColor;
    if(curr == 'transparent'){
      var curr = '#ffffff';
    }
    $(el).css('boxShadow', '0 0 10px #ccc');
    $(el).animate({ backgroundColor: color }, speed, function(){
        $(el).animate({ backgroundColor: curr }, speed, function(){
          $(el).css('backgroundColor', '');
          $(el).css('boxShadow', '');
        })
    });
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
    var items = obj.itemsWrapper.querySelectorAll(obj.items);
    var tagMethod = obj.method || 'parse';

    var tagholder = $(obj.tagholder);
    var field = mw.$('input[type="text"]', tagholder[0]);

    var def =  field.dataset('default');
    mw.log(field)
    o.createTag = function(el){
        var span_holder = mwd.createElement('span');
        var span_x = mwd.createElement('span');

        span_holder.className = 'mw-ui-btn mw-ui-btn-small';
        span_holder.id = 'id-'+el.value;
        span_holder.innerHTML = el.parentNode.textContent;

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
              var tag = o.createTag(this);
              html.push(tag);
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
      if(!!input) { $(input)[0].checked = false; }
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
               if(!mw.tools.hasClass(itemsWrapper.className, 'mw-tagger-hover') && !tagholder.hasClass('mw-tagger-hover')){
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
  }
}






Wait('$', function(){

  $.fn.getDropdownValue = function() {
    return this.dataset("value");
  };
  $.fn.setDropdownValue = function(val, triggerChange, isCustom, customValueToDisplay) {
     var isCustom = isCustom || false;
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


mw.datassetSupport = mw.is.obj(mwd.getElementsByTagName('html')[0].dataset) ? true : false;

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
    if(this.type==='checkbox'  || this.type==='radio' ){
      $(this).bindMultiple("change", function(){
        this.checked === true ? a.call(this) : b.call(this);
      });
    }
  });
};

});


mw.cookie = {
  get:function(name){
      var cookies=document.cookie.split(";");
      for (var i=0; i<cookies.length; i++){
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
    document.cookie = name + "=" +escape( value ) + ( ( expires ) ? ";expires=" + expires_date.toGMTString() : "" ) + ( ( path ) ? ";path=" + path : "" ) +  ( ( domain ) ? ";domain=" + domain : "" ) +  ( ( secure ) ? ";secure" : "" );
  },
  ui:function(a,b){
    var mwui = mw.cookie.get("mwui");
    var mwui = !mwui ? {} : $.parseJSON(mwui);
    if(a===undefined){return mwui}
    if(b===undefined){return mwui[a]!==undefined?mwui[a]:""}
    else{
        mwui[a] = b;
        var tostring = JSON.stringify(mwui);
        mw.cookie.set("mwui", tostring, false, "/");
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
    return mw.is.func(temp) ? temp(a,b,c) : temp;
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
     el.getElementsByTagName('input')[0].checked = true;
    }
  },
  off:function(el){
     if(el){
     var _el = $(el);
     _el.addClass('mw-switcher-off');
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
    build:function(type, text){
        var div = mwd.createElement('div');
        div.className = 'mw-notification mw-'+type;
        div.innerHTML = '<div>'+text+'</div>'
        return div;
    },
    append:function(type, text){
        var div = mw.notification.build(type, text);
        mw.$('#mw-notifications-holder').append(div);
        setTimeout(function(){
           div.style.opacity = 0;
           setTimeout(function(){
             $(div).remove()
           }, 1000);
        }, 1000);
    },
    success:function(text){
      mw.notification.append('success', text);
    },
    error:function(text){
      mw.notification.append('error', text);
    },
    warning:function(text){
      mw.notification.append('warning', text);
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






mw.googleFonts = {
  params:'?key=AIzaSyApMgI8vW2EfAFAeVa4hDvaLoaW9A3WY94&subsets=latin&sort=alpha',
  url:'https://www.googleapis.com/webfonts/v1/webfonts/',
  get:function(format, callback){  return false;
    var format = format || 'list';

    jQuery.getJSON(mw.googleFonts.url + mw.googleFonts.params, function(data){

        if(format==='list'){
            var html = '<li><h2>Google Fonts:</h2></li>';
            $.each(data.items, function(a,b){
                html+='<li>' + b.family + '</li>';
            });
            html+='';

            callback.call(html);
        }
    });
  }
}

$(window).load(function(){
  if(!window['mwAdmin']){
    mw.googleFonts.get('list', function(){
       var ul = $("#font_family_selector_main ul")[0];
       ul.innerHTML+=this
    });


  }
});
























