mw.drag = mw.drag || {};
mw.drag.plus = {
    locked: false,
    init: function (holder) {
        mw.drag.plusTop = mwd.querySelector('.mw-plus-top');
        mw.drag.plusBottom = mwd.querySelector('.mw-plus-bottom');
        mw.$(holder).bind('mousemove', function (e) {
            if (mw.drag.plus.locked === false && mw.isDrag === false) {
                if (e.pageY % 2 === 0) {
                    var node = mw.drag.plus.selectNode(e.target);
                    mw.drag.plus.set(node);
                    $(mwd.body).removeClass('editorKeyup');
                }
            }
            else {
                mw.drag.plusTop.style.top = -9999 + 'px';
                mw.drag.plusBottom.style.top = -9999 + 'px';
            }
        });
        mw.$(holder).bind('mouseleave', function (e) {
            if (mw.drag.plus.locked === false) {
                mw.drag.plus.set(undefined);
            }
        });
        mw.drag.plus.action();
    },
    selectNode: function (target) {

        if (target === undefined || target === null || mw.tools.hasClass(target, 'nodrop') || mw.tools.hasParentsWithClass(target, 'noedit') || mw.tools.hasParentsWithClass(target, 'noplus') || mw.tools.hasParentsWithClass(target, 'nodrop') || mw.tools.hasClass(target, 'edit')) {

            mw.drag.plusTop.style.top = -9999 + 'px';
            mw.drag.plusBottom.style.top = -9999 + 'px';
            return undefined;
        }
        if (mw.tools.hasClass(target, 'module')) {
            return target;
        }
        else if (mw.tools.hasParentsWithClass(target, 'module')) {
            return mw.tools.lastParentWithClass(target, 'module');
        }
        else if (mw.tools.hasClass(target, 'element')) {
            return target;
        }
        else if (target.nodeName === 'P' || target === mw.image_resizer) {
            return target;
        }
        else if (mw.tools.hasParentsWithTag(target, 'p')) {
            return mw.tools.firstParentWithTag(target, 'p');
        }
        else if (mw.tools.hasClass(target, 'mw-empty')) {
            return target;
        }
        else {
            mw.drag.plusTop.style.top = -9999 + 'px';
            mw.drag.plusBottom.style.top = -9999 + 'px';
            return undefined;
        }
    },
    set: function (node) {

        var element_hover_delay;


        element_hover_delay = setTimeout(function () {


            if (typeof node === 'undefined') {
                return;
            }
            var off = $(node).offset();
            var left = off.left;
            if (left < 15) {
                //  var left = 15;
            }

            mw.drag.plusTop.style.top = off.top + 'px';
            mw.drag.plusTop.style.left = left + 'px';
            // mw.drag.plusTop.style.display = 'block';
            mw.drag.plusTop.currentNode = node;
            mw.drag.plusBottom.style.top = (off.top + node.offsetHeight) + 'px';
            mw.drag.plusBottom.style.left = left + 'px';
            mw.drag.plusBottom.currentNode = node;
            mw.tools.removeClass([mw.drag.plusTop, mw.drag.plusBottom], 'active');


        }, 100);

    },
    tipPosition: function (node) {
        var off = $(node).offset();
        if (off.top > 130) {
            if ((off.top + node.offsetHeight) < ($(mwd.body).height() - 130)) {
                return 'right-center';
            }
            else {
                return 'right-bottom';
            }
        }
        else {
            return 'right-top';
        }
    },
    action: function () {
        var pls = [mw.drag.plusTop, mw.drag.plusBottom];
        $(pls).click(function () {
            var other = this === mw.drag.plusTop ? mw.drag.plusBottom : mw.drag.plusTop;
            if (!mw.tools.hasClass(this, 'active')) {
                mw.tools.addClass(this, 'active');
                mw.tools.removeClass(other, 'active');
                mw.drag.plus.locked = true;
                mw.$('.mw-tooltip-insert-module').remove();
                mw.drag.plusActive = this === mw.drag.plusTop ? 'top' : 'bottom';
                var tip = new mw.tooltip({
                    content: mwd.getElementById('plus-modules-list').innerHTML,
                    element: this,
                    position: mw.drag.plus.tipPosition(this.currentNode),
                    template: 'mw-tooltip-default mw-tooltip-insert-module'
                });
                mw.tabs({
                    nav: tip.querySelectorAll('.mw-ui-btn'),
                    tabs: tip.querySelectorAll('.module-bubble-tab'),
                });

                mw.$('.mw-ui-searchfield', tip).bind('keyup paste', function () {
                    var resultsLength = mw.drag.plus.search(this.value, mw.$('.module-bubble-tab:visible', tip)[0]);
                    if (resultsLength === 0) {
                        mw.$('.module-bubble-tab-not-found-message').html(mw.msg.no_results_for + ': <em>' + this.value + '</em>').show();
                    }
                    else {
                        mw.$(".module-bubble-tab-not-found-message").hide();
                    }
                });
            }
        });
        mw.$('#plus-modules-list li').each(function () {
            var name = $(this).attr('data-module-name');
            $(this).attr('onclick', 'InsertModule("' + name + '", {class:this.className})');
        });
    },
    search: function (val, root) {
        var all = root.querySelectorAll('.module_name'),
            l = all.length,
            i = 0,
            val = val.toLowerCase(),
            found = 0;
        isEmpty = val.replace(/\s+/g, '') == '';
        for (; i < l; i++) {
            var text = all[i].textContent.toLowerCase();
            if (text.contains(val) || isEmpty) {
                mw.tools.firstParentWithTag(all[i], 'li').style.display = 'list-item';
                if (text.contains(val)) found++;
            }
            else {
                mw.tools.firstParentWithTag(all[i], 'li').style.display = 'none';
            }
        }
        return found;
    }
}

InsertModule = function (module, cls) {
    var id = 'mwemodule-' + mw.random(), el = '<div id="' + id + '"></div>';
    if (mw.drag.plusActive == 'top') {
        $(mw.drag.plusTop.currentNode).before(el);
    }
    else if (mw.drag.plusActive == 'bottom') {
        $(mw.drag.plusBottom.currentNode).after(el);
    }

    mw.load_module(module, '#' + id, function () {
        mw.wysiwyg.change(document.getElementById(id));
        mw.drag.plus.locked = false;
        mw.drag.fixes();
        setTimeout(function () {
            mw.drag.fix_placeholders();
        }, 40);
        mw.resizable_columns();
        mw.dropable.hide();
    }, cls);
    mw.$('.mw-tooltip').hide();
}