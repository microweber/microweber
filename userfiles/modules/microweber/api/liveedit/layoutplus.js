mw.layoutPlus = {
    create: function(){
        this._top = $('<span class="mw-defaults mw-layout-plus mw-layout-plus-top"></span>');
        this._bottom = $('<span class="mw-defaults mw-layout-plus mw-layout-plus-bottom"></span>');
        mw.$(document.body).append(this._top).append(this._bottom);
    },
    hide: function () {
        this._top.css({top: -999, left: -999});
        this._bottom.css({top: -999, left: -999});
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
        mw.$('.modules-list li', tip).on('click', function () {
            var id = mw.id('mw-layout-'), el = '<div id="' + id + '"></div>';
            var $active = mw.$(mw.layoutPlus._active);
            $active[action](el);
            var name = $active.attr('data-module-name');
            var template = $(this).attr('template');
            var conf = {class: mw.layoutPlus._active.className, template: template};
            mw.load_module('layouts', '#' + id, function () {
                mw.wysiwyg.change(document.getElementById(id));
                mw.drag.fixes();
                setTimeout(function () {
                    mw.drag.fix_placeholders();
                }, 40);
                mw.dropable.hide();
            }, conf);
            $(tip).remove();
            scope.pause = false;
        });
    },
    initSelector: function () {
        var scope = this;
        this._top.on('click', function () {
            scope.pause = true;
            var tip = new mw.tooltip({
                content: mwd.getElementById('plus-layouts-list').innerHTML,
                element: this,
                position: 'top-center',
                template: 'mw-tooltip-default mw-tooltip-insert-module',
                id: 'mw-plus-tooltip-selector'
            });
            scope._prepareList(tip, 'before');
        });
        this._bottom.on('click', function () {
            scope.pause = true;
            var tip = new mw.tooltip({
                content: mwd.getElementById('plus-layouts-list').innerHTML,
                element: this,
                position: 'bottom-center',
                template: 'mw-tooltip-default mw-tooltip-insert-module',
                id: 'mw-plus-tooltip-selector'
            });
            scope._prepareList(tip, 'after');
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
            if(module.dataset.type === 'layouts' && !scope.pause) {
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
    // mw.layoutPlus.init();
});
