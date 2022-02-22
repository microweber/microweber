mw.liveedit.modulesToolbar = {
    init: function (selector) {
        var items = selector || ".modules-list li[data-module-name]";
        var $items = mw.$(items).not('.mt-ready').addClass('mt-ready');
        $items.on('mouseup touchend', function (){
            if(!document.body.classList.contains('dragStart')/* && !this.classList.contains('module-item-layout')*/) {
                if(this.classList.contains('module-item-layout')) {


                    /**/

                    var node = Array.from(document.querySelectorAll('.edit .module-layouts')).pop()
                    if(node) {
                        mw.element(node)['after'](this.outerHTML);
                        var next = this.nextElementSibling;
                        mw.tools.scrollTo(next, undefined, )

                        setTimeout(function (){
                            mw.drag.load_new_modules();

                            mw.wysiwyg.change(node.lastElementChild)
                        }, 78)
                    }

                    return;

                    /**/

                    var el = mw.liveEditSelector.selected[0];
                    var action = 'after';
                    var all;
                     if(!el || !document.body.contains(el) || !mw.tools.isEditable(el.parentNode)) {

                        el = null
                        all = document.querySelectorAll('.module-layouts'), i = 0, l = all.length;
                        for ( ; i < l; i++ ) {
                            if(mw.tools.inview(all[i]) && mw.tools.isEditable(all[i].parentNode)) {

                                el = all[i];
                                break;
                            }
                        }
                    }

                    if(!el){
                        el = document.querySelector('[data-layout-container]');

                        action = 'append';
                        if(el && mw.tools.isEditable(el)) {
                            mw.element(el)[action](this.outerHTML);
                            setTimeout(function (){
                                mw.drag.load_new_modules();
                                mw.tools.scrollTo(el.lastElementChild, undefined, 200)
                                mw.wysiwyg.change(el.lastElementChild)
                            }, 78)
                            return;
                        }
                    }

                    if(el) {

                        var layout = mw.tools.firstParentOrCurrentWithClass(el, 'module-layouts');

                        if(mw.tools.isEditable(layout.parentNode)){
                            mw.element(layout)[action](this.outerHTML);
                            setTimeout(function (){
                                mw.drag.load_new_modules();
                                mw.tools.scrollTo(layout.nextElementSibling, undefined, 200)
                                mw.wysiwyg.change(layout.nextElementSibling)
                            }, 78)
                        }
                    } else {
                        mw.notification.warning('Select element from the page or drag the <b>' + this.dataset.filter + '</b> to the desired place');
                    }
                } else {
                    if(mw.liveEditSelector.selected[0] && document.body.contains(mw.liveEditSelector.selected[0]) && mw.tools.isEditable(mw.liveEditSelector.selected[0].parentNode)) {
                         mw.element(mw.liveEditSelector.selected[0]).after(this.outerHTML);
                        setTimeout(function (){
                            mw.drag.load_new_modules();
                            mw.tools.scrollTo(mw.liveEditSelector.selected[0].nextElementSibling, undefined, 200)
                            mw.wysiwyg.change(mw.liveEditSelector.selected[0].nextElementSibling)
                        }, 78)
                    } else {
                        mw.notification.warning('Select element from the page or drag the <b>' + this.dataset.filter + '</b> to the desired place');
                    }
                }

            }
        });
        $items.draggable({
            revert: true,
            revertDuration: 0,
            distance: 20,
            start: function(a, b) {
                mw.isDrag = true;
                mw.dragCurrent = mw.ea.data.currentGrabbed = mw.GlobalModuleListHelper;
                mw.$(document.body).addClass("dragStart");
                mw.image_resizer._hide();

            },
            stop: function() {
                mw.isDrag = false;
                mw.pauseSave = true;
                var el = this;
                mw.$(document.body).removeClass("dragStart");
                setTimeout(function() {
                    mw.drag.load_new_modules();
                    mw.liveedit.recommend.increase($(mw.dragCurrent).attr("data-module-name"));
                    mw.drag.toolbar_modules(el);
                }, 200);
            }
        });
        $items.on('mouseenter touchstart', function() {
            mw.$(this).draggable("option", "helper", function() {
                var el = $(this);
                var clone = el.clone(true);
                clone.appendTo(document.body);
                clone.addClass('mw-module-drag-clone');
                mw.GlobalModuleListHelper = clone[0];
                clone.css({
                    width: el.width(),
                    height: el.height()
                });
                return clone[0];
            });
        });

    }
};
