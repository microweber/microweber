mw.drag = mw.drag || {}
mw.drag.columns = {
    step: 0.8,
    resizing: false,
    prepare: function () {
        mw.drag.columns.resizer = mwd.createElement('div');
        mw.drag.columns.resizer.contenteditable = 'false';
        mw.drag.columns.resizer.className = 'unselectable mw-columns-resizer';
        mw.drag.columns.resizer.pos = 0;
        $(mw.drag.columns.resizer).bind('mousedown', function () {
            mw.drag.columns.resizing = true;
            mw.drag.columns.resizer.pos = 0;
        });
        mwd.body.appendChild(mw.drag.columns.resizer);

        $(mw.drag.columns.resizer).hide();
    },
    resize: function (e) {
        if (!mw.drag.columns.resizer.curr) return false;
        var w = parseFloat(mw.drag.columns.resizer.curr.style.width);
        if (isNaN(w)) {
            w = $(mw.drag.columns.resizer.curr).outerWidth();
            var widthParentPixels = $(mw.drag.columns.resizer.curr).parent().outerWidth();
            w = (w / widthParentPixels) * 100;
        }


        var next = mw.drag.columns.nextColumn(mw.drag.columns.resizer.curr);

        if(typeof(next) == "undefined"){
            // dirty fix
            $(mw.drag.columns.resizer).hide();
            return false;

        }

        var w2 = parseFloat(next.style.width);
        if (isNaN(w2)) {
            w2 = $(next).outerWidth();
            var widthParentPixels = $(next).parent().outerWidth();
            w2 = (w2 / widthParentPixels) * 100;
         }

        //d("w1 "+ w + "  w2 "+ w2)

        if (mw.drag.columns.resizer.pos < e.pageX) {
            if (w2 < 10) return false;
            mw.drag.columns.resizer.curr.style.width = (w + mw.drag.columns.step) + '%';
            next.style.width = (w2 - mw.drag.columns.step) + '%';
        }
        else {
            if (w < 10) return false;
            mw.drag.columns.resizer.curr.style.width = (w - mw.drag.columns.step) + '%';
            next.style.width = (w2 + mw.drag.columns.step) + '%';
        }
        mw.drag.columns.resizer.pos = e.pageX;
        mw.drag.columns.position(mw.drag.columns.resizer.curr);
        $(window).trigger('columnResize', mw.drag.columns.resizer.curr);
    },
    position: function (el) {
        if (!!mw.drag.columns.nextColumn(el)) {
            mw.drag.columns.resizer.curr = el;
            var off = $(el).offset();
            $(mw.drag.columns.resizer).css({
                top: off.top,
                left: off.left + el.offsetWidth - 10,
                height: el.offsetHeight
            }).show();
        }
    },
    init: function () {
        mw.drag.columns.prepare();
        $(window).bind("onColumnOver", function (e, col) {
            mw.drag.columns.resizer.pos = 0;
            mw.drag.columns.position(col);
        });
        $(window).bind("onColumnOut", function (e, col) {
            $(mw.drag.columns.resizer).hide();
        });

    },
    nextColumn: function (col) {
        var next = col.nextElementSibling;
        if (next === null) {
            return undefined
        }
        if (mw.tools.hasClass(next, 'mw-col')) {
            return next;
        }
        else {
            return mw.drag.columns.nextColumn(next)
        }
    }
}
$(mwd).ready(function () {
    $(mwd.body).bind('mouseup', function () {
        if (mw.drag.plus.locked) {
            mw.wysiwyg.change(mw.drag.columns.resizer.curr);
        }
        mw.drag.columns.resizing = false;
        mw.drag.plus.locked = false;
        mw.tools.removeClass(mwd.body, 'mw-column-resizing');
    });
    $(mwd.body).bind('mousemove', function (e) {
        if (mw.drag.columns.resizing === true && mw.isDrag === false) {
            mw.drag.columns.resize(e);
            e.preventDefault();
            mw.drag.plus.locked = true;
            mw.tools.addClass(mwd.body, 'mw-column-resizing');
        }
    });
});