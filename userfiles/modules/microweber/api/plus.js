mw.drag = mw.drag || {};
mw.drag.plus = {
    locked: false,
    disabled: false,
   // mouse_moved: false,
    init: function (holder) {

        if(this.disabled) return;

        mw.drag.plusTop = mwd.querySelector('.mw-plus-top');
        mw.drag.plusBottom = mwd.querySelector('.mw-plus-bottom');

        if(mw.drag.plusTop) {
            mw.drag.plusTop.style.top = -9999 + 'px';
        }
        if(mw.drag.plusBottom) {
            mw.drag.plusBottom.style.top = -9999 + 'px';
        }
        mw.$(holder).on('mousemove touchmove click', function (e) {


            if (mw.drag.plus.locked === false && mw.isDrag === false) {
                if (e.pageY % 2 === 0 && mw.tools.isEditable(e)) {
                    var node = mw.drag.plus.selectNode(e.target);

                    mw.drag.plus.set(node);
                    mw.$(mwd.body).removeClass('editorKeyup');
                }
            }
            else {
                mw.drag.plusTop.style.top = -9999 + 'px';
                mw.drag.plusBottom.style.top = -9999 + 'px';
            }
        });
        mw.$(holder).on('mouseleave', function (e) {
            if (mw.drag.plus.locked === false) {
                mw.drag.plus.set(undefined);
            }
        });
        mw.drag.plus.action();
    },
    selectNode: function (target) {

        if(!target || mw.tools.hasAnyOfClassesOnNodeOrParent(target, ['noplus', 'noedit', 'noplus']) || mw.tools.hasClass(target, 'edit')) {
            mw.drag.plusTop.style.top = -9999 + 'px';
            mw.drag.plusBottom.style.top = -9999 + 'px';
            return;
        }
        var comp = mw.tools.firstMatchesOnNodeOrParent(target, ['.module', '.element', 'p', '.mw-empty']);

        if (comp
            && mw.tools.parentsOrCurrentOrderMatchOrOnlyFirstOrNone(target, ['regular-mode', 'safe-mode'])
            && mw.tools.parentsOrCurrentOrderMatchOrOnlyFirstOrNone(target, ['allow-drop', 'nodrop']))  {
            return comp;
        }
        else {
            mw.drag.plusTop.style.top = -9999 + 'px';
            mw.drag.plusBottom.style.top = -9999 + 'px';
            return undefined;
        }
    },
    set: function (node) {

        setTimeout(function () {

            if (typeof node === 'undefined') {
                return;
            }
            var $node = mw.$(node)
            var off = $node.offset(),
                toolbar = mwd.querySelector('#live_edit_toolbar');
            var oleft = Math.max(0, off.left - 10);
            if(toolbar && off.top < toolbar.offsetHeight){
              off.top = toolbar.offsetHeight + 10;
            }
            mw.drag.plusTop.style.top = off.top + 'px';
            mw.drag.plusTop.style.left = oleft + ($node.width()/2) + 'px';
            // mw.drag.plusTop.style.display = 'block';
            mw.drag.plusTop.currentNode = node;
            mw.drag.plusBottom.style.top = (off.top + node.offsetHeight) + 'px';
            mw.drag.plusBottom.style.left = oleft + ($node.width()/2) + 'px';
            mw.drag.plusBottom.currentNode = node;
            mw.tools.removeClass([mw.drag.plusTop, mw.drag.plusBottom], 'active');


        }, 100);

    },
    tipPosition: function (node) {
        var off = mw.$(node).offset();
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
        mw.$(pls).click(function () {
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
                    template: 'mw-tooltip-default mw-tooltip-insert-module',
                    id: 'mw-plus-tooltip-selector'
                });
                mw.tabs({
                    nav: tip.querySelectorAll('.mw-ui-btn'),
                    tabs: tip.querySelectorAll('.module-bubble-tab'),
                });

                mw.$('.mw-ui-searchfield', tip).on('keyup paste', function () {
                    var resultsLength = mw.drag.plus.search(this.value, tip);
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
            var name = mw.$(this).attr('data-module-name');
            if(name === 'layout'){
                var template = mw.$(this).attr('template');
                mw.$(this).attr('onclick', 'InsertModule("' + name + '", {class:this.className, template:"'+template+'"})');
            } else {
                mw.$(this).attr('onclick', 'InsertModule("' + name + '", {class:this.className})');
            }
        });
    },
    search: function (val, root) {
        var all = root.querySelectorAll('.module_name'),
            l = all.length,
            i = 0;
        val = val.toLowerCase();
        var found = 0;
        isEmpty = val.replace(/\s+/g, '') === '';
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
};

InsertModule = function (module, cls) {
    var id = 'mwemodule-' + mw.random(), el = '<div id="' + id + '"></div>', action;
    if (mw.drag.plusActive === 'top') {
        action = 'before';
        if(mw.tools.hasClass(mw.drag.plusTop.currentNode, 'allow-drop')) {
            action = 'prepend';
        }
    }
    else if (mw.drag.plusActive === 'bottom') {
        action = 'after';
        if(mw.tools.hasClass(mw.drag.plusTop.currentNode, 'allow-drop')) {
            action = 'append';
        }
    }
    mw.$(mw.drag.plusBottom.currentNode)[action](el);

    var el = $('#' + id).parent()[0];
    mw.load_module(module, '#' + id, function () {
        var node = document.getElementById(id)
        mw.wysiwyg.change(node);

        mw.drag.plus.locked = false;
        mw.drag.fixes();
        setTimeout(function () {
            mw.drag.fix_placeholders();
        }, 40);
        mw.dropable.hide();
    }, cls);
    mw.$('.mw-tooltip').hide();
};


