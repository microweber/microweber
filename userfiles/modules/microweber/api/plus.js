mw.drag = mw.drag || {};
mw.drag.plus = {
    locked: false,
    disabled: false,
   // mouse_moved: false,
    init: function (holder) {

        if(this.disabled) return;

        mw.drag.plusTop = document.querySelector('.mw-plus-top');
        mw.drag.plusBottom = document.querySelector('.mw-plus-bottom');

        if(mw.drag.plusTop) {
            mw.drag.plusTop.style.top = -9999 + 'px';
        }
        if(mw.drag.plusBottom) {
            mw.drag.plusBottom.style.top = -9999 + 'px';
        }
        mw.$(holder).on('mousemove touchmove click', function (e) {


            if (mw.drag.plus.locked === false && mw.isDrag === false) {
                if (e.pageY % 2 === 0 && mw.tools.isEditable(e)) {
                    var whichPlus;

                    var node = mw.drag.plus.selectNode(e.target);
                    if(node && e.type === 'mousemove') {
                        var off = $(node).offset();
                        whichPlus = (e.pageY - off.top) > ((off.top + node.offsetHeight) - e.pageY) ? 'top' : 'bottom';
                    }
                    mw.drag.plus.set(node, whichPlus);
                    mw.$(document.body).removeClass('editorKeyup');
                }
            }
            else {
                mw.drag.plusTop.style.top = -9999 + 'px';
                mw.drag.plusBottom.style.top = -9999 + 'px';
            }
        });
        mw.$(holder).on('mouseleave', function (e) {
            if (mw.drag.plus.locked === false && (e.target !== mw.drag.plusTop && e.target !== mw.drag.plusBottom) ) {
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
            // && mw.tools.parentsOrCurrentOrderMatchOrOnlyFirstOrNone(target, ['regular-mode', 'safe-mode'])
            && mw.tools.parentsOrCurrentOrderMatchOrOnlyFirstOrNone(target, ['allow-drop', 'nodrop']))  {
            return comp;
        }
        else {
            mw.drag.plusTop.style.top = -9999 + 'px';
            mw.drag.plusBottom.style.top = -9999 + 'px';
            return undefined;
        }
    },
    set: function (node, whichPlus) {
            if (typeof node === 'undefined') {
                return;
            }
            var $node = mw.$(node)
            var off = $node.offset(),
                toolbar = document.querySelector('#live_edit_toolbar');
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
            if(whichPlus) {
                if(whichPlus === 'top') {
                    mw.drag.plusTop.style.top = -9999 + 'px';

                } else {
                     mw.drag.plusBottom.style.top = -9999 + 'px';
                }
            }
            mw.drag.plusBottom.currentNode = node;
            mw.tools.removeClass([mw.drag.plusTop, mw.drag.plusBottom], 'active');

    },
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

    _rendModulesTip: null,
    rendModules: function (el) {
        var other = el === mw.drag.plusTop ? mw.drag.plusBottom : mw.drag.plusTop;
        console.log(el)
        if(mw.drag.plus._rendModulesTip) {
            mw.drag.plus._rendModulesTip.remove()
        }
         if (!mw.tools.hasClass(el, 'active')) {
             console.log(2)
            mw.tools.addClass(el, 'active');
            mw.tools.removeClass(other, 'active');
            mw.drag.plus.locked = true;
            mw.$('.mw-tooltip-insert-module').remove();
            mw.drag.plusActive = this === mw.drag.plusTop ? 'top' : 'bottom';

            var tip = mw.tooltip({
                content: document.getElementById('plus-modules-list').innerHTML,
                element: el,
                position: mw.drag.plus.tipPosition(this.currentNode),
                template: 'mw-tooltip-default mw-tooltip-insert-module',
                id: 'mw-plus-tooltip-selector',
                overlay: true
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
            mw.$('.modules-list li', tip).each(function () {
                this.addEventListener('click', function (){
                    var name = this.dataset.moduleName;
                    if(name === 'layout'){
                        var template = this.getAttribute('template');
                        mw.insertModule( name  , {class: this.className, template: template });
                    } else {
                        mw.insertModule( name  , {class: this.className,});
                    }
                    el.classList.remove('active');
                })
            });
             var getIcon = function (url) {
                 return new Promise(function (resolve){
                     if(mw._xhrIcons && mw._xhrIcons[url]) {
                         resolve(mw._xhrIcons[url]);
                     } else {
                         fetch(url, {cache: "force-cache"})
                             .then(function (data){
                                 return data.text();
                             }).then(function (data){
                             mw._xhrIcons[url] = data;
                             resolve(mw._xhrIcons[url])
                         });
                     }
                 });
             };



             $('[data-module-icon]').each(function (){

                 var src = this.dataset.moduleIcon.trim();
                 delete this.dataset.moduleIcon;
                 var img = this;
                 if(src.includes('.svg') && src.includes(location.origin)) {

                     var el = document.createElement('div');
                     el.className = img.className;
                     // var shadow = el.attachShadow({mode: 'open'});
                     var shadow = el ;
                     getIcon(src).then(function (data){

                          var shImg = document.createElement('div');
                         shImg.innerHTML = data;
                         shImg.part = 'mw-module-icon';
                         if(shImg.querySelector('svg') !== null) {
                             shImg.querySelector('svg').part = 'mw-module-icon-svg';
                             Array.from(shImg.querySelectorAll('style')).forEach(function (style) {
                                 style.remove()
                             })
                             Array.from(shImg.querySelectorAll('[id],[class]')).forEach(function (item) {
                                 item.removeAttribute('class')
                                 item.removeAttribute('id')
                             })
                             shadow.appendChild(shImg);
                             img.parentNode.replaceChild(el, img);
                         }
                     })
                 } else {
                     this.src = src;
                 }
             })


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


