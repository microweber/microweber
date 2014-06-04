mw.drag = mw.drag || {};
mw.drag.plus = {
  locked:false,
  init:function(holder){
    mw.drag.plusTop = mwd.querySelector('.mw-plus-top');
    mw.drag.plusBottom = mwd.querySelector('.mw-plus-bottom');
    mw.$(holder).bind('mousemove', function(e){
      if(mw.drag.plus.locked === false && mw.isDrag === false){
        if(e.pageY % 2 === 0){
          var node = mw.drag.plus.selectNode(e.target);
          mw.drag.plus.set(node);
          $(mwd.body).removeClass('editorKeyup');
        }
      }
      else{
         mw.drag.plusTop.style.top = -9999 +'px';
         mw.drag.plusBottom.style.top = -9999 +'px';
      }
    });
    mw.$(holder).bind('mouseleave', function(e){
      if(mw.drag.plus.locked === false){
          mw.drag.plus.set(undefined);
      }
    });
    mw.drag.plus.action();
  },
  selectNode:function(target){
      if(mw.tools.hasClass(target, 'module')){
          return target;
      }
      else if(mw.tools.hasParentsWithClass(target, 'module')){
          return mw.tools.lastParentWithClass(target, 'module');
      }
      else if(mw.tools.hasClass(target, 'element')){
          return target;
      }
      else if(target.nodeName === 'P'){
          return target;
      }
      else if(mw.tools.hasParentsWithTag(target, 'p')){
          return mw.tools.firstParentWithTag(target, 'p');
      }
      else{
         mw.drag.plusTop.style.top = -9999 +'px';
         mw.drag.plusBottom.style.top = -9999 +'px';
         return undefined;
      }
  },
  set:function(node){
    if(typeof node === 'undefined'){ return; }
    var off = $(node).offset();
    var left = off.left;
    if(left < 15){
      var left = 15;
    }
    mw.drag.plusTop.style.top = off.top + 'px';
    mw.drag.plusTop.style.left = left + 'px';
    mw.drag.plusTop.currentNode = node;
    mw.drag.plusBottom.style.top = (off.top + node.offsetHeight) + 'px';
    mw.drag.plusBottom.style.left = left + 'px';
    mw.drag.plusBottom.currentNode = node;
    mw.tools.removeClass([mw.drag.plusTop, mw.drag.plusBottom], 'active');
  },
  tipPosition:function(node){

      var off = $(node).offset();
      if(off.top > 130){
          if((off.top + node.offsetHeight) < ($(mwd.body).height() - 130)){
            return 'right-center';
          }
          else{
             return 'right-bottom';
          }
      }
      else{
        return 'right-top';
      }
  },
  action:function(){
    var pls = [mw.drag.plusTop, mw.drag.plusBottom];
    $(pls).click(function(){
      var other = this === mw.drag.plusTop ? mw.drag.plusBottom : mw.drag.plusTop;
      if(!mw.tools.hasClass(this, 'active')){
          mw.tools.addClass(this, 'active');
          mw.tools.removeClass(other, 'active');
          mw.drag.plus.locked = true;
          mw.$('.mw-tooltip-insert-module').remove();
          mw.drag.plusActive = this === mw.drag.plusTop ? 'top' : 'bottom';
          var tip = mw.tooltip({
            content:mwd.getElementById('plus-modules-list').innerHTML,
            element:this,
            position:mw.drag.plus.tipPosition(this.currentNode),
            template:'mw-tooltip-default mw-tooltip-insert-module'
          });
      }
    });
    mw.$('#plus-modules-list li').each(function(){
      var name = $(this).attr('data-module-name');
      $(this).attr('onclick', 'InsertModule("'+name+'", {class:this.className})');
    });
  }
}

InsertModule = function(module, cls){
    var id = 'mwemodule-'+mw.random(), el = '<div id="'+id+'"></div>';
    if(mw.drag.plusActive == 'top'){
        $(mw.drag.plusTop.currentNode).before(el);
    }
    else if(mw.drag.plusActive == 'bottom'){
        $(mw.drag.plusBottom.currentNode).after(el);
    }
    mw.load_module(module, '#'+id, function(){
        mw.drag.plus.locked = false;
        mw.drag.fixes();
        setTimeout(function(){mw.drag.fix_placeholders();}, 40)
        mw.resizable_columns();
        mw.dropable.hide();
      }, cls);
    mw.$('.mw-tooltip').hide();
}