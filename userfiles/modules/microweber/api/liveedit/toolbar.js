mw.liveedit.toolbar = {
    fixPad: function () {
        mwd.body.style.paddingTop = parseFloat($(mwd.body).css("paddingTop")) + mw.$("#live_edit_toolbar").height() + 'px';
    },
    setEditor: function(){
        mw
            .$(mwd.querySelector('.editor_wrapper_tabled'))
            .css({
                left: mw.$(mwd.querySelector('.toolbar-sections-tabs')).outerWidth(true) + mw.$(mwd.querySelector('.wysiwyg-undo-redo')).outerWidth(true) + 30,
                right: mw.$(mwd.querySelector('#mw-toolbar-right')).outerWidth(true)
            });
    },
    prepare: function () {
        mw.$("#liveedit_wysiwyg")
            .on('mousedown touchstart',function() {
                if (mw.$(".mw_editor_btn_hover").length === 0) {
                    mw.mouseDownOnEditor = true;
                    mw.$(this).addClass("hover");
                }
            })
            .on('mouseup touchend',function() {
                mw.mouseDownOnEditor = false;
                mw.$(this).removeClass("hover");
            });
    }
};
