mw.require("customfields.js");

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
                   var tip = this.mwtooltip;
                   mw.$('.create-content-menu', this.mwtooltip).click(function(){
                       $(tip).hide();
                   });
                   this.mwtooltip.style.display = 'none';
                   $(this).timeoutHover(function(){
                     mw.tools.tooltip.setPosition(this.mwtooltip, this, ($(this).dataset('tip') != '' ? $(this).dataset('tip') : 'bottom-center'))
                     $(this.mwtooltip).show();

                   }, function(){
                      if(this.mwtooltip.originalOver === false){
                        $(this.mwtooltip).hide();
                      }
                   });
                   $(this.mwtooltip).timeoutHover(function(){

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
      }
    },
    manageToolbarQuickNav:null,
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
      if( mw.admin.manageToolbarQuickNav === null){
        mw.admin.manageToolbarQuickNav = mwd.getElementById('content-edit-settings-tabs');
      }
      if(mw.admin.manageToolbarQuickNav !== null){
          if((scrolltop) >100){
            mw.$("#content-edit-settings-tabs").addClass('fixed');
            mw.$(".admin-manage-toolbar-scrolled").addClass('fix-tabs');
            mwd.getElementById('content-edit-settings-tabs').style.top = scrolltop - 15 + 'px';
          }
          else{
            mw.$("#content-edit-settings-tabs").removeClass('fixed');
            mw.$(".admin-manage-toolbar-scrolled").removeClass('fix-tabs');
          }
          QTABSArrow('#quick-add-post-options .active');
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
    },
    insertModule:function(module){
      mwd.querySelector('.mw-iframe-editor').contentWindow.InsertModule(module);
    },
    titleColumnNavWidth:function(){
      var _n = mwd.getElementById('content-title-field-buttons');
      if(_n !== null){
         _n.style.width=_n.querySelector('.mw-ui-btn-nav').offsetWidth+22+'px';
      }
    },
    postStates:{
      show:function(el, pos){
        if(!mw.admin.postStatesTip){
          mw.admin.postStates.build();
        }
        var el = el || mwd.querySelector('.btn-posts-state');
        var pos = pos || 'bottom-left';
        mw.tools.tooltip.setPosition(mw.admin.postStatesTip, el, pos);
        mw.admin.postStatesTip.style.display = 'block';
        mw.$('.btn-posts-state.tip').addClass('tip-disabled');
        $(mw.admin._titleTip).hide();
      },
      hide:function(e,d){
        if(!mw.admin.postStatesTip){
          mw.admin.postStates.build();
        }
        if(mw.admin.postStatesTip._over == false){
          mw.admin.postStatesTip.style.display = 'none';
        }
        mw.$('.btn-posts-state.tip').removeClass('tip-disabled');
      },
      timeoutHide:function(){
        if(!mw.admin.postStatesTip){
          mw.admin.postStates.build();
        }
        setTimeout(function(){
          if(mw.admin.postStatesTip._over == false){
            mw.admin.postStatesTip.style.display = 'none';
          }
        }, 444);
      },
      build:function(){
        mw.admin.postStatesTip = mw.tooltip({content:mwd.getElementById('post-states-tip').innerHTML, position:'bottom-left', element:'.btn-posts-state'});
        $(mw.admin.postStatesTip).addClass('posts-states-tooltip');
        mw.admin.postStatesTip.style.display = 'none';
        mw.admin.postStatesTip._over = false;
        $(mw.admin.postStatesTip).hover(function(){
          this._over = true;
        }, function(){
          this._over = false;
          //mw.admin.postStatesTip.style.display = 'none';
        });
        $(mwd.body).bind('mousedown', function(e){
            if(mw.admin.postStatesTip._over === false && mw.admin.postStatesTip.style.display == 'block' && !mw.tools.hasClass(e.target, 'btn-posts-state') && !mw.tools.hasParentsWithClass(e.target, 'btn-posts-state')){
                mw.admin.postStatesTip.style.display = 'none';
            }
        });
      },
      set:function(a){
        if(a == 'publish'){
          mw.$('.btn-publish').addClass('active');
          mw.$('.btn-unpublish').removeClass('active');
          mw.$('.btn-posts-state > span').attr('class', 'mw-icon-check').parent().dataset("tip", mw.msg.published);
          mw.$('#is_post_active').val('y');
          mw.$('.btn-posts-state.tip-disabled').removeClass('tip-disabled');
          mw.admin.postStatesTip.style.display = 'none';
        }
        else if(a == 'unpublish'){
          mw.$('.btn-publish').removeClass('active');
          mw.$('.btn-unpublish').addClass('active');
          mw.$('.btn-posts-state > span').attr('class', 'mw-icon-unpublish').parent().dataset("tip", mw.msg.unpublished);
          mw.$('#is_post_active').val('n');
          mw.$('.btn-posts-state.tip-disabled').removeClass('tip-disabled');
          mw.admin.postStatesTip.style.display = 'none';
        }
      },
      toggle:function(){
        if(!mw.admin.postStatesTip || mw.admin.postStatesTip.style.display == 'none'){
           mw.admin.postStates.show();
        }
        else{
             mw.admin.postStates.hide();
        }
      }
    },
    titleTip:function(el){
        if(mw.tools.hasClass(el, 'tip-disabled')){
            $(mw.admin._titleTip).hide();
            return false;
        }
        var pos = $(el).dataset('tipposition');
        if(pos == ''){var pos = 'bottom-center';}
        var text = $(el).dataset('tip');

        if(text.indexOf('.') === 0 || text.indexOf('#') === 0 ){
            var text = mw.$(text).html();
        }
        if(!mw.admin._titleTip){
            mw.admin._titleTip = mw.tooltip({skin:'dark', element:el, position:pos, content:text});
            $(mw.admin._titleTip).addClass('admin-universal-tooltip');
        }
        else{
           mw.$('.mw-tooltip-content', mw.admin._titleTip).html(text);
           mw.tools.tooltip.setPosition(mw.admin._titleTip, el, pos);
        }
        $(mw.admin._titleTip).show();
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

   $(mwd.body).bind('mousemove', function(event){
        if(mw.tools.hasClass(event.target, 'tip')){
            mw.admin.titleTip(event.target);
        }
        else if(mw.tools.hasParentsWithClass(event.target, 'tip')){
            mw.admin.titleTip(mw.tools.firstParentWithClass(event.target, 'tip'))
        }
        else{
           $(mw.admin._titleTip).hide();
        }
   });



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




