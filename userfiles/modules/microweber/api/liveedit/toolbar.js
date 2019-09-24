mw.liveedit.toolbar = {
    fixPad: function () {
        mwd.body.style.paddingTop = parseFloat($(mwd.body).css("paddingTop")) + mw.$("#live_edit_toolbar").height() + 'px';
    },
    setEditor: function(){
        /*mw
            .$(mwd.querySelector('.editor_wrapper_tabled'))
            .css({
                left: mw.$(mwd.querySelector('.toolbar-sections-tabs')).outerWidth(true) + mw.$(mwd.querySelector('.wysiwyg-undo-redo')).outerWidth(true) + 30,
                right: mw.$(mwd.querySelector('#mw-toolbar-right')).outerWidth(true)
            });*/
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
        mw.$(window).scroll(function() {
            if ($(window).scrollTop() > 10) {
                mw.tools.addClass(mwd.getElementById('live_edit_toolbar'), 'scrolling');
            } else {
                mw.tools.removeClass(mwd.getElementById('live_edit_toolbar'), 'scrolling');
            }

        });
        mw.$("#live_edit_toolbar").hover(function() {
            mw.$(mwd.body).addClass("toolbar-hover");
        }, function() {
            mw.$(mwd.body).removeClass("toolbar-hover");
        });
    },
    editor: {
        init: function () {
            this.ed = mwd.getElementById('liveedit_wysiwyg');
            this.nextBTNS = mw.$(".liveedit_wysiwyg_next");
            this.prevBTNS = mw.$(".liveedit_wysiwyg_prev") ;
        },
        calc: {
            SliderButtonsNeeded: function (parent) {
                var t = {left: false, right: false};
                if (parent == null || !parent) {
                    return;
                }
                var el = parent.firstElementChild;
                if ($(parent).width() > mw.$(el).width()) return t;
                var a = mw.$(parent).offset().left + mw.$(parent).width();
                var b = mw.$(el).offset().left + mw.$(el).width();
                if (b > a) {
                    t.right = true;
                }
                if ($(el).offset().left < mw.$(parent).offset().left) {
                    t.left = true;
                }
                return t;
            },
            SliderNormalize: function (parent) {
                if (parent === null || !parent) {
                    return false;
                }
                var el = parent.firstElementChild;
                var a = mw.$(parent).offset().left + mw.$(parent).width();
                var b = mw.$(el).offset().left + mw.$(el).width();
                if (b < a) {
                    return (a - b);
                }
                return false;
            },
            SliderNext: function (parent, step) {
                if (parent === null || !parent) {
                    return false;
                }
                var el = parent.firstElementChild;
                if ($(parent).width() > mw.$(el).width()) return 0;
                var a = mw.$(parent).offset().left + mw.$(parent).width();
                var b = mw.$(el).offset().left + mw.$(el).width();
                step = step || mw.$(parent).width();
                var curr = parseFloat(window.getComputedStyle(el, null).left);
                if (a < b) {
                    if ((b - step) > a) {
                        return (curr - step);
                    }
                    else {
                        return curr - (b - a);
                    }
                }
                else {
                    return curr - (b - a);
                }
            },
            SliderPrev: function (parent, step) {
                if (parent === null || !parent) {
                    return false;
                }
                var el = parent.firstElementChild;
                step = step || mw.$(parent).width();
                var curr = parseFloat(window.getComputedStyle(el, null).left);
                if (curr < 0) {
                    if ((curr + step) < 0) {
                        return (curr + step);
                    }
                    else {
                        return 0;
                    }
                }
                else {
                    return 0;
                }
            }
        },

        step: function () {
            return $(mw.liveedit.toolbar.editor.ed).width();
        },
        denied: false,
        buttons: function () {
            var b = mw.liveedit.toolbar.editor.calc.SliderButtonsNeeded(mw.liveedit.toolbar.editor.ed);
            if (!b) {
                return;
            }
            if (b.left) {
                mw.liveedit.toolbar.editor.prevBTNS.addClass('active');
            }
            else {
                mw.liveedit.toolbar.editor.prevBTNS.removeClass('active');
            }
            if (b.right) {
                mw.liveedit.toolbar.editor.nextBTNS.addClass('active');
            }
            else {
                mw.liveedit.toolbar.editor.nextBTNS.removeClass('active');
            }
        },
        slideLeft: function () {
            if (!mw.liveedit.toolbar.editor.denied) {
                mw.liveedit.toolbar.editor.denied = true;
                var el = mw.liveedit.toolbar.editor.ed.firstElementChild;
                var to = mw.liveedit.toolbar.editor.calc.SliderPrev(mw.liveedit.toolbar.editor.ed, mw.liveedit.toolbar.editor.step());
                $(el).animate({left: to}, function () {
                    mw.liveedit.toolbar.editor.denied = false;
                    mw.liveedit.toolbar.editor.buttons();
                });
            }
        },
        slideRight: function () {
            if (!mw.liveedit.toolbar.editor.denied) {
                mw.liveedit.toolbar.editor.denied = true;
                var el = mw.liveedit.toolbar.editor.ed.firstElementChild;

                var to = mw.liveedit.toolbar.editor.calc.SliderNext(mw.liveedit.toolbar.editor.ed, mw.liveedit.toolbar.editor.step());
                $(el).animate({left: to}, function () {
                    mw.liveedit.toolbar.editor.denied = false;
                    mw.liveedit.toolbar.editor.buttons();
                });
            }
        },
        fixConvertible: function (who) {
            who = who || ".wysiwyg-convertible";
            who = $(who);
            if (who.length > 1) {
                $(who).each(function () {
                    mw.liveedit.toolbar.editor.fixConvertible(this);
                });
                return false;
            }
            else {
                var w = $(window).width();
                var w1 = who.offset().left + who.width();
                if (w1 > w) {
                    who.css("left", -(w1 - w));
                }
                else {
                    who.css("left", 0);
                }
            }
        }
    }

};
