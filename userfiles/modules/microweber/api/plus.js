mw.drag = mw.drag || {};
mw.drag.plus = {
    locked: false,
    disabled: false,

    tipPosition: function (node) {
        return 'right-center';
        var off = mw.$(node).offset();
        if (off.top > 130) {
            if ((off.top + node.offsetHeight) < ($(document.body).height() - 130)) {
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
    rendModules: function (el) {
        var other = el === mw.drag.plusTop ? mw.drag.plusBottom : mw.drag.plusTop;
         if (!mw.tools.hasClass(el, 'active')) {
            mw.tools.addClass(el, 'active');
            mw.tools.removeClass(other, 'active');
            mw.drag.plus.locked = true;
            mw.$('.mw-tooltip-insert-module').remove();
            mw.drag.plusActive = this === mw.drag.plusTop ? 'top' : 'bottom';

            var tip = new mw.tooltip({
                content: document.getElementById('plus-modules-list').innerHTML,
                element: el,
                position: mw.drag.plus.tipPosition(this.currentNode),
                template: 'mw-tooltip-default mw-tooltip-insert-module',
                id: 'mw-plus-tooltip-selector'
            });
            setTimeout(function (){
                $('#mw-plus-tooltip-selector').addClass('active').find('.mw-ui-searchfield').focus();
            }, 10)
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
            mw.$('#plus-modules-list li').each(function () {
                var name = mw.$(this).attr('data-module-name');
                if(name === 'layout'){
                    var template = mw.$(this).attr('template');
                    mw.$(this).attr('onclick', 'mw.insertModule("' + name + '", {class:this.className, template:"'+template+'"})');
                } else {
                    mw.$(this).attr('onclick', 'mw.insertModule("' + name + '", {class:this.className})');
                }
            });

        }
    },
    action: function () {
        var pls = [mw.drag.plusTop, mw.drag.plusBottom];
        var $pls = mw.$(pls);
        $pls.on('mouseenter', function () {
            mw.tools.addClass(document.body, 'body-mw-module-plus-hover');
            mw.liveEditSelector.select(mw.drag.plusTop.currentNode);
        });
        $pls.on('mouseleave', function () {
            mw.tools.removeClass(document.body, 'body-mw-module-plus-hover')
        });
        $pls.on('click', function () {
            mw.drag.plus.rendModules(this)
        });

    },
    search: function (val, root) {
        var all = root.querySelectorAll('.module_name'),
            l = all.length,
            i = 0;
        val = val.toLowerCase();
        var found = 0;
        var isEmpty = val.replace(/\s+/g, '') === '';
        for (; i < l; i++) {
            var text = all[i].innerHTML.toLowerCase();
            var li = mw.tools.firstParentWithTag(all[i], 'li');
            var filter = (li.dataset.filter || '').trim().toLowerCase();
            if (text.contains(val) || isEmpty) {
                li.style.display = '';
                if (text.contains(val)) found++;
            }
            else if(filter.contains(val)){
                li.style.display = '';
                found++;
            }
            else {
                li.style.display = 'none';
            }
        }
        return found;
    }
};


var insertModule = function (target, module, config, pos) {
    return new Promise(function (resolve) {
        pos = pos || 'bottom';
        var action;
        var id = mw.id('mw-module-'), el = '<div id="' + id + '"></div>';
        if (pos === 'top') {
            action = 'before';
            if(mw.tools.hasClass(target, 'allow-drop')) {
                action = 'prepend';
            }
        } else if (pos === 'bottom') {
            action = 'after';
            if(mw.tools.hasClass(target, 'allow-drop')) {
                action = 'append';
            }
        }
        mw.$(mw.drag.plusBottom.currentNode)[action](el);
        mw.load_module(module, '#' + id, function () {
            resolve(this);
        }, config);
    });
};

mw.insertModule = function (module, cls) {

    var position = mw.drag.plusActive === 'top' ? 'top' : 'bottom';

    insertModule(mw.drag.plusTop.currentNode, module, cls, position).then(function (el) {
        mw.wysiwyg.change(el);
        mw.drag.plus.locked = false;
        mw.drag.fixes();
        setTimeout(function () { mw.drag.fix_placeholders(); }, 40);
        mw.dropable.hide();
        mw.wysiwyg.change(mw.drag.plusTop.currentNode);
    });
    mw.$('.mw-tooltip').hide();

};


