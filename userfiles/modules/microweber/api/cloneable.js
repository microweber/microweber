mw.drag.onCloneableControl = function(target, isOverControl){
    if(!this._onCloneableControl){
        this._onCloneableControl = mwd.createElement('div');
        this._onCloneableControl.className = 'mw-cloneable-control';
        var html = '';
        html += '<span class="mw-cloneable-control-item mw-cloneable-control-prev" title="Move backward"></span>';
        html += '<span class="mw-cloneable-control-item mw-cloneable-control-plus" title="Clone"></span>';
        html += '<span class="mw-cloneable-control-item mw-cloneable-control-minus" title="Remove"></span>' ;
        html += '<span class="mw-cloneable-control-item mw-cloneable-control-next" title="Move forward"></span>';
        this._onCloneableControl.innerHTML = html;

        mwd.body.appendChild(this._onCloneableControl);
        $('.mw-cloneable-control-plus', this._onCloneableControl).on('click', function(){
            var $t = $(mw.drag._onCloneableControl.__target).parent()
            mw.liveEditState.record({
                target: $t[0],
                value: $t[0].innerHTML
            });
            var parser = mw.tools.parseHtml(mw.drag._onCloneableControl.__target.outerHTML).body;
            var all = parser.querySelectorAll('[id]'), i = 0;
            for( ; i<all.length; i++){
                all[i].id = 'mw-cl-id-' + mw.random();
            }
            $(mw.drag._onCloneableControl.__target).after(parser.innerHTML);
            mw.liveEditState.record({
                target: $t[0],
                value: $t[0].innerHTML
            });
            mw.wysiwyg.change(mw.drag._onCloneableControl.__target);
            mw.drag.onCloneableControl('hide');
        });
        $('.mw-cloneable-control-minus', this._onCloneableControl).on('click', function(){
            var $t = $(mw.drag._onCloneableControl.__target).parent();
            mw.liveEditState.record({
                target: $t[0],
                value: $t[0].innerHTML
            });
            $(mw.drag._onCloneableControl.__target).fadeOut(function(){
                mw.wysiwyg.change(this);
                $(this).remove();
                mw.liveEditState.record({
                    target: $t[0],
                    value: $t[0].innerHTML
                });
            });
            mw.drag.onCloneableControl('hide');
        });
        $('.mw-cloneable-control-next', this._onCloneableControl).on('click', function(){
            var $t = $(mw.drag._onCloneableControl.__target).parent();
            mw.liveEditState.record({
                target: $t[0],
                value: $t[0].innerHTML
            });
            $(mw.drag._onCloneableControl.__target).next().after(mw.drag._onCloneableControl.__target)
            mw.liveEditState.record({
                target: $t[0],
                value: $t[0].innerHTML
            });
            mw.wysiwyg.change(mw.drag._onCloneableControl.__target);
            mw.drag.onCloneableControl('hide');
        });
        $('.mw-cloneable-control-prev', this._onCloneableControl).on('click', function(){
            var $t = $(mw.drag._onCloneableControl.__target).parent();
            mw.liveEditState.record({
                target: $t[0],
                value: $t[0].innerHTML
            });
            $(mw.drag._onCloneableControl.__target).prev().before(mw.drag._onCloneableControl.__target)
            mw.liveEditState.record({
                target: $t[0],
                value: $t[0].innerHTML
            });
            mw.wysiwyg.change(mw.drag._onCloneableControl.__target);
            mw.drag.onCloneableControl('hide');
        });
    }
    var clc = $(this._onCloneableControl);
    if(target === 'hide'){
        clc.hide();
    }
    else{
        clc.show();
        this._onCloneableControl.__target = target;

        var next = $(this._onCloneableControl.__target).next();
        var prev = $(this._onCloneableControl.__target).prev();
        var el = $(target), off = el.offset();


        if(next.length === 0){
            $('.mw-cloneable-control-next', clc).hide();
        }
        else{
            $('.mw-cloneable-control-next', clc).show();
        }
        if(prev.length === 0){
            $('.mw-cloneable-control-prev', clc).hide();
        }
        else{
            $('.mw-cloneable-control-prev', clc).show();
        }
        var leftCenter = (off.left > 0 ? off.left : 0) + (el.width()/2 - clc.width()/2) ;
        clc.show();
        if(isOverControl){
            return;
        }
        clc.css({
            top: off.top > 0 ? off.top : 0 ,
            //left: off.left > 0 ? off.left : 0
            left: leftCenter
        });


        var cloner = mwd.querySelector('.mw-cloneable-control');
        if(cloner) {
            mw._initHandles.getAll().forEach(function (curr) {
                masterRect = curr.wrapper.getBoundingClientRect();
                var clonerect = cloner.getBoundingClientRect();

                if (mw._initHandles.collide(masterRect, clonerect)) {
                    cloner.style.top = (parseFloat(curr.wrapper.style.top) + 10) + 'px';
                    cloner.style.left = ((parseInt(curr.wrapper.style.left, 10) + masterRect.width) + 10) + 'px';
                }
            });
        }
    }

}
