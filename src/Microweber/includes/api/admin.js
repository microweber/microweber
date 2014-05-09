mw.admin = {
    scrollBoxSettings:{
        height:'auto',
        size:5,
        distance:5
    },
    scrollBox:function(selector, settings){
        var settings = $.extend({}, mw.admin.scrollBoxSettings, settings);
        var el = mw.$(selector);
        el.slimScroll(settings);
        var scroller =  mw.$('.slimScrollBar', el[0].parentNode);
        scroller.bind('mousedown', function(){
            $(this).addClass('scrollMouseDown');
        });
        $(mwd.body).bind('mouseup', function(){
            mw.$('.scrollMouseDown').removeClass('scrollMouseDown');
        });
    },
    contentScrollBoxHeightMinus:0,
    contentScrollBoxHeightFix:function(node){
      mw.admin.contentScrollBoxHeightMinus = 0, exceptor = mw.tools.firstParentWithClass(node, 'scroll-height-exception-master');
      if( !exceptor ) {  return $(window).height(); }
      mw.$('.scroll-height-exception', exceptor).each(function(){
        mw.admin.contentScrollBoxHeightMinus = mw.admin.contentScrollBoxHeightMinus + $(this).outerHeight(true);
      });
      return $(window).height() - mw.admin.contentScrollBoxHeightMinus;
    },
    contentScrollBox:function(selector, settings){
       var el = mw.$(selector)[0];
       if(typeof el === 'undefined'){ return false; }
       mw.admin.scrollBox(el, settings);
       var newheight = mw.admin.contentScrollBoxHeightFix(el)
       el.style.height = newheight + 'px';
       el.parentNode.style.height = newheight  + 'px'
       $(window).bind('resize', function(){
            var newheight =  mw.admin.contentScrollBoxHeightFix(el)
            el.style.height = newheight + 'px';
            el.parentNode.style.height = newheight + 'px';
            $(el).slimscroll();
       });
    },
    treeboxwidth:function(){
      if(mwd.querySelector('.tree-column-active') === null){
        var w = mw.$('.fixed-side-column').width();
        mw.$('.tree-column').width(w);
      }
    },
    _treeboxwidth:function(){
        var i = 0, all = mwd.querySelectorAll('#pages_tree_toolbar li.active-bg > a .pages_tree_link_text'), l = all.length, max = 0;
        for( ; i<l; i++){
          var ch = all[i].textContent.length;
          if(ch > 15){
            var max = 15;
          }
        }
        if(max === 15){
            var w = mw.$('.fixed-side-column').width();
            mw.$('.tree-column, .fixed-side-column').width(310);
        }
        else{
            mw.$('.tree-column, .fixed-side-column').width(210);
        }
    },
    createContentBtns:function(){
         var create_content_btn = mwd.querySelectorAll('.create-content-btn');
         if(create_content_btn.length !== 0){
           $(create_content_btn).each(function(){
               if(!this.mwtooltip){
                   this.mwtooltip = mw.tooltip({
                     position:$(this).dataset('tip') != '' ? $(this).dataset('tip') : 'bottom-center',
                     content:mw.$('#create-content-menu').html(),
                     element:this,
                     skin:'dark'
                   });
                   this.mwtooltip.style.display = 'none';
                   $(this).timeoutHover(function(){
                     $(this.mwtooltip).show();
                   }, function(){
                      if(this.mwtooltip.originalOver === false){
                        $(this.mwtooltip).hide();
                      }
                   });
                   $(this.mwtooltip).timeoutHover(function(){
                      $(this).show();
                   }, function(){
                      if(this.originalOver === false){
                        $(this).hide();
                      }
                   });
               }
           });
         }
    },
    editor:{
      set:function(frame){
        $(frame).width('100%');
        return;
        if(!!frame && frame !== null && !!frame.contentWindow){
            var width_mbar = mw.$('#main-bar').width(), tree = mwd.querySelector('.tree-column'), width_tbar = $(tree).width(), ww = $(window).width();

            if(tree.style.display === 'none'){ width_tbar = 0; }
            if(width_mbar > 200){ width_mbar = 0; }

            $(frame).width(ww - width_tbar - width_mbar - 35).height(frame.contentWindow.document.body.offsetHeight);
        }
      },
	  init:function(area, params){
          var params = params || {};
          if(typeof params === 'object' ){
            if(typeof params.src != 'undefined'){
                delete(params.src);
            }
          }
          var params = typeof params === 'object' ? json2url(params) : params;
          var area = mw.$(area);
          var frame = mwd.createElement('iframe');
        //  frame.src = mw.settings.site_url+('?mw_quick_edit=true&'+params);
		      frame.src = mw.external_tool('wysiwyg?'+params);
          frame.className = 'mw-iframe-editor';
          frame.scrolling = 'no';
          var name =  'mweditor'+mw.random();
          frame.id = name;
          frame.name = name;
          frame.style.backgroundColor = "transparent";
          frame.setAttribute('frameborder', 0);
          frame.setAttribute('allowtransparency', 'true');
          area.empty().append(frame);
          $(frame).load(function(){
              frame.contentWindow.thisframe = frame;
              if(typeof frame.contentWindow.PrepareEditor === 'function'){
                frame.contentWindow.PrepareEditor();
              }
              mw.admin.editor.set(frame);
              $(frame.contentWindow.document.body).bind('keyup paste', function(){
                   mw.admin.editor.set(frame);
              });
          });
          mw.admin.editor.set(frame);
          $(window).bind('resize', function(){
            mw.admin.editor.set(frame);
          });
          return frame;
      },
      OLDinit:function(area, params){
          var params = params || {};
          if(typeof params === 'object' ){
            if(typeof params.src != 'undefined'){
                delete(params.src);
            }
          }
          var params = typeof params === 'object' ? json2url(params) : params;
          var area = mw.$(area);
          var frame = mwd.createElement('iframe');
          frame.src = mw.settings.site_url+('?mw_quick_edit=true&'+params);
          frame.className = 'mw-iframe-editor';
          frame.scrolling = 'no';
          var name =  'mweditor'+mw.random();
          frame.id = name;
          frame.name = name;
          frame.style.backgroundColor = "transparent";
          frame.setAttribute('frameborder', 0);
          frame.setAttribute('allowtransparency', 'true');
          area.empty().append(frame);
          $(frame).load(function(){
              frame.contentWindow.thisframe = frame;
              if(typeof frame.contentWindow.PrepareEditor === 'function'){
                frame.contentWindow.PrepareEditor();
              }
              mw.admin.editor.set(frame);
              $(frame.contentWindow.document.body).bind('keyup paste', function(){
                   mw.admin.editor.set(frame);
              });
          });
          mw.admin.editor.set(frame);
          $(window).bind('resize', function(){
            mw.admin.editor.set(frame);
          });
          return frame;
      }
    },
    manageToolbarSet:function(){
      var toolbar = mwd.querySelector('.admin-manage-toolbar');
      if(toolbar === null){ return false; }
      var scrolltop = $(window).scrollTop();
      if(scrolltop > 0){
        mw.tools.addClass(toolbar, 'admin-manage-toolbar-scrolled');
       // toolbar.style.width = toolbar.parentNode.offsetWidth + 'px';
        toolbar.style.top = scrolltop + 'px';
      }
      else{
         mw.tools.removeClass(toolbar, 'admin-manage-toolbar-scrolled');
         toolbar.style.top = 0;
      }
    },
    CategoryTreeWidth:function(p){
          AdminCategoryTree =  mwd.querySelector('.tree-column');
          if((p != false) && (p.contains('edit') || p.contains('new'))){
            if(AdminCategoryTree !== null){
                AdminCategoryTree.treewidthactivated = true;
                mw.$(AdminCategoryTree).addClass('tree-column-active');
                mw.$('.tree-column-active').click(function(){
                    if( AdminCategoryTree.treewidthactivated === true ){
                        $(this).removeClass('tree-column-active');
                        mw.admin.treeboxwidth();
                    }
                });
                $(mwd.body).bind('click', function(e){
                  if(AdminCategoryTree.treewidthactivated === true){
                    if(!mw.tools.hasParentsWithClass(e.target, 'tree-column')){
                      mw.$(AdminCategoryTree).addClass('tree-column-active');
                      mw.admin.manageToolbarSet();
                    }
                  }
                });
            }
          }
          else{
            mw.$(AdminCategoryTree).removeClass('tree-column-active');
            AdminCategoryTree.treewidthactivated = false;
          }
    }
}


$(mwd).ready(function(){
   mw.admin.treeboxwidth();
});

$(mww).bind('load', function(){
    mw.admin.contentScrollBox('.fixed-side-column-container');
    mw.admin.contentScrollBox('#main-menu', {color:'white'});
    mw.admin.treeboxwidth();
    mw.on.moduleReload('pages_tree_toolbar', function(){
       mw.admin.treeboxwidth();
       setTimeout(function(){
           mw.admin.treeboxwidth();
       }, 90);
    });
   mw.admin.createContentBtns();
   mw.admin.manageToolbarSet();

});

$(mww).bind('hashchange', function(){
    mw.admin.treeboxwidth();
});

$(mww).bind('scroll resize', function(){
    mw.admin.manageToolbarSet();
});
mw.on.moduleReload('pages_edit_container', function(){
   mw.admin.createContentBtns();
})




