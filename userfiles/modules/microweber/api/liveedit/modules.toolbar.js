mw.liveedit.modulesToolbar = {
    init: function (selector) {
        var items = selector || ".modules-list li[data-module-name]";
        var $items = mw.$(items);
        $items.on('mouseup touchend', function (){
            if(!document.body.classList.contains('dragStart')/* && !this.classList.contains('module-item-layout')*/) {
                if(this.classList.contains('module-item-layout')) {

                    var el = mw.liveEditSelector.selected[0];
                    if(!el) {
                        var all = document.querySelectorAll('.module-layouts'), i = 0, l = all.length;
                        for ( ; i < l; i++ ) {
                            if(mw.tools.inview(all[i])) {
                                el = all[i];
                                break;
                            }
                        }
                    }

                    if(el) {
                        var layout = mw.tools.firstParentOrCurrentWithClass(el, 'module-layouts');

                        mw.element(layout).after(this.outerHTML);
                        setTimeout(function (){
                            mw.drag.load_new_modules();
                            mw.tools.scrollTo(layout.nextElementSibling, undefined, 200)
                            mw.wysiwyg.change(layout.nextElementSibling)
                        }, 78)
                    } else {
                        mw.notification.warning('Select element from the page or drag the <b>' + this.dataset.filter + '</b> to the desired place');
                    }
                } else {
                    if(mw.liveEditSelector.selected[0]) {
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
