mw.require('selector.js');

var _handleInsertTargetDisplay;
var handleInsertTargetDisplay = function (target, pos) {
     if(!_handleInsertTargetDisplay) {
        _handleInsertTargetDisplay = mw.element('<div class="mw-handle-insert-target-display" />');
        mw.element(document.body).append(_handleInsertTargetDisplay);
    }
    if (target === 'hide'){
       _handleInsertTargetDisplay
           .removeClass('mw-handle-insert-target-display-top')
           .removeClass('mw-handle-insert-target-display-bottom')
           .hide();
       return;
    }   else {
        _handleInsertTargetDisplay.show();
    }

    var $el = $(target);
    var off = $el.offset();
    var css = { left: off.left, width: $el.outerWidth()};
    if (pos === 'top') {
        css.top = off.top;
    } else if(pos === 'bottom') {
        css.top = off.top + $el.outerHeight();
    }
    _handleInsertTargetDisplay
        .removeClass('mw-handle-insert-target-display-top')
        .removeClass('mw-handle-insert-target-display-bottom')
        .addClass(pos === 'top' ? 'mw-handle-insert-target-display-top' : 'mw-handle-insert-target-display-bottom')
        .css(css);
}

var dynamicModulesMenuTime = null;
var dynamicModulesMenu = function(e, el) {
    if(!mw.inaccessibleModules){
        mw.inaccessibleModules = document.createElement('div');
        mw.inaccessibleModules.className = 'mw-ui-btn-nav mwInaccessibleModulesMenu';
        document.body.appendChild(mw.inaccessibleModules);
        mw.$(mw.inaccessibleModules).on('mouseenter', function(){
            this._hovered = true;
        }).on('mouseleave', function(){
            this._hovered = false;
        });
    }

    var $el;
    if(e.type === 'moduleOver' || e.type === 'ModuleClick'){

        var parentModule = mw.tools.lastParentWithClass(el, 'module');
        var childModule = mw.tools.firstChildWithClass(el, 'module');

        $el = mw.$(el);
        if(!!parentModule && ( $el.offset().top - mw.$(parentModule).offset().top) < 10 ){
            el.__disableModuleTrigger = parentModule;
            $el.addClass('inaccessibleModule');
        }
        else if(!!childModule && ( mw.$(childModule).offset().top - $el.offset().top) < 10 ) {
            childModule.__disableModuleTrigger = el;
            mw.$(childModule).addClass('inaccessibleModule');
        }
        else{
            $el.removeClass('inaccessibleModule');
        }
    }


    var modules = mw.$(".inaccessibleModule", el);
    if(modules.length === 0){
        var parent = mw.tools.firstParentWithClass(el, 'module');
        if(parent){
            if(($el.offset().top - mw.$(parent).offset().top) < 10) {
                modules = mw.$([el]);
                el = parent;
                $el = mw.$(el);

            }
        }
    }
    if (e.type === 'ModuleClick') {
        mw.liveEditSelector.select(el);
    }

    if(modules.length && !mw.inaccessibleModules._hovered) {
        mw.inaccessibleModules.innerHTML = '';
    }
    modules.each(function(){
        var span = document.createElement('span');
        span.className = 'mw-handle-item mw-ui-btn mw-ui-btn-small';
        var type = mw.$(this).attr('data-type') || mw.$(this).attr('type');
        if(type){
            var title = mw.live_edit.registry[type] ? mw.live_edit.registry[type].title : type;
            title = title.replace(/\_/, ' ');
            span.innerHTML = mw.live_edit.getModuleIcon(type) + title;
            var el = this;
            span.onclick = function(){
                mw.tools.module_settings(el);
            };
            mw.inaccessibleModules.appendChild(span);
        }
    });
    if(modules.length > 0){
        var off = mw.$(el).offset();
        if(mw.tools.collision(el, mw.handleModule.wrapper)){
            off.top = parseFloat(mw.handleModule.wrapper.style.top);
            off.left = parseFloat(mw.handleModule.wrapper.style.left);
        }
        mw.inaccessibleModules.style.top = off.top + 'px';
        mw.inaccessibleModules.style.left = off.left + 'px';
        clearTimeout(dynamicModulesMenuTime);
        mw.$(mw.inaccessibleModules).show();
    }
    else{
        dynamicModulesMenuTime = setTimeout(function(){
            if(!mw.inaccessibleModules._hovered) {
                mw.$(mw.inaccessibleModules).hide();
            }

        }, 3000);

    }


    return $el[0];

};

var handleDomtreeSync = {};

mw.Handle = function(options) {

    this.options = options || {};

    var scope = this;

    this._visible = true;
    this.visible = function () {
        return this._visible;
    };

    this.createWrapper = function() {
        this.wrapper = document.createElement('div');
        this.wrapper.id = this.options.id || ('mw-handle-' + mw.random());
        this.wrapper.className = 'mw-defaults mw-handle-item ' + (this.options.className || 'mw-handle-type-default');
        this.wrapper.contenteditable = false;

        mw.$(this.wrapper).on('mousedown', function () {
            mw.tools.addClass(this, 'mw-handle-item-mouse-down');
        });
        mw.$(document).on('mouseup', function () {
            mw.tools.removeClass(scope.wrapper, 'mw-handle-item-mouse-down');
        });
        document.body.appendChild(this.wrapper);
    };

    this.create = function() {
        this.createWrapper();
        this.createHandler();

        this.createMenu();
    };

    this.setTitle = function (icon, title) {
        this.handleIcon.innerHTML = icon;
        this.handleTitle.innerHTML = title;
    };

    this.hide = function () {
        mw.$(this.wrapper).hide().removeClass('active');
        this._visible = false;
        return this;
    };

    this.show = function () {
        mw.$(this.wrapper).show();
        this._visible = true;
        return this;
    };

    this.createHandler = function(){
        this.handle = document.createElement('span');
        this.handleIcon = document.createElement('span');
        this.handleTitle = document.createElement('span');
        this.handle.className = 'mw-handle-handler';
        this.handleIcon.dataset.tip = 'Drag to rearrange';
        this.handleIcon.className = 'tip mw-handle-handler-icon';
        this.handleTitle.className = 'mw-handle-handler-title';

        this.handle.appendChild(this.handleIcon);
        this.createButtons();
        this.handle.appendChild(this.handleTitle);
        this.wrapper.appendChild(this.handle);

        this.handleTitle.onclick = function () {
            mw.$(scope.wrapper).toggleClass('active');
        };
        mw.$(document.body).on('click', function (e) {
            if(!mw.tools.hasParentWithId(e.target, scope.wrapper.id)){
                mw.$(scope.wrapper).removeClass('active');
            }
        });
    };

    this.menuButton = function (data) {
        var btn = document.createElement('span');
        btn.className = 'mw-handle-menu-item';
        if(data.icon) {
            var iconClass = data.icon;
            if (iconClass.indexOf('mdi-') === 0) {
                iconClass = 'mdi ' + iconClass
            }
            var icon = document.createElement('span');
            icon.className = iconClass + ' mw-handle-menu-item-icon';
            btn.appendChild(icon);
        }
        btn.appendChild(document.createTextNode(data.title));
        if(data.className){
            btn.className += (' ' + data.className);
        }
        if(data.id){
            btn.id = data.id;
        }
        if(data.action){
            btn.onmousedown = function (e) {
                e.preventDefault();
            };
            btn.onclick = function (e) {
                e.preventDefault();
                data.action.call(scope, e, this, data);
                scope.hide()
            };
        }
        return btn;
    };

    this._defaultButtons = [

    ];

    this.createMenuDynamicHolder = function(item){
        var dn = document.createElement('div');
        dn.className = 'mw-handle-menu-dynamic' + (item.className ? ' ' + item.className : '');
        return dn;
    };
    this.createMenu = function(){
        this.menu = document.createElement('div');
        this.menu.addEventListener('mouseleave', function () {
            scope.handle.classList.remove('active')
        })
        this.menu.className = 'mw-handle-menu ' + (this.options.menuClass ? this.options.menuClass : 'mw-handle-menu-default');
        if (this.options.menu) {
            for (var i = 0; i < this.options.menu.length; i++) {
                if(this.options.menu[i].title !== '{dynamic}') {
                    this.menu.appendChild(this.menuButton(this.options.menu[i])) ;
                }
                else {
                    this.menu.appendChild(this.createMenuDynamicHolder(this.options.menu[i])) ;
                }

            }
        }
        this.wrapper.appendChild(this.menu);
    };
    this.createButton = function(obj){
        var btn = document.createElement('span');
        btn.className = 'tip mdi ' + obj.icon + (obj.className ? ' ' + obj.className : '');
        btn.dataset.tip = obj.title;
        if (obj.hover) {
            btn.addEventListener('mouseenter', obj.hover[0] , false);
            btn.addEventListener('mouseleave', obj.hover[1] , false);
        }
        btn.onclick = function () {
            mw.tools.removeClass(this, 'active')
            obj.action(this);
            scope.hide();
        };
        return btn;
    };

    this.createButtons = function(){
        this.buttonsHolder = document.createElement('div');
        this.buttonsHolder.className = 'mw-handle-buttons';
        if (this.options.buttons) {
            for (var i = 0; i < this.options.buttons.length; i++) {
                this.buttonsHolder.appendChild(this.createButton(this.options.buttons[i])) ;
            }
        }
         this.handle.appendChild(this.buttonsHolder);
    };
    this.create();
    this.hide();
};

mw._activeModuleOver = {
    module: null,
    element: null
};

if(!mw._xhrIcons) {
    mw._xhrIcons = {}
}


mw._initHandles = {
    getNodeHandler:function (node) {
        if(mw._activeElementOver === node){
            return mw.handleElement
        } else if(mw._activeModuleOver === node) {
            return mw.handleModule
        } else if(mw._activeRowOver === node) {
            return mw.handleColumns;
        }
    },
    getAllNodes: function (but) {
        var all = [
            mw._activeModuleOver,
            mw._activeRowOver,
            mw._activeElementOver
        ];
        all = all.filter(function (item) {
            return !!item && item.nodeType === 1;
        });
        return all;
    },
    getAll: function (but) {
        var all = [
            mw.handleModule,
            mw.handleModuleActive,
            mw.handleColumns,
            mw.handleElement
        ];
        all = but ? all.filter(function (x) {
            return x !== but;
        }) :  all;
        return all.filter(function (item) {
            if(item){
                return item.visible();
            }

        });
    },
    hideAll:function (but) {
        this.getAll(but).forEach(function (item) {
            item.hide();
        });
    },
    collide: function(a, b) {
        return !(
            ((a.y + a.height) < (b.y)) ||
            (a.y > (b.y + b.height)) ||
            ((a.x + a.width) < b.x) ||
            (a.x > (b.x + b.width))
        );
    },
    _manageCollision: false,
    manageCollision:function () {

        var scope = this,
            max = 35,
            skip = [];

        scope.getAll().forEach(function (curr) {
            var master = curr, masterRect;
            //if (skip.indexOf(master) === -1){
            scope.getAll(curr).forEach(function (item) {
                masterRect = master.wrapper.getBoundingClientRect();
                var irect = item.wrapper.getBoundingClientRect();
                if (scope.collide(masterRect, irect)) {
                    skip.push(item);
                    var topMore = item === mw.handleElement ? 10 : 0;
                    item.wrapper.style.top = (parseInt(master.wrapper.style.top, 10) + topMore) + 'px';
                    item.wrapper.style.left = ((parseInt(master.wrapper.style.left, 10) + masterRect.width) + 10) + 'px';
                    master = curr;
                }
            });
        });

        var cloner = document.querySelector('.mw-cloneable-control');
        if(cloner) {
            scope.getAll().forEach(function (curr) {
                masterRect = curr.wrapper.getBoundingClientRect();
                var clonerect = cloner.getBoundingClientRect();

                if (scope.collide(masterRect, clonerect)) {
                    cloner.style.top = curr.wrapper.style.top;
                    cloner.style.left = ((parseInt(curr.wrapper.style.left, 10) + masterRect.width) + 10) + 'px';
                }
            });
        }
    },

    elements: function(){
        mw.handleElement = new mw.Handle({
            id: 'mw-handle-item-element',
            className: 'mw-handle-type-default',
            buttons: [
                    {
                        title: mw.lang('Insert'),
                        icon: 'mdi-plus',
                        className: 'mw-handle-insert-button',
                        hover: [
                            function (e){  handleInsertTargetDisplay(mw._activeElementOver, mw.handleElement.positionedAt)  },
                            function (e){  handleInsertTargetDisplay('hide')  }
                        ],
                        action: function (el) {
                             if (!mw.tools.hasClass(el, 'active')) {
                                mw.tools.addClass(el, 'active');
                                mw.drag.plus.locked = true;
                                mw.$('.mw-tooltip-insert-module').remove();
                                mw.drag.plusActive = this === mw.drag.plusTop ? 'top' : 'bottom';

                                var tooltip = new mw.ToolTip({
                                    content: document.getElementById('plus-modules-list').innerHTML,
                                    element: el,
                                    position: mw.drag.plus.tipPosition(this.currentNode),
                                    template: 'mw-tooltip-default mw-tooltip-insert-module',
                                    id: 'mw-plus-tooltip-selector',
                                    overlay: true
                                });
                                 tooltip.on('removed', function () {
                                     mw.drag.plus.locked = false;
                                 });
                                 mw._initHandles.hideAll();
                                 var tip = tooltip.tooltip.get(0);
                                setTimeout(function (){
                                    $('#mw-plus-tooltip-selector').addClass('active').find('.mw-ui-searchfield').focus();
                                }, 10);
                                mw.tabs({
                                    nav: tip.querySelectorAll('.mw-ui-btn'),
                                    tabs: tip.querySelectorAll('.module-bubble-tab'),
                                });

                                mw.$('.mw-ui-searchfield', tip).on('input', function () {
                                    var resultsLength = mw.drag.plus.search(this.value, tip);
                                    if (resultsLength === 0) {
                                        mw.$('.module-bubble-tab-not-found-message').html(mw.msg.no_results_for + ': <em>' + this.value + '</em>').show();
                                    }
                                    else {
                                        mw.$(".module-bubble-tab-not-found-message").hide();
                                    }
                                });
                                mw.$('#mw-plus-tooltip-selector li').each(function () {
                                    this.onclick = function () {
                                        var name = mw.$(this).attr('data-module-name');
                                        var conf = { class: this.className };
                                        if(name === 'layout') {
                                            conf.template = mw.$(this).attr('template');
                                        }
                                        mw.module.insert(mw._activeElementOver, name, conf, mw.handleElement.positionedAt);
                                        mw.wysiwyg.change(mw._activeElementOver)
                                        tooltip.remove();
                                    };
                                });
                                 var getIcon = function (url) {
                                     return new Promise(function (resolve){
                                         if(mw._xhrIcons && mw._xhrIcons[url]) {
                                             resolve(mw._xhrIcons[url])
                                         } else {
                                             fetch(url, {cache: "force-cache"})
                                                 .then(function (data){
                                                     return data.text();
                                                 }).then(function (data){
                                                 mw._xhrIcons[url] = data;
                                                 resolve(mw._xhrIcons[url])
                                             })
                                         }
                                     })
                                 }

                                 $('[data-module-icon]').each(function (){

                                     var src = this.dataset.moduleIcon.trim();
                                     delete this.dataset.moduleIcon;
                                     var img = this;
                                     if(src.includes('.svg') && src.includes(location.origin)) {
                                         var el = document.createElement('div');
                                         el.className = img.className;
                                         //var shadow = el.attachShadow({mode: 'open'});
                                         var shadow = el;
                                         getIcon(src).then(function (data){
                                             var shImg = document.createElement('div');
                                             shImg.innerHTML = data;
                                             shImg.part = 'mw-module-icon';
                                             shImg.querySelector('svg').part = 'mw-module-icon-svg';
                                             Array.from(shImg.querySelectorAll('style')).forEach(function (style){
                                                 style.remove()
                                             })
                                             shadow.appendChild(shImg);
                                             img.parentNode.replaceChild(el, img);
                                         })
                                     } else {
                                         this.src = src;
                                     }
                                 })
                        }
                    }
                }
            ],
            menu: [
                {
                    title: 'Edit HTML',
                    icon: 'mw-icon-code',
                    action: function () {
                        mw.editSource(mw._activeElementOver);
                    }
                },
                {
                    title: 'Edit Style',
                    icon: 'mdi mdi-layers',
                    action: function () {
                        mw.liveEditSelector.select(mw._activeElementOver);
                        mw.liveEditSettings.show();
                        mw.sidebarSettingsTabs.set(3);
                        if(mw.cssEditorSelector){
                            mw.liveEditSelector.active(true);
                            mw.liveEditSelector.select(mw._activeElementOver);
                        } else {
                            mw.$(mw.liveEditWidgets.cssEditorInSidebarAccordion()).on('load', function () {
                                setTimeout(function(){
                                    mw.liveEditSelector.active(true);
                                    mw.liveEditSelector.select(mw._activeElementOver);
                                }, 333);
                            });
                        }
                        mw.liveEditWidgets.cssEditorInSidebarAccordion();
                    }
                },
                {
                    title: 'Remove',
                    icon: 'mw-icon-bin',
                    className:'mw-handle-remove',
                    action: function () {
                        mw.drag.delete_element(mw._activeElementOver);
                        mw.handleElement.hide()
                    }
                }
            ]
        });


        mw.$(mw.handleElement.wrapper).draggable({
            handle: mw.handleElement.handleIcon,
            cursorAt: {
                //top: -30
            },
            start: function() {
                mw.isDrag = true;
                mw.dragCurrent = mw.ea.data.currentGrabbed = mw._activeElementOver;

                handleDomtreeSync.start = mw.dragCurrent.parentNode;

                if(!mw.dragCurrent.id){
                    mw.dragCurrent.id = 'element_' + mw.random();
                }
                mw.$(mw.dragCurrent).invisible().addClass("mw_drag_current");
                mw.trigger("AllLeave");
                mw.drag.fix_placeholders();
                mw.$(document.body).addClass("dragStart");
                mw.image_resizer._hide();
                mw.wysiwyg.change(mw.dragCurrent);
                mw.smallEditor.css("visibility", "hidden");
                mw.smallEditorCanceled = true;
            },
            stop: function() {
                mw.$(document.body).removeClass("dragStart");

                if(mw.liveEditDomTree) {
                    mw.liveEditDomTree.refresh(handleDomtreeSync.start)
                }
            }
        });

        mw.$(mw.handleElement.wrapper).on('click', function() {
            if (!$(mw._activeElementOver).hasClass("element-current")) {
                mw.$(".element-current").removeClass("element-current");

                if (mw._activeElementOver.nodeName === 'IMG') {

                    mw.trigger("ImageClick", mw._activeElementOver);
                } else {
                    mw.trigger("ElementClick", mw._activeElementOver);
                }
            }

        });

        mw.on("ElementOver", function(a, element, originalEvent) {
            mw._activeElementOver = element;
            mw.$(".mw_edit_delete, .mw_edit_delete_element, .mw-sorthandle-moveit, .column_separator_title").show();
            if (!mw.ea.canDrop(element)) {
                mw.$(".mw_edit_delete, .mw_edit_delete_element, .mw-sorthandle-moveit, .column_separator_title").hide();
                return false;
            }
            var el = mw.$(element);

            var o = el.offset();

            var pleft = parseFloat(el.css("paddingLeft"));
            var left_spacing = o.left;
            if (mw.tools.hasClass(element, 'jumbotron')) {
                left_spacing = left_spacing + pleft;

            }
            // Centered: left_spacing += (el.width()/2 - mw.handleElement.wrapper.offsetWidth/2);
            if(left_spacing<0){
                left_spacing = 0;
            }
            //todo: another icon
            var isSafe = false; // mw.tools.parentsOrCurrentOrderMatchOrOnlyFirst(element, ['safe-mode', 'regular-mode']);
            var _icon = isSafe ? '<svg  xmlns="http://www.w3.org/2000/svg" viewBox="0 0 504.03 440" height="17" class="safe-element-svg"><path fill="green" d="M252,2.89C178.7,2.89,102.4,19.44,102.4,19.44A31.85,31.85,0,0,0,76.76,50.69v95.59c0,165.67,159.7,234.88,159.7,234.88A31.65,31.65,0,0,0,252,385.27a32.05,32.05,0,0,0,15.56-4.11c.06,0,159.69-69.21,159.69-234.88V50.69a31.82,31.82,0,0,0-25.64-31.25S325.33,2.89,252,2.89Zm95.59,95.59a15.94,15.94,0,0,1,11.26,27.2L238.45,246.11a16,16,0,0,1-11.33,4.73,15.61,15.61,0,0,1-11.2-4.73l-55-55a15.93,15.93,0,0,1,22.53-22.53l43.69,43.82L336.34,103.15a16,16,0,0,1,11.27-4.67Zm0,0"/></svg>' : '<span class="mdi mdi-drag tip" data-tip="' + mw.lang('') + '"></span>';

            var icon = '<span class="mw-handle-element-title-icon '+(isSafe ? 'tip' : '')+'"  '+(isSafe ? ' data-tip="Current element is protected \n  from accidental deletion" data-tipposition="top-left"' : '')+' >'+ _icon +'</span>';

            var title = '<i class="mdi mdi-cog mw-handle-handler-settings-icon"></i>';

            mw.handleElement.setTitle(icon, title);

            if(el.hasClass('allow-drop')){
                mw.handleElement.hide();
            } else{
                mw.handleElement.show();
            }
            mw.handleElement.positionedAt = 'top';
            var posTop = o.top - 40;
            var elHeight = el.height();
            /*if (originalEvent.pageY > (o.top + elHeight/2)) {
                posTop = o.top + elHeight;
                mw.handleElement.positionedAt = 'bottom';
            }*/

            mw.$(mw.handleElement.wrapper).css({
                top: posTop,
                left: left_spacing
            }).removeClass('active');

            if(!element.id) {
                element.id = "element_" + mw.random();
            }

            mw.dropable.removeClass("mw_dropable_onleaveedit");
            mw._initHandles.manageCollision();

        });


    },
    modules: function () {
        var handlesModulesButtons = function (targetFn){
            return [
                {
                    title: mw.lang('Edit'),
                    icon: 'mdi-pencil',
                    action: function () {
                        mw.drag.module_settings(targetFn(),"admin");
                        mw.handleModule.hide();
                    }
                },
                {
                    title: mw.lang('Insert'),
                    className: 'mw-handle-insert-button',
                    icon: 'mdi-plus',
                    hover: [
                        function (e) {
                            handleInsertTargetDisplay(targetFn(), mw.handleModule.positionedAt);
                        },
                        function (e) {
                            handleInsertTargetDisplay('hide');
                        }
                    ],
                    action: function (node) {
                        var isLayout = mw.handleModule.isLayout
                        var target = targetFn();
                        if(target && target.dataset.type === 'layouts') {
                            isLayout = true;
                        }
                        if(isLayout) {
                            mw.layoutPlus.showSelectorUI(node);
                        } else {
                            mw.drag.plus.rendModules(node);
                        }



                    }
                },
            ];
        };

        var getActiveModuleOver = function () {
            return mw._activeModuleOver;
        };
        var getActiveDragCurrent = function () {
            //var el = mw.liveEditSelector && mw.liveEditSelector.selected ?  mw.liveEditSelector.selected[0] : null;
            var el = mw.liveEditSelector.activeModule;
            if (el && el.nodeType === 1) {
                return el;
            }
            if(mw.handleModuleActive._target) {
                return mw.handleModuleActive._target;
            }
        };

        var getDragCurrent = function () {
            if(mw._activeModuleOver){
                return mw._activeModuleOver;
            }
        };

        var handlesModuleConfig = {
            id: 'mw-handle-item-module',
            buttons: handlesModulesButtons(getActiveModuleOver),
            menu: [
                {
                    title: mw.lang('Edit'),
                    icon: 'mdi-pencil',
                    action: function () {
                        mw.drag.module_settings(mw._activeModuleOver,"admin");
                        mw.handleModule.hide();
                    }
                },
                {
                    title: 'Move Up',
                    icon: 'mdi mdi-chevron-double-up',
                    className:'mw_handle_module_up',
                    action: function () {
                        mw.drag.replace($(mw._activeModuleOver), 'prev');
                        mw.handleModule.hide();
                    }
                },
                {
                    title: 'Move Down',
                    icon: 'mdi mdi-chevron-double-down',
                    className:'mw_handle_module_down',
                    action: function () {
                        mw.drag.replace($(mw._activeModuleOver), 'next');
                        mw.handleModule.hide()
                    }
                },
                {
                    title: 'Clone',
                    icon: 'mdi mdi-content-duplicate',
                    className:'mw_handle_module_clone',
                    action: function () {
                        var parent = mw.tools.firstParentWithClass(mw._activeModuleOver, 'edit');

                        var pt = '[field="'+parent.getAttribute('field')+'"][rel="'+parent.getAttribute('rel')+'"]';
                        mw.liveEditState.record({
                            target: pt,
                            value: parent.innerHTML
                        });
                        var html = mw._activeModuleOver.outerHTML;
                        var el = document.createElement('div');
                        el.innerHTML = html;
                        $('[id]', el).each(function(){
                            this.id = mw.id('mw-id-');
                            this.removeAttribute('parent-module-id');
                        });
                        $(mw._activeModuleOver).after(el.innerHTML);
                        var newEl = $(mw._activeModuleOver).next();
                        mw.reload_module(newEl, function(){
                            mw.liveEditState.record({
                                target: pt,
                                value: parent.innerHTML
                            });
                            var node = $(mw._activeModuleOver).next()[0]
                            node.scrollIntoView();
                            mw.wysiwyg.change(node)
                        });

                        mw.handleModule.hide();
                    }
                },
                {
                    title: '{dynamic}',
                    className:'mw_handle_module_submodules'
                },
                {
                    title: '{dynamic}',
                    className:'mw_handle_module_spacing'
                },
                {
                    title: 'Reset',
                    icon: 'mw-icon-reload',
                    className:'mw-handle-remove',
                    action: function () {
                        if(mw._activeModuleOver && mw._activeModuleOver.id){
                            mw.tools.confirm_reset_module_by_id(mw._activeModuleOver.id)
                        }
                    }
                },
                {
                    title: 'Remove',
                    icon: 'mw-icon-bin',
                    className:'mw-handle-remove',
                    action: function () {
                        mw.drag.delete_element(mw._activeModuleOver);
                        mw.handleModule.hide();
                    }
                }
            ]
        };
        var handlesModuleConfigActive = {
            id: 'mw-handle-item-module-active',
            buttons: handlesModulesButtons(getActiveDragCurrent),
            menu: [
                {
                    title: 'Settings',
                    icon: 'mw-icon-gear',
                    action: function () {
                        mw.drag.module_settings(getActiveDragCurrent(),"admin");
                        $(mw.handleModuleActive.wrapper).removeClass('active');
                    }
                },
                {
                    title: 'Move Up',
                    icon: 'mdi mdi-chevron-double-up',
                    className:'mw_handle_module_up',
                    action: function () {
                        mw.drag.replace($(getActiveDragCurrent()), 'prev');
                    }
                },
                {
                    title: 'Move Down',
                    icon: 'mdi mdi-chevron-double-down',
                    className:'mw_handle_module_down',
                    action: function () {
                        mw.drag.replace($(getActiveDragCurrent()), 'next');
                    }
                },
                {
                    title: 'Clone',
                    icon: 'mdi mdi-content-duplicate',
                    className:'mw_handle_module_clone',
                    action: function () {
                        var parent = mw.tools.firstParentWithClass(mw._activeModuleOver, 'edit');
                        var pt = '[field="'+parent.getAttribute('field')+'"][rel="'+parent.getAttribute('rel')+'"]';
                        mw.liveEditState.record({
                            target: pt,
                            value: parent.innerHTML
                        });
                        var html = mw._activeModuleOver.outerHTML;
                        var el = document.createElement('div');
                        el.innerHTML = html;
                        $('[id]', el).each(function(){
                            this.id = mw.id('mw-id-');
                            this.removeAttribute('parent-module-id');
                        });
                        $(mw._activeModuleOver).after(el.innerHTML);
                        var newEl = $(mw._activeModuleOver).next();
                        mw.reload_module(newEl, function (){
                             mw.liveEditState.record({
                                target: pt,
                                value: parent.innerHTML
                            });
                            var node = $(mw._activeModuleOver).next()[0]
                            node.scrollIntoView();
                            mw.wysiwyg.change(node)
                        });
                        mw.handleModule.hide();
                    }
                },
                {
                    title: '{dynamic}',
                    className:'mw_handle_module_submodules mw_handle_module_submodules_active'
                },
                {
                    title: '{dynamic}',
                    className:'mw_handle_module_spacing'
                },
                {
                    title: 'Reset',
                    icon: 'mw-icon-reload',
                    className:'mw-handle-remove',
                    action: function () {
                        if(mw._activeModuleOver && mw._activeModuleOver.id){
                            mw.tools.confirm_reset_module_by_id(mw._activeModuleOver.id)
                        }
                    }
                },
                {
                    title: 'Remove',
                    icon: 'mw-icon-bin',
                    className:'mw-handle-remove',
                    action: function () {
                        mw.drag.delete_element(getActiveDragCurrent());
                        mw.handleModuleActive.hide();
                    }
                }
            ]
        };


        var dragConfig = function (curr, handle) {
            return {
                handle: handle.handleIcon,
                distance:20,
                cursorAt: {
                    //top: -30
                },
                start: function() {
                    mw.isDrag = true;
                    mw.dragCurrent = curr();
                    handleDomtreeSync.start = mw.dragCurrent.parentNode;
                    if (!mw.dragCurrent.id) {
                        mw.dragCurrent.id = 'module_' + mw.random();
                    }
                    if (mw.liveEditTools.isLayout(mw.dragCurrent)){
                        mw.$(mw.dragCurrent).css({
                            opacity:0
                        }).addClass("mw_drag_current");
                    } else {
                        mw.$(mw.dragCurrent).invisible().addClass("mw_drag_current");
                    }

                    mw.trigger("AllLeave");
                    mw.drag.fix_placeholders();
                    mw.$(document.body).addClass("dragStart");
                    mw.image_resizer._hide();
                    mw.wysiwyg.change(mw.dragCurrent);
                    mw.smallEditor.css("visibility", "hidden");
                    mw.smallEditorCanceled = true;
                },
                stop: function() {
                    mw.$(document.body).removeClass("dragStart");
                    if(mw.liveEditDomTree) {
                        mw.liveEditDomTree.refresh(handleDomtreeSync.start)
                    }
                }
            };
        };

        mw.handleModule = new mw.Handle(handlesModuleConfig);
        mw.handleModuleActive = new mw.Handle(handlesModuleConfigActive);

        mw.handleModule.type = 'hover';
        mw.handleModuleActive.type = 'active';

        mw.handleModule._hideTime = null;
        mw
            .$(mw.handleModule.wrapper)
            .draggable(dragConfig(getDragCurrent, mw.handleModule))
            .on("mousedown", function(e){
                mw.liveEditSelectMode = 'none';
            });


        mw
            .$(mw.handleModuleActive.wrapper)
            .draggable(dragConfig(getActiveDragCurrent, mw.handleModuleActive))
            .on("mousedown", function(e){
                mw.liveEditSelectMode = 'none';
            });


        var positionModuleHandle = function(e, pelement, handle, event){

            handle._element = pelement

            var element ;

            if(handle.type === 'hover') {
                element = dynamicModulesMenu(e, pelement) || pelement;
                mw._activeModuleOver = element;
            } else {
                //pelement = mw.tools.lastMatchesOnNodeOrParent(pelement, ['.module']);

                element = dynamicModulesMenu(e, pelement) || pelement;
                handle._target = pelement;
            }



            mw.$(".mw-handle-menu-dynamic", handle.wrapper).empty();
            mw.$('.mw_handle_module_up,.mw_handle_module_down').hide();
            var $el, hasedit;
            var isLayout = element && element.classList.contains('module') && element.getAttribute('data-type') === 'layouts';
            handle.isLayout = isLayout;
            handle.handle.classList[isLayout ? 'add' : 'remove']('mw-handle-target-layout');
            mw.$('.mw_handle_module_clone').hide();
            if(isLayout){
                mw.$('.mw_handle_module_clone').show();

                $el = mw.$(element);
                hasedit = mw.tools.parentsOrCurrentOrderMatchOrOnlyFirst($el[0].parentNode,['edit', 'module']);

                if(hasedit){
                    if($el.prev('[data-type="layouts"]')[0]){
                        mw.$('.mw_handle_module_up').show();
                    }
                    if($el.next('[data-type="layouts"]')[0]){
                        mw.$('.mw_handle_module_down').show();
                    }
                }
            }

            var el = mw.$(element);
            var o = el.offset();
            var width = el.width();
            var pleft = parseFloat(el.css("paddingLeft"));

            var lebar =  document.querySelector("#live_edit_toolbar");
            var minTop = (lebar ? lebar.offsetHeight : 0);
            if(mw.templateTopFixed) {
                var ex = document.querySelector(mw.templateTopFixed);
                if(ex && !ex.contains(el[0])){
                    minTop += ex.offsetHeight;
                }
            }

            var marginTop =  30;
            var topPos = o.top;

            if(topPos<minTop){
                topPos = minTop + el[0].offsetHeight;
                marginTop = 0
            }
            var ws = mw.$(window).scrollTop();
            if((topPos-50)<(ws+minTop)){
                topPos=(ws+minTop);
                // marginTop =  -15;
                if(el[0].offsetHeight < 100){
                    topPos = o.top+el[0].offsetHeight;
                    marginTop =  0;
                }
            }

            var handleLeft = o.left + pleft;
            if (handleLeft < 0) {
                handleLeft = 0;
            }

            var topPosFinal = topPos //+ marginTop;
            var $lebar = mw.$(lebar), $leoff = $lebar.offset();

            var outheight = el.outerHeight();

            if(topPosFinal < ($leoff.top + $lebar.height())){
                topPosFinal = (o.top + outheight) - (outheight > 100 ? 0 : handle.wrapper.clientHeight);
            }


            var elHeight = el.height();

            handle.positionedAt = 'top';
            /*if (event.pageY > (o.top + elHeight/2)) {
                topPosFinal += elHeight;
                handle.positionedAt = 'bottom';
            }*/
             if (element.dataset.type === 'layouts') {
                topPosFinal = o.top + 10;
                 handleLeft = handleLeft + 10;
            }

            clearTimeout(handle._hideTime);
            handle.show();
            mw.$(handle.wrapper)
                .removeClass('active')
                .css({
                    top: topPosFinal,
                    left: handleLeft,
                    //width: width,
                    //marginTop: marginTop
                }).addClass('mw-active-item');




            var canDrag = mw.tools.parentsOrCurrentOrderMatchOrOnlyFirst(element.parentNode, ['edit', 'module'])
                && mw.tools.parentsOrCurrentOrderMatchOrOnlyFirstOrNone(element, ['allow-drop', 'nodrop']);
            if(canDrag){
                mw.$(handle.wrapper).removeClass('mw-handle-no-drag');
            } else {
                mw.$(handle.wrapper).addClass('mw-handle-no-drag');
            }
            if ( !el ) {
                return;
            }
            var title = el.dataset("mw-title");
            var id = el.attr("id");



            var module_type = (el.dataset("type") || el.attr("type"));
            if(typeof(module_type) == 'undefined'){
                return;
            }

            var cln = el[0].querySelector('.cloneable');
            if(cln || mw.tools.hasClass(el[0], 'cloneable')){
                if(($(cln).offset().top - el.offset().top) < 20){
                    mw.tools.addClass(mw.drag._onCloneableControl, 'mw-module-near-cloneable');
                } else {
                    mw.tools.removeClass(mw.drag._onCloneableControl, 'mw-module-near-cloneable');
                }
            }

            var mod_icon = mw.live_edit.getModuleIcon(module_type);
            var mod_handle_title = (title ? title : mw.msg.settings);
            /*if(module_type === 'layouts'){
                mod_handle_title = '';
            }*/

            handle.setTitle(mod_icon, mod_handle_title);
            handle.handleTitle.dataset.tip = mw.lang('Module') + ': ' + mod_handle_title
            handle.handleTitle.classList.add('tip');
             if(!handle) {
                return;
            }

            mw.tools.classNamespaceDelete(handle, 'module-active-');
            mw.tools.addClass(handle, 'module-active-' + module_type.replace(/\//g, '-'));
             if (mw.live_edit_module_settings_array && mw.live_edit_module_settings_array[module_type]) {

                var new_el = document.createElement('div');
                new_el.className = 'mw_edit_settings_multiple_holder';

                var settings = mw.live_edit_module_settings_array[module_type];
                mw.$(settings).each(function () {

                    var handleDynamicView = false;



                    if(typeof(this) == 'object' && typeof(this[0]) !== 'undefined'){
                        handleDynamicView  = this[0];
                    } else {
                        handleDynamicView =  this;
                    }

                    if (handleDynamicView && typeof(handleDynamicView.view) !== 'undefined') {
                        var new_el = document.createElement('a');
                        new_el.className = 'mw_edit_settings_multiple';
                        new_el.title = handleDynamicView.title;
                        new_el.draggable = 'false';
                        var btn_id = 'mw_edit_settings_multiple_btn_' + mw.random();
                        new_el.id = btn_id;
                        if (handleDynamicView.type && handleDynamicView.type === 'tooltip') {
                            new_el.href = 'javascript:mw.drag.current_module_settings_tooltip_show_on_element("' + btn_id + '","' + handleDynamicView.view + '", "tooltip"); void(0);';

                        } else {
                            new_el.href = 'javascript:mw.drag.module_settings(undefined,"' + handleDynamicView.view + '"); void(0);';
                        }
                        var icon = '';
                        if (handleDynamicView.icon) {
                            icon = '<i class="mw-edit-module-settings-tooltip-icon ' + handleDynamicView.icon + '"></i>';
                        }
                        new_el.innerHTML =  (icon + '<span class="mw-edit-module-settings-tooltip-btn-title">' + handleDynamicView.title+'</span>');
                        mw.$(".mw_handle_module_spacing", handle.wrapper).append(new_el);
                    }
                });
            } else {

            }

            /*************************************/


            if(!element.id) {
                element.id = "module_" + mw.random();
            }
            mw._initHandles.manageCollision();
        };

        mw.on('ModuleClick', function(e, pelement, event){
            mw.handleModule.hide();
            positionModuleHandle(e, pelement, mw.handleModuleActive, event);
            var el = mw.$('.mw_handle_module_submodules');

            el.each(function (){
                var currEl = this;
                var nodes = [];
                if(currEl.classList.contains('mw_handle_module_submodules_active')) {
                    $(currEl).empty();
                    mw.$('.module', pelement).each(function () {

                        var type = this.getAttribute('data-type');

                        var hastitle = mw.live_edit.registry[type] ? mw.live_edit.registry[type].title : false;
                        var icon = mw.live_edit.getModuleIcon(type);
                        if(!icon){
                            icon  = '<span class="mw-icon-gear mw-handle-menu-item-icon"></span>';
                        }
                        if (hastitle) {
                            var menuitem = '<span class="mw-handle-menu-item dynamic-submodule-handle" data-module="'+this.id+'">'
                                + icon
                                + hastitle.replace(/_/g, ' ')
                                + '</span>';
                            nodes.push(menuitem);
                        }

                    });
                    nodes.forEach(function (node){
                        $(currEl).append(node);
                    })
                }


            })

        });

        mw.on('moduleOver', function (e, pelement, event) {
            if(mw.handleModuleActive._element === pelement) {
                mw.handleModule.hide();
                return;
            }

            positionModuleHandle(e, pelement, mw.handleModule, event);
            if(mw._activeModuleOver === mw.handleModuleActive._target) {
                mw.handleModule.hide();
            }


            var el = mw.$('.mw_handle_module_submodules');

            el.each(function (){
                var currEl = this;
                var nodes = [];
                if(!currEl.classList.contains('mw_handle_module_submodules_active')) {
                    $(currEl).empty();
                    mw.$('.module', pelement).each(function () {

                        var type = this.getAttribute('data-type');

                        var hastitle = mw.live_edit.registry[type] ? mw.live_edit.registry[type].title : false;
                        var icon = mw.live_edit.getModuleIcon(type);
                        if(!icon){
                            icon  = '<span class="mw-icon-gear mw-handle-menu-item-icon"></span>';
                        }
                        if (hastitle) {
                            var menuitem = '<span class="mw-handle-menu-item dynamic-submodule-handle" data-module="'+this.id+'">'
                                + icon
                                + hastitle.replace(/_/g, ' ')
                                + '</span>';
                            nodes.push(menuitem);
                        }

                    });
                    nodes.forEach(function (node){
                        $(currEl).append(node);
                    })
                }


            })

            mw.$('.text-background', pelement).each(function () {
                var bgEl = this;
                $.each([0,1], function(i){
                    var icon  = '<span class="mw-icon-gear mw-handle-menu-item-icon"></span>';
                    var menuitem = document.createElement('span');
                    menuitem.className = 'mw-handle-menu-item text-background-handle';
                    menuitem.innerHTML = icon + 'background text';
                    menuitem.__for = bgEl;
                    // menuitem = mw.$(menuitem);
                    el.eq(i).append(menuitem)
                })

            });


            mw.$('.text-background-handle').on('click', function () {
                var ok = mw.$('<button class="mw-ui-btn mw-ui-btn-medium mw-ui-btn-info pull-right">OK</button>');
                var cancel = mw.$('<button class="mw-ui-btn mw-ui-btn-medium pull-left">Cancel</button>');
                var footer = mw.$('<div></div>');
                var area = $('<textarea class="mw-ui-field w100" style="height: 200px"/>');
                var node = this.__for;
                area.val(mw.$(node).html());
                footer.append(cancel);
                footer.append(ok);
                var dialog = mw.dialog({
                    content: area,
                    footer: footer
                });
                ok.on('click', function () {
                    mw.liveEditState.record({
                        target: node,
                        value: node.innerHTML
                    });
                    mw.$(node).html(area.val());
                    dialog.remove();
                    mw.liveEditState.record({
                        target: node,
                        value: node.innerHTML
                    });
                });
                cancel.on('click', function () {
                    dialog.remove();
                });
            });
            mw.$('.dynamic-submodule-handle').on('click', function () {
                mw.tools.module_settings('#' + this.dataset.module);
            });
        });
    },
    columns:function(){
        mw.handleColumns = new mw.Handle({
            id: 'mw-handle-item-columns',
            // className:'mw-handle-type-element',
            menu:[
                {
                    title: 'One column',
                    action: function () {
                        mw.drag.create_columns(this,1);
                    }
                },
                {
                    title: '2 columns',
                    action: function () {
                        mw.drag.create_columns(this,2);
                    }
                },
                {
                    title: '3 columns',
                    action: function () {
                        mw.drag.create_columns(this,3);
                    }
                },
                {
                    title: '4 columns',
                    action: function () {
                        mw.drag.create_columns(this,4);
                    }
                },
                {
                    title: '5 columns',
                    action: function () {
                        mw.drag.create_columns(this,5);
                    }
                },
                {
                    title: 'Remove',
                    icon: 'mw-icon-bin',
                    className:'mw-handle-remove',
                    action: function () {
                        mw.drag.delete_element(mw._activeRowOver, function () {
                            mw.$(mw.drag.columns.resizer).hide();
                            mw.handleColumns.hide();
                        });
                    }
                }
            ]
        });
        mw.handleColumns.setTitle('<span class="mw-handle-columns-icon"></span>', '');

        mw.$(mw.handleColumns.wrapper).draggable({
            handle: mw.handleColumns.handleIcon,
            cursorAt: {
                //top: -30
            },
            start: function() {
                mw.isDrag = true;
                var curr = mw._activeRowOver ;
                mw.dragCurrent = mw.ea.data.currentGrabbed = curr;
                handleDomtreeSync.start = mw.dragCurrent.parentNode;
                mw.dragCurrent.id == "" ? mw.dragCurrent.id = 'element_' + mw.random() : '';
                mw.$(mw.dragCurrent).invisible().addClass("mw_drag_current");
                mw.trigger("AllLeave");
                mw.drag.fix_placeholders();
                mw.$(document.body).addClass("dragStart");
                mw.image_resizer._hide();
                mw.wysiwyg.change(mw.dragCurrent);
                mw.smallEditor.css("visibility", "hidden");
                mw.smallEditorCanceled = true;
                mw.$(mw.drag.columns.resizer).hide()
            },
            stop: function() {
                mw.$(document.body).removeClass("dragStart");
                if(mw.liveEditDomTree) {
                    mw.liveEditDomTree.refresh(handleDomtreeSync.start)
                }
            }
        });

        mw.on("RowOver", function(a, element) {

            mw._activeRowOver = element;
            var el = mw.$(element);
            var o = el.offset();
            var htop = o.top - 35;
            var left = o.left;

            if (htop < 55 && document.getElementById('live_edit_toolbar') !== null) {
                htop = 55;
                left = left - 100;
            }
            if (htop < 0 && document.getElementById('live_edit_toolbar') === null) {
                htop = 0;
            }


            mw.handleColumns.show()

            mw.$(mw.handleColumns.wrapper).css({
                top: htop,
                left: left,
                //width: width
            });
            mw._initHandles.manageCollision();

            var size = mw.$(element).children(".mw-col").length;
            mw.$("a.mw-make-cols").removeClass("active");
            mw.$("a.mw-make-cols").eq(size - 1).addClass("active");
            if(!element.id){
                element.id = "element_row_" + mw.random() ;
            }




        });
    },
    nodeLeave: function () {
        var scope = this;

        mw.on("ElementLeave", function(e, target) {
            mw.handleElement.hide();
        });
        mw.on("ModuleOver", function(e, target) {
            $('.mw-handle-item.mw-active-item.active').removeClass('active')
        })
        mw.on("ModuleLeave", function(e, target) {
            clearTimeout(mw.handleModule._hideTime);
            $('.mw-handle-item.mw-active-item.active').removeClass('active')
            $('.mw-handle-item.mw-active-item.active').removeClass('active')
            mw.handleModule._hideTime = setTimeout(function () {
                mw.handleModule.hide();
            }, 3000);

            //.removeClass('mw-active-item');
        });
        mw.on("RowLeave", function(e, target) {
            //mw.handleColumns.hide();
        });
    }
};




$(document).ready(function () {

    mw._initHandles.modules();
    mw._initHandles.elements();
    mw._initHandles.columns();
    mw._initHandles.nodeLeave();



});
