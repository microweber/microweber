mw.drag = mw.drag || {}
mw.drag.columns = {
    resizing:false,
    prepare:function(){
        mw.drag.columns.resizer = mwd.createElement('div');
        mw.drag.columns.resizer.contenteditable = 'false';
        mw.drag.columns.resizer.className = 'unselectable mw-columns-resizer';
        mw.drag.columns.resizer.pos = 0;
        $(mw.drag.columns.resizer).bind('mousedown', function(){
            mw.drag.columns.resizing = true;
            mw.drag.columns.resizer.pos = 0;
        });
        mwd.body.appendChild(mw.drag.columns.resizer);
    },
    resize:function(e){
        var w = parseFloat(mw.drag.columns.resizer.curr.style.width);
        var next = mw.drag.columns.nextColumn(mw.drag.columns.resizer.curr);
        var w2 = parseFloat(next.style.width);
        if(mw.drag.columns.resizer.pos < e.pageX){
            mw.drag.columns.resizer.curr.style.width = (w + 0.4) + '%';
            next.style.width = (w2 - 0.4) + '%';
        }
        else{
           mw.drag.columns.resizer.curr.style.width = (w - 0.4) + '%';
           next.style.width = (w2 + 0.4) + '%';
        }
        mw.drag.columns.resizer.pos = e.pageX;
        mw.drag.columns.position(mw.drag.columns.resizer.curr);
    },
    position:function(el){
        if(!!mw.drag.columns.nextColumn(el)){
        mw.drag.columns.resizer.curr = el;
        var off = $(el).offset();
        $(mw.drag.columns.resizer).css({
            top:off.top,
            left:off.left+el.offsetWidth - 21,
            height:el.offsetHeight
        });
        }
    },
    init:function(){
        mw.drag.columns.prepare();
        $(window).bind("onColumnOver", function(e, col){
            mw.drag.columns.resizer.pos = 0;
            mw.drag.columns.position(col);
        });
    },
    nextColumn:function(col){
      var next = col.nextElementSibling;
      if(next === null){ return undefined }
      if(mw.tools.hasClass(next, 'mw-col')){
        return next;
      }
      else{return mw.drag.columns.nextColumn(next)}
    }
}
$(mwd).ready(function(){
   $(mwd.body).bind('mouseup', function(){
      mw.drag.columns.resizing = false;
   });
   $(mwd.body).bind('mousemove', function(e){
     if(mw.drag.columns.resizing){
        mw.drag.columns.resize(e);
        e.stopPropagation()
     }
   });
});