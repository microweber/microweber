mw.layoutPlus = {
    create: function(){
        this._top = $('<span class="mw-defaults mw-layout-plus mw-layout-plus-top">Add Layout</span>');
        this._bottom = $('<span class="mw-defaults mw-layout-plus mw-layout-plus-bottom">Add Layout</span>');
        mw.$(document.body).append(this._top).append(this._bottom);

        this._top.on('mouseenter', function () {
            mw.tools.addClass(document.body, 'body-mw-layout-plus-hover');
            mw.liveEditSelector.select(mw.layoutPlus._active);
        });
        this._top.on('mouseleave', function () {
            mw.tools.removeClass(document.body, 'body-mw-layout-plus-hover');
        });
        this._bottom.on('mouseenter', function () {
            mw.tools.addClass(document.body, 'body-mw-layout-plus-hover');
            mw.liveEditSelector.select(mw.layoutPlus._active);
        });
        this._bottom.on('mouseleave', function () {
            mw.tools.removeClass(document.body, 'body-mw-layout-plus-hover')
        });
    },
    hide: function () {
        this._top.css({top: -999, left: -999});
        this._bottom.css({top: -999, left: -999});
        this.pause = false;
    },
    pause: false,
    _active: null,
    position:function(){
        var $layout = mw.$(this._active);
        var off = $layout.offset();
        var left = (off.left + ($layout.outerWidth()/2));
        this._top.css({top: off.top - 20, left: left});
        this._bottom.css({top: off.top + $layout.outerHeight(), left: left});
    },
    _prepareList:function (tip, action) {
        var scope = this;
        var items = mw.$('.modules-list li', tip);
        items.removeClass('tip')
        mw.$('input', tip).on('input', function () {
            var val = this.value.trim();
            if(!val) {
                items.hide().filter('.mw-accordion-title-2').show()
                return;
            }
            mw.tools.search(this.value, items, function (found) {
                var visible = found && !this.classList.contains('mw-accordion-title-2')
                $(this)[ visible ? 'show' : 'hide']();
                if(visible) {
                    var img = this.querySelector('[data-url]');
                    if (img) {
                        img.src = img.dataset.url;
                        delete img.dataset.url
                    }
                }
            });
        });
        mw.$('.modules-list li', tip).on('click', function () {
            var id = mw.id('mw-layout-'), el = '<div id="' + id + '"></div>';
            var $active = mw.$(mw.layoutPlus._active);
            $active[action](el);

            var name = $active.attr('data-module-name');
            var template = $(this).attr('template');

            var conf = {};
            if(mw.layoutPlus._active){
            var conf = {class: mw.layoutPlus._active.className, template: template};
            }
            /*mw.liveEditState.record({
                action: function () {
                    mw.$('#' + id).replaceWith('<div id="' + id + '"></div>');
                }
            });*/

            mw.load_module('layouts', '#' + id, function () {
                mw.wysiwyg.change(document.getElementById(id));
                mw.drag.fixes();
                /*mw.liveEditState.record({
                    action: function () {
                        mw.load_module('layouts', '#' + id, undefined, conf);
                    }
                });*/
                setTimeout(function () {
                    mw.drag.fix_placeholders();
                }, 40);
                mw.dropable.hide();
                mw.layoutPlus.mode === 'Dialog' ? mw.dialog.get(tip).remove()  : $(tip).remove();
            }, conf);
            scope.pause = false;
        });
    },
    mode: 'Dialog', //'tooltip', 'Dialog',
    showSelectorUI: function (el) {
        var scope = this;
        scope.pause = true;
        var tip = new mw[mw.layoutPlus.mode]({
            content: document.getElementById('plus-layouts-list').innerHTML,
            element: el,
            position: 'right-center',
            template: 'mw-tooltip-default mw-tooltip-insert-module',
            id: 'mw-plus-tooltip-selector',
            title: mw.lang('Select layout'),
            width: 500,
            overlay: true
        });
        scope._prepareList(document.getElementById('mw-plus-tooltip-selector'), 'before');
        $('#mw-plus-tooltip-selector input').focus();
        $('#mw-plus-tooltip-selector').addClass('active');
    },
    initSelector: function () {
        var scope = this;
        this._top.on('click', function () {
            scope.showSelectorUI(this);
        });
        this._bottom.on('click', function () {
            scope.showSelectorUI(this);
        });

    },
    handle: function () {
        var scope = this;
        mw.$(window).on('resize', function (e) {
            if (scope._active) {
                scope.position();
            }
        });
        mw.on('moduleOver ModuleClick', function (e, module) {
            if (module.dataset.type === 'layouts' && !scope.pause) {
                scope._active = module;
                scope.position();
            } else {
                scope.hide();
            }
        });
    },
    _ready: false,
    init: function () {
        if (!this._ready) {
            this._ready = true;
            this.create();
            this.handle();
            this.initSelector();
        }
    }
};

$(window).on('load', function () {
    mw.layoutPlus.init();
});
