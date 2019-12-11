mw.layoutPlus = {
    create: function(){
        this._top = $('<span class="mw-defaults mw-layout-plus mw-layout-plus-top"></span>');
        this._bottom = $('<span class="mw-defaults mw-layout-plus mw-layout-plus-bottom"></span>');
        mw.$(document.body).append(this._top).append(this._bottom);
    },
    init: function () {
        this.create();
        var scope = this;
        mw.on('LayoutOver', function (e, layout) {
            var $layout = mw.$(layout);
            var off = $layout.offset();
            var left = (off.left + ($layout.outerWidth()/2));
            scope._top.style.top = off.top + 'px';
            scope._top.style.left = left + 'px';
            scope._bottom.style.top = (off.top + $layout.outerHeight()) + 'px';
            scope._bottom.style.left = left + 'px';
        });
    }
};
