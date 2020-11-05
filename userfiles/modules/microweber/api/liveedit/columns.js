mw.drag = mw.drag || {};
mw.drag.columns = {
    step: 0.8,
    resizing: false,
    prepare: function () {
        mw.drag.columns.resizer = mwd.createElement('div');
        mw.wysiwyg.contentEditable(mw.drag.columns.resizer, false);
        mw.drag.columns.resizer.className = 'unselectable mw-columns-resizer';
        mw.drag.columns.resizer.pos = 0;
        mw.$(mw.drag.columns.resizer).on('mousedown', function () {
            mw.drag.columns.resizing = true;
            mw.drag.columns.resizer.pos = 0;
        });
        mwd.body.appendChild(mw.drag.columns.resizer);

        mw.$(mw.drag.columns.resizer).hide();
    },
    resize: function (e) {
        if (!mw.drag.columns.resizer.curr) return false;
        var w = parseFloat(mw.drag.columns.resizer.curr.style.width);
        var widthParentPixels;
        if (isNaN(w)) {
            w = mw.$(mw.drag.columns.resizer.curr).outerWidth();
            widthParentPixels = mw.$(mw.drag.columns.resizer.curr).parent().outerWidth();
            w = (w / widthParentPixels) * 100;
        }
        var next = mw.drag.columns.nextColumn(mw.drag.columns.resizer.curr);
        if(!next){
            mw.$(mw.drag.columns.resizer).hide();
            return false;
        }

        var w2 = parseFloat(next.style.width);
        if (isNaN(w2)) {
            w2 = mw.$(next).outerWidth();
            widthParentPixels = mw.$(next).parent().outerWidth();
            w2 = (w2 / widthParentPixels) * 100;
         }

        if (mw.drag.columns.resizer.pos < e.pageX) {
            if (w2 < 10 && !mw.tools.isRtl()) return false;
            mw.drag.columns.resizer.curr.style.width = mw.tools.isRtl()?(w - mw.drag.columns.step):(w + mw.drag.columns.step) + '%';
            var calc = mw.tools.isRtl() ? (w2 + mw.drag.columns.step) : (w2 - mw.drag.columns.step);
            next.style.width =  calc + '%';
        }
        else {
            if (w < 10 && !mw.tools.isRtl()) return false;
            mw.drag.columns.resizer.curr.style.width = mw.tools.isRtl()?(w + mw.drag.columns.step):(w - mw.drag.columns.step) + '%';
            var calc = mw.tools.isRtl() ? (w2 - mw.drag.columns.step) : (w2 + mw.drag.columns.step);
            next.style.width = calc + '%';
        }
        mw.drag.columns.resizer.pos = e.pageX;
        mw.drag.columns.position(mw.drag.columns.resizer.curr);
        mw.trigger('columnResize', mw.drag.columns.resizer.curr);
    },
    position: function (el) {
        if (!!mw.drag.columns.nextColumn(el)) {
            mw.drag.columns.resizer.curr = el;
            var off = mw.$(el).offset();
            mw.$(mw.drag.columns.resizer).css({
                top: off.top,
                left: mw.tools.isRtl() ? off.left - 10 : off.left + el.offsetWidth - 10,
                height: el.offsetHeight
            }).show();
        }
    },
    init: function () {
        mw.drag.columns.prepare();
        mw.on("ColumnOver", function (e, col) {
            mw.drag.columns.resizer.pos = 0;
            mw.drag.columns.position(col);
        });
        mw.on("ColumnOut", function (e, col) {
            mw.$(mw.drag.columns.resizer).hide();
        });

    },
    nextColumn: function (col) {
        var next = col.nextElementSibling;
        if (next === null) {
            return;
        }
        if (mw.tools.hasClass(next, 'mw-col')) {
            return next;
        }
        else {
            return mw.drag.columns.nextColumn(next);
        }
    }
}
$(mwd).ready(function () {
    mw.$(mwd.body).on('mouseup touchend', function () {
        if (mw.drag.plus.locked) {
            mw.wysiwyg.change(mw.drag.columns.resizer.curr);
        }
        mw.drag.columns.resizing = false;
        mw.drag.plus.locked = false;
        mw.tools.removeClass(mwd.body, 'mw-column-resizing');
    });
    mw.$(mwd.body).on('mousemove touchmove', function (e) {
        if (mw.drag.columns.resizing === true && mw.isDrag === false) {
            mw.drag.columns.resize(e);
            e.preventDefault();
            mw.drag.plus.locked = true;
            mw.tools.addClass(mwd.body, 'mw-column-resizing');
        }
    });
});
